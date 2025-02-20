<!DOCTYPE html>
@extends('layout.StudentClassRecordLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/js/app.js')
</head>

@section('studclassrecordcontent')

    <body>
        @if ($studentAssessments->isNotEmpty())
            @php
                $assessmentsGroupedByType = $studentAssessments->groupBy(function ($item) {
                    return strtolower($item->assessment->assessmentType);
                });

                $assessmentTypes = $assessmentsGroupedByType->keys();

            @endphp
            <div class="overflow-auto scrollbar-thin scrollbar-thumb-[#CCAA2C] scrollbar-track-gray-300 scroll-smooth">
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
                                        <tr class="shadow-lg border-b-2 border-white text-white divide-x-2 divide-white">
                                            {{-- @foreach ($assessmentTypes as $index => $type)
                                                @php
                                                    $classes = '';
                                                    $assessmentCount = count($assessmentsGroupedByType[$type]);

                                                    if ($assessmentCount === 1) {
                                                        if ($index === 0 && $index === count($assessmentTypes) - 1) {
                                                            $classes = 'rounded-t-md ';
                                                        } elseif ($index === 0) {
                                                            $classes = 'rounded-tl-md ';
                                                        } elseif ($index === count($assessmentTypes) - 1) {
                                                            $classes = 'rounded-tr-md ';
                                                        }
                                                    } elseif ($index === 0) {
                                                        $classes = 'rounded-tl-md ';
                                                    } elseif ($index === count($assessmentTypes) - 1) {
                                                        $classes = 'rounded-tr-md ';
                                                    }
                                                @endphp
                                                <th colspan="7"
                                                    class="px-6 py-3 text-center text-lg font-medium uppercase tracking-wider {{ $classes }} bg-[#CCAA2C]">
                                                    {{ $type }}
                                                </th>
                                            @endforeach --}}
                                        </tr>
                                        <tr class="divide-x divide-gray-200">
                                            @foreach ($assessmentTypes as $type)
                                                <th class="px-6 py-3 text-center font-medium">Assessment Title</th>
                                                <th class="px-6 py-3 text-center font-medium">Date of {{ ucwords($type) }}
                                                </th>
                                                @if ($type != 'attendance')
                                                    <th class="px-6 py-3 text-center font-medium">Score</th>
                                                @else
                                                    <th class="px-6 py-3 text-center font-medium">Attendance</th>
                                                @endif
                                                <th class="px-6 py-3 text-center font-medium">Date Encoded</th>
                                                @if ($type != 'attendance')
                                                    <th class="px-6 py-3 text-center font-medium">Remarks</th>
                                                @endif
                                                <th class="px-6 py-3 text-center font-medium">{{ ucwords($type) }}
                                                    Percentage
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="bg-stone-200 divide-y divide-gray-400">
                                        <tr class="divide-x divide-gray-400">
                                            @foreach ($assessmentTypes as $type)
                                                <td class="border-b border-gray-400 text-center">
                                                    <div class="divide-y divide-gray-400">
                                                        @foreach ($assessmentsGroupedByType[$type] as $assessment)
                                                            <div class="py-2">
                                                                <span
                                                                    class="font-semibold">{{ $assessment->assessment->assessmentName }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>

                                                <td class="border-b border-gray-400 text-center">
                                                    <div class="divide-y divide-gray-400">
                                                        @foreach ($assessmentsGroupedByType[$type] as $assessment)
                                                            <div class="py-2">
                                                                <span
                                                                    class="font-semibold">{{ date('m-d-Y', strtotime($assessment->assessment->assessmentDate)) }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>

                                                <td class="border-b border-gray-400 text-center">
                                                    @if ($type != 'attendance')
                                                        <div class="divide-y divide-gray-400">
                                                            @foreach ($assessmentsGroupedByType[$type] as $studentAssessment)
                                                                <div
                                                                    class=" {{ $studentAssessment->score >= $studentAssessment->assessment->passingItem ? 'bg-green-400' : 'bg-red-400' }}">
                                                                    @php
                                                                        $totalScore = 0;
                                                                        $totalItems = 0;
                                                                    @endphp

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
                                                                            <div class="text-[#6AB547] ">Present</div>
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

                                                <td class="border-b border-gray-400 text-center">
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
                                                    <td class="border-b border-gray-400 text-center">
                                                        <div class="divide-y divide-gray-400 font-bold">
                                                            @foreach ($assessmentsGroupedByType[$type] as $assessment)
                                                                <div class="py-2">
                                                                    <span class="font-semibold">
                                                                        {{ $assessment->remarks ?? '-' }}
                                                                    </span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                @endif

                                                <td class="border-b border-gray-400 text-center">
                                                    <div class="divide-y divide-gray-400">
                                                        @foreach ($assessmentsGroupedByType[$type] as $studentAssessment)
                                                            @php
                                                                $normalizedType = strtolower($type);
                                                                $gradingPercentage =
                                                                    $gradingPercentages[$normalizedType] ?? 0;
                                                                $typePercentage =
                                                                    $studentAssessment->assessment &&
                                                                    $studentAssessment->assessment->totalItem > 0
                                                                        ? ($studentAssessment->score /
                                                                                $studentAssessment->assessment
                                                                                    ->totalItem) *
                                                                            $gradingPercentage
                                                                        : 0;
                                                                $formattedPercentage = number_format(
                                                                    $typePercentage,
                                                                    2,
                                                                );
                                                            @endphp
                                                            <div class="py-2">
                                                                <span
                                                                    class="font-semibold">{{ $formattedPercentage }}%</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </body>

    </html>
@endsection
