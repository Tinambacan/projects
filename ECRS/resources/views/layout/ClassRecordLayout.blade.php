@php
    $loginID = session('loginID');
    $userinfo = session('userinfo');
    $user = session('user');
    $role = session('role');
    // $role = session('role');
@endphp

<!DOCTYPE html>
@extends('layout.AppLayout')
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/js/app.js')
    @vite('resources/js/classrecordlayout.js')
    @vite('resources/css/dataTable.css')
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
</head>

<body>
    @section('content')
        <div class="flex w-full">
            <div class="flex flex-col  w-full">

                <div class="flex my-5 md:mb-5 mb-6 rounded-md justify-start items-start z-10 {{ $classRecords->isArchived ? 'md:pt-14 pt-20' : 'md:pt-5 pt-7' }} ">
                    <a href="{{ route('faculty.class-record') }}"
                        class="flex gap-2 text-white  dark:hover:bg-[#161616] hover:bg-gray-200 rounded-md cursor-pointer p-2">
                        <div class="text-red-900 dark:text-[#CCAA2C] flex gap-1 justify-center items-center">
                            <i class="fa-solid fa-circle-arrow-left text-2xl"></i>
                        </div>
                        <span class="md:text-lg text-md text-black dark:text-white">Back to class record list</span>
                    </a>
                </div>

                <div
                    class="w-full flex justify-center items-center ">
                    @if ($classRecords->isArchived == 1)
                        <div
                            class="w-full fixed z-30 flex  text-center md:text-[16px] text-sm bg-white shadow-md shadow-gray-400  md:top-20 top-16 p-2">
                            <div>
                                <div class="w-full font-bold">
                                    <div class=" bg-white">
                                        <span>This class record has been archived by your professor. You cannot add or edit
                                            anything.</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @else
                        <div></div>
                    @endif

                    {{-- <div class="w-full font-bold">
                        @if ($classRecords->isArchived == 1)
                            <div class=" rounded-md p-2 border shadow-md border-red-900 bg-white">
                                <span>This class record has been archived. You
                                    cannot add or edit anything.</span>
                            </div>
                        @else
                            <div></div>
                        @endif
                    </div> --}}
                </div>
                <div class="flex flex-col mb-5">
                    <div class=" text-center flex justify-center">
                        {{-- {{ $classRecords->course->courseTitle }} --}}
                        <x-titleText>
                            {{ $classRecords->course->courseTitle }}
                        </x-titleText>
                    </div>

                    <div class="md:text-2xl text-xl font-bold dark:text-white text-center">
                        <div class="flex gap-2 justify-center my-5 md:flex-row flex-col">
                            <span>Class Record</span>
                            <div>{{ $classRecords->schoolYear }}</div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <div class="flex gap-2 justify-center">
                                <span>{{ $classRecords->course->program->programCode }}</span>
                                {{ $classRecords->yearLevel }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between gap-4 w-full {{ $classRecords->isArchived ? 'mb-5' : '' }}">
                    <div id="tabs-container" class=" overflow-x-auto shadow-md">
                        <div id="tabs" class="flex tab  whitespace-nowrap md:text-lg text-md   dark:border-[#B7B4B4] border-gray-300">
                            <div id="student-info-tab" data-type="student-info"
                                class=" cursor-pointer p-2 rounded-tl-lg shadow-md  dark:bg-[#404040] dark:text-white border-gray-300 border-[1px]  text-gray-700  focus:outline-none focus:ring-2 focus:ring-red-900 dark:focus:ring-[#CCAA2C] focus:ring-offset-2">
                                Student Information
                            </div>
                            @foreach ($gradingDistributions as $distribution)
                                @php
                                    $formattedTypeDistribution = strtolower(
                                        str_replace(' ', '-', $distribution->gradingDistributionType),
                                    );
                                @endphp
                                <div id="{{ $formattedTypeDistribution }}-tab" data-type="{{ $formattedTypeDistribution }}"
                                    data-term="{{ $distribution->term }}"
                                    data-class-record-id="{{ $classRecords->classRecordID }}"
                                    class="cursor-pointer p-2 dark:bg-[#404040] dark:text-white  border-[1px] border-gray-300  text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-red-900 dark:focus:ring-[#CCAA2C] focus:ring-offset-2">
                                    {{ ucfirst($distribution->gradingDistributionType) }}
                                </div>
                            @endforeach
                            <div id="sem-grade-tab" data-type="semester-grade"
                                class=" cursor-pointer p-2 rounded-tr-lg  dark:bg-[#404040] dark:text-white border-[1px] border-gray-300   text-gray-700 bg-white   focus:outline-none focus:ring-2 focus:ring-red-900 dark:focus:ring-[#CCAA2C] focus:ring-offset-2">
                                Semester Grade
                            </div>
                        </div>
                    </div>
                    @if (request()->routeIs('faculty.view-class-record-info') ||
                            request()->routeIs('faculty.view-class-record-stud-info-details') || request()->routeIs('faculty.view-class-record-stud-grade'))
                        <div class="flex justify-center items-center">
                            <x-information>
                                <div class="shadow-lg rounded-lg">
                                    <div class="flex flex-col gap-3 p-4 rounded-md dark:text-white text-sm md:text-md">
                                        <div><span class="text-green-500 font-bold">Published Status</span> means that the
                                            grades
                                            and
                                            scores are reflected to the students
                                            and ready for viewing.</div>
                                        <div><span class="text-red-500 font-bold">Unpublished Status</span> means that the
                                            grades
                                            and scores are not yet ready for viewing.</div>
                                    </div>
                            </x-information>
                        </div>
                    @endif
                </div>
                <main>
                    @yield('classrecordcontent')
                </main>
            </div>
        </div>
    @endsection
</body>


</html>
