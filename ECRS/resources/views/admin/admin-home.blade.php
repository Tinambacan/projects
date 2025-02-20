<!DOCTYPE html>
@extends('layout.AppLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/js/app.js')
    @vite('resources/js/admin-dashboard.js')
    @vite('resources/js/chart.js')
</head>

@section('content')

    <body>
        <div class="flex w-full">
            <div class="flex flex-col w-full transition-all duration-300">
                <div id="customStyle" class="text-3xl text-red-900 font-bold mt-10 dark:text-[#CCAA2C]">
                    Dashboard
                </div>
                <div class="w-full mt-10">
                    <div class="flex w-full justify-center items-center">
                        <div class="flex gap-10 text-white">
                            <div class="flex gap-3 bg-[#CCAA2C] p-5 rounded-xl" >
                                <div class="flex justify-center items-center">
                                    <i id="customStyle" class="fa-solid fa-users text-5xl"></i>
                                </div>
                                <div class="flex flex-col  justify-center">
                                    <div class="text-xl">Total Students Enrolled</div>
                                    <div class="text-2xl font-bold">300</div>
                                </div>
                            </div>

                            <div class="flex gap-3 bg-[#4473B9] p-5 rounded-xl">
                                <div class="flex justify-center items-center">
                                    <i class="fa-solid fa-chalkboard-user text-5xl"></i>
                                </div>
                                <div class="flex flex-col justify-center">
                                    <div class="text-xl">Total Instructors</div>
                                    <div class="text-2xl font-bold">300</div>
                                </div>
                            </div>

                            <div class="flex gap-3 bg-[#9747FF] p-5 rounded-xl">
                                <div class="flex justify-center items-center">
                                    <i class="fa-solid fa-clipboard-check text-5xl"></i>
                                </div>
                                <div class="flex flex-col justify-center">
                                    <div class="text-xl">Total Submitted Class Record</div>
                                    <div class="text-2xl font-bold">300</div>
                                </div>
                            </div>

                            <div class="flex gap-3 bg-[#D94646] p-5 rounded-xl">
                                <div class="flex justify-center items-center">
                                    <i class="fa-solid fa-file-circle-xmark text-5xl"></i>
                                </div>
                                <div class="flex flex-col justify-center">
                                    <div class="text-xl">Total Unsubmitted Class Record</div>
                                    <div class="text-2xl font-bold">300</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="w-full">
                        <div class="flex gap-5 w-full justify-center items-center">
                            <div class="flex gap-3">
                                <div class="flex justify-center items-center font-bold">Professor:</div>
                                <select id="prof-selector" class="p-2 border-2 border-gray-300 rounded shadow-lg">
                                    <option value="">Select Professor</option>
                                    @foreach ($professors as $professor)
                                        <option value="{{ $professor->loginID }}">
                                            {{ $professor->Fname }} {{ $professor->Lname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex gap-3">
                                <div class="flex justify-center items-center font-bold">Course:</div>
                                <select id="course-selector" class="p-2 border-2 border-gray-300 rounded shadow-lg">
                                    <option value="">Select Course</option>
                                </select>
                            </div>
                            <div class="flex gap-3">
                                <div class="flex justify-center items-center font-bold">Academic Year:</div>
                                <select id="acad-selector" class="p-2 border-2 border-gray-300 rounded shadow-lg">
                                    <option value="">Select Academic Year</option>
                                </select>
                            </div>
                            <div class="flex gap-3">
                                <div class="flex justify-center items-center font-bold">Semester:</div>
                                <select id="sem-selector" class="p-2 border-2 border-gray-300 rounded shadow-lg">
                                    <option value="">Select Semester</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="flex gap-5 w-full justify-center items-center ">
                            <div class="flex gap-3">
                                <div class="flex justify-center items-center font-bold">Program:</div>
                                <select id="program-selector" class="p-2 border-2 border-gray-300 rounded shadow-lg">
                                    <option value="">Select Program</option>
                                </select>
                            </div>
                            <div class="flex gap-5  justify-center items-center">
                                <div class="flex gap-3">
                                    <div class="flex justify-center items-center font-bold">Total number of students:
                                    </div>
                                    <span id="total-students">0</span>
                                </div>
                                <div class="flex gap-3">
                                    <div class="flex justify-center items-center font-bold">Number Passed:</div>
                                    <span id="passed-count">0</span>
                                </div>
                                <div class="flex gap-3">
                                    <div class="flex justify-center items-center font-bold">Number Failed:</div>
                                    <span id="failed-count">0</span>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="flex gap-2 justify-center items-center py-12">
                    <div class="bg-white rounded-md p-3 shadow-lg">
                        <canvas id="students-chart" width="400" height="400"></canvas>
                    </div>

                    <div class="bg-white rounded-md p-3 shadow-lg ">
                        {{-- <canvas id="students-chart" width="200" height="200"></canvas> --}}
                        <canvas id="students-chart-bar" width="400" height="400"></canvas>

                    </div>
                </div>

            </div>
        </div>
    </body>

    </html>
@endsection
