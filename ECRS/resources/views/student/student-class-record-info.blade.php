<!DOCTYPE html>
@extends('layout.StudentClassRecordLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/js/app.js')
    {{-- @vite('resources/js/stud-assess-table.js') --}}
</head>

@section('studclassrecordcontent')

    <body>
        <div
            class="flex flex-col gap-4 overflow-auto scrollbar-thin scrollbar-thumb-[#CCAA2C] scrollbar-track-gray-300 scroll-smooth">
            @if ($studentAssessments->isNotEmpty())
                @php
                    $totalGrade = 0;
                    
                    $publishedGrading = $gradingDistributions->firstWhere(
                        fn($grading) => strtolower($grading['gradingDistributionType']) === strtolower($selectedTab)
                    );

                    $assessmentsGroupedByType = $studentAssessments->groupBy(function ($item) {
                        return strtolower($item->assessment->assessmentType);
                    });

                    $assessmentTypes = $assessmentsGroupedByType->keys();
                @endphp

                @foreach ($assessmentTypes as $type)
                    <div class="border rounded-lg overflow-hidden">
                        <div class="relative">
                            <div class="sticky left-0 right-0 top-0 z-20 bg-[#CCAA2C] shadow-lg">
                                <div class="px-6 py-3 text-center text-lg font-medium uppercase tracking-wider text-white">
                                    {{ $type }}
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full table-auto  text-black">
                                    <thead class="bg-gray-50">
                                        {{-- <tr class="shadow-lg border-b-2 border-white text-white sticky w-full">
                                            <th colspan="7"
                                                class="px-6 py-3 text-center text-lg font-medium uppercase tracking-wider rounded-t-md bg-[#CCAA2C] w-full">
                                                {{ $type }}
                                            </th>
                                        </tr> --}}
                                        <tr class="divide-x divide-gray-200">
                                            <th
                                                class="px-6 py-3 text-center font-medium whitespace-nowrap sticky left-0 bg-gray-50 z-10">
                                                Assessment Title</th>
                                            <th class="px-6 py-3 text-center font-medium">Date of {{ ucwords($type) }}</th>
                                            @if ($type != 'attendance')
                                                <th class="px-6 py-3 text-center font-medium">Score</th>
                                            @else
                                                <th class="px-6 py-3 text-center font-medium">Attendance</th>
                                            @endif
                                            <th class="px-6 py-3 text-center font-medium">Date Encoded</th>
                                            @if ($type != 'attendance')
                                                <th class="px-6 py-3 text-center font-medium">Remarks</th>
                                                <th class="px-6 py-3 text-center font-medium">Total</th>
                                            @endif
                                            <th class="px-6 py-3 text-center font-medium">{{ ucwords($type) }} Percentage
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-stone-200 divide-y divide-gray-400 ">
                                        <tr class="divide-x divide-gray-400">
                                            <td class=" text-center whitespace-nowrap sticky left-0 bg-gray-100 z-1">
                                                <div class="divide-y divide-gray-400">
                                                    @foreach ($assessmentsGroupedByType[$type] as $assessment)
                                                        <div class="py-2">
                                                            <form action="/store-assessment-id-student" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="assessmentID"
                                                                    value="{{ $assessment->assessment->assessmentID }}">
                                                                <input type="hidden" name="gradingDistributionType"
                                                                    value="{{ $selectedGradingDistributions }}">
                                                                <button type="submit"
                                                                    class=" hover:text-gray-500 hover:rounded-md text-center w-full flex justify-center">
                                                                    <span
                                                                        class="font-semibold">{{ $assessment->assessment->assessmentName }}</span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <div class="divide-y divide-gray-400">
                                                    @foreach ($assessmentsGroupedByType[$type] as $assessment)
                                                        <div class="py-2">
                                                            <span
                                                                class="font-semibold">{{ date('m-d-Y', strtotime($assessment->assessment->assessmentDate)) }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>


                                            <td class=" text-center">
                                                @if ($type != 'attendance')
                                                    @php
                                                        $totalScore = 0;
                                                        $totalItems = 0;
                                                    @endphp
                                                    <div class="divide-y divide-gray-400">
                                                        @foreach ($assessmentsGroupedByType[$type] as $studentAssessment)
                                                            <div
                                                                class=" {{ $studentAssessment->score >= $studentAssessment->assessment->passingItem ? 'bg-green-400' : 'bg-red-400' }}">
                                                                @php
                                                                    $totalScore += $studentAssessment->score;
                                                                    $totalItems += $studentAssessment->assessment
                                                                        ? $studentAssessment->assessment->totalItem
                                                                        : 0;
                                                                @endphp
                                                                <div class="py-2">
                                                                    <span class="font-semibold">
                                                                        <span class="">
                                                                            {{ $studentAssessment->score ?? '-' }}
                                                                        </span>
                                                                        /
                                                                        {{ $studentAssessment->assessment ? $studentAssessment->assessment->totalItem : 'N/A' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    @php
                                                        $totalScore = 0;
                                                        $totalItems = 0;
                                                    @endphp
                                                    <div class="font-bold divide-y divide-gray-400">
                                                        @foreach ($assessmentsGroupedByType[$type] as $studentAssessment)
                                                            @php
                                                                $totalScore += $studentAssessment->score;
                                                                $totalItems += $studentAssessment->assessment
                                                                    ? $studentAssessment->assessment->totalItem
                                                                    : 0;
                                                            @endphp
                                                            <div class="py-2">
                                                                @switch($studentAssessment->score)
                                                                    @case('1.0')
                                                                        <div class="text-green-500 ">Present</div>
                                                                    @break

                                                                    @case('0.0')
                                                                        <div class="text-[#9E1A14]">Absent</div>
                                                                    @break

                                                                    @case('0.75')
                                                                        <div class="text-[#CCAA2C]">Late</div>
                                                                    @break

                                                                    @case('N/A')
                                                                        <div class="text-[#5B82BD]">Excuse</div>
                                                                    @break
                                                                @endswitch
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>

                                            <td class=" text-center">
                                                <div class="divide-y divide-gray-400">
                                                    @foreach ($assessmentsGroupedByType[$type] as $assessment)
                                                        <div class="py-2">
                                                            <span
                                                                class="font-semibold">{{ date('m-d-Y', strtotime($assessment->created_at)) }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>

                                            @if ($type != 'attendance')
                                                <td class=" text-center">
                                                    <div class="divide-y divide-gray-400">
                                                        @foreach ($assessmentsGroupedByType[$type] as $assessment)
                                                            <div class="py-2">
                                                                <span class="font-semibold">
                                                                    {{ $assessment->remarks ?? '-' }}
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>

                                                <td class=" text-center">
                                                    <span class="font-semibold">{{ $totalScore }} /
                                                        {{ $totalItems }}</span>
                                                </td>
                                            @endif

                                            <td class=" text-center">
                                                @php
                                                    $normalizedType = strtolower($type);
                                                    $gradingPercentage = $gradingPercentages[$normalizedType] ?? 0;
                                                    $typePercentage = $totalItems > 0 ? ($totalScore / $totalItems) * $gradingPercentage : 0;
                                                    $formattedPercentage = number_format($typePercentage, 2);
                                            
                                                    $totalGrade += $typePercentage; // Sum up the percentages
                                                @endphp
                                                <span class="font-semibold">{{ $formattedPercentage }}% </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if($publishedGrading && $publishedGrading->isPublished == 1)
                    <p class="text-end font-bold">{{ ucwords(str_replace('-', ' ', $selectedTab)) }} Grade: {{ number_format($totalGrade, 2) }}%</p>
                @endif
            
            @else
                <p class="text-gray-600 dark:text-white">No assessments found for quarter <span
                        class="font-bold text-center">{{ ucwords($selectedGradingDistributions) }}</span></p>
            @endif
        </div>

    </body>

    </html>
@endsection
