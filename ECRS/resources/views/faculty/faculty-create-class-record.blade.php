<!DOCTYPE html>
@extends('layout.AppLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/js/app.js')
    {{-- @vite('resources/js/class-record-info-page.js') --}}
    @vite('resources/js/classrecord.js')
    @vite('resources/js/ajax-start.js')
    <title>ECRS | Create Class Record</title>
    <style>
        .checkbox-size {
            transform: scale(1.5);
            margin: 0;
            cursor: pointer;
        }
    </style>
</head>

@section('content')

    <body>
        <div class="pt-8 ">
            <div class="flex  my-3 mb-10 rounded-md justify-start items-start">
                <a href="{{ route('faculty.class-record') }}"
                    class="flex gap-2 text-white p-2 dark:hover:bg-[#161616] hover:bg-gray-200 rounded-md cursor-pointer">
                    <div class="text-red-900 dark:text-[#CCAA2C] flex gap-1 justify-center items-center">
                        <i class="fa-solid fa-circle-arrow-left text-2xl"></i>
                        {{-- <i class="fa-solid fa-rectangle-list text-2xl"></i> --}}
                    </div>
                    <span class="md:text-lg text-sm text-black dark:text-white">Back to class record list</span>
                </a>
            </div>
            <div>
                <div class="dark:text-white w-full mx-auto px-0 sm:px-0 md:px-2 lg:px-12 xl:px-32 2xl:px-60">
                    <div class="w-full flex justify-center items-center  my-2 ">
                        {{-- Create Class Record <p id="course-description" class="ml-2"></p> --}}
                        <x-titleText>
                            <div class="flex">
                                Create Class Record <p id="course-description" class="ml-2"></p>
                            </div>
                        </x-titleText>
                    </div>
                    <div class="flex md:justify-between md:flex-row flex-col py-5 gap-2">
                        <div id="stepper-content">
                            <div class="step-content" data-step="1">
                                <span class="text-red-900 dark:text-[#CCAA2C] font-bold md:text-xl text-md">Class Record
                                    Information</span>
                            </div>
                            <div class="step-content hidden" data-step="2">
                                <span class="text-red-900 dark:text-[#CCAA2C] font-bold md:text-xl text-md">Grading Period
                                    Configuration</span>
                            </div>
                            <div class="step-content hidden" data-step="3">
                                <span class="text-red-900 dark:text-[#CCAA2C] font-bold md:text-xl text-md">Grade
                                    Configuration</span>
                            </div>
                            <div class="step-content hidden" data-step="4">
                                <span class="text-red-900 dark:text-[#CCAA2C] font-bold md:text-xl text-md">Final Grade
                                    Configuration</span>
                            </div>
                            <div class="step-content hidden" data-step="5">
                                <span class="text-red-900 dark:text-[#CCAA2C] font-bold md:text-xl text-md">Special Grade
                                    Configuration</span>
                            </div>
                        </div>

                        <div id="stepper" class="flex items-center relative  justify-end">
                            <div class="step-container flex items-center">
                                <div class="step z-20" data-step="1">
                                    <div
                                        class="step-number flex justify-center items-center md:w-8 md:h-8 h-7 w-7 rounded-full bg-red-900 text-white font-bold transition-all duration-300">
                                        1</div>
                                </div>
                                <div class="step-border md:w-14 w-7 h-2 z-10 bg-gray-200 transition-all duration-300"></div>
                            </div>
                            <div class="step-container flex items-center">
                                <div class="step z-20" data-step="2">
                                    <div
                                        class="step-number flex justify-center items-center md:w-8 md:h-8 h-7 w-7 rounded-full bg-gray-300 text-gray-500 font-bold transition-all duration-300">
                                        2</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] dark:text-white  rounded-lg shadow-lg mb-10 w-full"
                        id="classRecordInfo-card">
                        <div class="px-5 py-5">
                            <div class="flex w-full flex-col ">
                                <div class="flex gap-10 md:flex-row flex-col" id="classrecord-information" data-step="1">
                                    <div class=" w-full mt-5">
                                        <div class="space-y-4">
                                            <div class="flex items-start space-x-4">
                                                <div class="w-1/2">
                                                    <div
                                                        class="bg-gray-200 rounded-lg shadow-lg w-full h-32 flex justify-center items-center mb-4">
                                                        <img id="image-preview"
                                                            class="w-full h-full object-cover rounded-lg"
                                                            src="https://via.placeholder.com/150" alt="Image Preview" />
                                                    </div>
                                                    <label for="image-upload" class="w-full cursor-pointer">
                                                        <div
                                                            class="w-full rounded-lg border border-gray-300 bg-white text-black py-2 px-4 text-center focus:outline-none focus:shadow-outline text-sm">
                                                            Upload Image
                                                        </div>
                                                        <input id="image-upload" type="file" class="hidden"
                                                            accept="image/*" onchange="previewImage(event)" />
                                                    </label>
                                                </div>
                                                <div class="w-full -mt-3.5">
                                                    <div>
                                                        <label class="block  text-sm font-bold mb-2">Branch:
                                                            <span class="text-red-500">*</span></label>
                                                        <select id="branch-select" required
                                                            class="block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500">
                                                            <option value="">Select PUP Branch</option>
                                                        </select>
                                                    </div>
                                                    <div class="my-2">
                                                        <label class="block text-sm font-bold mb-2">Program:
                                                            <span class="text-red-500">*</span></label>
                                                        <select id="program-select" required
                                                            class="block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500">
                                                            <option value="">Select Program</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-bold mb-2">Course:
                                                            <span class="text-red-500">*</span></label>
                                                        <select id="course-select" required
                                                            class="block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500">
                                                            <option value="">Select Course</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="flex gap-2">
                                                <div class="w-full">
                                                    <label class="block  text-sm font-bold mb-2">Semester:
                                                        <span class="text-red-500">*</span></label>
                                                    <select id="semester" name="semester" required
                                                        class="block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500">
                                                        <option value="">Select Semester</option>
                                                        <option value="1">1st Semester</option>
                                                        <option value="2">2nd Semester</option>
                                                        <option value="3">Summer Semester</option>
                                                    </select>
                                                </div>
                                                <div class="w-full">
                                                    <label class="block  text-sm font-bold mb-2">Number of
                                                        Terms: <span class="text-red-500">*</span></label>
                                                    <select id="grading-period" required
                                                        class="block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500">
                                                        <option value="">Select No. of Terms</option>
                                                        <option value="1">1 Term</option>
                                                        <option value="2">2 Terms</option>
                                                        <option value="3">3 Terms</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="flex gap-2">
                                                <div class="w-full">
                                                    <label class="block  text-sm font-bold mb-2">Academic
                                                        Year:
                                                        <span class="text-red-500">*</span></label>
                                                    <select id="academic-year" required
                                                        class="block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500">
                                                        <option value="">Select Academic Year</option>
                                                    </select>
                                                </div>
                                                <div class="w-full">
                                                    <label class="block  text-sm font-bold mb-2">Year
                                                        Level:
                                                        <span class="text-red-500">*</span></label>
                                                    <input required placeholder="1-1" type="text" id="year-level"
                                                        class="block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500">
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="w-full">
                                        <div class="flex flex-col justify-center items-center mb-2">
                                            <span class="font-bold text-md ">Class Schedule</span>
                                            <i class="">Select day(s) that apply</i>
                                        </div>
                                        <div class="flex flex-col justify-center items-center ">
                                            <div class="flex flex-col gap-4">
                                                <div class="day-row flex flex-col gap-2 mb-2">
                                                    <div class="flex md:flex-row flex-col gap-5 items-center -mb-2">
                                                        <div class="flex gap-2 flex-col">
                                                            <div
                                                                class="flex items-center justify-center font-bold ">
                                                                <span>Day</span>
                                                            </div>
                                                            <select name="" id=""
                                                                class="flex flex-col gap-2 rounded-2xl p-2 shadow-md border border-gray-300  day-container text-gray-700">
                                                                <option value="">Select Day</option>
                                                                <option value="Monday">Monday</option>
                                                                <option value="Tuesday">Tuesday</option>
                                                                <option value="Wednesday">Wednesday</option>
                                                                <option value="Thursday">Thursday</option>
                                                                <option value="Friday">Friday</option>
                                                                <option value="Saturday">Saturday</option>
                                                                <option value="Sunday">Sunday</option>
                                                            </select>
                                                        </div>

                                                        <div class="flex gap-2 flex-col">
                                                            <div
                                                                class="flex items-center justify-center font-bold">
                                                                <span>Time</span>
                                                            </div>
                                                            <div class="flex flex-row gap-2 px-4">
                                                                <div
                                                                    class="time-inputs flex flex-col md:gap-2 gap-1 ">
                                                                    <div class="flex md:gap-2 gap-1">
                                                                        <input type="time"
                                                                            class="start-time border border-gray-300 rounded-2xl md:p-2 p-2  md:w-full w-24 text-gray-700"
                                                                            required disabled>
                                                                        <span class="self-center">to</span>
                                                                        <input type="time"
                                                                            class="end-time border border-gray-300 rounded-2xl md:p-2 p-2  md:w-full w-24 text-gray-700"
                                                                            required disabled>
                                                                    </div>
                                                                </div>
                                                                <div class="flex justify-center items-center">
                                                                    <i
                                                                        class="add-time fa-solid fa-plus cursor-pointer text-gray-400 text-lg dark:text-[#CCAA2C] bg-white p-1 rounded-md shadow-lg border border-gray-300 hover:bg-gray-200"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <button id=""
                                                    class="add-schedule-button border border-gray-300 rounded-2xl p-2  md:w-1/2 mx-auto block  hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                                                    Add Schedule <i class="ml-2 fa-solid fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end mt-5  gap-2">
                                    <button id="next-btn"
                                        class=" rounded-lg py-1 px-1 w-28 shadow-md border border-gray-300  hover:bg-gray-100 dark:hover:bg-[#1E1E1E] ">Next</button>
                                </div>
                            </div>
                        </div>

                        <div class="hidden px-5 " id="grade-distribution" data-step="2">
                            <div class="flex gap-2">
                                <div class="flex flex-col  items-center justify-center w-full">
                                    <p id="total-grade-distribution"
                                        class="text-xl text-center text-red-900 dark:text-[#CCAA2C] font-bold mb-4">
                                        Total: 0%</p>

                                    <div class="flex gap-2 flex-col">
                                        <div id="first-grading" class="flex justify-center gap-4">
                                            <div>
                                                <label class="text-center block  text-sm font-bold mb-2">Term
                                                    Name:
                                                    <span class="text-red-500">*</span></label>
                                                <input type="text"
                                                    class="first-grade-type-input shadow-lg block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500 text-center"
                                                    placeholder="1st Term Name">
                                            </div>
                                            <div>
                                                <label class="text-center block  text-sm font-bold mb-2">Term
                                                    Percentage:
                                                    <span class="text-red-500">*</span></label>
                                                <input type="number"
                                                    class="first-grade-distribution-input shadow-lg block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500 text-center"
                                                    placeholder="1st Term Percentage">
                                            </div>
                                        </div>

                                        <div id="second-grading" class="flex justify-center my-3 gap-4">
                                            <input type="text"
                                                class="second-grade-type-input shadow-lg block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500 text-center"
                                                placeholder="2nd Term Name">
                                            <input type="number"
                                                class="second-grade-distribution-input shadow-lg block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500 text-center"
                                                placeholder="2nd Term Percentage">
                                        </div>

                                        <div id="third-grading" class="flex justify-center gap-4">
                                            <input type="text"
                                                class=" third-grade-type-input shadow-lg block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500 text-center"
                                                placeholder="3rd Term Name">
                                            <input type="number"
                                                class="third-grade-distribution-input shadow-lg block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500 text-center"
                                                placeholder="3rd Term Percentage">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end gap-2 pt-8 mb-5">
                                <button id="grade-config-back-btn"
                                    class="hidden   rounded-lg py-1 px-1 w-28 shadow-md border border-gray-300  hover:bg-gray-100 dark:hover:bg-[#1E1E1E] ">Back</button>
                                <button id="grade-config-next-btn"
                                    class="hidden   rounded-lg py-1 px-1 w-28 shadow-md border border-gray-300  hover:bg-gray-100 dark:hover:bg-[#1E1E1E] ">Next</button>
                            </div>
                        </div>



                        {{-- <div class="flex w-full flex-col px-5 py-5"> --}}
                        <div id="normal-grade-configuration" class="step-content hidden  pb-5 px-5">
                            <div id="header-grade-configuration">

                            </div>
                            <div id="midterm-grade" value="1">
                                <p id="midterm-name" class="text-xl text-center text-red-900 dark:text-[#CCAA2C] font-bold mb-2">
                                    Midterm Grading
                                    System Percentage </p>
                                <p id="midterm-total" class="text-xl text-center font-bold mb-2 ">Total:
                                    100</p>

                                <div class=" font-semibold text-sm m-2">
                                    <input type="checkbox" id="applyAll" class="mr-1 checkbox-size">
                                    Click the box to apply these grading percentages to all terms.
                                </div>

                                <div class="flex md:justify-between flex-col md:flex-row  gap-5">
                                    <div
                                        class="border rounded-lg border-gray-300 md:w-1/2 p-4 shadow-lg px-5  w-full">
                                        <div class="flex">
                                            <p class="text-lg font-bold mb-2 flex justify-center items-center gap-1">
                                                Class Standing:
                                                <span class="flex shadow-md">
                                                    <input type="number" id="class-standing-percentage"
                                                        class="border rounded-bl-md rounded-tl-md border-black text-center text-black"
                                                        min="0" max="100" value="70">
                                                    <span
                                                        class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                </span>
                                            </p>
                                        </div>
                                        <div id="class-standing-container" class="mb-4">
                                            <div class="flex items-center space-x-1 mb-2 gap-2">
                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b pl-2 border-black focus:outline-none text-black"
                                                    value="Activity">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input class-standing-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="flex items-center space-x-1 mb-2 gap-2">
                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b  border-black focus:outline-none text-black pl-2"
                                                    value="Assignment">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input class-standing-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="flex items-center space-x-1 mb-2 gap-2">
                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Attendance">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input class-standing-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="flex items-center space-x-1 mb-2 gap-2">
                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Quiz">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input class-standing-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-1 mb-5 gap-2">
                                            <p class="md:w-1/3 w-1/2 font-bold">Total</p>
                                            <span>:</span>
                                            <input type="number" readonly
                                                class="text-center border-b border-black focus:outline-none w-20 total-grade-class-standing text-black"
                                                min="0" max="100" value="0">
                                            <span>%</span>
                                        </div>
                                        <div class="flex items-center mb-2 md:gap-3 gap-2">
                                            <p class="md:text-[15px] text-sm">Assessment Name:</p>
                                            <input type="text" id="new-class-standing-name"
                                                class="shadow-md border rounded-md border-gray-300 focus:outline-none md:w-40 w-32 py-1 px-1 text-black"
                                                placeholder="Assessment Name">
                                            <button id="add-class-standing"
                                                class="rounded-lg border border-gray-300 px-1 py-1 md:w-14 w-20 hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">+
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                    <div
                                        class="border rounded-lg border-gray-300 md:w-1/2 p-4 shadow-lg px-5  w-full">
                                        {{-- <p class="text-lg font-bold mb-2">Examination: <input type="number"
                                                    id="examination-percentage"
                                                    class="border-b border-black focus:outline-none w-16" min="0"
                                                    max="100" value="30">
                                            </p> --}}

                                        <div class="flex">
                                            <p class="text-lg font-bold mb-2 flex justify-center items-center gap-1">
                                                Examination:
                                                <span class="flex shadow-md">
                                                    <input type="number" id="examination-percentage"
                                                        class="border rounded-bl-md rounded-tl-md border-black text-center text-black"
                                                        min="0" max="100" value="30">
                                                    <span
                                                        class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                </span>
                                            </p>
                                        </div>
                                        <div id="examination-container" class="mb-4">
                                            <div class="flex items-center space-x-1 mb-2 gap-2">
                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Laboratory">
                                                <span>:</span>
                                                <input type="number"
                                                    class="border-b border-black text-center w-20 grade-input examination-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="flex items-center space-x-1 mb-2 gap-2">
                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Project">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input examination-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="flex items-center space-x-1 mb-2 gap-2">
                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Written Exam">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input examination-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-1 mb-5 gap-2">
                                            <p class="md:w-1/3 w-1/2 font-bold">Total</p>
                                            <span>:</span>
                                            <input type="number" readonly
                                                class="text-center border-b border-black focus:outline-none w-20 total-examination-input text-black"
                                                min="0" max="100" value="0">
                                            <span>%</span>
                                        </div>
                                        <div class="flex items-center mb-2 md:gap-3 gap-2">
                                            <p class="md:text-[15px] text-sm">Assessment Name:</p>
                                            <input type="text" id="new-examination-name"
                                                class="shadow-md border rounded-md border-gray-300 focus:outline-none md:w-40 w-32 py-1 px-1 text-black"
                                                placeholder="Assessment Name">
                                            <button id="add-examination"
                                                class="rounded-lg border border-gray-300 px-1 py-1 md:w-14 w-20 hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">+
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2 pt-8">
                                    <button id="midterm-back-btn"
                                        class=" rounded-lg py-1 px-1 w-28 shadow-md border border-gray-300 hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">Back</button>
                                    <button id="midterm-next-btn"
                                        class=" rounded-lg py-1 px-1 w-28 shadow-md border border-gray-300  hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">Next</button>
                                    <button id="submit-btn"
                                        class="midterm-submit  rounded-lg py-1 px-1 md:w-1/5 w-3/5 shadow-lg border border-gray-300 hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">Create
                                        Class Record</button>
                                </div>
                            </div>

                            <div id="final-grade" class="hidden" value="2">
                                <p id="final-name" class="text-xl text-center text-red-900 dark:text-[#CCAA2C] font-bold mb-4">Finals
                                    Grading
                                    System Percentage</p>
                                <p id="final-total" class="text-xl text-center font-bold mb-4 ">Total:
                                    100</p>
                                <div class=" flex md:justify-between flex-col md:flex-row  gap-5">
                                    <div class="border rounded-lg border-gray-300 md:w-1/2 w-full p-4 shadow-lg px-5">
                                        <div class="flex">
                                            <p class="text-lg font-bold mb-2 flex justify-center items-center gap-1">
                                                Class Standing:
                                                <span class="flex shadow-md">
                                                    <input type="number" id="final-class-standing-percentage"
                                                        class="border rounded-bl-md rounded-tl-md border-black text-center text-black"
                                                        min="0" max="100" value="70">
                                                    <span
                                                        class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                </span>
                                            </p>
                                        </div>
                                        <div id="final-class-standing-container" class="mb-4">
                                            <div class="flex items-center space-x-1 mb-2 gap-2">

                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Activity">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input-finals final-class-standing-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                                {{-- <p class="md:w-1/3 w-1/2">Activity:</p>
                                                    <div class="flex shadow-md">
                                                        <input type="number"
                                                            class="rounded-bl-md rounded-tl-md border-gray-300 text-center w-20 grade-input-finals final-class-standing-input border"
                                                            min="0" max="100" value="0">
                                                        <span
                                                            class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                    </div> --}}
                                            </div>
                                            <div class="flex items-center space-x-1 mb-2 gap-2">
                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Assignment">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input-finals final-class-standing-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                                {{-- <p class="md:w-1/3 w-1/2">Assignment:</p>
                                                    <div class="flex shadow-md">
                                                        <input type="number"
                                                            class="rounded-bl-md rounded-tl-md border-gray-300 text-center w-20 grade-input-finals final-class-standing-input border"
                                                            min="0" max="100" value="0">
                                                        <span
                                                            class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                    </div> --}}
                                            </div>
                                            <div class="flex items-center space-x-1 mb-2 gap-2">

                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Attendance">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input-finals final-class-standing-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                                {{-- <p class="md:w-1/3 w-1/2">Attendance:</p>
                                                    <div class="flex shadow-md">
                                                        <input type="number"
                                                            class="rounded-bl-md rounded-tl-md border-gray-300 text-center w-20 grade-input-finals final-class-standing-input border"
                                                            min="0" max="100" value="0">
                                                        <span
                                                            class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                    </div> --}}
                                            </div>
                                            <div class="flex items-center space-x-1 mb-2 gap-2">
                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Quiz">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input-finals final-class-standing-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-1 mb-5 gap-2">
                                            {{-- <input type="checkbox" class="mr-2"> --}}
                                            <p class="md:w-1/3 w-1/2 font-bold">Total</p>
                                            <span>:</span>
                                            <input type="number"
                                                class="text-center border-b border-black focus:outline-none w-20 total-class-standing-final-input text-black"
                                                min="0" max="100" value="0">
                                            <span>%</span>
                                        </div>
                                        <div class="flex items-center mb-2 md:gap-3 gap-2">
                                            <p class="md:text-[15px] text-sm">Assessment Name:</p>
                                            <input type="text" id="final-new-class-standing-name"
                                                class="shadow-md border rounded-md border-gray-300 focus:outline-none md:w-40 w-32 py-1 px-1 text-black"
                                                placeholder="Assessment Name">
                                            <button id="final-add-class-standing"
                                                class="rounded-lg border border-gray-300 px-1 py-1 md:w-14 w-20 hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">+
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                    <div class="border rounded-lg border-gray-300 md:w-1/2 w-full p-4 shadow-lg px-5 ">
                                        <div class="flex">
                                            <p class="text-lg font-bold mb-2  flex justify-center items-center gap-1">
                                                Examination:
                                                <span class="flex shadow-md">
                                                    <input type="number" id="final-examination-percentage"
                                                        class="border rounded-bl-md rounded-tl-md border-black text-center text-black"
                                                        min="0" max="100" value="30">
                                                    <span
                                                        class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                </span>
                                            </p>
                                        </div>
                                        <div id="final-examination-container" class="mb-4">
                                            <div class="flex items-center space-x-1 mb-2 gap-2">

                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Laboratory">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input-finals final-examination-input examination-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="flex items-center space-x-1 mb-2 gap-2">
                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Project">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input-finals final-examination-input examination-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="flex items-center space-x-1 mb-2 gap-2">
                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Written Exam">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input-finals final-examination-input examination-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-1 mb-5 gap-2">
                                            <p class="md:w-1/3 w-1/2 font-bold">Total</p>
                                            <span>:</span>
                                            <input type="number"
                                                class="text-center border-b border-black focus:outline-none w-20 total-final-examination-input text-black"
                                                min="0" max="100" value="0">
                                            <span>%</span>
                                        </div>
                                        <div class="flex items-center mb-2 md:gap-3 gap-2">
                                            <p class="md:text-[15px] text-sm">Assessment Name:</p>
                                            <input type="text" id="final-new-examination-name"
                                                class="shadow-md border rounded-md border-gray-300 focus:outline-none md:w-40 w-32 py-1 px-1 text-black"
                                                placeholder="Assessment Name">
                                            <button id="final-add-examination"
                                                class="rounded-lg border border-gray-300 px-1 py-1 md:w-14 w-20 hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">+
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2 pt-8">
                                    <button id="final-back-btn"
                                        class="rounded-lg py-1 px-1 w-28 shadow-md border border-gray-300 hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">Back</button>
                                    <button id="final-next-btn"
                                        class=" rounded-lg py-1 px-1 w-28 shadow-md border border-gray-300 hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">Next</button>
                                    <button id="submit-btn"
                                        class="final-submit  rounded-lg py-1 px-1 md:w-1/5 w-3/5  shadow-lg border border-gray-300 hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">Create
                                        Class Record</button>
                                </div>
                            </div>

                            <div id="special-grade" class="hidden" value="3">
                                <p id="special-name" class="text-xl text-center text-red-900 dark:text-[#CCAA2C] font-bold mb-4">
                                    Special Grading
                                    System Percentage
                                </p>
                                <p id="special-total" class="text-xl text-center font-bold mb-4">Total: 100</p>
                                <div class="flex  md:justify-between flex-col md:flex-row gap-5">
                                    <div class="border rounded-lg border-gray-300 md:w-1/2 w-full p-4 shadow-lg px-5">
                                        <div class="flex">
                                            <p class="text-lg font-bold mb-2 flex justify-center items-center gap-1">
                                                Class Standing:
                                                <span class="flex shadow-md">
                                                    <input type="number" id="special-class-standing-percentage"
                                                        class="border rounded-bl-md rounded-tl-md border-black text-center text-black"
                                                        min="0" max="100" value="70">
                                                    <span
                                                        class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                </span>
                                            </p>
                                        </div>

                                        <div id="special-class-standing-container" class="mb-4">
                                            <div class="flex items-center space-x-1 mb-2 gap-2">
                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Activity">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input-special special-class-standing-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="flex items-center space-x-1 mb-2 gap-2">

                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Assignment">
                                                <span>:</span>
                                                <input type="number"
                                                    class=" text-center border-b border-black focus:outline-none w-20 grade-input-special special-class-standing-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                                {{-- <div class="flex shadow-md">
                                                        <input type="number"
                                                            class="rounded-bl-md rounded-tl-md border-gray-300 text-center w-20 grade-input-special special-class-standing-input border"
                                                            min="0" max="100" value="0">
                                                        <span
                                                            class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                    </div> --}}
                                            </div>
                                            <div class="flex items-center space-x-1 mb-2 gap-2">
                                                {{-- <input type="checkbox" class="mr-2"> --}}

                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Attendance">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input-special special-class-standing-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                                {{-- <div class="flex shadow-md">
                                                        <input type="number"
                                                            class="rounded-bl-md rounded-tl-md border-gray-300 text-center w-20 grade-input-special special-class-standing-input border"
                                                            min="0" max="100" value="0">
                                                        <span
                                                            class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                    </div> --}}
                                            </div>

                                            <div class="flex items-center space-x-1 mb-2 gap-2">
                                                {{-- <input type="checkbox" class="mr-2"> --}}
                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Quiz">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input-special special-class-standing-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                                {{-- <p class="md:w-1/3 w-1/2">Quiz:</p>
                                                    <div class="flex shadow-md">
                                                        <input type="number"
                                                            class="rounded-bl-md rounded-tl-md border-gray-300 text-center w-20 grade-input-special special-class-standing-input border"
                                                            min="0" max="100" value="0">
                                                        <span
                                                            class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                    </div> --}}
                                            </div>

                                        </div>
                                        <div class="flex items-center space-x-1 mb-5 gap-2">
                                            {{-- <input type="checkbox" class="mr-2"> --}}
                                            <p class="md:w-1/3 w-1/2 font-bold">Total</p>
                                            <span>:</span>
                                            <input type="number"
                                                class="text-center border-b border-black focus:outline-none w-20 total-class-standing-special-input text-black"
                                                min="0" max="100" value="0">
                                            <span>%</span>
                                            {{-- <div class="flex shadow-md">
                                                    <input type="number" readonly
                                                        class="rounded-bl-md rounded-tl-md border-gray-300 text-center w-20 total-class-standing-special-input border"
                                                        min="0" max="100" value="0">
                                                    <span
                                                        class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                </div> --}}
                                        </div>
                                        <div class="flex items-center mb-2 gap-2">
                                            <div class="flex items-center mb-2 md:gap-3 gap-2">
                                                <p class="md:text-[15px] text-sm">Assessment Name:</p>
                                                <input type="text" id="special-new-class-standing-name"
                                                    class="shadow-md border rounded-md border-gray-300 focus:outline-none md:w-40 w-32 py-1 px-1 text-black"
                                                    placeholder="Assessment Name">
                                                <button id="special-add-class-standing"
                                                    class="rounded-lg border border-gray-300 px-1 py-1 md:w-14 w-20 hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">+
                                                    Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border rounded-lg border-gray-300 md:w-1/2 w-full p-4 shadow-lg px-5">
                                        {{-- <p class="text-lg font-bold mb-2">Examination: <input type="number"
                                                    id="special-examination-percentage" class="border-b border-black"
                                                    min="0" max="100" value="30"></p> --}}

                                        <div class="flex">
                                            <p class="text-lg font-bold mb-2 flex justify-center items-center gap-1">
                                                Examination:
                                                <span class="flex shadow-md">
                                                    <input type="number" id="special-examination-percentage"
                                                        class="border rounded-bl-md rounded-tl-md border-black text-center text-black"
                                                        min="0" max="100" value="30">
                                                    <span
                                                        class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                </span>
                                            </p>
                                        </div>
                                        <div id="special-examination-container" class="mb-4">
                                            <div class="flex items-center space-x-1 mb-2 gap-2">
                                                {{-- <p class="w-1/2">Laboratory:</p>  --}}

                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Laboratory">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input-special special-examination-input examination-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                                {{-- <div class="flex shadow-md">
                                                        <input type="number"
                                                            class="rounded-bl-md rounded-tl-md border-gray-300 text-center w-20 grade-input-special special-examination-input examination-input border"
                                                            min="0" max="100" value="0">
                                                        <span
                                                            class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                    </div> --}}
                                            </div>
                                            <div class="flex items-center space-x-1 mb-2 gap-2">
                                                {{-- <p class="w-1/2">Project:</p>
                                                    <input type="number"
                                                        class="border-b border-black focus:outline-none w-20 grade-input-special special-examination-input examination-input"
                                                        min="0" max="100" value="0"> --}}

                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Project">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input-special special-examination-input examination-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                                {{-- <div class="flex shadow-md">
                                                        <input type="number"
                                                            class="rounded-bl-md rounded-tl-md border-gray-300 text-center w-20 grade-input-special special-examination-input examination-input border"
                                                            min="0" max="100" value="0">
                                                        <span
                                                            class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                    </div> --}}
                                            </div>
                                            <div class="flex items-center space-x-1 mb-2 gap-2">
                                                <input type="text"
                                                    class="assessment-type-input md:w-1/3 w-1/2 border-b border-black focus:outline-none text-black pl-2"
                                                    value="Written Exam">
                                                <span>:</span>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 grade-input-special special-examination-input examination-input text-black"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-1 mb-5 gap-2">
                                            <p class="md:w-1/3 w-1/2 font-bold">Total</p>
                                            <span>:</span>
                                            <input type="number"
                                                class="text-center border-b border-black focus:outline-none w-20 total-special-examination-input text-black"
                                                min="0" max="100" value="0">
                                            <span>%</span>
                                            {{-- <div class="flex shadow-md">
                                                    <input type="number" readonly
                                                        class="rounded-bl-md rounded-tl-md border-gray-300 text-center w-20 total-special-examination-input border"
                                                        min="0" max="100" value="0">
                                                    <span
                                                        class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                </div> --}}
                                        </div>
                                        <div class="flex items-center mb-2 gap-2">
                                            {{-- <input type="text" id="special-new-examination-name"
                                                    class="border-b border-black focus:outline-none w-40"
                                                    placeholder="Assessment Name">
                                                <button id="special-add-examination"
                                                    class="rounded-lg border border-gray-300 px-2 py-1">Add new
                                                    +</button> --}}

                                            <div class="flex items-center mb-2 md:gap-3 gap-2">
                                                <p class="md:text-[15px] text-sm">Assessment Name:</p>
                                                <input type="text" id="special-new-examination-name"
                                                    class="shadow-md border rounded-md border-gray-300 focus:outline-none md:w-40 w-32 py-1 px-1 text-black"
                                                    placeholder="Assessment Name">
                                                <button id="special-add-examination"
                                                    class="rounded-lg border border-gray-300 px-1 py-1 md:w-14 w-20 hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">+
                                                    Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2 pt-8">
                                    <button id="special-back-btn"
                                        class=" rounded-lg py-1 px-1 w-28 shadow-md border border-gray-300 hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">Back</button>
                                    <button id="special-next-btn"
                                        class=" rounded-lg py-1 px-1 w-28 shadow-md border border-gray-300 hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">Next</button>
                                    <button id="submit-btn"
                                        class="special-submit rounded-lg py-1 px-1 md:w-1/5 w-3/5 shadow-lg border border-gray-300 hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">Create
                                        Class Record</button>
                                </div>
                            </div>
                        </div>
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
        </div>
        <x-loader modalLoaderId="loader-modal-create" titleLoader="Creating class record" />

    </body>

    </html>
@endsection
