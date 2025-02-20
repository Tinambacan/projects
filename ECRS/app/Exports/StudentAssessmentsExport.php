<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class StudentAssessmentsExport implements WithMultipleSheets
{
    protected $studentAssessments;
    protected $gradingPercentages;
    protected $studentInfo;

    public function __construct($studentAssessments, $gradingPercentages, $studentInfo)
    {
        $this->studentAssessments = $studentAssessments;
        $this->gradingPercentages = $gradingPercentages;
        $this->studentInfo = $studentInfo;
    }

    public function sheets(): array
    {
        return [
            new StudentInfoSheet($this->studentInfo),
            new StudentGradesSheet($this->studentAssessments, $this->gradingPercentages),
        ];
    }
}

class StudentGradesSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $studentAssessments;
    protected $gradingPercentages;

    public function __construct($studentAssessments, $gradingPercentages)
    {
        $this->studentAssessments = $studentAssessments;
        $this->gradingPercentages = $gradingPercentages;
    }

    public function collection()
    {
        return $this->studentAssessments->map(function ($assessment) {
            $gradingPercentage = $this->gradingPercentages[strtolower($assessment->assessment->assessmentType)] ?? 0;
            $score = $assessment->score ?? 0;
            $totalItems = $assessment->assessment->totalItem ?? 0;
            $formattedPercentage = $totalItems > 0 ? number_format(($score / $totalItems) * $gradingPercentage, 2) : 0;

            return [
                'Assessment Name' => $assessment->assessment->assessmentName,
                'Assessment Type' => $assessment->assessment->assessmentType,
                'Date of Assessment' => $assessment->assessment->assessmentDate,
                'Score' => $score,
                'Total Items' => $totalItems,
                'Grading Percentage' => $formattedPercentage,
                'Date Encoded' => $assessment->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Assessment Name',
            'Assessment Type',
            'Date of Assessment',
            'Score',
            'Total Items',
            'Grading Percentage',
            'Date Encoded',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);

        $sheet->getStyle('A:G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:G')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        return [];
    }

    public function title(): string
    {
        return 'Student Grades';
    }
}

class StudentInfoSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $studentInfo;

    public function __construct($studentInfo)
    {
        $this->studentInfo = $studentInfo;
    }

    public function collection()
    {
        // Convert studentInfo array to a collection
        return collect($this->studentInfo);
    }

    public function headings(): array
    {
        return [
            'Information',
            'Fields',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:B1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(30);

        $sheet->getStyle('A:B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:B')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        return [];
    }

    public function title(): string
    {
        return 'Student Information';
    }
}
