<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
    @vite('resources/js/app.js')
    @vite('resources/js/class-record-quiz.js')
    <title>{{ ucfirst($gradingDistribution->gradingDistributionType) }} | {{ ucwords($storedAssessmentType) }}</title>
</head>

<body>
    @extends('layout.ClassRecordLayout')

    @section('classrecordcontent')
        <div class="flex justify-center w-full">
            <div class="flex flex-col w-full">
                <div id="grading-type-section" class=" rounded-lg  md:mt-8 mt-3 mb-3">
                    <div class="flex relative justify-end items-end flex-col ">
                        <div class="absolute inset-0 flex justify-center items-center md:mt-0 my-5 ">
                            <div class="flex justify-center text-md">
                                <select id="section-selector"
                                    class="dark:bg-[#404040] dark:text-white dark:border-[#B7B4B4] shadow-md w-full px-4 py-2 font-bold  bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-900 dark:focus:ring-[#CCAA2C] focus:ring-offset-2 " >
                                </select>
                            </div>
                        </div>
                        <span id="isArchived" hidden>{{ $classRecords->isArchived }}</span>
                        @if ($classRecords->isArchived == 0)
                            <div class="md:inline hidden">
                                <div class="flex gap-1 dark:text-white  relative ">
                                    <div class="relative group flex justify-center items-center ">
                                        <div id="add-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-btn"
                                            class=" flex gap-2 justify-center items-center text-red-900 rounded-lg">
                                            <i
                                                class="fa-solid fa-file-circle-plus cursor-pointer z-10 text-red-900 text-xl dark:text-[#CCAA2C] hover:bg-gray-200 p-1 rounded-md"></i>
                                        </div>
                                        <x-tooltips tooltipTitle="Add {{ ucwords($storedAssessmentType) }}" />
                                    </div>

                                    <div class="relative group flex justify-center items-center send-batch-stud-scores">
                                        <input type="hidden" id="selectedAssessIDs" name="selectedAssessIDs"
                                            value="">
                                        <input type="hidden" name="classRecordIDScore"
                                            value="{{ $classRecords->classRecordID }}" />
                                        <input type="hidden" name="gradingType"
                                            value="{{ $gradingDistribution->gradingDistributionType }}" />
                                        <input type="hidden" name="gradingTerm" value="{{ $gradingDistribution->term }}" />
                                        <div
                                            class="text-lg flex gap-2 justify-center items-center text-red-900 rounded-lg ">
                                            <i
                                                class="fa-solid fa-upload cursor-pointer z-10 text-red-900 text-xl dark:text-[#CCAA2C] hover:bg-gray-200 p-1 rounded-md"></i>
                                        </div>
                                        <x-tooltips tooltipTitle="Publish scores" />
                                    </div>
                                    @if ($storedAssessmentType !== 'attendance')
                                        <div class="relative group flex justify-center items-center">
                                            <form class="export-template-btn">
                                                @csrf
                                                <input type="hidden" name="classRecordID"
                                                    value="{{ $classRecords->classRecordID }}" />
                                                <input type="hidden" name="assessmentType"
                                                    value="{{ $storedAssessmentType }}" />
                                                <button type="submit"
                                                    class="text-lg flex gap-2 justify-center items-center text-red-900 rounded-lg">
                                                    <i
                                                        class="fa-solid fa-file-export cursor-pointer z-10 text-red-900 text-xl dark:text-[#CCAA2C] hover:bg-gray-200 p-1 rounded-md"></i>
                                                </button>
                                            </form>
                                            <x-tooltips
                                                tooltipTitle="Export {{ ucwords($storedAssessmentType) }} Template" />
                                        </div>

                                        <div class="relative group flex justify-center items-center">
                                            <i id=""
                                                class="import-assessment-btn fa-solid fa-file-import flex gap-2 justify-center items-center  cursor-pointer z-10 text-red-900 text-xl dark:text-[#CCAA2C] hover:bg-gray-200 p-1 rounded-md"></i>
                                            <x-tooltips
                                                tooltipTitle="Import {{ ucwords($storedAssessmentType) }} Template" />
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div></div>
                        @endif
                    </div>
                </div>

                <div id="student-info-section" class="  rounded-lg md:my-0  flex gap-2 flex-col mt-10">
                    @if ($classRecords->isArchived == 0)
                        <div class="md:hidden inline">
                            <div class="flex gap-1 dark:text-white relative justify-end">
                                <div class="relative group flex justify-center items-center">
                                    <div id="add-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-btn"
                                        class="text-lg flex gap-2 justify-center items-center text-red-900 rounded-lg">
                                        <i
                                            class="fa-solid fa-file-circle-plus cursor-pointer z-10 text-red-900 text-2xl dark:text-[#CCAA2C]"></i>
                                    </div>
                                    <x-tooltips tooltipTitle="Add {{ ucwords($storedAssessmentType) }}" />
                                </div>

                                <div class="relative group flex justify-center items-center send-batch-stud-scores">
                                    <input type="hidden" id="selectedAssessIDs" name="selectedAssessIDs" value="">
                                    <input type="hidden" name="classRecordIDScore"
                                        value="{{ $classRecords->classRecordID }}" />
                                    <input type="hidden" name="gradingType"
                                        value="{{ $gradingDistribution->gradingDistributionType }}" />
                                    <input type="hidden" name="gradingTerm" value="{{ $gradingDistribution->term }}" />
                                    <div class="text-lg flex gap-2 justify-center items-center text-red-900 rounded-lg ">
                                        <i
                                            class="fa-solid fa-upload cursor-pointer z-10 text-red-900 text-2xl dark:text-[#CCAA2C]"></i>
                                    </div>
                                    <x-tooltips tooltipTitle="Publish scores" />
                                </div>
                                @if ($storedAssessmentType !== 'attendance')
                                    <div class="relative group flex justify-center items-center">
                                        <form class="export-template-btn">
                                            @csrf
                                            <input type="hidden" name="classRecordID"
                                                value="{{ $classRecords->classRecordID }}" />
                                            <input type="hidden" name="assessmentType"
                                                value="{{ $storedAssessmentType }}" />
                                            <button type="submit"
                                                class="text-lg flex gap-2 justify-center items-center text-red-900 rounded-lg">
                                                <i
                                                    class="fa-solid fa-file-export cursor-pointer z-10 text-red-900 text-2xl dark:text-[#CCAA2C] hover:bg-gray-200 p-1 rounded-md"></i>
                                            </button>
                                        </form>
                                        <x-tooltips tooltipTitle="Export {{ ucwords($storedAssessmentType) }} Template" />
                                    </div>
                                    <div class="relative group flex justify-center items-center">
                                        <i id=""
                                            class=" import-assessment-btn fa-solid fa-file-import flex gap-2 justify-center items-center  cursor-pointer z-10 text-red-900 text-2xl dark:text-[#CCAA2C] hover:bg-gray-200 p-1 rounded-md"></i>
                                        <x-tooltips tooltipTitle="Import {{ ucwords($storedAssessmentType) }} Template" />
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div></div>
                    @endif

                    <x-loader modalLoaderId="export-template" titleLoader="Please wait..." />

                    <div
                        class="bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] dark:text-white text-black p-3 rounded-md animate-fadeIn shadow-lg {{ $classRecords->isArchived ? 'mt-3' : 'mt-0' }}">
                        <table id="assessInfoTable" class="display text-center">
                            <thead>
                                <tr>
                                    <th style="text-align: center">
                                        <input type="checkbox" class="rounded-full" name="select_all" value=""
                                            id="assess_select_all">
                                    </th>
                                    <th style="text-align: center">{{ ucwords($storedAssessmentType) }} Title</th>
                                    @if ($storedAssessmentType !== 'attendance')
                                        <th style="text-align: center">{{ ucwords($storedAssessmentType) }} Total Score
                                        </th>
                                        <th style="text-align: center">{{ ucwords($storedAssessmentType) }} Passing
                                            Score
                                        </th>
                                    @endif
                                    <th style="text-align: center">{{ ucwords($storedAssessmentType) }} Date</th>
                                    <th style="text-align: center">Status</th>
                                    <th style="text-align: center">Actions</th>

                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- @foreach ($assessmentInformation as $assessment)
                                    <tr>
                                        <td class="text-md" style="text-align: center">
                                            <input type="checkbox" class="assess_checkbox text-center"
                                                data-assess-id="{{ $assessment->assessmentID }}">
                                        </td>
                                        <td style="text-align: center">{{ $assessment->assessmentName }} </td>

                                        @if ($assessment->assessmentType !== 'Attendance')
                                            <td style="text-align: center">{{ $assessment->totalItem }}</td>
                                            <td style="text-align: center">{{ $assessment->passingItem }}</td>
                                        @endif
                                        <td style="text-align: center">
                                            {{ date('m-d-Y', strtotime($assessment->assessmentDate)) }} </td>
                                        <td class="text-center">
                                            @if ($assessment->isPublished == 1)
                                                <span class="bg-green-500 text-white p-2 rounded-md">Published</span>
                                            @else
                                                <div class="relative group flex justify-center items-center">
                                                    <button class="send-stud-scores cursor-pointer"
                                                        data-assessment-id="{{ $assessment->assessmentID }}">
                                                        <span
                                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md">Unpublished</span>
                                                    </button>
                                                    <div
                                                        class="absolute top-[-53px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                                        <div
                                                            class="flex justify-center items-center text-center transition-all duration-300 relative">
                                                            <span
                                                                class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Publish
                                                                scores</span>
                                                            <div
                                                                class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center text-2xl flex gap-1 justify-center items-center">
                                            <div class="relative group flex justify-center items-center">
                                                <div class="flex justify-center items-center ">
                                                    <form action="/store-assessment-id" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="assessmentID"
                                                            value="{{ $assessment->assessmentID }}">
                                                        <input type="hidden" name="gradingDistributionType"
                                                            value="{{ $gradingDistribution->gradingDistributionType }}">
                                                        <input type="hidden" name="assessmentType"
                                                            value="{{ $storedAssessmentType }}">
                                                        <button type="submit"
                                                            class="text-white hover:bg-gray-200 hover:rounded-md p-1 text-center w-full flex justify-center">
                                                            <i class="fa-solid fa-book text-blue-500"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                <x-tooltips tooltipTitle="View Details" />
                                            </div>
                                            <div class="relative group flex justify-center items-center">
                                                <div class="flex justify-center items-center ">
                                                    <i class="fa-solid fa-pen-to-square text-green-500 edit-assessment hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer"
                                                        data-assessment-id="{{ $assessment->assessmentID }}"
                                                        data-assessment-name="{{ $assessment->assessmentName }}"
                                                        data-assessment-date="{{ date('m-d-Y', strtotime($assessment->assessmentDate)) }}"
                                                        data-total-item="{{ $assessment->totalItem }}"
                                                        data-passing-item="{{ $assessment->passingItem }}"
                                                        data-assessment-type="{{ $storedAssessmentType }}"></i>
                                                </div>
                                                <x-tooltips tooltipTitle="Edit Details" />
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach --}}

        <x-loader modalLoaderId="send-scores-loader" titleLoader="Sending to email" />


        <x-modal title="Add {{ ucwords($storedAssessmentType) }}"
            modalId="{{ 'add-' . strtolower(str_replace(' ', '-', $storedAssessmentType)) . '-modal' }}"
            closeBtnId="{{ 'close-btn-add-' . strtolower(str_replace(' ', '-', $storedAssessmentType)) }}">
            <div class="rounded-lg transform transition-all max-w-screen-sm flex justify-center items-center">
                <div class="flex gap-10 mt-5 w-full">
                    <div class="flex flex-col w-full">
                        <form id="add-assessment-form" action="{{ route('assessment.store-info') }}">
                            @csrf
                            <input type="hidden" name="classRecordID" value="{{ $classRecords->classRecordID }}" />
                            <input type="hidden" name="assessmentType" value="{{ $storedAssessmentType }}" />

                            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4 gap-2 w-full px-3">
                                {{-- <div class="my-2 flex flex-col gap-2">
                                    <label for="{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-name"
                                        class="block font-bold">
                                        {{ ucwords($storedAssessmentType) }} Title:
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="assessmentName"
                                        id="{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-name"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-72 w-full"
                                        placeholder="Enter title" autocomplete="off" required />
                                </div> --}}

                                <div class=" flex flex-col gap-2">
                                    <label for="{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-name"
                                        class="block font-bold">
                                        {{ ucwords($storedAssessmentType) }} Title:
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div>
                                        <input type="text" name="assessmentName" id="assessmentName" pattern="[a-zA-Z0-9\s.,!?]+"
                                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-72 w-full"
                                            placeholder="Enter title" autocomplete="off" maxlength="20" required />
                                        <div class="border-gray-200 text-red-800 dark:text-red-600 text-sm font-bold">
                                            <div class="flex gap-1">
                                                <span id="char-count">20</span><span>characters remaining</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                <div class="my-2 flex flex-col gap-2">
                                    <label for="{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-date"
                                        class="block font-bold">
                                        {{ ucwords($storedAssessmentType) }} Date:
                                        <span class="text-red-500">*</span></label>
                                    <input type="date"
                                        id="{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-date"
                                        name="assessmentDate" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-56 w-full"
                                        autocomplete="off" required />

                                </div>
                            </div>

                            @if ($storedAssessmentType !== 'attendance')
                                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4 gap-2 px-3">
                                    <div class="my-2 flex flex-col gap-2">
                                        <label for="{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-total"
                                            class="block font-bold">
                                            {{ ucwords($storedAssessmentType) }} Total Score:
                                            <span class="text-red-500">*</span></label>
                                        <input type="number" name="totalItem"
                                            id="{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-total"
                                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2  w-full"
                                            placeholder="Enter total score" autocomplete="off" required />
                                    </div>

                                    <div class="my-2 flex flex-col gap-2">
                                        <label
                                            for="{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-passing"
                                            class="block font-bold">
                                            {{ ucwords($storedAssessmentType) }} Passing Percentage:
                                            <span class="text-red-500">*</span></label>
                                        <div class="relative flex">
                                            <input type="number" name="passingItem"
                                                id="{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-passing"
                                                class="appearance-none border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 w-full"
                                                placeholder="Enter passing percentage" autocomplete="off" required />

                                            <div class="flex justify-center items-center">
                                                <i
                                                    class="fas fa-percent text-lg sm:text-2xl md:text-lg text-gray-400 dark:text-black toggle-password cursor-pointer absolute right-[10px]"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="hidden">
                                    <div class="flex gap-5 ">

                                        <div class="my-2 flex flex-col gap-2">
                                            <label for="attendance-total" class="block font-bold">
                                                Attendance Total Item:
                                            </label>
                                            <input type="text" name="totalItem" id="attendance-total" value="1"
                                                readonly
                                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-64 w-full"
                                                autocomplete="off" />
                                        </div>

                                        <!-- Optionally, hide passingItem input for attendance -->
                                        <input type="hidden" name="passingItem" value="0" />
                                    </div>
                                </div>
                            @endif

                            <div class="flex gap-2 justify-center items-center py-6 ">
                                <button type="submit"
                                    class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                                    <span>Add {{ ucwords($storedAssessmentType) }}</span>
                                </button>
                                {{-- <button type="button" id="cancel-button"
                                    class="text-black rounded-lg p-3 shadow-lg border border-gray-300 close-btn dark:text-white">
                                    <span>Cancel</span>
                                </button> --}}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </x-modal>

        <x-modal title="Edit {{ ucwords($storedAssessmentType) }}"
            modalId="{{ 'edit-' . strtolower(str_replace(' ', '-', $storedAssessmentType)) . '-modal' }}"
            closeBtnId="{{ 'close-btn-edit-' . strtolower(str_replace(' ', '-', $storedAssessmentType)) }}">
            <div class="rounded-lg transform transition-all w-full max-w-screen-sm md:px-10 px-3">
                <div class="flex gap-10 mt-5 w-full">
                    <div class="flex flex-col w-full">
                        <form id="edit-assessment-form" action="{{ route('assessment.update-midterms') }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="classRecordID" value="{{ $classRecords->classRecordID }}" />
                            <input type="hidden" name="assessmentID" id="edit-assessment-id" />
                            <input type="hidden" name="assessmentType" value="{{ $storedAssessmentType }}" />

                            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4 gap-2 w-full">
                                <div class="my-2 flex flex-col gap-2">
                                    <label for="edit-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-name"
                                        class="block font-bold">
                                        {{ ucwords($storedAssessmentType) }} Title:
                                    </label>
                                    {{-- <div>
                                        <input type="text" name="assessmentName"
                                            id="edit-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-name"
                                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-64 w-full"
                                            placeholder="Enter title" autocomplete="off" required />
                                        <div class="border-gray-200 text-red-800 text-sm">
                                            <div class="flex gap-1">
                                                <span id="edit-char-count">20</span><span>characters remaining</span>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div>
                                        <input type="text" name="assessmentName"
                                            id="edit-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-name"
                                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-64 w-full"
                                            placeholder="Enter title" autocomplete="off" required />
                                        <div class="border-gray-200 text-red-800 dark:text-red-600 text-sm font-bold">
                                            <div class="flex gap-1">
                                                <span id="edit-char-count">20</span><span> characters remaining</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="my-2 flex flex-col gap-2">
                                    <label for="edit-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-date"
                                        class="block font-bold">
                                        {{ ucwords($storedAssessmentType) }} Date:
                                    </label>
                                    <input type="date" name="assessmentDate"
                                        id="edit-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-date"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-56 w-full"
                                        autocomplete="off" required />
                                </div>
                            </div>
                            @if ($storedAssessmentType !== 'attendance')
                                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4 gap-2 ">
                                    <div class="my-2 flex flex-col gap-2">
                                        <label
                                            for="edit-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-total"
                                            class="block font-bold">
                                            {{ ucwords($storedAssessmentType) }} Total Score:
                                        </label>
                                        <input type="text" name="totalItem"
                                            id="edit-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-total"
                                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-64 w-full"
                                            placeholder="Enter total score" autocomplete="off" required />
                                    </div>

                                    <div class="my-2 flex flex-col gap-2">
                                        <label
                                            for="edit-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-passing"
                                            class="block font-bold">
                                            {{ ucwords($storedAssessmentType) }} Passing Score:
                                        </label>
                                        <input type="text" name="passingItem"
                                            id="edit-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-passing"
                                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-64 w-full"
                                            placeholder="Enter passing score" autocomplete="off" required />
                                    </div>
                                </div>
                            @endif

                            <div class="flex gap-2 justify-center items-center py-6">
                                <button type="submit"
                                    class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                                    <span>Save</span>
                                </button>
                                {{-- <button type="button"
                                    class="text-black rounded-lg p-3 shadow-lg border border-gray-300 close-btn dark:text-white">
                                    <span>Cancel</span>
                                </button> --}}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </x-modal>

        <x-modal title="Duplicate {{ ucwords($storedAssessmentType) }}"
            modalId="{{ 'duplicate-' . strtolower(str_replace(' ', '-', $storedAssessmentType)) . '-modal' }}"
            closeBtnId="{{ 'close-btn-duplicate-' . strtolower(str_replace(' ', '-', $storedAssessmentType)) }}">
            <div class="rounded-lg  transform transition-all w-full max-w-screen-sm md:px-10 px-3">
                <div class="flex gap-10 mt-5 w-full">
                    <div class="flex flex-col w-full">
                        <form id="duplicate-assessment-form" action="{{ route('assessment.duplicate-midterms') }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="classRecordID" value="{{ $classRecords->classRecordID }}" />
                            <input type="hidden" name="assessmentType" value="{{ $storedAssessmentType }}" />
                            <input type="hidden" name="totalItem" value="1" />
                            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4 gap-2 w-full">
                                <div class="my-2 flex flex-col gap-2">

                                    <div class=" flex flex-col gap-2">
                                        <label
                                            for="duplicate-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-name"
                                            class="block font-bold">
                                            {{ ucwords($storedAssessmentType) }} Title:
                                        </label>
                                        <div>
                                            <input type="text" name="assessmentName"
                                                id="duplicate-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-name"
                                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-64 w-full"
                                                placeholder="Enter title" autocomplete="off" required />
                                            <div class="border-gray-200 text-red-800 dark:text-red-600 text-sm font-bold">
                                                <div class="flex gap-1">
                                                    <span id="duplicate-char-count">20</span><span>characters remaining</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="my-2 flex flex-col gap-2">
                                    <label
                                        for="duplicate-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-date"
                                        class="block font-bold">
                                        {{ ucwords($storedAssessmentType) }} Date:
                                    </label>
                                    <input type="date" name="assessmentDate"
                                        id="duplicate-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-date"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-56 w-full"
                                        autocomplete="off" required />
                                </div>
                            </div>
                            @if ($storedAssessmentType !== 'attendance')
                                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4 gap-2 ">
                                    <div class="my-2 flex flex-col gap-2">
                                        <label
                                            for="duplicate-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-total"
                                            class="block font-bold">
                                            {{ ucwords($storedAssessmentType) }} Total Score:
                                        </label>
                                        <input type="text" name="totalItem"
                                            id="duplicate-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-total"
                                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-64 w-full"
                                            placeholder="Enter total score" autocomplete="off" required />
                                    </div>

                                    <div class="my-2 flex flex-col gap-2">
                                        <label
                                            for="duplicate-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-passing"
                                            class="block font-bold">
                                            {{ ucwords($storedAssessmentType) }} Passing Score:
                                        </label>
                                        <input type="text" name="passingItem"
                                            id="duplicate-{{ strtolower(str_replace(' ', '-', $storedAssessmentType)) }}-passing"
                                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-64 w-full"
                                            placeholder="Enter passing score" autocomplete="off" required />
                                    </div>
                                </div>
                            @endif

                            <div class="flex gap-2 justify-center items-center py-6">
                                <button type="submit"
                                    class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                                    <span>Save</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </x-modal>

        <x-modal title="Import Assessment List" modalId="import-assessment-modal"
            closeBtnId="close-btn-import-assessment">
            <div class="rounded-lg shadow-xl transform transition-all  w-full max-w-screen-sm">
                <div class="flex justify-center items-center gap-10 mt-5">
                    <div class="flex flex-col">
                        <form id="import-assessment-form" action="{{ route('import.assessment.template') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="classRecordID" value="{{ $classRecords->classRecordID }}" />
                            <input type="hidden" name="assessmentType" value="{{ $storedAssessmentType }}" />
                            <div class="flex gap-3 justify-center items-center mt-6">
                                <div>
                                    <input type="file" name="file" id="file" accept=".xlsx" required
                                        class="block w-full file:rounded-l-full shadow-lg border-r-2 border-zinc-300 rounded-full file:text-sm file:bg-amber-400 file:text-white rounded-l-lg hover:file:bg-amber-500 file:py-1.5 file:px-3.5 cursor-pointer">
                                </div>
                            </div>
                            <div class="flex justify-center items-center p-5">
                                <button type="submit"
                                    class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                                    <span>Import</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </x-modal>

        {{-- <x-modal title="Import Assessment List" modalId="import-assessment-modal" closeBtnId="close-btn-import-assessment">
            <div class="rounded-lg shadow-xl transform transition-all max-w-screen-sm flex justify-center items-center">
                <div class="flex gap-10 mt-5 w-full">
                    <div class="flex flex-col w-full">
                        <form id="import-assessment-form" action="{{ route('import.assessment.template') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="classRecordID" value="{{ $classRecords->classRecordID }}" />
                            <input type="hidden" name="assessmentType" value="{{ $storedAssessmentType }}" />
                            <div class="flex gap-3 justify-center items-center mt-6">
                                <div>
                                    <input type="file" name="file" id="file" accept=".xlsx" required
                                        class="block w-full file:rounded-l-full shadow-lg border-r-2 border-zinc-300 rounded-full file:text-sm file:bg-amber-400 file:text-white rounded-l-lg hover:file:bg-amber-500 file:py-1.5 file:px-3.5 cursor-pointer">
                                </div>
                            </div>
                            <div class="flex justify-center items-center p-5">
                                <button type="submit"
                                    class="text-black rounded-lg md:p-3 p-2 shadow-lg border border-gray-300 dark:text-white">
                                    <span>Import</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </x-modal> --}}
    @endsection


</body>

</html>
