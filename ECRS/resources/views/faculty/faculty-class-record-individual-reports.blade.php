<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
    <title>Individual Grades</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
    @vite('resources/js/file-submit.js')
</head>

<body>
    @extends('layout.ClassRecordLayout')

    @section('classrecordcontent')
        <div class="flex flex-col mb-5">
            @if ($classRecords->isArchived == 0)
                <div class="flex justify-between items-center  font-bold  mt-5">
                    <div class="flex items-center md:text-2xl text-lg gap-2  text-center w-full">
                        <div>Individual Reports: </div>
                        <div class="text-red-900">{{ strtoupper($student->studentLname) }},
                            {{ strtoupper($student->studentFname) }}</div>
                    </div>
                    {{-- <div class="flex items-center gap-1 cursor-pointer bg-red-200" onclick="window.location.href='{{ route('download.report', ['studentID' => $student->studentID]) }}'">
                <i class="fa-solid fa-file-pdf text-2xl text-red-900" ></i>
                <span class="text-sm text-red-900">Download PDF</span>
            </div> --}}

                    <div class="flex gap-2 bg-red-900 dark:bg-[#CCAA2C] text-white justify-center items-center p-2 rounded-md cursor-pointer hover:bg-red-800 w-44"
                        onclick="window.location.href='{{ route('download.report', ['studentID' => $student->studentID]) }}'">
                        <i class="fa-solid
                        fa-file-pdf cursor-pointer text-2xl"></i>
                        <span class="text-sm">Download PDF</span>
                    </div>

                    {{-- <div class="flex gap-2 bg-red-900 dark:bg-[#CCAA2C] text-white justify-center items-center p-2 rounded-md cursor-pointer hover:bg-red-800 w-44"
                        onclick="window.location.href='{{ route('download.report', ['studentID' => $student->studentID]) }}'">
                        <i class="fa-solid
                    fa-file-excel cursor-pointer text-2xl"></i>
                        <span class="text-sm">Download Excel</span>
                    </div> --}}

                </div>
            @else
                <div></div>
            @endif

        </div>

        @foreach ($gradingDistributionTypes as $term => $termName)
            @if (isset($studentScores[$student->studentID]['rawScores'][$term]))
                <div
                    class="mb-5 bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] dark:text-white text-black p-5 rounded-md animate-fadeIn shadow-lg">
                    <h3 class="text-2xl font-bold text-center mb-4">{{ strtoupper($termName) }}</h3>
                    @foreach ($studentScores[$student->studentID]['rawScores'][$term] as $type => $assessments)
                        <div class="assessment-type mb-5">
                            <h4 class="text-lg font-semibold mb-3">{{ strtoupper(ucfirst($type)) }}</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white dark:bg-[#161616] border-collapse">
                                    <thead>
                                        <tr>
                                            <th
                                                class="py-2 px-4 border bg-red-900 text-white border-gray-300 dark:border-[#404040]">
                                                Assessment Name</th>
                                            <th
                                                class="py-2 px-4 border bg-red-900 text-white border-gray-300 dark:border-[#404040]">
                                                Score</th>
                                            @if ($type !== 'attendance')
                                                <th
                                                    class="py-2 px-4 border bg-red-900 text-white border-gray-300 dark:border-[#404040]">
                                                    Passing Scores</th>
                                            @endif
                                            <th
                                                class="py-2 px-4 border bg-red-900 text-white border-gray-300 dark:border-[#404040]">
                                                Total Items</th>
                                            <th
                                                class="py-2 px-4 border bg-red-900 text-white border-gray-300 dark:border-[#404040]">
                                                Assessment Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($assessments as $assessment)
                                            <tr class="hover:bg-gray-100 dark:hover:bg-[#252525]">
                                                <td
                                                    class="text-font bold text-center py-2 px-4 border border-gray-300 dark:border-[#404040]">
                                                    {{ $assessment['assessmentName'] }}</td>
                                                <td
                                                    class="text-center py-2 px-4 border border-gray-300 dark:border-[#404040]">
                                                    {{ $assessment['score'] }}</td>
                                                @if ($type !== 'attendance')
                                                    <td
                                                        class="text-center py-2 px-4 border border-gray-300 dark:border-[#404040]">
                                                        {{ $assessment['passingItem'] }}</td>
                                                @endif
                                                <td
                                                    class="text-center py-2 px-4 border border-gray-300 dark:border-[#404040]">
                                                    {{ $assessment['totalItem'] }}</td>
                                                <td
                                                    class="text-center py-2 px-4 border border-gray-300 dark:border-[#404040]">
                                                    {{ $assessment['assessmentDate'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="final-score mt-3">
                                @php
                                    $assessmentPercentage = isset($assessmentData[strtolower($type)]) 
                                        ? round(optional($assessmentData[strtolower($type)]->where('term', $term)->first())->percentage ?? 0, 2) 
                                        : 0;
                            
                                    $studentScore = isset($studentScores[$student->studentID]['finalScores'][$term][$type]) 
                                        ? round($studentScores[$student->studentID]['finalScores'][$term][$type], 2) 
                                        : 0;
                                @endphp
                                <p class="text-md font-bold text-end">
                                    {{ ucfirst($type) }} Grade Percentage:
                                    {{ $studentScore }}% / {{ $assessmentPercentage }}%
                                </p>
                            </div>
                            
                            
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach
    @endsection
    <x-loader modalLoaderId="loader-modal-submit" />
</body>


</html>
