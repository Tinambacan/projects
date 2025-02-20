<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
    <title>{{ $details->assessmentName }} Details</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
    @vite('resources/js/class-record-assess.js')
</head>

<body>
    @extends('layout.ClassRecordLayout')

    @section('classrecordcontent')
        <div class="flex justify-center w-full">
            <div class="flex flex-col w-full  rounded-lg  animate-fadeIn">
                <div id="grading-type-section" class="rounded-lg  my-3">
                    <div class="flex relative justify-end items-end flex-col">
                        <div class="absolute inset-0 flex justify-center items-center md:mt-0 mt-5">
                            <div class="flex justify-center text-md">
                                <select id="section-selector"
                                    class="dark:bg-[#404040] font-bold dark:text-white dark:border-[#B7B4B4] shadow-md w-full px-4 py-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-900 dark:focus:ring-[#CCAA2C] focus:ring-offset-2">
                                </select>
                            </div>
                        </div>
                        <span id="isArchived" hidden>{{ $classRecords->isArchived }}</span>
                        @if ($classRecords->isArchived == 0)
                            @if ($storedAssessmentType !== 'attendance')
                                <div class="flex justify-end">
                                    <div class="md:inline hidden  z-10">
                                        <button id=""
                                            class="publish-score-btn text-red-900 dark:text-white rounded-lg p-2 shadow-md border border-gray-300 bg-white dark:bg-[#CCAA2C]"
                                            data-assessment-id="{{ $assessmentID }}"
                                            data-class-record-id="{{ $classRecordID }}">
                                            <div class="text-sm flex gap-2 justify-center items-center cursor-pointer">
                                                <i class="fa-solid fa-upload cursor-pointer "></i>
                                                <div class="flex justify-center items-center">
                                                    <span>Publish Score</span>
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div></div>
                        @endif
                    </div>
                </div>

                <div id="details-section"
                    class="p-2 rounded-lg my-6 mt-14 md:mt-4  shadow-md dark:bg-[#1E1E1E] bg-white bg-opacity-50 backdrop-blur-sm border dark:border-[#404040] sticky top-28 z-30 dark:bg-opacity-50 dark:backdrop-blur-md">
                    @if ($classRecords->isArchived == 0)
                        @if ($storedAssessmentType !== 'attendance')
                            <div class="flex justify-end">
                                <div class="md:hidden inline">
                                    <button id=""
                                        class="publish-score-btn text-red-900 dark:text-white rounded-lg p-2 shadow-md border border-gray-300 bg-white dark:bg-[#CCAA2C] z-10"
                                        data-assessment-id="{{ $assessmentID }}"
                                        data-class-record-id="{{ $classRecordID }}">
                                        <div class="text-sm flex gap-2 justify-center items-center cursor-pointer">
                                            <i class="fa-solid fa-upload cursor-pointer "></i>
                                            <div class="flex justify-center items-center">
                                                <span>Publish Score</span>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        @endif
                    @else
                        <div></div>
                    @endif
                    <div class="flex md:justify-between md:flex-row flex-col  relative">
                        <div class="absolute inset-0 flex justify-center items-center md:-mt-0 -mt-5">
                            {{-- <span class="text-red-900 font-bold text-3xl dark:text-[#CCAA2C]">
                                {{ $details->assessmentName }}
                            </span> --}}

                            <x-titleText>
                                {{ $details->assessmentName }}
                            </x-titleText>
                        </div>
                        <div class="flex justify-start">
                            <input type="hidden" name="classRecordID" value="{{ $classRecordID }}">
                            <form action="/redirect-to-lists" method="POST">
                                @csrf
                                <input type="hidden" name="gradingDistributionType"
                                    value="{{ $gradingDistribution->gradingDistributionType }}">
                                <button type="submit"
                                    class=" relative z-10 hover:bg-gray-100 dark:hover:bg-[#161616] rounded-md group p-2">
                                    <div class="flex justify-start gap-2">
                                        {{-- <i
                                            class="fa-solid fa-circle-arrow-left  rounded-full  text-red-900 dark:text-[#CCAA2C]] text-2xl"></i>
                                        <div class="flex justify-center items-center">
                                            <span class="dark:text-white md:text-lg text-sm">Back to List of
                                                {{ ucwords($storedAssessmentType) }}</span>
                                        </div> --}}


                                        <div
                                            class="text-red-900 dark:text-[#CCAA2C] flex gap-1 justify-center items-center">
                                            <i class="fa-solid fa-circle-arrow-left text-2xl"></i>
                                        </div>
                                        <span class="text-md text-black dark:text-white">Back to list of
                                            {{ ucwords($storedAssessmentType) }}</span>

                                    </div>
                                </button>
                            </form>
                        </div>

                        <div class="flex gap-3 relative z-10 dark:text-white items-center flex-row md:mt-0 mt-12">
                            @if ($storedAssessmentType !== 'attendance')
                                <div><span class="font-bold">Total score: </span> <span
                                        id="total-item">{{ $details->totalItem }}</span>
                                </div>
                                <div><span class="font-bold">Passing score: </span>
                                    <span>{{ $details->passingItem }}</span>
                                </div>

                                <div><span class="font-bold">Date: </span>
                                    <span>{{ date('m-d-Y', strtotime($details->assessmentDate)) }}</span>
                                </div>
                            @else
                                <div class="text-lg py-2">Date:
                                    <span>{{ date('F j, Y', strtotime($details->assessmentDate)) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>




                @if ($storedAssessmentType == 'attendance')
                    @if ($classRecords->isArchived == 0)
                        <div class="flex justify-end py-3">
                            <form id="assessmentFormAttendance" class="md:flex md:gap-2 grid grid-cols-2 gap-2">
                                @csrf
                                <input type="hidden" id="selectedStudentIDsAttendance" name="selectedStudentIDs"
                                    value="">
                                <input type="hidden" name="assessmentID" value="{{ $assessmentID }}">
                                <input type="hidden" name="classRecordID" value="{{ $classRecordID }}">

                                <button value="1.0"
                                    class=" border border-[#78DC82] rounded-xl shadow-md text-[#6AB547] dark:text-white px-5 py-2">Present</button>
                                <button value="0.0"
                                    class=" border border-[#9E1A14] rounded-xl shadow-md text-[#9E1A14] dark:text-white px-5">Absent</button>
                                <button value="0.75"
                                    class=" border border-[#CCAA2C] rounded-xl shadow-md text-[#CCAA2C] dark:text-white px-5 md:py-0 py-2">Late</button>
                                <button value="N/A"
                                    class=" border border-[#5B82BD] rounded-xl shadow-md text-[#5B82BD]  dark:text-white px-5">Excuse</button>
                            </form>
                        </div>
                    @else
                        <div></div>
                    @endif
                @endif
                <span id="type" hidden>{{ $storedAssessmentType }}</span>
                <div
                    class="bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] dark:text-white text-black p-3 rounded-md animate-fadeIn shadow-lg">
                    <table id="assessmentDetailsTable" class="display">
                        <thead>
                            <tr>
                                <th style="text-align: center">
                                    <input type="checkbox" class="rounded-full" name="select_all" value=""
                                        id="score_select_all">
                                </th>
                                <th style="text-align: center">Student Number</th>
                                <th style="text-align: center">Student Name</th>
                                @if ($storedAssessmentType == 'attendance')
                                    <th style="text-align: center">Attendance</th>
                                    <th style="text-align: center">Status</th>
                                    <th style="text-align: center">Status</th>
                                @else
                                    <th style="text-align: center">Score</th>
                                    <th style="text-align: center">Remarks</th>
                                    <th style="text-align: center">Status</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ($classRecords->students as $student)
                                <tr>
                                    <td class="text-md" style="text-align: center">
                                        <input type="checkbox" class="score_checkbox text-center"
                                            data-student-id="{{ $student->studentID }}">
                                    </td>

                                    <td style="text-align:
                                            center">
                                        {{ $student->studentNo }}
                                    </td>

                                    <td style="text-align: center">{{ $student->studentFname }}
                                        {{ $student->studentLname }}</td>

                                    @if ($storedAssessmentType == 'attendance')
                                        <form id="assessmentFormAttendance">
                                            @csrf
                                            <input type="hidden" name="assessmentID" value="{{ $assessmentID }}">
                                            <input type="hidden" name="classRecordID" value="{{ $classRecordID }}">
                                            <td style="text-align: center">
                                                <select name="scores[{{ $student->studentID }}]"
                                                    class="attendance-selector p-2 border-2 border-gray-300 rounded shadow-lg"
                                                    data-student-id="{{ $student->studentID }}">
                                                    <option value=""
                                                        {{ !isset($studentScores[$student->studentID]) || is_null($studentScores[$student->studentID]) ? 'selected' : '' }}>
                                                        Not set</option>
                                                    <option value="1.0"
                                                        {{ (string) ($studentScores[$student->studentID] ?? '') === '1.0' ? 'selected' : '' }}>
                                                        Present</option>
                                                    <option value="0.0"
                                                        {{ (string) ($studentScores[$student->studentID] ?? '') === '0.0' ? 'selected' : '' }}>
                                                        Absent</option>
                                                    <option value="0.75"
                                                        {{ (string) ($studentScores[$student->studentID] ?? '') === '0.75' ? 'selected' : '' }}>
                                                        Late</option>
                                                    <option value="N/A"
                                                        {{ (string) ($studentScores[$student->studentID] ?? '') === 'N/A' ? 'selected' : '' }}>
                                                        Excuse</option>
                                                </select>
                                            </td>
                                        </form>

                                        <td style="text-align: center">
                                            @php
                                                $isViewable =
                                                    $studentAssessments
                                                        ->where('studentID', $student->studentID)
                                                        ->first()->isRawScoreViewable ?? null;
                                            @endphp
                                            @if ($isViewable === 1)
                                                <span class="bg-green-500 text-white p-2 rounded-md">Published</span>
                                            @elseif ($isViewable === 0)
                                                <div class="relative group flex justify-center items-center">
                                                    <button class="publish-indiv cursor-pointer"
                                                        data-assessment-id="{{ $assessmentID }}"
                                                        data-class-record-id="{{ $classRecordID }}"
                                                        data-student-id="{{ $student->studentID }}">
                                                        <input type="hidden" name="gradingType"
                                                            value="{{ $gradingDistribution->gradingDistributionType }}" />
                                                        <input type="hidden" name="gradingTerm"
                                                            value="{{ $gradingDistribution->term }}" />
                                                        <span
                                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md">Unpublished</span>
                                                    </button>

                                                    <div
                                                        class="absolute top-[-53px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                                        <div
                                                            class="flex justify-center items-center text-center transition-all duration-300 relative">
                                                            <span
                                                                class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Publish
                                                                score</span>
                                                            <div
                                                                class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="bg-gray-300 text-white p-2 rounded-md">N/A</span>
                                            @endif
                                        </td>
                                    @else
                                        <div>
                                            <form id="assessmentForm" action="/store-scores" method="POST">
                                                @csrf
                                                <input type="hidden" name="assessmentID" value="{{ $assessmentID }}">
                                                <input type="hidden" name="classRecordIDScores"
                                                    value="{{ $classRecordID }}">
                                                <td style="text-align: center">
                                                    <input type="number" name="scores[{{ $student->studentID }}]"
                                                        value="{{ old('scores.' . $student->studentID, $studentScores[$student->studentID] ?? '') }}"
                                                        class="score-input w-32 bg-gray-200 text-center"
                                                        style="-moz-appearance: textfield; -webkit-appearance: none; margin: 0;"
                                                        data-student-id="{{ $student->studentID }}">
                                                </td>
                                            </form>
                                        </div>
                                        <td style="text-align: center">
                                            @php
                                                $isViewable =
                                                    $studentAssessments
                                                        ->where('studentID', $student->studentID)
                                                        ->first()->isRawScoreViewable ?? null;
                                            @endphp
                                            @if ($isViewable === 1)
                                                <span class="bg-green-500 text-white p-2 rounded-md">Published</span>
                                            @elseif ($isViewable === 0)
                                                <div class="relative group flex justify-center items-center">
                                                    <button class="publish-indiv cursor-pointer"
                                                        data-assessment-id="{{ $assessmentID }}"
                                                        data-class-record-id="{{ $classRecordID }}"
                                                        data-student-id="{{ $student->studentID }}">
                                                        <input type="hidden" name="gradingType"
                                                            value="{{ $gradingDistribution->gradingDistributionType }}" />
                                                        <input type="hidden" name="gradingTerm"
                                                            value="{{ $gradingDistribution->term }}" />
                                                        <span
                                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md">Unpublished</span>
                                                    </button>
                                                    <div
                                                        class="absolute top-[-53px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                                        <div
                                                            class="flex justify-center items-center text-center transition-all duration-300 relative">
                                                            <span
                                                                class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Publish
                                                                score</span>
                                                            <div
                                                                class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="bg-red-500  text-white p-2 rounded-md">Unpublished</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <x-loader modalLoaderId="send-email-loader" titleLoader="Sending to email" />
    @endsection
</body>

</html>
