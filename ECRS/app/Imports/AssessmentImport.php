<?php

namespace App\Imports;

use App\Models\Assessment;
use App\Models\StudentAssessment;
use App\Models\Student;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssessmentImport implements ToModel, WithHeadingRow
{
    protected $classRecordID;
    protected $assessmentType;
    protected $gradingTerm;
    protected $assessment;

    public function __construct($classRecordID, $assessmentType, $gradingTerm)
    {
        $this->classRecordID = $classRecordID;
        $this->assessmentType = $assessmentType;
        $this->gradingTerm = $gradingTerm;
        $this->assessment = null; // Initialize as null
    }

    public function model(array $row)
    {
        // Skip rows that are entirely empty or consist of null values, excluding 'remarks'
        $filteredRow = array_filter($row, function ($value, $key) {
            return $key !== 'remarks' && ($value !== null && $value !== ''); // Ensure 0 is not excluded
        }, ARRAY_FILTER_USE_BOTH);        
        

        if (empty($filteredRow)) {
            Log::warning('Skipping row with all fields empty except remarks', ['row' => $row]);
            return;
        }

        // Log the row data to verify that the 'classRecordID' is being provided correctly
        Log::info('Processing row', ['row' => $row]);

        // Validate the classRecordID in the Excel file
        if (!$this->isValidClassRecordID($row)) {
            Log::error('Invalid classRecordID in Excel file', [
                'expected_classRecordID' => $this->classRecordID,
                'provided_classRecordID' => $row['classrecordid'] ?? 'not provided',
            ]);

            throw new \Exception('Invalid classRecordID in Excel file. Import aborted.');
        }

        // Process the assessment or student data based on the row type
        if ($this->isAssessmentSheet($row)) {
            if (empty($row['total_item']) || !is_numeric($row['total_item'])) {
                Log::error('Invalid total_item value in assessment sheet', ['row' => $row]);
                return;
            }

            $this->createOrRetrieveAssessment($row);
        } elseif ($this->isStudentSheet($row)) {
            if ($this->assessment === null) {
                Log::error('No assessment found before processing student data', [
                    'assessmentType' => $this->assessmentType,
                    'classrecordid' => $this->classRecordID,
                    'term' => $this->gradingTerm,
                ]);
                return;
            }

            $this->processStudentData($row);
        }
    }

    private function isValidClassRecordID(array $row)
    {
        // Get the provided classRecordID from the row and trim any spaces
        $providedClassRecordID = trim($row['classrecordid'] ?? null);

        // Ensure both provided and expected classRecordID are integers for comparison
        if ((int)$providedClassRecordID !== (int)$this->classRecordID) {
            Log::error('ClassRecordID mismatch', [
                'expected' => $this->classRecordID,
                'provided' => $providedClassRecordID,
            ]);
            return false;
        }
        return true;
    }

    private function isAssessmentSheet(array $row): bool
    {
        return isset($row['assessment_type']) && isset($row['total_item']);
    }

    private function isStudentSheet(array $row): bool
    {
        return isset($row['student_no']) && isset($row['scores']);
    }

    private function createOrRetrieveAssessment(array $row)
    {
        // Check if an assessment with the same name, classRecordID, and gradingTerm already exists
        $existingAssessment = Assessment::where('assessmentName', $row['assessment_name'])
            ->where('classRecordID', $this->classRecordID)
            ->where('term', $this->gradingTerm)
            ->first();

        if ($existingAssessment) {
            Log::warning('Assessment already exists', [
                'assessmentName' => $row['assessment_name'],
                'classRecordID' => $this->classRecordID,
                'term' => $this->gradingTerm,
            ]);
            throw new \Exception('Assessment already exists');
        }

        // Create a new assessment if it doesn't exist
        $this->assessment = Assessment::create([
            'assessmentType' => $this->assessmentType,
            'assessmentName' => $row['assessment_name'],
            'totalItem' => $row['total_item'],
            'passingItem' => ($row['total_item'] * ($row['passing_percentage'])) / 100,
            'assessmentDate' => isset($row['assessment_date']) ? Carbon::parse($row['assessment_date'])->format('Y-m-d') : now(),
            'term' => $this->gradingTerm,
            'classRecordID' => $this->classRecordID,
            'isPublished' => 0,
        ]);

        Log::info('Assessment created successfully', [
            'assessmentID' => $this->assessment->assessmentID,
            'assessmentType' => $this->assessmentType,
            'classRecordID' => $this->classRecordID,
            'term' => $this->gradingTerm,
        ]);
    }

    private function processStudentData(array $row)
    {
        // Skip the header row or invalid rows
        if (!isset($row['scores'])) {
            Log::warning('Skipping invalid or empty row', ['row' => $row]);
            return;
        }
        
    
        $studentNo = $row['student_no'] ?? null;
        $scores = $row['scores'] ?? null;
        $remarks = $row['remarks'] ?? null;
    
        // Ensure classRecordID is assigned from the row or fallback to the constructor's value
        $classRecordID = $row['classrecordid'] ?? $this->classRecordID;
    
        // Log the processing information
        Log::info('Processing student data', [
            'student_no' => $studentNo,
            'scores' => $scores,
            'classRecordID' => $classRecordID
        ]);
    
        // Ensure classRecordID is not null or empty
        if (empty($classRecordID)) {
            Log::error('Missing classRecordID in the row or constructor', [
                'student_no' => $studentNo,
                'scores' => $scores,
                'row_data' => $row
            ]);
            return; // Skip processing this row
        }
    
        // Check for valid student data (no missing student number or scores)
        if (is_null($studentNo) || is_null($scores)) {
            Log::warning('Invalid row: Student No or Scores are null', [
                'student_no' => $studentNo,
                'scores' => $scores
            ]);
            return;
        }
    
        // Ensure $this->assessment is not null
        if ($this->assessment === null) {
            Log::error('Assessment object is null', [
                'student_no' => $studentNo,
            ]);
            return; // Exit early if assessment is not set
        }

        // Validate that the score does not exceed the totalItem
        if ($scores > $this->assessment->totalItem) {
            Log::error('Score exceeds totalItem', [
                'student_no' => $studentNo,
                'scores' => $scores,
                'totalItem' => $this->assessment->totalItem
            ]);
            throw new \Exception('Score exceeds totalItem for student: ' . $studentNo);
        }
    
        // Fetch the student record based on studentNo and classRecordID
        $student = Student::where('studentNo', $studentNo)
            ->where('classRecordID', $classRecordID)
            ->first();
    
        // Log and return if the student record is not found
        if (!$student) {
            Log::warning('Student not found', [
                'student_no' => $studentNo,
                'classRecordID' => $classRecordID
            ]);
            return;
        }
    
        // Ensure an assessment is available before creating a student assessment record
        if (!$this->assessment) {
            Log::error('No assessment found for processing student data', [
                'assessmentID' => $this->assessmentType,
                'classRecordID' => $this->classRecordID,
                'term' => $this->gradingTerm
            ]);
            return;
        }
    
        // Create the student assessment record
        StudentAssessment::create([
            'studentID' => $student->studentID,
            'assessmentID' => $this->assessment->assessmentID,
            'classRecordID' => $classRecordID,
            'score' => $scores,
            'remarks' => $remarks,
            'isRequestedToView' => 0,
            'isRawScoreViewable' => 0,
        ]);
    
        Log::info('Student assessment record created successfully', [
            'student_no' => $studentNo,
            'assessmentID' => $this->assessment->assessmentID
        ]);
    }
}

