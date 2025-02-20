<!DOCTYPE html>
@extends('layout.AppLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/js/app.js')
    @vite('resources/js/stud-record-details.js')
    @vite('resources/css/dataTable.css')
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ECRS | {{ $selectedClassRecord->course->courseTitle }} </title>
</head>

@section('content')

    <body>

        <div class="w-full mx-auto px-0 sm:px-0 md:px-2 lg:px-12 xl:px-32 2xl:px-56">
            <div
                class="w-full flex flex-col justify-center items-center {{ $selectedClassRecord->isArchived ? 'md:pt-5 pt-20' : 'pt-0 md:pt-5' }}   ">
                @if ($selectedClassRecord)
                    @if ($selectedClassRecord->isArchived == 1)
                        <div
                            class="w-full fixed z-20 flex  text-center md:text-[16px] text-sm bg-white shadow-md shadow-gray-400  md:top-20 top-16 p-2">
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

                    <div
                        class=" animate-fadeIn flex justify-center items-center text-center   mb-4 {{ $selectedClassRecord->isArchived ? 'md:mt-16 mt-10' : 'md:mt-12 mt-10' }}">
                        <x-titleText>
                            <p>{{ $selectedClassRecord->course->courseTitle }}</p>
                        </x-titleText>
                    </div>

                    <div
                        class="bg-red-900 animate-fadeIn rounded-md text-white p-4 flex justify-between text-md dark:text-red-900 dark:bg-white font-bold md:flex-row flex-col gap-2 w-full">
                        <div class="flex flex-col gap-2">
                            @if ($students)
                                <div class="md:block hidden">
                                    <span class="flex gap-1 text-xl font-bold text-ellipsis truncate w-full">
                                        {{ ucwords($students->studentLname . ', ' . $students->studentFname . ' ' . $students->studentMname) }}
                                        ({{ $students->studentNo }})
                                    </span>
                                </div>
                            @else
                                <div>No student information available.</div>
                            @endif
                            <div class="flex flex-col gap-2">
                                <div class="flex gap-1">
                                    <p class="font-bold">Program: </p>
                                    {{ $selectedClassRecord->course->program->programCode }}
                                </div>
                                <div class="flex gap-1">
                                    <p class="font-bold">Professor: </p>
                                    {{ ucwords($classRecordOwner->salutation . ' ' . $classRecordOwner->Fname . ' ' . $classRecordOwner->Lname) }}
                                </div>
                            </div>
                        </div>
                        <div class="flex md:items-end flex-col md:justify-end gap-2">
                            <div class="flex gap-1">
                                <p class="font-bold">Academic Year: </p>
                                {{ $selectedClassRecord->schoolYear }}
                            </div>

                            <div class="flex gap-1">
                                <p class="font-bold">Semester: </p>
                                @if ($selectedClassRecord->semester == 1)
                                    First
                                @elseif($selectedClassRecord->semester == 2)
                                    Second
                                @elseif($selectedClassRecord->semester == 3)
                                    Summer
                                @else
                                    {{ $selectedClassRecord->semester }}
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <h2>No class record selected. Please select a class record from your dashboard.</h2>
                @endif
                <div class="flex justify-between   w-full my-5 animate-fadeIn">
                    <div class="flex gap-2 ">
                        <span class="font-bold text-xl flex justify-center items-center dark:text-white">Term: </span>
                        @if (request()->routeIs('student.class-record-info'))
                            <div>
                                <select id="section-selector"
                                    class="p-2 border border-gray-300 rounded shadow-lg  bg-white text-gray-900 dark:bg-[#404040] dark:text-white">
                                    @foreach ($gradingDistributions as $distribution)
                                        @php
                                            $formattedTypeDistribution = strtolower(
                                                str_replace(' ', '-', $distribution->gradingDistributionType),
                                            );
                                        @endphp
                                        <option data-type="{{ $formattedTypeDistribution }}"
                                            data-term="{{ $distribution->term }}"
                                            value="{{ $formattedTypeDistribution }}">
                                            {{ $distribution->gradingDistributionType }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class=" text-gray-900  dark:text-white text-xl">
                                {{ ucwords($selectedgradingDistributionType) }}</div>
                        @endif
                    </div>

                    <div class="flex justify-center items-center">
                        <div class="flex gap-2">
                            {{-- <div id="send-request-btn"
                                class="text-lg flex gap-2 justify-center items-center text-red-900 rounded-lg p-2 shadow-lg border border-gray-300 bg-white dark:bg-[#404040] dark:text-white">
                                <i
                                    class="fa-solid fa-arrow-up-right-from-square cursor-pointer z-10 text-red-900 dark:text-white"></i>

                                <div class="flex justify-center items-center">
                                    <span class="text-sm">Send Request</span>
                                </div>
                            </div> --}}



                            @if (request()->routeIs('student.class-record-info'))
                                {{-- <div
                                    class="text-lg flex gap-2 justify-center items-center text-red-900 rounded-lg p-2 shadow-lg border border-gray-300 bg-white dark:bg-[#404040] dark:text-white">
                                    <a href="{{ route('export.student.assessments') }}">

                                        <div class="flex justify-center items-center gap-1">
                                            <i
                                                class="fa-solid fa-print cursor-pointer z-10 text-red-900 dark:text-white"></i>
                                            <span class="text-sm">Print Scores</span>
                                        </div>
                                    </a>
                                </div> --}}

                                <div>
                                    <x-button id="print-scores-btn" data-assessments="{{ $studentAssessments->count() }}">
                                        <i class="fa-solid fa-print text-red-900 dark:text-white"></i>
                                        <span class="text-sm">Print Scores</span>
                                    </x-button>
                                </div>
                                <x-loader modalLoaderId="print-score-st" titleLoader="Please wait..." />
                            @endif
                        </div>
                    </div>
                </div>

                <main
                    class=" bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] dark:text-white text-black  shadow-lg w-full p-5 rounded-md overflow-hidden animate-fadeIn">
                    @yield('studclassrecordcontent')
                </main>

                <div class="flex justify-between my-5 w-full animate-fadeIn">
                    @if (request()->routeIs('student.class-record-info'))
                        <a href="/student/dashboard"
                            class="rounded-2xl cursor-pointer dark:hover:bg-[#161616] hover:bg-gray-200 p-2 text-left flex flex-row text-base font-bold ml-2 group">
                            <div class="flex justify-center items-center ">
                                <i class="fa-solid fa-house md:text-2xl text-md text-white p-2 rounded-md bg-red-900 "></i>
                            </div>
                            <div class="flex justify-center items-center pl-2">
                                <span class="text-red-900 uppercase dark:text-white md:text-lg text-sm">Home</span>
                            </div>
                        </a>
                    @else
                        <form action="/redirect-to-lists-assessment-stud" method="POST">
                            @csrf
                            <input type="hidden" name="gradingDistributionType"
                                value="{{ $selectedgradingDistributionType }}">
                            <button type="submit">
                                <div
                                    class="rounded-2xl cursor-pointer dark:hover:bg-[#161616] hover:bg-gray-200 p-2 text-left flex flex-row text-base font-bold ml-2 group">
                                    <div class="flex justify-center items-center ">
                                        <i
                                            class="fa-solid  fa-list md:text-2xl text-md text-white p-2 rounded-md bg-red-900 "></i>
                                    </div>
                                    <div class="flex justify-center items-center pl-2">
                                        <span class="text-red-900 uppercase dark:text-white md:text-lg text-sm">Back to List
                                            of
                                            Assessments</span>
                                    </div>
                                </div>
                            </button>
                        </form>
                    @endif

                    @if ($selectedClassRecord->isArchived == 0)
                        <div id="feedback-btn" data-assessments="{{ $studentAssessments->count() }}"
                            class="rounded-2xl cursor-pointer dark:hover:bg-[#161616] hover:bg-gray-200 p-2 text-left flex flex-row text-base font-bold ml-2 group">
                            <div class="flex justify-center items-center ">
                                <i
                                    class="fa-solid fa-comment-dots md:text-2xl text-md text-white p-2 rounded-md bg-red-900 "></i>
                            </div>
                            <div class="flex justify-center items-center pl-2">
                                <span class="text-red-900 uppercase dark:text-white md:text-lg text-sm">Feedback</span>
                            </div>
                        </div>
                    @else
                        <div></div>
                    @endif
                </div>

                <x-modal title="Student's Feedback" modalId="feedback-modal" closeBtnId="close-btn-feedback">
                    <form id="feedback-form">
                        @csrf
                        <div class="  transform transition-all  w-full max-w-screen-sm dark:bg-[#161616]">
                            <div class="max-w-md mx-auto ">
                                <div class="mt-5">
                                    <span>Any discrepancy?</span>
                                </div>
                                <div class="my-5 bg-white rounded-lg overflow-hidden border border-gray-200 ">
                                    <div class="text-gray-800">
                                        <input type="hidden" id="professor-id" value="{{ $classRecordOwner->loginID }}">
                                        <div class="border-b-2 border-gray-200">
                                            <input type="text" id="subject" name="subject"
                                                class="block w-full outline-none p-2 font-bold" placeholder="Subject"
                                                value="{{ $selectedClassRecord->course->courseTitle }}" disabled>
                                        </div>
                                        <div>
                                            <textarea id="body" name="body" pattern="[a-zA-Z0-9\s.,!?]+"
                                                class="block w-full rounded-md border-gray-300 shadow-sm text-gray-700 p-2 outline-none" rows="4"
                                                placeholder="Enter your message here..." maxlength="50" required></textarea>
                                        </div>
                                        <div class="border-gray-200 text-red-800">
                                            <div class="flex gap-2 p-2">
                                                <span>Characters left:</span><span id="char-count">50</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-center items-center p-5 ">
                                <button
                                    class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                                    <span>Submit</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </x-modal>
                <x-loader modalLoaderId="send-feedback-st" titleLoader="Submitting feedback" />
            </div>
        </div>

    </body>

    </html>
@endsection
