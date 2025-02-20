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
</head>

@section('content')

    <body>
        <div class="pt-8">
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
            <div class="w-full">
                <div class="dark:text-white w-full mx-auto px-0 sm:px-0 md:px-2 lg:px-12 xl:px-32 2xl:px-60">
                    <div class="w-full flex  justify-center items-center my-2">
                        <x-titleText>
                            <div class="flex">
                                <p id="edit-course-description" class="mr-2"></p> Class Record
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

                    <div class="bg-white border border-gray-300 rounded-lg shadow-lg mb-5" id="classRecordInfo-card">
                        <div class="px-5 py-5">
                            <div class="flex w-full flex-col">
                                <div id="edit-classrecord-information" data-step="1"
                                    class="flex gap-10 md:flex-row flex-col">
                                    <div class="w-full mt-5">
                                        <div class="space-y-4">
                                            <div class="flex items-start space-x-4">
                                                <div class="w-1/2">
                                                    <div
                                                        class="bg-gray-200 rounded-lg shadow-lg w-full h-32 flex justify-center items-center mb-4">
                                                        <img id="edit-image-preview"
                                                            class="w-full h-full object-cover rounded-lg"
                                                            src="https://via.placeholder.com/150" alt="Image Preview" />
                                                    </div>
                                                    <label for="edit-image-upload" class="w-full cursor-pointer">
                                                        <div
                                                            class="w-full rounded-lg border border-gray-300 bg-white text-black py-2 px-4 text-center focus:outline-none focus:shadow-outline">
                                                            Upload Image
                                                        </div>
                                                        <input id="edit-image-upload" type="file" class="hidden"
                                                            accept="image/*" onchange="previewImage(event)" />
                                                    </label>
                                                </div>
                                                <div class="w-full -mt-3.5">
                                                    <div>
                                                        <label class="block text-gray-700 text-sm font-bold mb-2">Branch:
                                                            <span class="text-red-500">*</span></label>
                                                        <select id="edit-branch-select" required
                                                            class="block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500">
                                                            <option value="">Select PUP Branch</option>
                                                            <option value="1">Taguig</option>
                                                            <option value="2">Sta. Mesa, Manila</option>
                                                            <option value="3">San Juan</option>
                                                            <option value="4">Parañaque</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-gray-700 text-sm font-bold mb-2">Program:
                                                            <span class="text-red-500">*</span></label>
                                                        <select id="edit-program-select" required
                                                            class="block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500">
                                                            <option value="">Select Program</option>
                                                        </select>
                                                    </div>
                                                    <div class="my-2">
                                                        <label class="block text-gray-700 text-sm font-bold mb-2">Course:
                                                            <span class="text-red-500">*</span></label>
                                                        <select id="edit-course-select" required
                                                            class="block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500">
                                                            <option value="">Select Course</option>
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="flex gap-2">
                                                <div class="w-full">
                                                    <label class="block text-gray-700 text-sm font-bold mb-2">Semester:
                                                        <span class="text-red-500">*</span></label>
                                                    <select id="edit-semester" name="semester" required
                                                        class="block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500">
                                                        <option value="">Select Semester</option>
                                                        <option value="1">1st Semester</option>
                                                        <option value="2">2nd Semester</option>
                                                        <option value="3">Summer Semester</option>
                                                    </select>
                                                </div>
                                                <div class="w-full">
                                                    <label class="block text-gray-700 text-sm font-bold mb-2">Number of
                                                        Terms: <span class="text-red-500">*</span></label>
                                                    <select id="edit-grading-period" required disabled
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
                                                    <label class="block text-gray-700 text-sm font-bold mb-2">Academic
                                                        Year:
                                                        <span class="text-red-500">*</span></label>
                                                    <select id="edit-academic-year" required
                                                        class="block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500">
                                                        <option value="">Select Academic Year</option>
                                                    </select>
                                                </div>
                                                <div class="w-full">
                                                    <label class="block text-gray-700 text-sm font-bold mb-2">Year
                                                        Level:
                                                        <span class="text-red-500">*</span></label>
                                                    <input required placeholder="1-1" type="text" id="edit-year-level"
                                                        name="yearLevel"
                                                        class="block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="w-full">
                                        <div class="flex flex-col justify-center items-center mb-2">
                                            <span class="font-bold text-md text-gray-700">Class Schedule</span>
                                            <i class="text-gray-700">Select day(s) that apply</i>
                                        </div>
                                        <div class="flex flex-col justify-center items-center ">
                                            <div
                                                class="flex w-full font-bold mb-2 text-black justify-center items-center px-20">
                                                <div class="flex w-full items-center justify-center">
                                                    <div class="w-48">
                                                        <div class="flex items-center justify-center">
                                                            <span>Day</span>
                                                        </div>
                                                    </div>

                                                    <div class="w-full">
                                                        <div class="flex items-center justify-center mr-8">
                                                            <span>Time</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex flex-col gap-4">

                                                <div class="edit-day-row flex flex-col gap-2">
                                                    <div class="flex gap-5 items-center">

                                                    </div>
                                                </div>
                                                <button id=""
                                                    class="edit-add-schedule-button border border-gray-300 rounded-2xl p-2  w-1/2 mx-auto block text-gray-700 hover:bg-gray-200">
                                                    Add Schedule <i class="ml-2 fa-solid fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="w-full">
                                        <div class="flex flex-col justify-center items-center mb-2">
                                            <span class="font-bold text-md text-gray-700">Class Schedule</span>
                                            <i class="text-gray-700">Select day(s) that apply</i>
                                        </div>
                                        <div class="flex flex-col justify-center items-center ">
                                            <div class="flex flex-col gap-4">
                                                <div class="edit-day-row flex flex-col gap-2">
                                                    <div class="flex gap-5 items-center">

                                                    </div>
                                                </div>
                                                {{-- <button id=""
                                                    class="edit-add-schedule-button border border-gray-300 rounded-2xl p-2  w-1/2 mx-auto block text-gray-700 hover:bg-gray-200">
                                                    Add Schedule <i class="ml-2 fa-solid fa-plus"></i>
                                                </button> --}}

                                                <button id=""
                                                    class="edit-add-schedule-button border border-gray-300 rounded-2xl p-2  md:w-1/2 mx-auto block text-gray-700 hover:bg-gray-200">
                                                    Add Schedule <i class="ml-2 fa-solid fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end items-center md:mt-5  mt-5 ">
                                    <button id="edit-next-btn"
                                        class=" text-black rounded-lg py-1 px-1 w-28 shadow-md border border-gray-300">Next</button>
                                </div>
                            </div>
                        </div>


                        {{-- <div class="hidden" id="edit-grade-distribution" data-step="2">
                                <div class="flex gap-2 justify-center items-center">
                                    <div class="flex flex-col text-center">
                                        <p id="edit-total-grade-distribution"
                                            class="text-xl text-center text-red-900 font-bold mb-4">
                                            Total: 0%</p>
                                        <div class="flex justify-between px-10 mb-2 ">
                                            <label class="text-center block text-gray-700 text-sm font-bold mb-2">Term
                                                Name:
                                                <span class="text-red-500">*</span></label>
                                            <label class=" block text-gray-700 text-sm font-bold mb-2">Term Percentage:
                                                <span class="text-red-500">*</span></label>
                                        </div>

                                        <div class="gap-4 flex flex-col">
                                            <div id="edit-first-grading" class="flex justify-center gap-4">
                                                <div>
                                                    <input type="text"
                                                        class="text-center text-black border-gray-300 edit-first-grade-type-input border-2 rounded-lg p-2 shadow-lg"
                                                        placeholder="1st Term Name">
                                                </div>
                                                <div class="flex items-end">
                                                    <input type="number"
                                                        class="text-center text-black border-gray-300 edit-first-grade-type-input border-2 rounded-lg p-2 shadow-lg edit-first-grade-distribution-input h-full"
                                                        placeholder="1st Term Percentage">
                                                </div>

                                            </div>

                                            <div id="edit-second-grading" class="flex justify-center my-3 gap-4">
                                                <input type="text"
                                                    class="text-center text-black border-gray-300 edit-second-grade-type-input border-2 rounded-lg p-2 shadow-lg"
                                                    placeholder="2nd Term Name">
                                                <div class="flex items-end">
                                                    <input type="number"
                                                        class="text-center border-gray-300 edit-second-grade-type-input border-2 rounded-lg p-2 shadow-lg edit-second-grade-distribution-input  h-full"
                                                        placeholder="2nd Term Percentage">
                                                </div>
                                            </div>


                                            <div id="edit-third-grading" class="flex justify-center gap-4">
                                                <input type="text"
                                                    class="text-center text-black border-gray-300 edit-third-grade-type-input border-2 rounded-lg p-2 shadow-lg"
                                                    placeholder="3rd Term Name">
                                                <div class="flex items-end">

                                                    <input type="number"
                                                        class="text-center text-black border-gray-300 edit-third-grade-type-input border-2 rounded-lg p-2 shadow-lg edit-third-grade-distribution-input h-full"
                                                        placeholder="3rd Term Percentage">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end p-4 gap-2 pt-8">
                                    <button id="edit-grade-config-back-btn"
                                        class="hidden text-black rounded-lg py-1 px-1 w-28 shadow-lg border border-gray-300">Back</button>
                                    <button id="edit-grade-config-next-btn"
                                        class="hidden text-black rounded-lg py-1 px-1 w-28 shadow-lg border border-gray-300">Next</button>
                                </div>
                            </div> --}}


                        <div class="hidden px-5 md:-mt-0 -mt-5 pb-5" id="edit-grade-distribution" data-step="2">
                            <div class="flex gap-2">
                                <div class="flex flex-col  items-center justify-center w-full">
                                    <p id="edit-total-grade-distribution"
                                        class="text-xl text-center text-red-900 font-bold mb-4">
                                        Total: 0%</p>

                                    <div class="flex gap-2 flex-col">
                                        <div id="edit-first-grading" class="flex justify-center gap-4">
                                            <div>
                                                <label class="text-center block text-gray-700 text-sm font-bold mb-2">Term
                                                    Name:
                                                    <span class="text-red-500">*</span></label>
                                                <input type="text"
                                                    class="edit-first-grade-type-input shadow-lg block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500 text-center"
                                                    placeholder="1st Term Name">
                                            </div>
                                            <div>
                                                <label class="text-center block text-gray-700 text-sm font-bold mb-2">Term
                                                    Percentage:
                                                    <span class="text-red-500">*</span></label>
                                                <input type="number"
                                                    class="edit-first-grade-distribution-input shadow-lg block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500 text-center"
                                                    placeholder="1st Term Percentage">
                                            </div>
                                        </div>

                                        <div id="edit-second-grading" class="flex justify-center my-3 gap-4">
                                            <input type="text"
                                                class="edit-second-grade-type-input shadow-lg block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500 text-center"
                                                placeholder="2nd Term Name">
                                            <input type="number"
                                                class="edit-second-grade-distribution-input shadow-lg block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500 text-center"
                                                placeholder="2nd Term Percentage">
                                        </div>

                                        <div id="edit-third-grading" class="flex justify-center gap-4">
                                            <input type="text"
                                                class=" edit-third-grade-type-input shadow-lg block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500 text-center"
                                                placeholder="3rd Term Name">
                                            <input type="number"
                                                class="edit-third-grade-distribution-input shadow-lg block w-full bg-white border border-gray-300 rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-blue-500 text-center"
                                                placeholder="3rd Term Percentage">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end gap-2 pt-8  mt-5">
                                <button id="edit-grade-config-back-btn"
                                    class="hidden text-black rounded-lg py-1 px-1 w-28 shadow-md border border-gray-300">Back</button>
                                <button id="edit-grade-config-next-btn"
                                    class="hidden text-black rounded-lg py-1 px-1 w-28 shadow-md border border-gray-300">Next</button>
                            </div>
                        </div>


                        {{-- <div class="flex w-full flex-col px-5 py-5 "> --}}
                        <div id="edit-normal-grade-configuration" class=" text-black md:-mt-0 -mt-5 pb-5 px-5">
                            <div id="edit-header-grade-configuration">

                            </div>

                            <div id="edit-midterm-grade" value="1">
                                <p id="edit-midterm-name" class="text-xl text-center text-red-900 font-bold mb-4">
                                    Midterm Grading
                                    System Percentage</p>
                                <p id="edit-midterm-total" class="text-xl text-center font-bold mb-4 text-black ">
                                    Midterm: 0
                                </p>
                                <div class="text-black font-semibold text-sm m-2" id="checkbox-container">
                                    <input type="checkbox" id="edit-applyAll" class="mr-1 checkbox-size">
                                    Click the box to apply these grading percentages to all terms.
                                </div>
                                <div class="flex md:justify-between flex-col md:flex-row gap-5">
                                    <div class="border rounded-lg border-gray-300 md:w-1/2 p-4 shadow-lg px-5 w-full">
                                        {{-- <p class="text-lg font-bold mb-2">Class Standing:
                                                <input type="number" id="edit-class-standing-percentage"
                                                    class="border-b border-black focus:outline-none w-16 text-black"
                                                    min="0" max="100" value="0">
                                            </p> --}}

                                        <div class="flex">
                                            <p class="text-lg font-bold mb-2 flex justify-center items-center gap-1">
                                                Class Standing:
                                                <span class="flex shadow-md">
                                                    <input type="number" id="edit-class-standing-percentage"
                                                        class="border rounded-bl-md rounded-tl-md border-black text-center"
                                                        min="0" max="100" value="0">
                                                    <span
                                                        class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                </span>
                                            </p>
                                        </div>
                                        <div id="edit-class-standing-container" class="mb-4">
                                            <div class="assessment-cs flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Activity:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input class-standing-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="assessment-cs flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Assignment:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input class-standing-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="assessment-cs flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Attendance:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input class-standing-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="assessment-cs flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Quiz:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input class-standing-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-1 mb-2">
                                            {{-- <p class="w-1/2 text-black font-bold">Total:</p>
                                                <input type="number" readonly
                                                    class="border-b border-black focus:outline-none w-20 edit-total-grade-class-standing text-black"
                                                    min="0" max="100" value="0"> --}}

                                            <div class="flex">
                                                <p class="text-lg font-bold mb-2 flex justify-center items-center gap-1">
                                                    Total:
                                                    <span class="flex shadow-md">
                                                        <input type="number"
                                                            class="border rounded-bl-md rounded-tl-md border-black text-center edit-total-grade-class-standing"
                                                            min="0" max="100" value="0">
                                                        <span
                                                            class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="new-assessment-cs  flex items-center mb-2 gap-2">
                                            <input type="text" id="edit-new-class-standing-name"
                                                class="border-b border-black focus:outline-none w-40"
                                                placeholder="Assessment Name">
                                            <button id="edit-add-class-standing"
                                                class="rounded-lg border border-gray-300 px-2 py-1">Add new +</button>
                                        </div>
                                    </div>
                                    <div class="border rounded-lg border-gray-300 md:w-1/2 p-4 shadow-lg px-5 w-full">
                                        {{-- <p class="text-lg font-bold mb-2 text-black">Examination:
                                                <input type="number" id="edit-examination-percentage"
                                                    class="border-b border-black focus:outline-none w-16 text-black"
                                                    min="0" max="100" value="0">
                                            </p> --}}

                                        <div class="flex">
                                            <p class="text-lg font-bold mb-2 flex justify-center items-center gap-1">
                                                Examination:
                                                <span class="flex shadow-md">
                                                    <input type="number" id="edit-examination-percentage"
                                                        class="border rounded-bl-md rounded-tl-md border-black text-center"
                                                        min="0" max="100" value="0">
                                                    <span
                                                        class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                </span>
                                            </p>
                                        </div>
                                        <div id="edit-examination-container" class="mb-4">
                                            <div class="assessment-exam flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Laboratory:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input examination-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="assessment-exam flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Project:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input examination-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="assessment-exam flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Written Exam:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input examination-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-1 mb-2">
                                            {{-- <p class="w-1/2 text-black font-bold">Total:</p>
                                                <input type="number" readonly
                                                    class="border-b border-black focus:outline-none w-20 edit-total-examination-input text-black"
                                                    min="0" max="100" value="0"> --}}

                                            <div class="flex">
                                                <p class="text-lg font-bold mb-2 flex justify-center items-center gap-1">
                                                    Total:
                                                    <span class="flex shadow-md">
                                                        <input type="number"
                                                            class="border rounded-bl-md rounded-tl-md border-black text-center edit-total-examination-input"
                                                            min="0" max="100" value="0">
                                                        <span
                                                            class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                    </span>
                                                </p>
                                            </div>

                                        </div>
                                        <div class="new-assessment-exam flex items-center mb-2 gap-2">
                                            <input type="text" id="edit-new-examination-name"
                                                class="border-b border-black focus:outline-none w-40"
                                                placeholder="Assessment Name">
                                            <button id="edit-add-examination"
                                                class="rounded-lg border border-gray-300 px-2 py-1">Add new +</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end  gap-2 pt-8 pb-3">
                                    <button id="edit-midterm-back-btn"
                                        class="text-black rounded-lg py-1 px-1 w-28 shadow-md border border-gray-300">Back</button>
                                    <button id="edit-midterm-next-btn"
                                        class="text-black rounded-lg py-1 px-1 w-28 shadow-md border border-gray-300 hidden">Next</button>
                                    <button id="edit-submit-btn"
                                        class="edit-midterm-submit text-black rounded-lg py-1 px-1 w-28  shadow-md border border-gray-300">Save</button>
                                </div>
                            </div>


                            <div id="edit-final-grade" class="hidden md:-mt-0 -mt-5 pb-5" value="2">
                                <p id="edit-final-name" class="text-xl text-center text-red-900 font-bold mb-4">
                                    Finals Grading
                                    System Percentage</p>
                                <p id="edit-final-total" class="text-xl text-center font-bold mb-4 text-black">
                                    Finals: 0</p>
                                <div class="flex md:justify-between flex-col md:flex-row  gap-5">
                                    <div class="border rounded-lg border-gray-300 md:w-1/2 p-4 shadow-lg px-5 w-full">
                                        {{-- <p class="text-lg font-bold mb-2 text-black">Class Standing: <input
                                                    type="number" id="edit-final-class-standing-percentage"
                                                    class="border-b border-black" min="0" max="100"
                                                    value="0"></p> --}}

                                        <div class="flex">
                                            <p class="text-lg font-bold mb-2 flex justify-center items-center gap-1">
                                                Class Standing:
                                                <span class="flex shadow-md">
                                                    <input type="number" id="edit-final-class-standing-percentage"
                                                        class="border rounded-bl-md rounded-tl-md border-black text-center"
                                                        min="0" max="100" value="0">
                                                    <span
                                                        class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                </span>
                                            </p>
                                        </div>
                                        <div id="edit-final-class-standing-container" class="mb-4">
                                            <div class="assessment-cs-final flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Activity:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-finals edit-final-class-standing-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="assessment-cs-final flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Assignment:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-finals edit-final-class-standing-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="assessment-cs-final flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Attendance:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-finals edit-final-class-standing-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="assessment-cs-final flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Quiz:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-finals edit-final-class-standing-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-1 mb-2">
                                            {{-- <p class="w-1/2 text-black font-bold">Total:</p>
                                                <input type="number" readonly
                                                    class="border-b border-black focus:outline-none w-20 edit-total-class-standing-final-input text-black"
                                                    min="0" max="100" value="0"> --}}

                                            <div class="flex">
                                                <p class="text-lg font-bold mb-2 flex justify-center items-center gap-1">
                                                    Total:
                                                    <span class="flex shadow-md">
                                                        <input type="number"
                                                            class="border rounded-bl-md rounded-tl-md border-black text-center edit-total-class-standing-final-input"
                                                            min="0" max="100" value="0">
                                                        <span
                                                            class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="new-assessment-cs-final  flex items-center mb-2 gap-2">
                                            <input type="text" id="edit-final-new-class-standing-name"
                                                class="border-b border-black focus:outline-none w-40"
                                                placeholder="Assessment Name">
                                            <button id="edit-final-add-class-standing"
                                                class="rounded-lg border border-gray-300 px-2 py-1">Add new +</button>
                                        </div>
                                    </div>
                                    <div class="border rounded-lg border-gray-300 md:w-1/2 p-4 shadow-lg px-5 w-full">
                                        {{-- <p class="text-lg font-bold mb-2 text-black">Examination: <input
                                                    type="number" id="edit-final-examination-percentage"
                                                    class="border-b border-black" min="0" max="100"
                                                    value="0"></p> --}}

                                        <div class="flex">
                                            <p class="text-lg font-bold mb-2 flex justify-center items-center gap-1">
                                                Examination:
                                                <span class="flex shadow-md">
                                                    <input type="number" id="edit-final-examination-percentage"
                                                        class="border rounded-bl-md rounded-tl-md border-black text-center"
                                                        min="0" max="100" value="0">
                                                    <span
                                                        class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                </span>
                                            </p>
                                        </div>
                                        <div id="edit-final-examination-container" class="mb-4">
                                            <div class="assessment-exam-final flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Laboratory:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-finals edit-final-examination-input examination-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="assessment-exam-final flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Project:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-finals edit-final-examination-input examination-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="assessment-exam-final flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Written Exam:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-finals edit-final-examination-input examination-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-1 mb-2">
                                            {{-- <p class="w-1/2 text-black font-bold">Total:</p>
                                                <input type="number" readonly
                                                    class="border-b border-black focus:outline-none w-20 edit-total-final-examination-input text-black"
                                                    min="0" max="100" value="0"> --}}

                                            <div class="flex">
                                                <p class="text-lg font-bold mb-2 flex justify-center items-center gap-1">
                                                    Total:
                                                    <span class="flex shadow-md">
                                                        <input type="number"
                                                            class="border rounded-bl-md rounded-tl-md border-black text-center edit-total-final-examination-input"
                                                            min="0" max="100" value="0">
                                                        <span
                                                            class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="new-assessment-exam-final flex items-center mb-2 gap-2">
                                            <input type="text" id="edit-final-new-examination-name"
                                                class="border-b border-black focus:outline-none w-40"
                                                placeholder="Assessment Name">
                                            <button id="edit-final-add-examination"
                                                class="rounded-lg border border-gray-300 px-2 py-1">Add new +</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end p-4 gap-2 pt-8 pb-3">
                                    <button id="edit-final-back-btn"
                                        class="text-black rounded-lg py-1 px-1 w-28 shadow-md border border-gray-300">Back</button>
                                    <button id="edit-final-next-btn"
                                        class="text-black rounded-lg py-1 px-1 w-28 shadow-md border border-gray-300 hidden">Next</button>
                                    <button id="edit-submit-btn"
                                        class="edit-final-submit text-black rounded-lg py-1 px-1 w-28  shadow-md border border-gray-300">Save</button>
                                </div>
                            </div>

                            <div id="edit-special-grade" class="hidden md:-mt-0 -mt-5 pb-5" value="3">
                                <p id="edit-special-name" class="text-xl text-center text-red-900 font-bold mb-4">
                                    Special
                                    Grading System Percentage</p>
                                <p id="edit-special-total" class="text-xl text-center font-bold mb-4 text-black">
                                    Total: 0
                                </p>
                                <div class="flex md:justify-between flex-col md:flex-row  gap-5">
                                    <div class="border rounded-lg border-gray-300 md:w-1/2 p-4 shadow-lg px-5 w-full">
                                        {{-- <p class="text-lg font-bold mb-2 text-black">Class Standing:
                                                <input type="number" id="edit-special-class-standing-percentage"
                                                    class="border-b border-black text-black" min="0"
                                                    max="100" value="0">
                                            </p> --}}
                                        <div class="flex">
                                            <p class="text-lg font-bold mb-2 flex justify-center items-center gap-1">
                                                Class Standing:
                                                <span class="flex shadow-md">
                                                    <input type="number" id="edit-special-class-standing-percentage"
                                                        class="border rounded-bl-md rounded-tl-md border-black text-center"
                                                        min="0" max="100" value="0">
                                                    <span
                                                        class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                </span>
                                            </p>
                                        </div>
                                        <div id="edit-special-class-standing-container" class="mb-4">
                                            <div class="assessment-cs-special flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Activity:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-special edit-special-class-standing-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="assessment-cs-special flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Assignment:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-special edit-special-class-standing-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="assessment-cs-special flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Attendance:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-special edit-special-class-standing-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="assessment-cs-special flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Quiz:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-special edit-special-class-standing-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-1 mb-2">
                                            {{-- <p class="w-1/2 text-black font-bold">Total:</p>
                                                <input type="number"
                                                    class="border-b border-black focus:outline-none w-20 edit-total-class-standing-special-input text-black"
                                                    min="0" max="100" value="0"> --}}

                                            <div class="flex">
                                                <p class="text-lg font-bold mb-2 flex justify-center items-center gap-1">
                                                    Total:
                                                    <span class="flex shadow-md">
                                                        <input type="number"
                                                            class="border rounded-bl-md rounded-tl-md border-black text-center edit-total-class-standing-special-input"
                                                            min="0" max="100" value="0">
                                                        <span
                                                            class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="new-assessment-cs-special flex items-center mb-2 gap-2">
                                            <input type="text" id="edit-special-new-class-standing-name"
                                                class="border-b border-black focus:outline-none w-40"
                                                placeholder="Assessment Name">
                                            <button id="edit-special-add-class-standing"
                                                class="rounded-lg border border-gray-300 px-2 py-1">Add new +</button>
                                        </div>
                                    </div>
                                    <div class="border rounded-lg border-gray-300 md:w-1/2 p-4 shadow-lg px-5 w-full">
                                        {{-- <p class="text-lg font-bold mb-2 text-black">Examination:
                                                <input type="number" id="edit-special-examination-percentage"
                                                    class="border-b border-black text-black" min="0"
                                                    max="100" value="0">
                                            </p> --}}

                                        <div class="flex">
                                            <p class="text-lg font-bold mb-2 flex justify-center items-center gap-1">
                                                Examination:
                                                <span class="flex shadow-md">
                                                    <input type="number" id="edit-special-examination-percentage"
                                                        class="border rounded-bl-md rounded-tl-md border-black text-center"
                                                        min="0" max="100" value="0">
                                                    <span
                                                        class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                </span>
                                            </p>
                                        </div>
                                        <div id="edit-special-examination-container" class="mb-4">
                                            <div class="assessment-exam-special flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Laboratory:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-special edit-special-examination-input examination-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="assessment-exam-special flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Project:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-special edit-special-examination-input examination-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                            <div class="assessment-exam-special flex items-center space-x-1 mb-2">
                                                <p class="md:w-1/3 w-1/2">Written Exam:</p>
                                                <input type="number"
                                                    class="text-center border-b border-black focus:outline-none w-20 edit-grade-input-special edit-special-examination-input examination-input"
                                                    min="0" max="100" value="0">
                                                <span>%</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-1 mb-2">
                                            {{-- <p class="w-1/2 text-black font-bold">Total:</p>
                                                <input type="number"
                                                    class="border-b border-black focus:outline-none w-20 edit-total-special-examination-input text-black"
                                                    min="0" max="100" value="0"> --}}
                                            <div class="flex">
                                                <p class="text-lg font-bold mb-2 flex justify-center items-center gap-1">
                                                    Total:
                                                    <span class="flex shadow-md">
                                                        <input type="number"
                                                            class="border rounded-bl-md rounded-tl-md border-black text-center edit-total-special-examination-input"
                                                            min="0" max="100" value="0">
                                                        <span
                                                            class="border p-1 px-2 rounded-br-md rounded-tr-md border-gray-300">%</span>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="new-assessment-exam-special flex items-center mb-2 gap-2">
                                            <input type="text" id="edit-special-new-examination-name"
                                                class="border-b border-black focus:outline-none w-40"
                                                placeholder="Assessment Name">
                                            <button id="edit-special-add-examination"
                                                class="rounded-lg border border-gray-300 px-2 py-1">Add new +</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-end p-4 gap-2 pt-8 pb-3">
                                    <button id="edit-special-back-btn"
                                        class="text-black rounded-lg py-1 px-1 w-28 shadow-md border border-gray-300">Back</button>
                                    <button id="edit-special-next-btn"
                                        class="text-black rounded-lg py-1 px-1 w-28 shadow-md border border-gray-300 hidden">Next</button>
                                    <button id="edit-submit-btn"
                                        class="edit-special-submit text-black rounded-lg py-1 px-1 w-28  shadow-md border border-gray-300">Save</button>
                                </div>
                            </div>
                        </div>
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
        </div>
        <x-loader modalLoaderId="loader-modal-update" titleLoader="Updating class record" />
        <x-loader modalLoaderId="loader-get-update" titleLoader="Please Wait" />
    </body>

    </html>
@endsection
