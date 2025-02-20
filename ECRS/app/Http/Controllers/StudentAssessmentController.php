<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StudentAssessment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\AuditTrail;
use App\Models\Login;
use App\Models\Student;
use App\Models\ClassRecord;
use App\Models\Assessment;
use Illuminate\Support\Facades\Log;

class StudentAssessmentController extends Controller
{

    public function saveScores(Request $request)
    {
        $request->validate([
            'assessmentID' => 'required|exists:assessment_tbl,assessmentID',
            'classRecordID' => 'required|exists:class_record_tbl,classRecordID',
            'scores' => 'array',
            'scores.*' => 'nullable|numeric',
        ]);

        $assessmentID = $request->input('assessmentID');
        $classRecordID = $request->input('classRecordID');
        $scores = $request->input('scores', []);

        // Fetch user and class record details for audit trail
        $userAdmin = Login::with('registration')->where('loginID', session('loginID'))->first();
        $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;

        $classRecord = ClassRecord::find($classRecordID);
        $programCode = $classRecord->program->programCode ?? 'N/A';
        $yearLevel = $classRecord->yearLevel;
        $courseCode = $classRecord->course->courseCode ?? 'N/A';
        $classRecordDescription = "{$programCode} {$yearLevel} {$courseCode}";

        // Fetch assessment name
        $assessment = Assessment::find($assessmentID);
        $assessmentName = $assessment ? $assessment->assessmentName : 'Unknown Assessment';

        // Iterate over scores and save each, creating an audit trail for each change
        foreach ($scores as $studentID => $score) {
            $existingScore = StudentAssessment::where([
                'studentID' => $studentID,
                'assessmentID' => $assessmentID,
                'classRecordID' => $classRecordID
            ])->first();

            // Retrieve student details for audit log
            $student = Student::find($studentID);
            $studentName = $student->studentLname . ', ' . $student->studentFname;

            $actionType = $existingScore ? 'Update' : 'Create';

            // Prepare old and new values for the audit trail
            $oldValue = $existingScore ? json_encode(['score' => $existingScore->score]) : null;
            $newValue = json_encode(['score' => $score]);

            // Update or create score record
            if ($existingScore) {
                $existingScore->update(['score' => $score]);
            } else {
                StudentAssessment::create([
                    'studentID' => $studentID,
                    'assessmentID' => $assessmentID,
                    'classRecordID' => $classRecordID,
                    'isRequestedToView' => 0,
                    'isRawScoreViewable' => 0,
                    'score' => $score,
                ]);
            }

            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => $actionType,
                'table_name' => 'student_assessment_tbl',
                'old_value' => $oldValue,
                'new_value' => $newValue,
                'description' => "Saved score for {$studentName}: {$score} in {$classRecordDescription} for assessment {$assessmentName}",
                'action_time' => Carbon::now(),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Scores saved successfully.']);
    }

    public function saveRemarks(Request $request)
    {
        Log::debug('Incoming Request:', $request->all());
        $request->validate([
            'assessmentID' => 'required|exists:assessment_tbl,assessmentID',
            'classRecordID' => 'required|exists:class_record_tbl,classRecordID',
            'remarks' => 'array',
            'remarks.*' => 'nullable|string|max:50',  // Max 50 characters for remarks
        ]);

        $assessmentID = $request->input('assessmentID');
        $classRecordID = $request->input('classRecordID');
        $remarks = $request->input('remarks', []);

        // Fetch user and class record details for audit trail
        $userAdmin = Login::with('registration')->where('loginID', session('loginID'))->first();
        $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;

        $classRecord = ClassRecord::find($classRecordID);
        $programCode = $classRecord->program->programCode ?? 'N/A';
        $yearLevel = $classRecord->yearLevel;
        $courseCode = $classRecord->course->courseCode ?? 'N/A';
        $classRecordDescription = "{$programCode} {$yearLevel} {$courseCode}";

        // Fetch assessment name
        $assessment = Assessment::find($assessmentID);
        $assessmentName = $assessment ? $assessment->assessmentName : 'Unknown Assessment';

        // Iterate over remarks and save each, creating an audit trail for each change
        foreach ($remarks as $studentID => $remark) {
            $existingRemark = StudentAssessment::where([
                'studentID' => $studentID,
                'assessmentID' => $assessmentID,
                'classRecordID' => $classRecordID
            ])->first();

            // Retrieve student details for audit log
            $student = Student::find($studentID);
            $studentName = $student->studentLname . ', ' . $student->studentFname;

            $actionType = $existingRemark ? 'Update' : 'Create';

            // Prepare old and new values for the audit trail
            $oldValue = $existingRemark ? json_encode(['remark' => $existingRemark->remark]) : null;
            $newValue = json_encode(['remark' => $remark]);

            // Update or create remark record
            if ($existingRemark) {
                $existingRemark->update(['remarks' => $remark]);
            } else {
                StudentAssessment::create([
                    'studentID' => $studentID,
                    'assessmentID' => $assessmentID,
                    'classRecordID' => $classRecordID,
                    'remarks' => $remark,  // Saving the remark
                    'isRequestedToView' => 0,
                    'isRawScoreViewable' => 0,
                ]);
            }

            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => $actionType,
                'table_name' => 'student_assessment_tbl',
                'old_value' => $oldValue,
                'new_value' => $newValue,
                'description' => "Saved remark for {$studentName}: {$remark} in {$classRecordDescription} for assessment {$assessmentName}",
                'action_time' => Carbon::now(),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Remarks saved successfully.']);
    }



    public function saveScoreAttendance(Request $request)
    {
        $request->validate([
            'assessmentID' => 'required|exists:assessment_tbl,assessmentID',
            'classRecordID' => 'required|exists:class_record_tbl,classRecordID',
            'scores' => 'array',
            'scores.*' => 'nullable',
        ]);

        $assessmentID = $request->input('assessmentID');
        $classRecordID = $request->input('classRecordID');
        $scores = $request->input('scores', []);

        // Fetch user and class record details for audit trail
        $userAdmin = Login::with('registration')->where('loginID', session('loginID'))->first();
        $userName = $userAdmin->registration->Lname . ', ' . $userAdmin->registration->Fname;

        $classRecord = ClassRecord::find($classRecordID);
        $programCode = $classRecord->program->programCode ?? 'N/A';
        $yearLevel = $classRecord->yearLevel;
        $courseCode = $classRecord->course->courseCode ?? 'N/A';
        $classRecordDescription = "{$programCode} {$yearLevel} {$courseCode}";

        // Fetch assessment name
        $assessment = Assessment::find($assessmentID);
        $assessmentName = $assessment ? $assessment->assessmentName : 'Unknown Assessment';

        // Iterate over scores and save each, creating an audit trail for each change
        foreach ($scores as $studentID => $score) {
            $existingScore = StudentAssessment::where([
                'studentID' => $studentID,
                'assessmentID' => $assessmentID,
                'classRecordID' => $classRecordID
            ])->first();

            // Retrieve student details for audit log
            $student = Student::find($studentID);
            $studentName = $student->studentLname . ', ' . $student->studentFname;

            $actionType = $existingScore ? 'Update' : 'Create';

            // Prepare old and new values for the audit trail
            $oldValue = $existingScore ? json_encode(['score' => $existingScore->score]) : null;
            $newValue = json_encode(['score' => $score]);

            // Update or create score record
            if ($existingScore) {
                $existingScore->update(['score' => $score]);
            } else {
                StudentAssessment::create([
                    'studentID' => $studentID,
                    'assessmentID' => $assessmentID,
                    'classRecordID' => $classRecordID,
                    'isRequestedToView' => 0,
                    'isRawScoreViewable' => 0,
                    'score' => $score,
                ]);
            }

            // Create audit trail entry with assessment name in description
            AuditTrail::create([
                'record_id' => session('loginID'),
                'user' => $userName,
                'action' => $actionType,
                'table_name' => 'student_assessment_tbl',
                'old_value' => $oldValue,
                'new_value' => $newValue,
                'description' => "Saved score for {$studentName}: {$score} in {$classRecordDescription} for assessment {$assessmentName}",
                'action_time' => Carbon::now(),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Attendance saved successfully.']);
    }
}
