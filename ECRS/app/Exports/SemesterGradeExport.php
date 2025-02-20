<?php

namespace App\Exports;

use App\Models\ClassRecord;
use App\Models\GradingDistribution;
use App\Models\Grading;
use App\Models\Assessment;
use App\Models\StudentAssessment;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SemesterGradeExport implements WithMultipleSheets
{
    protected $classRecordID;
    protected $recordType;

    public function __construct($classRecordID)
    {
        $this->classRecordID = $classRecordID;

        // Retrieve the recordType from the class record
        $classRecord = ClassRecord::find($this->classRecordID);
        $this->recordType = $classRecord->recordType ?? 1; // Default to 1st term if not found
    }

    public function sheets(): array
    {
        // Initialize the array with the common "Semester Grade" sheet
        $sheets = [
            'Class Record Information' => new ClassRecordInformation($this->classRecordID),
            'Grading Percentage' => new GradingPercentage($this->classRecordID),
            'Semester Grade' => new SemesterGradeSheet($this->classRecordID),
        ];

        // Conditionally add sheets based on the recordType
        if ($this->recordType == 1) {
            // Only show 1st Term
            $sheets['1st Term'] = new FirstTermBreakDown($this->classRecordID);
        } elseif ($this->recordType == 2) {
            // Show 1st Term and 2nd Term
            $sheets['1st Term'] = new FirstTermBreakDown($this->classRecordID);
            $sheets['2nd Term'] = new SecondTermBreakDown($this->classRecordID);
        } elseif ($this->recordType == 3) {
            // Show 1st Term, 2nd Term, and 3rd Term
            $sheets['1st Term'] = new FirstTermBreakDown($this->classRecordID);
            $sheets['2nd Term'] = new SecondTermBreakDown($this->classRecordID);
            $sheets['3rd Term'] = new ThirdTermBreakDown($this->classRecordID);
        }

        return $sheets;
    }
}


class SemesterGradeSheet  implements FromView, WithTitle, WithStyles
{
    protected $classRecordID;

    public function __construct($classRecordID)
    {
        $this->classRecordID = $classRecordID;
    }
    public function styles(Worksheet $sheet)
    {
        // Apply styles
        $sheet->getStyle('A1:D1')->getFont()->setBold(true); // Make first row bold

        // Adjust column widths
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        // Optional: center text horizontally and vertically for all columns
        $sheet->getStyle('C:H')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C:H')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        return [];
    }

    public function view(): View
    {
        $classRecord = ClassRecord::with(['program', 'course', 'students'])
            ->where('classRecordID', $this->classRecordID) // Use $this->classRecordID
            ->first();

        $gradingDistributions = GradingDistribution::where('classRecordID', $this->classRecordID)->get();
        $termDistribution = $gradingDistributions->groupBy('term');

        $grades = DB::table('student_assessment_tbl AS sa')
            ->join('assessment_tbl AS a', 'sa.assessmentID', '=', 'a.assessmentID')
            ->join('grading_tbl AS g', function ($join) {
                $join->on('a.assessmentType', '=', 'g.assessmentType')
                    ->on('a.term', '=', 'g.term')
                    ->where('g.classRecordID', '=', $this->classRecordID);
            })
            ->select(
                'sa.studentID',
                'a.term',
                DB::raw('SUM(sa.score) / SUM(a.totalItem) * 100 * MAX(g.percentage) / 100 AS assessmentGrade')
            )
            ->groupBy('sa.studentID', 'a.term', 'a.assessmentType')
            ->get()
            ->groupBy('studentID')
            ->map(function ($grades, $studentID) use ($termDistribution) {
                $termGrades = [];
                $semestralGrade = 0;
                $isIncomplete = false;

                foreach ([1, 2, 3] as $term) {
                    $termAssessments = $grades->where('term', $term);

                    // Check if the student has scores for all assessments in the term
                    $missingAssessment = DB::table('assessment_tbl AS a')
                        ->leftJoin('student_assessment_tbl AS sa', function ($join) use ($studentID) {
                            $join->on('a.assessmentID', '=', 'sa.assessmentID')
                                ->where('sa.studentID', '=', $studentID);
                        })
                        ->where('a.term', $term)
                        ->where('a.classRecordID', '=', $this->classRecordID) // Restrict to the current class record
                        ->whereNull('sa.score') // Check if score is missing
                        ->exists();

                    $termGrade = $termAssessments->sum('assessmentGrade');
                    $termPercentage = $termDistribution[$term][0]->gradingDistributionPercentage ?? 0;

                    // If any assessment score is missing, mark the term as 'INC'
                    if ($missingAssessment) {
                        $termGrades["term{$term}Grade"] = 'INC';
                        $isIncomplete = true;
                    } else {
                        $termGrades["term{$term}Grade"] = number_format($termGrade, 2);
                        $semestralGrade += $termGrade * ($termPercentage / 100);
                    }
                }

                if ($isIncomplete) {
                    $termGrades['semestralGrade'] = 'INC';
                    $termGrades['pointGrade'] = 'INC';
                    $termGrades['gwa'] = 'INC';
                    $termGrades['remarks'] = 'INC';
                } else {
                    $termGrades['semestralGrade'] = number_format($semestralGrade, 2);
                    $pointGrade = $this->convertToPointGrade($semestralGrade);
                    list($gwa, $remarks) = $this->convertToGWAAndRemarks($pointGrade);

                    $termGrades['pointGrade'] = $pointGrade;
                    $termGrades['gwa'] = $gwa;
                    $termGrades['remarks'] = $remarks;
                }

                return $termGrades;
            });

        return view('faculty.excel.faculty-class-record-semester-excel-grade', [
            'classRecords' => $classRecord,
            'gradingDistributions' => $gradingDistributions,
            'grades' => $grades,
        ]);
    }

    public function title(): string
    {
        return 'Semestral Grade'; // Set the custom worksheet name
    }


    private function convertToPointGrade($semestralGrade)
    {
        if ($semestralGrade == 100) {
            return 1.00;
        } elseif ($semestralGrade >= 99.875 && $semestralGrade <= 99.999) {
            return 1.01;
        } elseif ($semestralGrade >= 99.750 && $semestralGrade <= 99.874) {
            return 1.02;
        } elseif ($semestralGrade >= 99.625 && $semestralGrade <= 99.749) {
            return 1.03;
        } elseif ($semestralGrade >= 99.500 && $semestralGrade <= 99.624) {
            return 1.04;
        } elseif ($semestralGrade >= 99.125 && $semestralGrade <= 99.499) {
            return 1.07;
        } elseif ($semestralGrade >= 99.000 && $semestralGrade <= 99.124) {
            return 1.08;
        } elseif ($semestralGrade >= 98.875 && $semestralGrade <= 98.999) {
            return 1.09;
        } elseif ($semestralGrade >= 98.750 && $semestralGrade <= 98.874) {
            return 1.10;
        } elseif ($semestralGrade >= 98.625 && $semestralGrade <= 98.749) {
            return 1.11;
        } elseif ($semestralGrade >= 98.500 && $semestralGrade <= 98.624) {
            return 1.12;
        } elseif ($semestralGrade >= 98.375 && $semestralGrade <= 98.499) {
            return 1.13;
        } elseif ($semestralGrade >= 98.250 && $semestralGrade <= 98.374) {
            return 1.14;
        } elseif ($semestralGrade >= 98.125 && $semestralGrade <= 98.249) {
            return 1.15;
        } elseif ($semestralGrade >= 98.000 && $semestralGrade <= 98.124) {
            return 1.16;
        } elseif ($semestralGrade >= 97.000 && $semestralGrade <= 97.999) {
            return 1.124;
        } elseif ($semestralGrade >= 96.000 && $semestralGrade <= 96.999) {
            return 1.132;
        } elseif ($semestralGrade >= 95.000 && $semestralGrade <= 95.999) {
            return 1.140;
        } elseif ($semestralGrade >= 94.000 && $semestralGrade <= 94.999) {
            return 1.148;
        } elseif ($semestralGrade >= 93.000 && $semestralGrade <= 93.999) {
            return 1.156;
        } elseif ($semestralGrade >= 92.000 && $semestralGrade <= 92.999) {
            return 1.164;
        } elseif ($semestralGrade >= 91.000 && $semestralGrade <= 91.999) {
            return 1.172;
        } elseif ($semestralGrade >= 90.000 && $semestralGrade <= 90.999) {
            return 1.18;
        } elseif ($semestralGrade >= 89.000 && $semestralGrade <= 89.999) {
            return 1.88;
        } elseif ($semestralGrade >= 88.000 && $semestralGrade <= 88.999) {
            return 1.96;
        } elseif ($semestralGrade >= 87.000 && $semestralGrade <= 87.999) {
            return 2.04;
        } elseif ($semestralGrade >= 86.000 && $semestralGrade <= 86.999) {
            return 2.12;
        } elseif ($semestralGrade >= 85.000 && $semestralGrade <= 85.999) {
            return 2.20;
        } elseif ($semestralGrade >= 84.000 && $semestralGrade <= 84.999) {
            return 2.28;
        } elseif ($semestralGrade >= 83.000 && $semestralGrade <= 83.999) {
            return 2.36;
        } elseif ($semestralGrade >= 82.000 && $semestralGrade <= 82.999) {
            return 2.44;
        } elseif ($semestralGrade >= 81.000 && $semestralGrade <= 81.999) {
            return 2.52;
        } elseif ($semestralGrade >= 80.000 && $semestralGrade <= 80.999) {
            return 2.60;
        } elseif ($semestralGrade >= 79.000 && $semestralGrade <= 79.999) {
            return 2.68;
        } elseif ($semestralGrade >= 78.000 && $semestralGrade <= 78.999) {
            return 2.76;
        } elseif ($semestralGrade >= 77.000 && $semestralGrade <= 77.999) {
            return 2.84;
        } elseif ($semestralGrade >= 76.000 && $semestralGrade <= 76.999) {
            return 2.92;
        } elseif ($semestralGrade >= 75.000 && $semestralGrade <= 75.999) {
            return 3.00;
        } elseif ($semestralGrade >= 74.000 && $semestralGrade <= 74.999) {
            return 3.08;
        } elseif ($semestralGrade >= 73.000 && $semestralGrade <= 73.999) {
            return 3.16;
        } elseif ($semestralGrade >= 72.000 && $semestralGrade <= 72.999) {
            return 3.24;
        } elseif ($semestralGrade >= 71.000 && $semestralGrade <= 71.999) {
            return 3.32;
        } elseif ($semestralGrade >= 70.000 && $semestralGrade <= 70.999) {
            return 3.40;
        } else {
            return 5.00;
        }
    }
    private function convertToGWAAndRemarks($pointGrade)
    {
        if ($pointGrade >= 1.000 && $pointGrade <= 1.125) {
            return ["1.00", "Passed"];
        } elseif ($pointGrade >= 1.126 && $pointGrade <= 1.375) {
            return ["1.25", "Passed"];
        } elseif ($pointGrade >= 1.376 && $pointGrade <= 1.625) {
            return ["1.50", "Passed"];
        } elseif ($pointGrade >= 1.626 && $pointGrade <= 1.875) {
            return ["1.75", "Passed"];
        } elseif ($pointGrade >= 1.876 && $pointGrade <= 2.125) {
            return ["2.00", "Passed"];
        } elseif ($pointGrade >= 2.126 && $pointGrade <= 2.375) {
            return ["2.25", "Passed"];
        } elseif ($pointGrade >= 2.376 && $pointGrade <= 2.625) {
            return ["2.50", "Passed"];
        } elseif ($pointGrade >= 2.626 && $pointGrade <= 2.875) {
            return ["2.75", "Passed"];
        } elseif ($pointGrade >= 2.876 && $pointGrade <= 3.125) {
            return ["3.00", "Passed"];
        } elseif ($pointGrade >= 3.126 && $pointGrade <= 5.00) {
            return ["5.00", "Failed"];
        } else {
            return ["Incomplete", "Incomplete"];
        }
    }
}


class FirstTermBreakDown implements FromView, WithTitle, WithStyles
{
    protected $classRecordID;

    public function __construct($classRecordID)
    {
        $this->classRecordID = $classRecordID;
    }

    public function styles(Worksheet $sheet)
    {
        // Apply styles to the first row (headers)
        $sheet->getStyle('A1:Z1')->getFont()->setBold(true);

        // Adjust column widths for specific columns
        $sheet->getColumnDimension('A')->setWidth(30); // Set width for column A
        $sheet->getColumnDimension('B')->setWidth(30); // Set width for column B

        // Adjust widths for columns C to Z
        foreach (range('C', 'Z') as $column) {
            $sheet->getColumnDimension($column)->setWidth(20); // Set desired width for each column
        }

        // Optional: center text horizontally and vertically for all columns
        $sheet->getStyle('C:Z')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C:Z')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        return [];
    }

    public function view(): View
    {
        // Fetch the list of students for the class record
        $students = Student::whereHas('classrecord', function ($query) {
            $query->where('classRecordID', $this->classRecordID);
        })->get();

        // Fetch distinct assessment types for the 1st term
        $assessmentTypes = Grading::where('classRecordID', $this->classRecordID)
            ->where('term', 1)
            ->distinct()
            ->pluck('assessmentType')
            ->map(function ($type) {
                return strtolower(trim($type)); // Normalize assessment types
            })
            ->toArray();

        // Fetch assessment titles and total items from Assessment table for all activities in the 1st term
        $assessmentTitles = Assessment::where('classRecordID', $this->classRecordID)
            ->where('term', 1)
            ->get(['assessmentType', 'assessmentName', 'totalItem']);

        // Organize assessment titles by assessmentType and sum total items
        $organizedAssessmentTitles = [];
        $totalItemsData = [];
        foreach ($assessmentTitles as $assessment) {
            $type = strtolower(trim($assessment->assessmentType));
            $organizedAssessmentTitles[$type][] = $assessment->assessmentName;

            // Sum total items for each assessment type
            $totalItemsData[$type] = ($totalItemsData[$type] ?? 0) + $assessment->totalItem;
        }

        // Combine total items data with default values
        $combinedTotalItems = array_fill_keys($assessmentTypes, 0);
        $combinedTotalItems = array_merge($combinedTotalItems, $totalItemsData);

        // Fetch percentages for each assessment type in the 1st term
        $assessmentData = Grading::where('classRecordID', $this->classRecordID)
            ->where('term', 1)
            ->get(['assessmentType', 'percentage'])
            ->keyBy(function ($item) {
                return strtolower(trim($item->assessmentType));
            });

        $studentScores = [];

        // Fetch the student scores for each assessment type
        foreach ($assessmentTypes as $type) {
            $gradingInfo = $assessmentData->get($type);

            // Get assessment IDs for the specific assessment type in the 1st term
            $assessmentIDs = Assessment::where('classRecordID', $this->classRecordID)
                ->where('assessmentType', $type)
                ->where('term', 1)
                ->pluck('assessmentID');

            // Fetch scores for each student based on assessment IDs
            $scores = StudentAssessment::whereIn('assessmentID', $assessmentIDs)
                ->where('classRecordID', $this->classRecordID)
                ->get();

            // Store raw scores for each student and assessment type
            foreach ($scores as $score) {
                $studentID = $score->studentID;

                // Initialize student score data if not set
                if (!isset($studentScores[$studentID])) {
                    $studentScores[$studentID] = [
                        'rawScores' => [],
                        'finalScore' => 0,
                    ];
                }

                // Store the raw score for this assessment type
                $studentScores[$studentID]['rawScores'][$type][] = $score->score;

                // Calculate final score
                $totalItem = $combinedTotalItems[$type] ?? 1; // Avoid division by zero
                $percentage = $gradingInfo->percentage ?? 0;

                // Ensure score is valid and percentage is not zero
                if ($totalItem > 0 && $percentage > 0) {
                    $finalScore = ($score->score / $totalItem) * $percentage; // Calculate weighted score
                    $studentScores[$studentID]['finalScore'] += $finalScore;
                }
            }
        }
        // dd($studentScores);
        return view('faculty.excel.faculty-class-record-1stTerm-breakdown', [
            'students' => $students,
            'studentScores' => $studentScores,
            'assessmentTitles' => $organizedAssessmentTitles,
            'combinedTotalItems' => $combinedTotalItems,
            'assessmentData' => $assessmentData,
        ]);
    }

    public function title(): string
    {
        return '1st Term'; // Set the custom worksheet name
    }
}

class SecondTermBreakDown implements FromView, WithTitle, WithStyles
{
    protected $classRecordID;

    public function __construct($classRecordID)
    {
        $this->classRecordID = $classRecordID;
    }

    public function styles(Worksheet $sheet)
    {
        // Apply styles to the first row (headers)
        $sheet->getStyle('A1:Z1')->getFont()->setBold(true);

        // Adjust column widths for specific columns
        $sheet->getColumnDimension('A')->setWidth(30); // Set width for column A
        $sheet->getColumnDimension('B')->setWidth(30); // Set width for column B

        // Adjust widths for columns C to Z
        foreach (range('C', 'Z') as $column) {
            $sheet->getColumnDimension($column)->setWidth(20); // Set desired width for each column
        }

        // Optional: center text horizontally and vertically for all columns
        $sheet->getStyle('C:Z')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C:Z')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        return [];
    }

    public function view(): View
    {
        // Fetch the list of students for the class record
        $students = Student::whereHas('classrecord', function ($query) {
            $query->where('classRecordID', $this->classRecordID);
        })->get();

        // Fetch distinct assessment types for the 2nd term
        $assessmentTypes = Grading::where('classRecordID', $this->classRecordID)
            ->where('term', 2)
            ->distinct()
            ->pluck('assessmentType')
            ->map(function ($type) {
                return strtolower(trim($type)); // Normalize assessment types
            })
            ->toArray();

        // Fetch assessment titles and total items from Assessment table for all activities in the 2nd term
        $assessmentTitles = Assessment::where('classRecordID', $this->classRecordID)
            ->where('term', 2)
            ->get(['assessmentType', 'assessmentName', 'totalItem']);

        // Organize assessment titles by assessmentType and sum total items
        $organizedAssessmentTitles = [];
        $totalItemsData = [];
        foreach ($assessmentTitles as $assessment) {
            $type = strtolower(trim($assessment->assessmentType));
            $organizedAssessmentTitles[$type][] = $assessment->assessmentName;

            // Sum total items for each assessment type
            $totalItemsData[$type] = ($totalItemsData[$type] ?? 0) + $assessment->totalItem;
        }

        // Combine total items data with default values
        $combinedTotalItems = array_fill_keys($assessmentTypes, 0);
        $combinedTotalItems = array_merge($combinedTotalItems, $totalItemsData);

        // Fetch percentages for each assessment type in the 2nd term
        $assessmentData = Grading::where('classRecordID', $this->classRecordID)
            ->where('term', 2)
            ->get(['assessmentType', 'percentage'])
            ->keyBy(function ($item) {
                return strtolower(trim($item->assessmentType));
            });

        $studentScores = [];

        // Fetch the student scores for each assessment type
        foreach ($assessmentTypes as $type) {
            $gradingInfo = $assessmentData->get($type);

            // Get assessment IDs for the specific assessment type in the 2nd term
            $assessmentIDs = Assessment::where('classRecordID', $this->classRecordID)
                ->where('assessmentType', $type)
                ->where('term', 2)
                ->pluck('assessmentID');

            // Fetch scores for each student based on assessment IDs
            $scores = StudentAssessment::whereIn('assessmentID', $assessmentIDs)
                ->where('classRecordID', $this->classRecordID)
                ->get();

            // Store raw scores for each student and assessment type
            foreach ($scores as $score) {
                $studentID = $score->studentID;

                // Initialize student score data if not set
                if (!isset($studentScores[$studentID])) {
                    $studentScores[$studentID] = [
                        'rawScores' => [],
                        'finalScore' => 0,
                    ];
                }

                // Store the raw score for this assessment type
                $studentScores[$studentID]['rawScores'][$type][] = $score->score;

                // Calculate final score
                $totalItem = $combinedTotalItems[$type] ?? 1; // Avoid division by zero
                $percentage = $gradingInfo->percentage ?? 0;

                // Ensure score is valid and percentage is not zero
                if ($totalItem > 0 && $percentage > 0) {
                    $finalScore = ($score->score / $totalItem) * $percentage; // Calculate weighted score
                    $studentScores[$studentID]['finalScore'] += $finalScore;
                }
            }
        }
        // dd($studentScores);
        return view('faculty.excel.faculty-class-record-2ndTerm-breakdown', [
            'students' => $students,
            'studentScores' => $studentScores,
            'assessmentTitles' => $organizedAssessmentTitles,
            'combinedTotalItems' => $combinedTotalItems,
            'assessmentData' => $assessmentData,
        ]);
    }

    public function title(): string
    {
        return '2nd Term'; // Set the custom worksheet name
    }
}

class ThirdTermBreakDown implements FromView, WithTitle, WithStyles
{
    protected $classRecordID;

    public function __construct($classRecordID)
    {
        $this->classRecordID = $classRecordID;
    }

    public function styles(Worksheet $sheet)
    {
        // Apply styles to the first row (headers)
        $sheet->getStyle('A1:Z1')->getFont()->setBold(true);

        // Adjust column widths for specific columns
        $sheet->getColumnDimension('A')->setWidth(30); // Set width for column A
        $sheet->getColumnDimension('B')->setWidth(30); // Set width for column B

        // Adjust widths for columns C to Z
        foreach (range('C', 'Z') as $column) {
            $sheet->getColumnDimension($column)->setWidth(20); // Set desired width for each column
        }

        // Optional: center text horizontally and vertically for all columns
        $sheet->getStyle('C:Z')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C:Z')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        return [];
    }

    public function view(): View
    {
        // Fetch the list of students for the class record
        $students = Student::whereHas('classrecord', function ($query) {
            $query->where('classRecordID', $this->classRecordID);
        })->get();

        // Fetch distinct assessment types for the 3rd term
        $assessmentTypes = Grading::where('classRecordID', $this->classRecordID)
            ->where('term', 3)
            ->distinct()
            ->pluck('assessmentType')
            ->map(function ($type) {
                return strtolower(trim($type)); // Normalize assessment types
            })
            ->toArray();

        // Fetch assessment titles and total items from Assessment table for all activities in the 3rd term
        $assessmentTitles = Assessment::where('classRecordID', $this->classRecordID)
            ->where('term', 3)
            ->get(['assessmentType', 'assessmentName', 'totalItem']);

        // Organize assessment titles by assessmentType and sum total items
        $organizedAssessmentTitles = [];
        $totalItemsData = [];
        foreach ($assessmentTitles as $assessment) {
            $type = strtolower(trim($assessment->assessmentType));
            $organizedAssessmentTitles[$type][] = $assessment->assessmentName;

            // Sum total items for each assessment type
            $totalItemsData[$type] = ($totalItemsData[$type] ?? 0) + $assessment->totalItem;
        }

        // Combine total items data with default values
        $combinedTotalItems = array_fill_keys($assessmentTypes, 0);
        $combinedTotalItems = array_merge($combinedTotalItems, $totalItemsData);

        // Fetch percentages for each assessment type in the 3rd term
        $assessmentData = Grading::where('classRecordID', $this->classRecordID)
            ->where('term', 3)
            ->get(['assessmentType', 'percentage'])
            ->keyBy(function ($item) {
                return strtolower(trim($item->assessmentType));
            });

        $studentScores = [];

        // Fetch the student scores for each assessment type
        foreach ($assessmentTypes as $type) {
            $gradingInfo = $assessmentData->get($type);

            // Get assessment IDs for the specific assessment type in the 3rd term
            $assessmentIDs = Assessment::where('classRecordID', $this->classRecordID)
                ->where('assessmentType', $type)
                ->where('term', 3)
                ->pluck('assessmentID');

            // Fetch scores for each student based on assessment IDs
            $scores = StudentAssessment::whereIn('assessmentID', $assessmentIDs)
                ->where('classRecordID', $this->classRecordID)
                ->get();

            // Store raw scores for each student and assessment type
            foreach ($scores as $score) {
                $studentID = $score->studentID;

                // Initialize student score data if not set
                if (!isset($studentScores[$studentID])) {
                    $studentScores[$studentID] = [
                        'rawScores' => [],
                        'finalScore' => 0,
                    ];
                }

                // Store the raw score for this assessment type
                $studentScores[$studentID]['rawScores'][$type][] = $score->score;

                // Calculate final score
                $totalItem = $combinedTotalItems[$type] ?? 1; // Avoid division by zero
                $percentage = $gradingInfo->percentage ?? 0;

                // Ensure score is valid and percentage is not zero
                if ($totalItem > 0 && $percentage > 0) {
                    $finalScore = ($score->score / $totalItem) * $percentage; // Calculate weighted score
                    $studentScores[$studentID]['finalScore'] += $finalScore;
                }
            }
        }
        // dd($studentScores);
        return view('faculty.excel.faculty-class-record-3rdTerm-breakdown', [
            'students' => $students,
            'studentScores' => $studentScores,
            'assessmentTitles' => $organizedAssessmentTitles,
            'combinedTotalItems' => $combinedTotalItems,
            'assessmentData' => $assessmentData,
        ]);
    }

    public function title(): string
    {
        return '3rd Term'; // Set the custom worksheet name
    }
}

class ClassRecordInformation implements FromView, WithTitle, WithStyles
{
    protected $classRecordID;

    public function __construct($classRecordID)
    {
        $this->classRecordID = $classRecordID;
    }

    public function styles(Worksheet $sheet)
    {
        // Apply styles to the first row (headers)
        $sheet->getStyle('A1:Z1')->getFont()->setBold(true);

        // Adjust column widths for specific columns
        $sheet->getColumnDimension('A')->setWidth(30); // Set width for column A
        $sheet->getColumnDimension('B')->setWidth(30); // Set width for column B


        return [];
    }

    public function view(): View
    {
        $classRecord = ClassRecord::with(['program', 'course', 'schedules', 'login.registration'])->find($this->classRecordID);

        $branchDescription = null;
        if ($classRecord && $classRecord->branch) {
            // Fetch the branch description based on branchID
            $branch = DB::table('branch_tbl')->where('branchID', $classRecord->branch)->first();
            $branchDescription = $branch ? $branch->branchDescription : 'No branch description available';
        }

        return view('faculty.excel.faculty-class-record-information', [
            'classRecord' => $classRecord,
            'branchDescription' => $branchDescription
        ]);
    }

    public function title(): string
    {
        return 'Class Record Information';
    }
}

class GradingPercentage implements FromView, WithTitle, WithStyles
{
    protected $classRecordID;

    public function __construct($classRecordID)
    {
        $this->classRecordID = $classRecordID;
    }

    public function styles(Worksheet $sheet)
    {
        // Apply styles to the first row (headers)
        $sheet->getStyle('A1:Z1')->getFont()->setBold(true);

        foreach (range('A', 'Z') as $column) {
            $sheet->getColumnDimension($column)->setWidth(20); // Set desired width for each column
        }

        $sheet->getStyle('A:Z')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:Z')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        return [];
    }

    public function view(): View
    {
        // Retrieve the class record
        $classRecord = ClassRecord::find($this->classRecordID);

        // Fetch grading distribution for the class record grouped by term
        $gradingDistribution = GradingDistribution::where('classRecordID', $this->classRecordID)
            ->orderBy('term')
            ->get()
            ->groupBy('term');

        // Fetch grading details for the class record grouped by term and isExamination
        $grading = Grading::where('classRecordID', $this->classRecordID)
            ->orderBy('term')
            ->get()
            ->groupBy(function ($item) {
                return $item->term . '-' . ($item->isExamination ? 'exam' : 'class');
            });

        return view('faculty.excel.faculty-class-record-grading-percentage', [
            'classRecord' => $classRecord,
            'gradingDistribution' => $gradingDistribution,
            'grading' => $grading,
        ]);
    }
    public function title(): string
    {
        return 'Grading Percentage';
    }
}
