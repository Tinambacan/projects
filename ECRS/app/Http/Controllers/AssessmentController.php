<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Student;
use App\Models\ClassRecord;
use App\Models\Grading;
use App\Models\GradingDistribution;
use Illuminate\Http\Request;
use App\Models\StudentAssessment;
use Carbon\Carbon;
use App\Models\AuditTrail;
use App\Models\Login;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssessmentTemplateExport;
use App\Imports\AssessmentImport;
use Illuminate\Support\Facades\Log;

class AssessmentController extends Controller
{

    public function storeAssessmentMidterms(Request $request)
    {
        $validatedData = $request->validate([
            'assessmentName' => 'required|max:50',
            'assessmentDate' => 'required|date',
            'totalItem' => 'nullable|numeric', // Updated to nullable
            'passingItem' => 'nullable|numeric', // Updated to nullable
            'classRecordID' => 'required|exists:class_record_tbl,classRecordID',
            'assessmentType' => 'required|string',
        ]);

        $assessmentType = ucfirst($validatedData['assessmentType']);

        // Use null coalescing operator to handle missing keys
        $totalItem = $validatedData['totalItem'] ?? null;
        $passingItem = $validatedData['passingItem'] ?? null;
        $passingPercentage = $totalItem * ($passingItem / 100);

        // Create a new assessment record
        Assessment::create([
            'assessmentName' => $validatedData['assessmentName'],
            'assessmentDate' => $validatedData['assessmentDate'],
            'totalItem' => $totalItem, // Can be null
            'passingItem' => $passingPercentage, // Can be null
            'term' => 1,
            'classRecordID' => $validatedData['classRecordID'],
            'assessmentType' => $assessmentType,
        ]);

        return response()->json(['success' => true, 'message' => "$assessmentType added successfully."]);
    }

    public function storeAssessmentInfo(Request $request)
    {
        $selectedClassRecordID = session('selectedClassRecordID');
        $classRecord = ClassRecord::find($selectedClassRecordID);
        $programCode = $classRecord->program->programCode ?? 'N/A';
        $yearLevel = $classRecord->yearLevel;
        $courseCode = $classRecord->course->courseCode ?? 'N/A';
        $classRecordDescription = $programCode . ' ' . $yearLevel . ' ' . $courseCode;

        $userAdmin = Login::with('registration')
            ->where('loginID', session('loginID'))
            ->first();

        $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;

        $gradingTerm = session('gradingTerm');

        $validatedData = $request->validate([
            'assessmentName' => 'required|max:50',
            'assessmentDate' => 'required|date',
            'totalItem' => 'required|numeric',
            'passingItem' => 'nullable|numeric',
            'classRecordID' => 'required|exists:class_record_tbl,classRecordID',
            'assessmentType' => 'required|string',
        ]);

        $assessmentType = ucfirst($validatedData['assessmentType']);

        $totalItem = $validatedData['totalItem'] ?? null;
        $passingItem = $validatedData['passingItem'] ?? null;

        $passingPercentage = null;
        if ($totalItem !== null && $passingItem !== null) {
            $passingPercentage = $totalItem * ($passingItem / 100);
        }

        $existingAssessment = Assessment::where('classRecordID', $validatedData['classRecordID'])
            ->where('term', $gradingTerm)
            ->where('assessmentName', $validatedData['assessmentName'])
            ->first();

        if ($existingAssessment) {
            return response()->json([
                'message' => 'Assessment Name Already Existed.',
                'assessmentType' => $assessmentType,
            ], 409); // 409 Conflict status code
        }

        // Create a new assessment record
        $assessment = Assessment::create([
            'assessmentName' => $validatedData['assessmentName'],
            'assessmentDate' => $validatedData['assessmentDate'],
            'totalItem' => $totalItem,
            'passingItem' => $passingPercentage,
            'isPublished' => 0,
            'term' => $gradingTerm,
            'classRecordID' => $validatedData['classRecordID'],
            'assessmentType' => $assessmentType,
        ]);

        // Create audit trail for assessment creation
        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Add',
            'table_name' => 'assessment_tbl',
            'new_value' => json_encode([
                'assessmentName' => $assessment->assessmentName,
                'assessmentDate' => $assessment->assessmentDate,
                'totalItem' => $totalItem,
                'passingItem' => $passingPercentage,
                'term' => $gradingTerm,
                'classRecordID' => $assessment->classRecordID,
                'assessmentType' => $assessmentType,
            ]),
            'description' => "Added {$assessmentType} assessment '{$assessment->assessmentName}' for {$classRecordDescription}",
            'action_time' => Carbon::now(),
        ]);

        return response()->json(['success' => true, 'message' => "$assessmentType added successfully."]);
    }



    public function storeAssessmentFinals(Request $request)
    {
        $validatedData = $request->validate([
            'assessmentName' => 'required|max:50',
            'assessmentDate' => 'required|date',
            'totalItem' => 'required|numeric',
            'passingItem' => 'required|numeric',
            'classRecordID' => 'required|exists:class_record_tbl,classRecordID',
            'assessmentType' => 'required|string',
        ]);

        $assessmentType = ucfirst($validatedData['assessmentType']);
        $totalItem = $validatedData['totalItem'] ?? null;
        $passingItem = $validatedData['passingItem'] ?? null;

        $passingPercentage = $totalItem * ($passingItem / 100);
        // Create a new assessment record
        Assessment::create([
            'assessmentName' => $validatedData['assessmentName'],
            'assessmentDate' => $validatedData['assessmentDate'],
            'totalItem' => $validatedData['totalItem'],
            'passingItem' => $passingPercentage,
            'term' => 2,
            'classRecordID' => $validatedData['classRecordID'],
            'assessmentType' => $assessmentType,
        ]);

        return response()->json(['success' => true, 'message' => "$assessmentType added successfully."]);
    }

    public function storeAssessmentID(Request $request)
    {
        // Validate the request to ensure 'assessmentID' is provided and is an integer
        $request->validate([
            'assessmentID' => 'required|integer',
        ]);

        // Retrieve the validated 'assessmentID'
        $assessmentID = $request->input('assessmentID');

        $assessmentType = session('assessmentType'); // Fetching from session

        $gradingDistributionType = $request->input('gradingDistributionType');

        $gradingDistributionType = strtolower($gradingDistributionType);



        // Store 'assessmentID' in the session
        session(['selectedAssessmentID' => $assessmentID]);

        // Retrieve the assessment details from the database
        $assessment = Assessment::find($assessmentID);

        if (!$assessment) {
            abort(404, 'Assessment not found.');
        }

        // Retrieve the term from the assessment
        $term = $assessment->term;

        // Retrieve the assessment type from the request or from the assessment details
        $assessmentType = $request->input('assessmentType', $assessment->assessmentType);

        // Convert the assessment type to a slug format for use in the URL
        $gradingDistributionType = strtolower(str_replace(' ', '-', $gradingDistributionType));

        // Retrieve the Grading Distribution using the gradingDistributionType
        $gradingDistribution = GradingDistribution::where('gradingDistributionType', $gradingDistributionType)->first();

        // Debugging: Print the grading distribution
        // dd($gradingDistribution);

        // If a term is available, generate the redirect URL
        if ($term) {
            $redirectUrl = route('faculty.view-class-record-stud-info-details', [
                'gradingDistributionType' => $gradingDistributionType,
                'assessmentType' => $assessmentType
            ]);
        }

        // Redirect the user
        return redirect($redirectUrl);
    }



    public function updateAssessmentMidterms(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'classRecordID' => 'required|integer',
            'assessmentID' => 'required|integer',
            'assessmentName' => 'required|string',
            'assessmentDate' => 'required|date',
            'totalItem' => 'nullable|numeric',
            'passingItem' => 'nullable|numeric',
            'assessmentType' => 'required|string',
        ]);

        $classRecordID = $request->input('classRecordID');
        $assessmentID = $request->input('assessmentID');
        $assessmentName = $request->input('assessmentName');
        $assessmentDate = $request->input('assessmentDate');
        $totalItem = $request->input('totalItem');
        $passingItem = $request->input('passingItem');
        $assessmentType = ucfirst($request->input('assessmentType'));
        $passingPercentage = $totalItem * ($passingItem / 100);
        $gradingTerm = session('gradingTerm');
        $existingAssessment = Assessment::where('classRecordID', $classRecordID)
            ->where('term', $gradingTerm)
            ->where('assessmentName', $assessmentName)
            ->where('assessmentID', '!=', $assessmentID) // Exclude the current assessment being updated
            ->first();

        if ($existingAssessment) {
            return response()->json([
                'message' => 'Assessment Name Already Existed',
                'assessmentType' => $assessmentType,
            ], 409); // 409 Conflict status code
        }


        $assessment = Assessment::find($assessmentID);

        if ($assessment) {
            // Fetch the old values before updating
            $oldAssessmentData = $assessment->only([
                'assessmentName',
                'assessmentDate',
                'totalItem',
                'passingItem',
                'assessmentType'
            ]);

            $studentAssessments = StudentAssessment::where('assessmentID', $assessmentID)
                ->where('classRecordID', $classRecordID)
                ->get();

            $invalidAssessments = [];
            foreach ($studentAssessments as $studentAssessment) {
                if ($studentAssessment->score > $totalItem) {
                    $student = Student::find($studentAssessment->studentID);
                    if ($student) {
                        $invalidAssessments[] = [
                            'studentFname' => $student->studentFname,
                            'studentLname' => $student->studentLname,
                            'studentScore' => $studentAssessment->score,
                        ];
                    }
                }
            }

            if (empty($invalidAssessments)) {
                $assessment->update([
                    'assessmentName' => $assessmentName,
                    'assessmentDate' => $assessmentDate,
                    'totalItem' => $totalItem,
                    'passingItem' => $passingPercentage,
                    'assessmentType' => $assessmentType,
                ]);

                // Prepare new assessment data for audit trail
                $newAssessmentData = [
                    'assessmentName' => $assessmentName,
                    'assessmentDate' => $assessmentDate,
                    'totalItem' => $totalItem,
                    'passingItem' => $passingPercentage,
                    'assessmentType' => $assessmentType,
                ];

                // Capture user info and class record description for audit
                $userAdmin = Login::with('registration')
                    ->where('loginID', session('loginID'))
                    ->first();
                $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;

                $classRecord = ClassRecord::find($classRecordID);
                $programCode = $classRecord->program->programCode ?? 'N/A';
                $yearLevel = $classRecord->yearLevel;
                $courseCode = $classRecord->course->courseCode ?? 'N/A';
                $classRecordDescription = $programCode . ' ' . $yearLevel . ' ' . $courseCode;

                // Log audit trail
                AuditTrail::create([
                    'record_id' => session('loginID'),
                    'user' => $userName,
                    'action' => 'Update',
                    'table_name' => 'assessment_tbl',
                    'old_value' => json_encode($oldAssessmentData),
                    'new_value' => json_encode($newAssessmentData),
                    'description' => "Updated {$assessmentType} assessment '{$assessmentName}' in {$classRecordDescription}",
                    'action_time' => Carbon::now(),
                ]);

                return response()->json(['message' => 'Assessment updated successfully!', 'assessmentType' => $assessmentType], 200);
            } else {
                return response()->json([
                    'message' => 'Total score cannot be less than any student\'s score.',
                    'invalidStudentAssessments' => $invalidAssessments,
                ], 400);
            }
        }

        return response()->json(['message' => 'Assessment not found.'], 404);
    }

    public function duplicateAssessmentMidterms(Request $request)
    {
        $gradingTerm = session('gradingTerm');

        // Validate the request
        $validated = $request->validate([
            'classRecordID' => 'required|integer',
            'assessmentType' => 'required|string',
            'assessmentName' => 'required|string|max:255',
            'assessmentDate' => 'required|date',
            'totalItem' => 'required|integer',
            'passingItem' => 'nullable|integer',
        ]);

        // Check for duplicate assessment
        $existingAssessment = Assessment::where('classRecordID', $validated['classRecordID'])
            ->where('term', $gradingTerm)
            ->where('assessmentName', $validated['assessmentName'])
            ->first();

        if ($existingAssessment) {
            return response()->json([
                'message' => 'Assessment Name Already Existed',
                'assessmentType' => $request->assessmentType,
            ], 409); // 409 Conflict status code
        }

        // Add additional fields to the validated data
        $validated['term'] = $gradingTerm;
        $validated['isPublished'] = 0;

        // Create the assessment and capture the created record
        $assessment = Assessment::create($validated);

        // Create audit trail
        $selectedClassRecordID = session('selectedClassRecordID');
        $classRecord = ClassRecord::find($selectedClassRecordID);
        $programCode = $classRecord->program->programCode ?? 'N/A';
        $yearLevel = $classRecord->yearLevel;
        $courseCode = $classRecord->course->courseCode ?? 'N/A';
        $classRecordDescription = $programCode . ' ' . $yearLevel . ' ' . $courseCode;

        $userAdmin = Login::with('registration')
            ->where('loginID', session('loginID'))
            ->first();

        $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;

        AuditTrail::create([
            'record_id' => session('loginID'),
            'user' => $userName,
            'action' => 'Duplicate',
            'table_name' => 'assessment_tbl',
            'new_value' => json_encode([
                'assessmentName' => $assessment->assessmentName,
                'assessmentDate' => $assessment->assessmentDate,
                'totalItem' => $assessment->totalItem,
                'passingItem' => $assessment->passingItem,
                'term' => $assessment->term,
                'classRecordID' => $assessment->classRecordID,
                'assessmentType' => $assessment->assessmentType,
            ]),
            'description' => "Added {$assessment->assessmentType} assessment '{$assessment->assessmentName}' for {$classRecordDescription}",
            'action_time' => now(),
        ]);

        return response()->json([
            'message' => 'Assessment duplicated successfully!',
            'assessmentType' => $request->assessmentType,
        ]);
    }




    public function updateAssessmentFinals(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'classRecordID' => 'required|integer',
            'assessmentID' => 'required|integer',
            'assessmentName' => 'required|string',
            'assessmentDate' => 'required|date',
            'totalItem' => 'required|numeric',
            'passingItem' => 'required|numeric',
            'assessmentType' => 'required|string',
        ]);

        // Retrieve the data from the request
        $classRecordID = $request->input('classRecordID');
        $assessmentID = $request->input('assessmentID');
        $assessmentName = $request->input('assessmentName');
        $assessmentDate = $request->input('assessmentDate');
        $totalItem = $request->input('totalItem');
        $passingItem = $request->input('passingItem');
        $assessmentType = $request->input('assessmentType');
        $passingPercentage = $totalItem * ($passingItem / 100);
        // Find the assessment record by ID
        $assessment = Assessment::find($assessmentID);
        if ($assessment) {
            // Fetch student's scores for the given assessment
            $studentAssessments = StudentAssessment::where('assessmentID', $assessmentID)
                ->where('classRecordID', $classRecordID)
                ->get();

            // Check if any student's score is greater than the new totalItem
            $invalidAssessments = [];
            foreach ($studentAssessments as $studentAssessment) {
                if ($studentAssessment->score > $totalItem) {
                    $student = Student::find($studentAssessment->studentID);
                    if ($student) {
                        $invalidAssessments[] = [
                            'studentFname' => $student->studentFname,
                            'studentLname' => $student->studentLname,
                            'studentScore' => $studentAssessment->score
                        ];
                    }
                }
            }

            if (empty($invalidAssessments)) {
                // Update assessment record
                $assessment->update([
                    'assessmentName' => $assessmentName,
                    'assessmentDate' => $assessmentDate,
                    'totalItem' => $totalItem,
                    'passingItem' => $passingPercentage,
                    // 'assessmentType' => $assessmentType, // Commented out as it was not updated
                ]);

                return response()->json(['message' => 'Assessment updated successfully!', 'assessmentType' => $assessmentType], 200);
            } else {
                return response()->json([
                    'message' => 'Total score cannot be less than any student\'s score.',
                    'invalidStudentAssessments' => $invalidAssessments
                ], 400);
            }
        }

        // Return a JSON response with an error message if the assessment is not found
        return response()->json(['message' => 'Assessment not found.'], 404);
    }

    public function getAssessments($classRecordID, $term)
    {
        try {
            // Fetch assessments based on classRecordID and term
            $assessments = Grading::where('classRecordID', $classRecordID)
                ->where('term', $term)
                ->pluck('assessmentType');

            return response()->json($assessments);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function storeAssessmentType(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'assessmentType' => 'required|string',
            'term' => 'required|integer',
            'selectedTab' => 'required|string',
        ]);

        // Store the term, selectedTab, and assessmentType in the session
        session([
            'gradingTerm' => $request->term,
            'selectedTab' => $request->selectedTab,
            'assessmentType' => $request->assessmentType
        ]);

        // Return a JSON response with the stored session data
        return response()->json([
            'success' => true,
            'message' => 'Assessment type and term stored successfully.',
            'storedData' => [
                'gradingTerm' => session('gradingTerm'),
                'assessmentType' => session('assessmentType'),
                'selectedTab' => session('selectedTab')
            ]
        ]);
    }

    public function storeDistributionType(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'term' => 'required|integer',
            'selectedTab' => 'required|string',
        ]);

        // Store the term, selectedTab, and assessmentType in the session
        session([
            'gradingTerm' => $request->term,
            'selectedTab' => $request->selectedTab,
        ]);

        // Return a JSON response with the stored session data
        return response()->json([
            'success' => true,
            'message' => 'Assessment type and term stored successfully.',
            'storedData' => [
                'gradingTerm' => session('gradingTerm'),
                'selectedTab' => session('selectedTab')
            ]
        ]);
    }

    public function exportTemplate(Request $request)
    {
        $classRecordID = $request->input('classRecordID');
        $assessmentType = $request->input('assessmentType');

        // Fetch the class record with its related program and course information
        $classRecord = ClassRecord::with('program', 'course')
            ->where('classRecordID', $classRecordID)
            ->first();

        if (!$classRecord) {
            return response()->json(['message' => 'Class record not found'], 404);
        }

        // Retrieve programCode, courseTitle, and yearLevel
        $programCode = $classRecord->program->programCode ?? 'UnknownProgram';
        $courseTitle = $classRecord->course->courseTitle ?? 'UnknownCourse';
        $yearLevel = $classRecord->yearLevel ?? 'UnknownYearLevel';

        // Create the dynamic file name
        $fileName = "{$programCode}_{$yearLevel}_{$courseTitle}_{$assessmentType}_assessment_template.xlsx";

        // Use the Excel facade to download the file
        return Excel::download(new AssessmentTemplateExport($classRecordID, $assessmentType), $fileName);
    }


    public function importAssessment(Request $request)
    {
        // Validate the request: file and classRecordID
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
            'classRecordID' => 'required|exists:class_record_tbl,classRecordID',
            'assessmentType' => 'required|string',
        ]);

        try {
            $classRecordID = $request->input('classRecordID');
            $assessmentType = $request->input('assessmentType');
            $gradingTerm = session('gradingTerm'); // Fetch grading term from session

            // Import data using an import class (like 'AssessmentImport')
            Excel::import(new AssessmentImport($classRecordID, $assessmentType, $gradingTerm), $request->file('file'));

            // Retrieve the user's name for the audit trail
            $userAdmin = Login::with('registration')
                ->where('loginID', session('loginID'))
                ->first();

            $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;

            // Create an audit trail record
            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => 'Import',
                'table_name' => 'assessment_tbl',
                'description' => "Import Assessment List",
                'action_time' => Carbon::now(),
            ]);

            return response()->json(['message' => 'Assessment and student scores imported successfully']);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error during assessment import', ['error' => $e->getMessage()]);

            if ($e->getMessage() === 'Assessment already exists') {
                return response()->json(['message' => 'Assessment already exists. Import aborted.'], 409);
            } elseif ($e->getMessage() === 'Invalid classRecordID in Excel file. Import aborted.') {
                return response()->json(['message' => 'ClassRecordID mismatch. Import aborted.'], 400);
            } elseif (strpos($e->getMessage(), 'Score exceeds totalItem') !== false) {
                return response()->json(['message' => $e->getMessage()], 400);
            }

            return response()->json(['message' => 'Error during import process: ' . $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'assessmentID' => 'required|integer',
            'classRecordID' => 'required|integer',
        ]);

        Assessment::where('assessmentID', $validated['assessmentID'])
            ->where('classRecordID', $validated['classRecordID'])
            ->update(['isPublished' => 1]);

        return response()->json(['message' => 'Assessment status updated successfully.'], 200);
    }
}
