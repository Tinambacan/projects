<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
    <title>{{ ucwords($assessmentType) }} Details</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
    @vite('resources/js/class-record-assess.js')
</head>

<body>
    @extends('layout.ClassRecordLayout')

    @section('classrecordcontent')
        <div class="flex justify-center w-full">
            <div class="flex flex-col w-full shadow-xl  rounded-lg px-4">
                <div id="new-table-section" class="p-2 rounded-lg my-3 relative shadow-md">
                    <div class="flex justify-between items-center relative">
                        <div class="absolute inset-0 flex justify-center items-center">
                            <span class="text-red-900 font-bold text-3xl">
                                {{ $details->assessmentName }}
                            </span>
                        </div>
                        <a class="flex justify-start gap-2 relative z-10 hover:bg-gray-100 p-2 rounded-md"
                            href="/faculty/class-record/midterm/{{ $assessmentType }}">
                            <i class="fa-solid fa-arrow-left bg-red-900 rounded-full p-2 text-white"></i>
                            <span class="flex justify-center items-center">Back to List of
                                {{ ucwords($assessmentType) }}</span>
                        </a>

                        <div class="flex gap-3 relative z-10">
                            @if (
                                !request()->is('faculty/class-record/midterm/attendance/details') &&
                                    !request()->is('faculty/class-record/finals/attendance/details'))
                                <div><span class="font-bold">Total score: </span> <span  id="total-item">{{ $details->totalItem }}</span>
                                </div>
                                <div><span class="font-bold">Passing score: </span> <span>{{ $details->passingItem }}</span>
                                </div>
                            @endif
                            <div><span class="font-bold">Date: </span>
                                <span>{{ date('m-d-Y', strtotime($details->assessmentDate)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="flex justify-end">
                    <div id="publish-score-btn"
                        class="hidden text-white rounded-lg p-2 shadow-lg border border-gray-300 bg-green-500">
                        <div class="text-md flex gap-2 justify-center items-center">
                            <i class="fa-solid fa-upload cursor-pointer z-10 "></i>
                            <div class="flex justify-center items-center">
                                <span class="text-md">Publish Score</span>
                            </div>
                        </div>
                    </div>
                </div> --}}

                  {{-- <td class="text-md" style="text-align: center">
                                        <input type="checkbox" class="score_checkbox text-center"
                                            value="{{ $assessmentID }}" data-id="{{ $assessmentID }}"
                                            data-stud-id="{{ $tbl_student->studentID }}">
                                    </td> --}}

                <form method="POST" action="/notify-students">
                    @csrf
                    <input type="hidden" id="selectedStudentIDs" name="selectedStudentIDs" value="">
                    <div class="flex justify-end">
                        <button id="publish-score-btn"
                            class="hidden text-white rounded-lg p-2 shadow-lg border border-gray-300 bg-green-500">
                            <div class="text-md flex gap-2 justify-center items-center">
                                <i class="fa-solid fa-upload cursor-pointer z-10"></i>
                                <div class="flex justify-center items-center">
                                    <span class="text-md">Publish Score</span>
                                </div>
                            </div>
                        </button>
                    </div>
                </form>
                {{-- Alert message --}}
                <div class="relative z-10 h-full">
                    <div id="alert-success" class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 max-w-sm bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded hidden" role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">Student Score updated successfully.</span>
                    </div>
                </div>


                <form id="assessmentForm" action="/store-scores" method="POST">
                    @csrf
                    <input type="hidden" name="assessmentID" value="{{ $assessmentID }}">
                    <input type="hidden" name="classRecordIDScores" value="{{ $classRecordID }}">
                    <table id="assessmentDetailsTable" class="display">
                        <thead>
                            <tr>
                                <th class="flex flex-col" style="text-align: center">
                                    <div scope="col">
                                        <input type="checkbox" class="rounded-full" name="select_all" value=""
                                            id="score_select_all" </div>
                                        <div>
                                            <span>All</span>
                                        </div>
                                </th>
                                <th style="text-align: center">Student Number</th>
                                <th style="text-align: center">Student Name</th>
                                <th style="text-align: center">Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($classRecords->students as $student)
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
                                    <td style="text-align: center">
                                        <input type="number" name="scores[{{ $student->studentID }}]"
                                            value="{{ old('scores.' . $student->studentID, $studentScores[$student->studentID] ?? '') }}"
                                            class="w-32 bg-gray-200 text-center"
                                            style="-moz-appearance: textfield; -webkit-appearance: none; margin: 0;"
                                            data-student-id="{{ $student->studentID }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>


            </div>
        </div>
    @endsection
</body>

</html>
