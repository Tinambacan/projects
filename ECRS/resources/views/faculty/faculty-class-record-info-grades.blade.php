<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
    <title>Grades</title>
    <style>
        .arrow {
            display: block;
            width: 0px;
            height: 0px;
            border: 10px solid transparent;
        }

        .arrow-left {
            border-left: 10px solid #ccc;
            border-right: none;
        }

        .arrow-right {
            border-right: 10px solid #ccc;
            border-left: none;
        }

        .arrow.arrow-right {
            border-right-color: #0px;
            border-left-width: #ccc;
        }

        .grade-distribution {
            position: absolute;
            right: 0;
            z-index: 40;
            overflow: hidden;
            /* height: 23rem; */
            border-radius: 8px;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 0;
            opacity: 0;
            transform: translateX(10px);
            transition: all 1.0s ease;
        }

        .card.open {
            width: 270px;
            opacity: 1;
            transform: translateX(0);
        }

        @media (min-width: 480px) {
            .card.open {
                width: 350px;
            }
        }
    </style>
    @vite('resources/js/app.js')
    @vite('resources/js/grades.js')
</head>

<body>
    @extends('layout.ClassRecordLayout')

    @section('classrecordcontent')
        <div id="grading-type-section" class="p-2 rounded-lg  my-3 md:mt-10 mt-3">
            <div class="flex relative justify-end items-end flex-col ">
                <div class="absolute inset-0 flex justify-center items-center md:mt-0 mt-5">
                    <div class="flex justify-center text-md">
                        <select id="section-selector"
                            class="dark:bg-[#404040] dark:text-white dark:border-[#B7B4B4] shadow-md w-full px-4 py-2 font-bold text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-900 dark:focus:ring-[#CCAA2C] focus:ring-offset-2">
                        </select>
                    </div>
                </div>

                <div class="md:inline hidden">
                    <div class="grade-distribution flex justify-end -mt-6 -mr-5 ">
                        <div class="button-container">
                            <button
                                class="grade-percentage-btn bg-red-900 dark:bg-[#CCAA2C] text-white md:p-3 p-2 font-normal md:gap-2 gap-1  w-full flex justify-center items-center">
                                <div class="">
                                    <i class="arrow arrow-right arrow-icon"></i>
                                </div>
                                <p class="text-md">Grade Percentage</p>
                            </button>
                        </div>

                        <div class="card border p-2">
                            @foreach ($gradingTitle as $distribution)
                                <div class="text-center py-2">
                                    <p class="text-md font-bold">
                                        {{ $distribution->gradingDistributionType }}
                                    </p>
                                </div>
                            @endforeach

                            <div class="h-56 overflow-y-auto">
                                <table class="table-auto mb-3 w-full">
                                    <thead>
                                        <tr class="bg-red-900 dark:bg-[#CCAA2C] gap-2">
                                            <td
                                                class="p-2 text-white rounded-tl-md md:text-md  text-sm font-bold text-center">
                                                Assessment Type
                                            </td>
                                            <td
                                                class="p-2 text-white rounded-tr-md md:text-md  text-sm font-bold text-center">
                                                Grading Percentage
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($assessmentData as $type => $data)
                                            <tr data-grading-id="{{ $data->gradingID }}">
                                                <td class="p-2 border border-gray-300 md:text-md  text-sm">
                                                    {{ ucwords($type) }}
                                                </td>
                                                <td class="p-2 border border-gray-300 text-center md:text-md  text-sm">
                                                    @if ($classRecords->isArchived == 1)
                                                        <p class="text-gray-600">
                                                            {{ number_format($data->percentage, 2) == '0.00' ? '0%' : number_format($data->percentage, 0) }}%
                                                        </p>
                                                    @else
                                                        <input type="number" name="percentage"
                                                            class="percentage-input border-gray-300 bg-gray-100 text-center -mr-2"
                                                            value="{{ number_format($data->percentage, 2) == '0.00' ? '0%' : number_format($data->percentage, 0) }}"
                                                            min="0" max="100" step="1">%
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                        <tr class="total-row">
                                            <td class="p-2 border border-gray-300 font-bold md:text-md  text-sm">
                                                Total</td>
                                            <td
                                                class="p-2 border border-gray-300 text-center total-percentage md:text-md  text-sm">
                                                {{ number_format($totalPercentage, 2) == '0.00' ? '0%' : number_format($totalPercentage, 0) . '%' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="text-center">
                                    <button id="save-btn"
                                        class="bg-red-900 dark:bg-[#CCAA2C] text-white p-2 font-normal rounded-md  md:text-md text-sm">
                                        Save
                                    </button>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="md:hidden inline">
            <div class="grade-distribution flex justify-end mt-8">
                <div class="button-container">
                    <button
                        class="grade-percentage-btn bg-red-900 dark:bg-[#CCAA2C] text-white md:p-3 p-2 font-normal md:gap-2 gap-1  w-full flex justify-center items-center">
                        <div class="">
                            <i class="arrow arrow-right arrow-icon"></i>
                        </div>
                        <p class="md:text-lg text-sm">Grade Percentage</p>
                    </button>
                </div>

                <div class="card border p-2">
                    @foreach ($gradingTitle as $distribution)
                        <div class="text-center py-2">
                            <p class="lg:text-md md:text-lg text-sm font-bold">
                                {{ $distribution->gradingDistributionType }}
                                {{ $distribution->term }}</p>
                        </div>
                    @endforeach

                    <div class="h-56 overflow-y-auto">
                        <table class="table-auto mb-3 w-full">
                            <thead>
                                <tr class="bg-red-900 dark:bg-[#CCAA2C] gap-2">
                                    <td class="p-2 text-white rounded-tl-md lg:text-md md:text-lg text-sm text-center">
                                        Assessment Type
                                    </td>
                                    <td class="p-2 text-white rounded-tr-md lg:text-md md:text-lg text-sm text-center">
                                        Grading Percentage
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assessmentData as $type => $data)
                                    <tr data-grading-id="{{ $data->gradingID }}">
                                        <td class="p-2 border border-gray-300 lg:text-md md:text-lg text-sm">
                                            {{ ucwords($type) }}</td>
                                        <td class="p-2 border border-gray-300 text-center lg:text-md md:text-lg text-sm">
                                            <input type="number" name="percentage"
                                                class="percentage-input border-gray-300 bg-gray-100 text-center -mr-2 {{ $classRecords->isArchived ? 'cursor-not-allowed' : '' }}"
                                                value="{{ number_format($data->percentage, 2) == '0.00' ? '0%' : number_format($data->percentage, 0) }}"
                                                min="0" max="100" step="1"
                                                {{ $classRecords->isArchived ? 'disabled' : '' }}>
                                            %
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="total-row">
                                    <td class="p-2 border border-gray-300 font-bold lg:text-md md:text-lg text-sm">
                                        Total</td>
                                    <td
                                        class="p-2 border border-gray-300 text-center total-percentage lg:text-md md:text-lg text-sm">
                                        {{ number_format($totalPercentage, 2) == '0.00' ? '0%' : number_format($totalPercentage, 0) . '%' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <button id="save-btn"
                            class="bg-red-900 dark:bg-[#CCAA2C] text-white p-2 font-normal rounded-md md:text-md text-sm {{ $classRecords->isArchived ? 'cursor-not-allowed opacity-50' : '' }}"
                            {{ $classRecords->isArchived ? 'disabled' : '' }}>
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="flex flex-col items-start gap-4 mt-8">
            <div class="publish-grade-btn flex gap-2 bg-red-900 dark:bg-[#CCAA2C] text-white justify-center items-center p-2 rounded-md cursor-pointer hover:bg-red-800"
                data-term="{{ $distribution->term }}" data-class-record-id="{{ $classRecords->classRecordID }}"
                data-grading-type="{{ $distribution->gradingDistributionType }}">
                <i class="fa-solid fa-upload text-xl"></i>
                <span class="text-xs">
                    @foreach ($gradingTitle as $distribution)
                        <div class="text-center">
                            <p class="text-sm font-bold">Publish {{ $distribution->gradingDistributionType }} Grades</p>
                        </div>
                    @endforeach
                </span>
            </div>
            <div class="font-bold flex gap-2 dark:text-white text-black">
                Grades status:
                <p class="{{ $distribution->isPublished == 1 ? 'text-green-500' : 'text-red-500' }}">
                    {{ $distribution->isPublished == 1 ? 'Published' : 'Not Published' }}</p>
            </div>
        </div> --}}

        <div class="flex flex-col items-start gap-4 mt-8">
            @foreach ($gradingTitle as $distribution)
                <div class="toggle-grade-btn flex gap-2 
                    {{ $distribution->isPublished == 1 ? 'bg-gray-500 hover:bg-gray-600' : 'bg-red-900 hover:bg-red-800' }} 
                    dark:bg-[#CCAA2C] text-white justify-center items-center p-2 rounded-md cursor-pointer"
                    data-term="{{ $distribution->term }}" data-class-record-id="{{ $classRecords->classRecordID }}"
                    data-is-published="{{ $distribution->isPublished }}"
                    data-grading-type="{{ $distribution->gradingDistributionType }}">

                    <i class="fa-solid {{ $distribution->isPublished == 1 ? 'fa-times' : 'fa-upload' }} text-xl"></i>
                    <span class="text-sm">
                        {{ $distribution->isPublished == 1 ? 'Unpublish' : 'Publish' }} {{ $distribution->gradingDistributionType }} Grades
                    </span>
                </div>
            @endforeach

            <div class="font-bold flex gap-2 dark:text-white text-black">
                Grades status:
                <p class="{{ $distribution->isPublished == 1 ? 'text-green-500' : 'text-red-500' }}">
                    {{ $distribution->isPublished == 1 ? 'Published' : 'Not Published' }}
                </p>
            </div>
        </div>




        <div
            class="border overflow-hidden bg-white  dark:bg-[#161616] border-gray-300 dark:border-[#404040] dark:text-white text-black p-3 rounded-md animate-fadeIn shadow-lg md:mt-5 mt-24">
            <div class="relative">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-full table-auto">
                        <thead>
                            <tr class="border-b">
                                <th class="p-2 border border-r-4 border-r-red-900 border-gray-300  sticky left-0  z-10  dark:bg-[#404040] bg-white  dark:text-white"
                                    rowspan="2">Student
                                    Name
                                </th>
                                @foreach ($assessmentData as $type => $data)
                                    <th class="p-2 border border-gray-300">
                                        <i class="fa-solid fa-file-lines text-red-800 fa-lg"></i><br>{{ ucwords($type) }}
                                    </th>
                                @endforeach
                                <th class="p-2 overflow-auto border border-gray-300">
                                    <i class="fa-solid fa-circle-plus text-red-800 fa-lg"></i><br>Total
                                </th>
                            </tr>
                            <tr class="border-b">
                                @foreach ($assessmentTypes as $type)
                                    <th class="p-2 text-gray-400 border border-gray-300 font-normal">
                                        {{ $totalItemsData[$type] ?? 0 }} Items
                                    </th>
                                @endforeach
                                <th class="p-2 text-gray-400 border border-gray-300 font-normal">
                                    {{ array_sum($totalItemsData) }} Items
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($classRecords->students as $student)
                                @php
                                    $totalScoreSum = array_sum(
                                        array_map(
                                            fn($type) => $studentScores[$type][$student->studentID] ?? 0,
                                            $assessmentTypes,
                                        ),
                                    );
                                    $totalScoreSum = number_format($totalScoreSum, 2);
                                @endphp
                                <tr class="border-b">
                                    <td
                                        class="text-center border border-r-4 border-r-red-900 border-gray-300 whitespace-nowrap sticky left-0 md:text-base text-xs p-2 dark:bg-[#404040] bg-white  dark:text-white">
                                        <strong>{{ $student->studentLname }}</strong>, {{ $student->studentFname }}
                                    </td>
                                    @foreach ($assessmentTypes as $type)
                                        @php
                                            $totalScore = $studentScores[$type][$student->studentID] ?? 0;
                                            $totalScore = number_format($totalScore, 2);
                                        @endphp
                                        <td class="text-center border border-gray-300">{{ $totalScore }}%</td>
                                    @endforeach
                                    <td class="text-center border border-gray-300">{{ $totalScoreSum }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
</body>

</html>
