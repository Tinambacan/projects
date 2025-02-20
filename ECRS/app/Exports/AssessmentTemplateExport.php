<?php

namespace App\Exports;

use App\Models\ClassRecord;
use App\Models\Student;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromArray;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Protection;

class AssessmentTemplateExport implements WithMultipleSheets
{
    protected $classRecordID;
    protected $assessmentType;

    public function __construct($classRecordID, $assessmentType)
    {
        $this->classRecordID = $classRecordID;
        $this->assessmentType = $assessmentType;
    }

    public function sheets(): array
    {
        return [
            new AssessmentDetailsSheet($this->classRecordID, $this->assessmentType),
            new StudentScoresSheet($this->classRecordID),
        ];
    }
}

class AssessmentDetailsSheet implements FromArray, WithTitle, WithStyles
{
    protected $classRecordID;
    protected $assessmentType;

    public function __construct($classRecordID, $assessmentType)
    {
        $this->classRecordID = $classRecordID;
        $this->assessmentType = $assessmentType;
    }

    public function array(): array
    {
        $classRecord = ClassRecord::find($this->classRecordID);

        // Field names (Row 1) and corresponding results (Row 2)
        return [
            // First row: field names
            ['Class Record', 'Assessment Type', 'Assessment Name', 'Assessment Date', 'Total Item', 'Passing Percentage', 'classrecordid'],
            // Second row: results
            [
                $classRecord->program->programCode . ' ' . $classRecord->yearLevel . ' ' . $classRecord->course->courseTitle,
                $this->assessmentType,
                'Sample Title',
                now()->format('Y-m-d'),
                100,
                50,
                $this->classRecordID, // This will be hidden
            ],
        ];
    }

    public function title(): string
    {
        return 'Assessment Details';
    }
    public function styles(Worksheet $sheet)
    {
        // Enable sheet protection
        $sheet->getProtection()->setSheet(true);
        $sheet->getProtection()->setPassword('password123'); // Replace with a strong password

        // Unlock all cells by default
        $sheet->getStyle('A1:G2')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);

        // Lock the header row (Row 1)
        $sheet->getStyle('A1:G1')->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);

        // Lock the `classRecordID` column (Column G)
        $sheet->getStyle('G1:G2')->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);

        // Lock `A2` and `B2`
       $sheet->getStyle('A2:B2')->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);

        // Hide the `classRecordID` column
        $sheet->getColumnDimension('G')->setVisible(false);

        // Set styles for headers (field names)
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(60);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);

        // Align cells
        $sheet->getStyle('A:F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:F')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Align `classRecordID` column to the right
        $sheet->getStyle('G:G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        return [];
    }
}
class StudentScoresSheet implements FromArray, WithTitle, WithStyles
{
    protected $classRecordID;

    public function __construct($classRecordID)
    {
        $this->classRecordID = $classRecordID;
    }

    public function array(): array
{
    // Get all students for the given classRecordID
    $students = Student::where('classRecordID', $this->classRecordID)->get();

    // Initialize the data array with headers, including the 'Class Record ID' column
    $data = [
        ['Student No', 'Student Name', 'Scores', 'Remarks', 'classrecordid'],
    ];

    // Add each student data row, including the classRecordID in the last column
    foreach ($students as $student) {
        $data[] = [
            $student->studentNo,
            $student->studentLname . ' ' . $student->studentFname,
            '',  // Scores column (empty initially)
            '',  // Remarks column (empty initially)
            $this->classRecordID,  // Add classRecordID in the last column
        ];
    }

    return $data;
}


    public function styles(Worksheet $sheet)
    {
        // Enable sheet protection with optional password
        $sheet->getProtection()->setSheet(true);
        $sheet->getProtection()->setPassword('password123'); // Optional

        // Unlock all cells by default
        $sheet->getStyle('A1:D1000')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);

        // Lock all cells in columns A and B
        $sheet->getStyle('A1:A1000')->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);
        $sheet->getStyle('B1:B1000')->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);

        $sheet->getColumnDimension('E')->setVisible(false);

        // Set styles for the header row
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(30);

        // Align cells
        $sheet->getStyle('A:D')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:D')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        return [];
    }


    public function title(): string
    {
        return 'Student Scores';
    }
}
