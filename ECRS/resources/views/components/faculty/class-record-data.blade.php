<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <title>Document</title> --}}
    @vite('resources/js/app.js')
    @vite('resources/js/classrecord.js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
</head>

<body>
    <div class="flex justify-center w-full pt-14 md:pt-14">
        <div class="bg-red-900 dark:bg-[#CCAA2C] flex gap-2 rounded-l-xl p-2 md:px-5 px-2">
            <span
                class="text-white dark:text-[#161616] flex justify-center items-center md:text-xl text-md w-10 md:w-[100px] text-center"
                id="filter-label">All</span>
            <div class="relative">
                <div class="flex justify-center items-center" id="program-dropdown-toggle">
                    <i
                        class="fa-solid fa-circle-chevron-down text-white dark:text-[#161616] hover:text-gray-900 rounded-lg cursor-pointer text-2xl"></i>
                </div>
                <ul id="filter-dropdown" class="absolute hidden bg-white text-red-900 rounded-lg shadow-lg mt-2 right-0 z-50">
                    <li class="cursor-pointer p-2 hover:bg-red-900 hover:text-white" data-filter="All">All</li>
                    <li class="cursor-pointer p-2 hover:bg-red-900 hover:text-white" data-filter="Program">Program</li>
                    <li class="cursor-pointer p-2 hover:bg-red-900 hover:text-white" data-filter="Course">Course</li>
                </ul>
                
            </div>
        </div>

        <div class="w-full">
            <input id="search-input" type="text"
                class="h-full bg-gray-200 w-full px-4 dark:bg-[#8F8F8F] dark:placeholder:text-white"
                placeholder="Search Program or Course">
        </div>

        <div class="bg-red-900 dark:bg-[#CCAA2C] rounded-r-xl p-2 flex justify-center items-center md:px-5 px-3">
            <i
                class="fa-solid fa-magnifying-glass dark:text-[#161616] text-white hover:text-gray-900 rounded-lg cursor-pointer md:text-2xl text-lg"></i>
        </div>
    </div>
    @if ($classRecords->isEmpty())
        <div class=" flex justify-center items-center h-[60vh]">
            <div class="text-center flex flex-col gap-3">
                <p class="text-2xl text-neutral-500 dark:dark:text-white">No class records yet</p>
                <a href="create-class-record"
                    class="text-gray-400 text-xl cursor-pointer hover:text-red-900 dark:hover:text-[#CCAA2C] hover:underline">Create
                    one?</a>
            </div>
        </div>
    @else
        <div class="flex gap-8 mt-5 md:mb-0 mb-5 md:justify-between lg:flex-row flex-col">
            <div class="flex gap-4 md:flex-row flex-col">
                <div class="flex items-center gap-2">
                    <p class="whitespace-nowrap text-lg text-black dark:text-white font-bold">Academic Year:</p>
                    {{-- <select name="academicYear" id="academic-year"
                        class="px-2 py-1 rounded shadow-lg dark:bg-[#404040] dark:text-white dark:border-[#B7B4B4] border">
                       
                    </select> --}}
                    <select name="academicYear" id="academic-year"
                        class="dark:bg-[#404040] dark:text-white dark:border-[#B7B4B4] shadow-md w-full px-4 py-2 font-medium text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-900 dark:focus:ring-[#CCAA2C] focus:ring-offset-2">
                        <option value="">All Years</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <p class="whitespace-nowrap text-lg text-black dark:text-white font-bold">Semester:</p>
                    {{-- <select name="academicYear" id="semester"
                        class="px-2 py-1 border rounded shadow-lg bg-white text-gray-900 dark:bg-[#404040] dark:text-white">
                       
                    </select> --}}

                    <select name="academicYear" id="semester"
                        class="dark:bg-[#404040] dark:text-white dark:border-[#B7B4B4] shadow-md w-full px-4 py-2 font-medium text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-900 dark:focus:ring-[#CCAA2C] focus:ring-offset-2">
                        <option value="">All Semesters</option>
                        <option value="1">First Semester</option>
                        <option value="2">Second Semester</option>
                        <option value="3">Summer Semester</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-5 lg:flex-row flex-col-reverse">
                <div class="flex justify-end items-end table-view">
                    <a href="create-class-record">
                        <div
                            class="text-red-900 dark:text-white shadow-md p-2 flex gap-2  bg-white border border-gray-400 dark:bg-[#161616] rounded-xl justify-center items-center  hover:bg-neutral-200 dark:hover:bg-[#100f0f] cursor-pointer">
                            <i class="fa-solid fa-circle-plus "></i>
                            <span class="text-md font-bold">New class record</span>
                        </div>
                    </a>
                </div>

                <div class="flex gap-2 justify-end">
                    <div class="flex justify-center items-center text-black dark:text-white font-bold">
                        View:
                    </div>
                    <div class="relative group flex justify-center items-center cursor-pointer">
                        <div id="grid-view-btn"
                            class="flex justify-center items-center text-red-900 rounded-lg text-3xl">
                            <i class="fa-solid fa-grip hover:bg-gray-200 p-1 rounded-md"></i>
                        </div>
                        <x-tooltips tooltipTitle="Grid View" />
                    </div>

                    <div class="relative group flex justify-center items-center cursor-pointer">
                        <div id="table-view-btn"
                            class="flex justify-center items-center text-red-900 rounded-lg text-3xl">
                            <i class="fa-solid fa-table-list hover:bg-gray-200 p-1 rounded-md"></i>
                        </div>
                        <x-tooltips tooltipTitle="List View" />
                    </div>

                    {{-- <div style="" class="relative group flex justify-center items-center cursor-pointer">
                        <div id="sendJson"
                            class="flex justify-center items-center text-red-900 rounded-lg text-3xl">
                            <i class="fa-solid fa-paper-plane"></i>
                        </div>
                        <x-tooltips tooltipTitle="JSON" />
                    </div> --}}
                </div>
            </div>

        </div>
        <div class="flex justify-end items-end grid-view-btn">
            <div class="inline sm:hidden ">
                <a href="create-class-record">
                    <div
                        class="text-red-900 dark:text-white shadow-md p-2 flex gap-2  bg-white border border-gray-400 dark:bg-[#161616] rounded-xl justify-center items-center  hover:bg-neutral-200 dark:hover:bg-[#100f0f] cursor-pointer">
                        <i class="fa-solid fa-circle-plus "></i>
                        <span class="text-md font-bold">New class record</span>
                    </div>
                </a>
            </div>
        </div>
        <div id="grid-view" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3  gap-5 my-4 mb-10">
            <div class=" hidden sm:inline" id="new-class-record-container">
                <a href="create-class-record" id="new-class-record">
                    <div
                        class="flex flex-col h-full bg-neutral-300 dark:bg-[#161616] rounded-xl justify-center items-center gap-5 hover:bg-neutral-200 dark:hover:bg-[#100f0f] cursor-pointer">
                        <i class="fa-solid fa-circle-plus text-5xl text-neutral-500"></i>
                        <span class="text-xl text-neutral-500">New class record</span>
                    </div>
                </a>
            </div>
            @foreach ($classRecords as $record)
                <div
                    class=" w-full relative  transition-transform transform hover:scale-105 duration-300 ease-in-out group">
                    @if (is_null($record->recordType))
                        <div class="flex absolute w-full pr-2 pt-3">
                            <div
                                class="flex {{ is_null($record->recordType) ? 'justify-between' : 'justify-end' }}  w-full">
                                <div class="flex">
                                    <div class="relative flex w-full justify-center items-center">
                                        <div class="bg-[#CCAA2C] text-white font-bold py-1 px-8 rounded-r-full">
                                            New
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex absolute w-full pr-2 pt-3">
                            <div
                                class="flex {{ is_null($record->recordType) ? 'justify-between' : 'justify-end' }}  w-full">
                                <div class="flex justify-end">
                                    <button
                                        class="flex  modify-btn text-lg text-gray-800 gap-2 rounded-lg  font-bold justify-center items-center ">
                                        <i
                                            class="fa-solid fa-ellipsis text-3xl text-white hover:bg-red-800 p-1 rounded-md px-3"></i>
                                    </button>

                                    <div class="modify-record absolute hidden bg-stone-500 items-center rounded-md shadow-lg mt-8 mr-3 z-40 text-center w-24 md:w-1/3"
                                        class="flex flex-col gap-5">
                                        <div>
                                            <button
                                                class="text-white hover:bg-stone-600 hover:rounded-t-md p-2 text-center w-full edit-btn"
                                                data-class-record-id="{{ $record->classRecordID }}">Edit
                                            </button>
                                        </div>
                                        <div>
                                            <button
                                                class="text-white hover:bg-stone-600 hover:rounded-b-md p-2 text-center w-full flex justify-center archive-btn"
                                                data-class-record-id="{{ $record->classRecordID }}"
                                                data-course-title="{{ $record->course->courseTitle }}"
                                                data-program-title="{{ $record->course->program->programCode }}
                                    {{ $record->yearLevel }}">Archive
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="record-item" data-title="{{ $record->course->courseTitle }}"
                        data-code="{{ $record->course->courseCode }}"
                        data-program-title="{{ $record->course->program->programTitle }}"
                        data-program-code="{{ $record->course->program->programCode }}"
                        data-school-year="{{ $record->schoolYear }}" data-semester="{{ $record->semester }}"
                        data-record-type="{{ $record->recordType }}">
                        <form class="rounded-xl flex cursor-pointer flex-col " action="/store-class-record-id"
                            method="POST">
                            @csrf
                            <input type="hidden" name="classRecordID" value="{{ $record->classRecordID }}">
                            <button type="submit">
                                <div class="h-48 rounded-t-xl bg-cover bg-center flex items-center justify-center shadow-xl {{ $record->classImg ? '' : 'bg-red-900' }} "
                                    style="{{ $record->classImg ? 'background-image: url(\'' . url($record->classImg) . '\');' : '' }}">
                                    @if (!$record->classImg)
                                        <img src="{{ asset('images/logo-bg.png') }}" alt="Placeholder"
                                            class="h-40">
                                    @endif

                                </div>
                                <div
                                    class="shadow-xl p-5 flex flex-col gap-2 rounded-b-xl dark:bg-[#1E1E1E] dark:border-[#161616] border text-left">
                                    <p id="fontSize"
                                        class="truncate font-bold text-ellipsis text-2xl text-red-900 dark:text-[#CCAA2C] group-hover:underline">
                                        {{ $record->course->courseTitle }}
                                    </p>
                                    <div class="text-black dark:text-white flex flex-col gap-2">
                                        <span class="font">{{ $record->course->courseCode }}</span>
                                        <p><strong>Program: {{ $record->course->program->programCode }}
                                                {{ $record->yearLevel }}
                                                ({{ $record->branchDetail->branchDescription ?? 'No Branch Info' }})
                                            </strong></p>
                                        <p><strong>Semester:</strong>
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
                                        <p><strong>School Year:</strong> {{ $record->schoolYear }}</p>
                                    </div>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

        </div>
        <div id="no-record-message" class="text-center text-red-600 hidden font-bold text-2xl">
            No class record found
        </div>
        <div class="table-view hidden mb-10 mt-4">
            <div class="md:overflow-visible overflow-auto relative w-full ">
                <table class="w-full lg:min-w-[700px]  relative">
                    <thead class="bg-red-900 text-white">
                        <tr class="border-b-4">
                            <th class="rounded-tl-lg py-2 px-2 w-10 md:w-0 text-center">Course</th>
                            <th class="w-10 md:w-0 px-3 text-center">Course Code</th>
                            <th class="w-10 md:w-0 px-3 text-center">Program Code</th>
                            <th class="w-10 md:w-0 px-3 text-center">Semester</th>
                            <th class="w-10 md:w-0 px-3 text-center">School Year</th>
                            <th class="rounded-tr-lg w-10 md:w-0 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="relative" data-record-type="{{ $record->recordType }}">
                        @foreach ($classRecords as $record)
                            <tr class="record-item text-black dark:text-white border-b border-gray-300 hover:bg-[#d9efff] dark:hover:bg-[#036bb6]" data-title="{{ $record->course->courseTitle }}" data-code="{{ $record->course->courseCode }}" data-program-title="{{ $record->course->program->programTitle }}" data-program-code="{{ $record->course->program->programCode }}" data-school-year="{{ $record->schoolYear }}" data-semester="{{ $record->semester }}" data-record-type="{{ $record->recordType }}">
                                <td class="text-center p-2">
                                    <form action="/store-class-record-id" method="POST">
                                        @csrf
                                        <input type="hidden" name="classRecordID"
                                            value="{{ $record->classRecordID }}">
                                        <button type="submit" class="cursor-pointer hover:underline font-bold">
                                            {{ $record->course->courseTitle }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center px-2">{{ $record->course->courseCode }}</td>
                                <td class="text-center px-2">
                                    <strong>{{ $record->course->program->programCode }} {{ $record->yearLevel }}
                                        ({{ $record->branchDetail->branchDescription ?? 'No Branch Info' }})
                                    </strong>
                                </td>
                                <td class="text-center px-2">
                                    @if ($record->semester == 1)
                                        1st Semester
                                    @elseif($record->semester == 2)
                                        2nd Semester
                                    @elseif($record->semester == 3)
                                        Summer Semester
                                    @else
                                        Unknown Semester
                                    @endif
                                </td>
                                <td class="text-center px-2">{{ $record->schoolYear }}</td>
                                <td class="text-center text-2xl">
                                    <div class="flex justify-center items-center gap-1">
                                        @if (is_null($record->recordType))
                                            <div class="relative group flex justify-center items-center">
                                                <div class="flex justify-center items-center">
                                                    <i class="fa-solid fa-pen-to-square text-green-500 edit-button hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer edit-btn"
                                                        data-class-record-id="{{ $record->classRecordID }}"></i>
                                                </div>
                                                <x-tooltips tooltipTitle="Edit Info" />
                                            </div>
                                        @else
                                            <div class="relative group flex justify-center items-center">
                                                <div class="flex justify-center items-center">
                                                    <form action="/store-class-record-id" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="classRecordID"
                                                            value="{{ $record->classRecordID }}">
                                                        <button type="submit" class="cursor-pointer">
                                                            <i
                                                                class="fa-solid fa-book text-blue-500 hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                <x-tooltips tooltipTitle="View Info" />
                                            </div>
                                            <div class="relative group flex justify-center items-center">
                                                <div class="flex justify-center items-center">
                                                    <i class="fa-solid fa-pen-to-square text-green-500 edit-button hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer edit-btn"
                                                        data-class-record-id="{{ $record->classRecordID }}"></i>
                                                </div>
                                                <x-tooltips tooltipTitle="Edit Info" />
                                            </div>

                                            <div class="relative group flex justify-center items-center">
                                                <div class="flex justify-center items-center">
                                                    <i class="fa-solid  fa-box-archive text-yellow-500 edit-button hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer archive-btn"
                                                        data-class-record-id="{{ $record->classRecordID }}"
                                                        data-course-title="{{ $record->course->courseTitle }}"
                                                        data-program-title="{{ $record->course->program->programCode }}
                                                {{ $record->yearLevel }}"></i>
                                                </div>
                                                <x-tooltips tooltipTitle="Archive Class Record" />
                                            </div>
                                        @endif


                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


    @endif
</body>

</html>
