<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/js/stud-class-record.js')
</head>

<body>
    @if ($classRecords->isEmpty())
        <div class="flex justify-center items-center h-[60vh]">
            <div class="text-center flex flex-col gap-3">
                <p class="text-2xl text-neutral-500">No class records yet</p>
            </div>
        </div>
    @else
        <div class="flex justify-center items-center p-2 pt-4 gap-4 md:flex-row flex-col md:text-lg text-md">
            <div class="flex items-center gap-4">
                <p class="whitespace-nowrap  text-black dark:text-white font-bold">Academic Year:</p>
                <select name="academicYear" id="academic-year"
                    class="dark:bg-[#404040] dark:text-white dark:border-[#B7B4B4] shadow-md w-full px-4 py-2 font-medium text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-900 dark:focus:ring-[#CCAA2C] focus:ring-offset-2 ">
                    <option value="">All Years</option>
                </select>
            </div>
            <div class="flex items-center gap-4">
                <p class="whitespace-nowrap text-black dark:text-white font-bold">Semester:</p>
                <select name="academicYear" id="semester"
                    class="dark:bg-[#404040] dark:text-white dark:border-[#B7B4B4] shadow-md w-full px-4 py-2 font-medium text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-900 dark:focus:ring-[#CCAA2C] focus:ring-offset-2">
                    <option value="">All Semesters</option>
                    <option value="1">First Semester</option>
                    <option value="2">Second Semester</option>
                    <option value="3">Summer Semester</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3  gap-4 my-4 mb-5 class-record-container">
            @if (collect($classRecords)->isEmpty())
            <p>No class records found.</p>
        @else
            @foreach ($classRecords as $record)
                <div class="w-full p-2 relative transition-transform transform hover:scale-105 duration-300 ease-in-out class-record group">
                    <form class="rounded-xl flex cursor-pointer flex-col" action="/store-stud-class-record-id" method="POST">
                        @csrf
                        <input type="hidden" name="classRecordIDView" value="{{ $record->classRecordID }}">
                        <button type="submit">
                            <div class="h-48 rounded-t-xl bg-cover bg-center flex items-center justify-center shadow-xl {{ $record->classImg ? '' : 'bg-red-900' }}"
                                style="{{ $record->classImg ? 'background-image: url(\'' . url($record->classImg) . '\');' : '' }}">
                                @if (!$record->classImg)
                                    <img src="{{ asset('images/logo-bg.png') }}" alt="Placeholder" class="h-40">
                                @endif
                            </div>
                            <div class="shadow-xl p-5 flex flex-col gap-2 rounded-b-xl dark:bg-[#1E1E1E] dark:border-[#161616] border text-left">
                                <p id="fontSize" class="truncate font-bold text-ellipsis text-2xl text-red-900 dark:text-[#CCAA2C] group-hover:underline">
                                    {{ $record->course->courseTitle }}
                                </p>
                                <div class="text-black dark:text-white flex flex-col gap-2">
                                    <span class="font-bold">{{ $record->course->courseCode }}</span>
                                    <p class="semester"><strong>Semester:</strong>
                                        @if ($record->semester == 1)
                                            1st Semester
                                        @elseif($record->semester == 2)
                                            2nd Semester
                                        @elseif($record->semester == 3)
                                            Summer Semester
                                        @else
                                            Unknown Semester
                                        @endif
                                    </p>
                                    <p class="school-year"><strong>School Year:</strong> {{ $record->schoolYear }}</p>
                                    <p class="academic-grade"><strong>Total Grade:</strong> 
                                        @php
                                            $gwa = 'Not set';
                                            $classRecordID = $record->classRecordID;
                                            $studentID = $studentClassRecords[$classRecordID] ?? null; // Use the student ID from the class record mapping
                                            if (isset($studentGrades[$classRecordID][$studentID])) {
                                                $gwa = $studentGrades[$classRecordID][$studentID]['gwa'];
                                            }
                                        @endphp
                                        {{ $gwa }}
                                    </p>
                                </div>
                            </div>
                        </button>
                    </form>
                </div>
            @endforeach
        @endif
        

        


        </div>
        <div class="hidden no-results-message">
            <div class="flex justify-center items-center text-neutral-500 h-[50vh] text-2xl">
                No class record found.
            </div>
        </div>
    @endif

</body>

</html>
