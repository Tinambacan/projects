<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
    <title>Semester Grades</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
    @vite('resources/js/file-submit.js')
</head>

<body>
    @extends('layout.ClassRecordLayout')

    @section('classrecordcontent')
        <div class="flex justify-end text-center w-full  text-red-900 ">
            <span id="isArchived" hidden>{{ $classRecords->isArchived }}</span>
            @if ($classRecords->isArchived == 0)
                <div class="flex py-3 gap-4 ">
                    <form id="submit-grades-form" action="{{ route('submit-grades.excel') }}" method="POST"
                        class="flex gap-2 bg-red-900 dark:bg-[#CCAA2C] text-white justify-center items-center p-2 rounded-md cursor-pointer hover:bg-red-800">
                        @csrf
                        <button type="submit" id="submit-button" class="flex gap-2 items-center"
                            style="background: none; border: none; padding: 0; cursor: pointer;">
                            <i class="fa-solid fa-arrow-up-from-bracket text-xl"></i>
                            <div class="flex justify-center items-center">
                                <span class="text-xs">Submit</span>
                            </div>

                        </button>
                    </form>
                    <div class="flex gap-2 bg-red-900 dark:bg-[#CCAA2C] text-white justify-center items-center p-2 rounded-md cursor-pointer hover:bg-red-800"
                        id="download-excel-btn" data-class-record-id="{{ $classRecords->classRecordID }}"
                        data-course-title="{{ $classRecords->course->courseTitle }}"
                        data-year="{{ $classRecords->yearLevel }}"
                        data-program-title="{{ $classRecords->course->program->programCode }}">
                        <i class="fa-solid
                        fa-file-excel cursor-pointer text-xl"></i>
                        <span class="text-xs">Download Excel</span>
                    </div>
                </div>
            @else
                <div></div>
            @endif
        </div>
        <div class=" {{ $classRecords->isArchived ? 'pt-3' : 'pt-0' }}">
            <div
                class="bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] dark:text-white text-black p-3 rounded-md animate-fadeIn shadow-lg">
                <table id="semesterTable" class="display">
                    <thead>
                        <tr class="border-b">
                            <th class="p-2" style="text-align:center">Student Name</th>
                            <th class="p-2" style="text-align:center">Student Number</th>
                            @foreach ($gradingDistributions as $distribution)
                                @if ($distribution->term == 1)
                                    <th class="p-2" style="text-align:center">
                                        {{ $distribution->gradingDistributionType }}
                                        Grade
                                    </th>
                                @endif
                                @if ($distribution->term == 2)
                                    <th class="p-2" style="text-align:center">
                                        {{ $distribution->gradingDistributionType }}
                                        Grade
                                    </th>
                                @endif
                                @if ($distribution->term == 3)
                                    <th class="p-2" style="text-align:center">
                                        {{ $distribution->gradingDistributionType }}
                                        Grade
                                    </th>
                                @endif
                            @endforeach
                            <th class="p-2" style="text-align:center">Semestral Grade</th>
                            <th class="p-2" style="text-align:center">Point Grade</th>
                            <th class="p-2" style="text-align:center">SIS</th>
                            <th class="p-2" style="text-align:center">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classRecords->students as $student)
                            <tr class="border-b text-center" onclick="window.location='{{ route('faculty-class-record-individual-reports', ['studentID' => $student->studentID]) }}'">
                                <td class="text-center">
                                    {{ $student->studentLname }}, {{ $student->studentFname }}
                                </td>
                                <td class="text-center">{{ $student->studentNo }}</td>

                                @if ($gradingDistributions->contains('term', 1))
                                    <td class="text-center" style="text-align: center">
                                        {{ $grades[$student->studentID]['term1Grade'] ?? 'No Grade' }}</td>
                                @endif
                                @if ($gradingDistributions->contains('term', 2))
                                    <td class="text-center" style="text-align: center">
                                        {{ $grades[$student->studentID]['term2Grade'] ?? 'No Grade' }}</td>
                                @endif
                                @if ($gradingDistributions->contains('term', 3))
                                    <td class="text-center" style="text-align: center">
                                        {{ $grades[$student->studentID]['term3Grade'] ?? 'No Grade' }}</td>
                                @endif

                                <td class="text-center" style="text-align: center">
                                    {{ $grades[$student->studentID]['semestralGrade'] ?? 'No Grade' }}</td>
                                <td class="text-center" style="text-align: center">
                                    {{ $grades[$student->studentID]['pointGrade'] ?? 'No Grade' }}</td>
                                <td class="text-center" style="text-align: center">
                                    {{ $grades[$student->studentID]['gwa'] ?? 'No Grade' }}</td>
                                <td class="text-center">{{ $grades[$student->studentID]['remarks'] ?? 'No Grade' }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <x-loader modalLoaderId="download-excel" titleLoader="Please wait..." />
        </div>
    @endsection
    <x-loader modalLoaderId="loader-modal-submit" />
</body>


</html>
