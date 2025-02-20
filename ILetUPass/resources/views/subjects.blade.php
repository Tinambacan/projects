@php
    $loginID = session('login_ID');
    $roleNum = session('role');
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
    <title>Subjects</title>
</head>

<style>
    .carousel-button {
        display: none;
    }

    @media (max-width: 1000px) {
        .subject-carousel-item {
            flex: 0 0 50%;
        }
    }

    @media (max-width: 600px) {

        .carousel-container {
            position: relative;
            overflow: hidden;
        }

        .carousel-wrapper {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .carousel-item {
            flex: 0 0 100%;
            padding: 1rem;
        }

        .carousel-button {
            display: block;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            padding: 1rem;
            background-color: transparent;
            border: none;
            cursor: pointer;
            font-size: 1.5rem;
        }

        .prev-button {
            left: 0;
            display: block;
        }

        .next-button {
            right: 0;
            display: block;
        }

    }
</style>

<body>

    {{-- If Student --}}
    @if ($roleNum == 1)
        <div class="flex items-center justify-center">
            <div class="animate-fade-in-down">
                <div class="flex">
                    <img class="mx-auto animate-fade-in-up" src="{{ URL('images/LogoPNG.png') }}"
                        style="height: 8rem; width: 8rem;">
                </div>
            </div>
        </div>

        <div class="carousel-container overflow-y-auto max-h-[80vh]">
            @if (count($subjects) > 0)
                <div class="carousel-wrapper flex flex-nowrap md:flex-wrap p-5">
                    @foreach ($subjects as $subject)
                        <div class="carousel-item w-1/4 p-5 subject-carousel-item">
                            <div id="genEd"
                                class="animate-no-fade-down hover:bg-orange-300 p-3 rounded-md cursor-pointer hover:animate-no-fade-up mb-5"
                                onclick="setSubject(this)" data-subject-id="{{ $subject->subject_ID }}">

                                <div class="p-6 bg-orange-500 rounded-md shadow-md">
                                    <div class="flex">
                                        <img class="mx-auto animate-fade-in-up"
                                            src="{{ asset('images/' . $subject->subject_image) }}"
                                            style="height: 10rem; width: 10rem;">
                                    </div>
                                </div>
                                <h1 class="flex justify-center p-4 text-3xl font-bold text-blue-900">
                                    {{ $subject->subject_name }}
                                </h1>
                                <div class="text-blue-900 text-lg flex text-center">
                                    {{ $subject->subject_desc }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div
                    class="rounded-md mb-3 px-auto py-auto text-neutral-500 text-3xl  overflow-hidden font-bold overscroll-none flex justify-center items-center pt-44">
                    <p>No Subjects found!</p>
                </div>
            @endif
            <i class=" carousel-button prev-button fa-solid fa-circle-arrow-left text-2xl text-orange-500  hidden"></i>
            <i class="carousel-button next-button fa-solid fa-circle-arrow-right text-2xl  text-orange-500 hidden"></i>
        </div>

        {{-- If Prof --}}
    @elseif($roleNum == 2)
        <div class="ml-10 pt-5 w-full animate-fade-in-up overflow-y-auto max-h-[96vh]">
            <div class="mr-6 mb-5 ">
                <h2 class=" ml-4 font-bold text-4xl  pt-10 text-indigo-800 text-shadow-[0_4px_5px_#808080]">
                    Dashboard
                </h2>
            </div>

            <div class="flex justify-center items-center space-x-24">
                <div class="bg-white rounded-r-xl rounded-l-xl shadow-2xl  items-center relative">
                    <canvas id="myPieChart"></canvas>
                    <div class="text-neutral-300  flex  justify-center text-2xl  font-bold items-center">
                        <p id="SubjectTakenPie"></p>
                    </div>

                </div>
                <div class="bg-white rounded-r-xl rounded-l-xl shadow-2xl w-1/3 p-5">
                    <div class=" flex justify-end">
                        <select name="mySelect" id="mySelect" class="p-2 rounded-xl bg-orange-600 text-white">
                            <option value="Highest" selected>Highest</option>
                            <option value="Lowest">Lowest</option>
                        </select>

                    </div>
                    <canvas id="myHighestBarChart"></canvas>
                    <canvas id="myLowestBarChart"></canvas>

                    <div class="text-neutral-300  flex  justify-center   max-[800px]:pb-32 text-2xl  font-bold">
                        <p id="SubjectTakeBar"></p>
                    </div>
                </div>
            </div>

            <div class="mr-6 mb-5 ">
                <h2 class=" ml-4 font-bold text-4xl  pt-10 text-indigo-800 text-shadow-[0_4px_5px_#808080]">
                    Leading Scores of the Week
                </h2>
            </div>

            <div class="flex ml-4">
                <div class="flex flex-col space-y-2">
                    <div>
                        <i class="fa-solid fa-circle text-green-500"></i> <span class="text-lg font-bold">Total
                            Score</span>
                    </div>
                    <div>
                        <i class="fa-solid fa-circle text-blue-500"></i> <span class="text-lg font-bold">Total
                            Attempt</span>
                    </div>
                    <div>
                        <i class="fa-solid fa-circle text-red-500"></i> <span class="text-lg font-bold">No. Subject
                            Played</span>
                    </div>
                </div>

            </div>

            <div class="flex justify-center items-center">
                <div class="flex space-x-14 pb-10">

                    <div class=" h-48 w-28 mt-48 flex items-center justify-center z-20 relative"
                        style="background: #F08F59">
                        @if ($thirdPlace)
                            <div class="flex flex-col z-40 absolute gap-2 mb-72 mt-5">
                                <div class="text-sm text-gray-800 text-center font-bold">{{ $thirdPlace->full_name }}
                                </div>
                                <img class="mx-auto animate-fade-in-up rounded-full  border-4 border-amber-800"
                                    src="{{ asset('avatars/' . $thirdPlace->profile_photo_path) }}"
                                    style="height: 7rem; width: 7rem; background:#F8D086;">
                            </div>
                            <div class="flex flex-col items-center justify-center ">
                                <div class="text-xl text-blue-500  text-shadow-[0_4px_5px_#808080]">
                                    {{ $thirdPlace->total_attempts }}
                                </div>
                                <div class="text-xl text-red-500  text-shadow-[0_4px_5px_#808080]">
                                    {{ $thirdPlace->subjects_played }}
                                </div>
                                <div class="text-4xl text-green-500  text-shadow-[0_4px_5px_#808080]">
                                    {{ $thirdPlace->total_score }}
                                </div>
                            </div>
                            <div class=" z-40 absolute p-2 mt-48 w-32 flex justify-center rounded-full text-white shadow-md  text-shadow-[0_4px_5px_#808080]"
                                style="background: #C05417">
                                3rd
                            </div>
                        @else
                            <p class="text-center text-amber-900">No 3rd place student record</p>
                        @endif
                    </div>
                    <div class=" h-96 w-28 z-20 flex items-center justify-center relative" style="background: #F8D086">
                        @if ($firstPlace)
                            <div class="flex flex-col z-40 absolute -mt-10 mb-96 gap-2">
                                <div class="text-sm text-gray-800 text-center font-bold">{{ $firstPlace->full_name }}
                                </div>
                                <img class="mx-auto animate-fade-in-up rounded-full  border-4 border-yellow-400"
                                    src="{{ asset('avatars/' . $firstPlace->profile_photo_path) }}"
                                    style="height: 7rem; width: 7rem; background:#F8D086;">
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <div class="text-xl text-blue-500  text-shadow-[0_4px_5px_#808080]">
                                    {{ $firstPlace->total_attempts }}
                                </div>
                                <div class="text-xl text-red-500  text-shadow-[0_4px_5px_#808080]">
                                    {{ $firstPlace->subjects_played }}
                                </div>
                                <div class="text-4xl text-green-500  text-shadow-[0_4px_5px_#808080]">
                                    {{ $firstPlace->total_score }}
                                </div>
                            </div>
                            <div class=" z-40 absolute p-2 mt-96 w-32 flex justify-center rounded-full text-white shadow-md  text-shadow-[0_4px_5px_#808080]"
                                style="background: #FCC125">
                                1st
                            </div>
                        @else
                            <p class="text-center text-yellow-600">No 1st place student record</p>
                        @endif
                    </div>
                    <div class=" h-80 w-28 mt-16 z-20 flex items-center justify-center relative"
                        style="background: #D2D2D2">
                        @if ($secondPlace)
                            <div class="flex flex-col z-40 absolute -mt-10 mb-80 gap-2">
                                <div class="text-sm text-gray-800 text-center font-bold">{{ $secondPlace->full_name }}
                                </div>
                                <img class="mx-auto animate-fade-in-up rounded-full  border-4 border-zinc-400"
                                    src="{{ asset('avatars/' . $secondPlace->profile_photo_path) }}"
                                    style="height: 7rem; width: 7rem; background:#F8D086;">
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <div class="text-2xl text-blue-500  text-shadow-[0_4px_5px_#808080]">
                                    {{ $secondPlace->total_attempts }}
                                </div>
                                <div class="text-2xl text-red-500  text-shadow-[0_4px_5px_#808080]">
                                    {{ $secondPlace->subjects_played }}
                                </div>
                                <div class="text-4xl text-green-500  text-shadow-[0_4px_5px_#808080]">
                                    {{ $secondPlace->total_score }}
                                </div>
                            </div>
                            <div class=" z-40 absolute p-2 mt-80 w-32 flex justify-center rounded-full text-white shadow-md  text-shadow-[0_4px_5px_#808080]"
                                style="background: #A7A7A7 ">
                                2nd
                            </div>
                        @else
                            <p class="text-center text-gray-400">No 2nd place student record</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- If Admin --}}
    @elseif($roleNum == 3)
        <div class="flex items-center justify-center">
            <div class="animate-fade-in-down">
                <div class="flex">
                    <img class="mx-auto animate-fade-in-up" src="{{ URL('images/LogoPNG.png') }}"
                        style="height: 8rem; width: 8rem;">
                </div>
            </div>
        </div>
        <div class="flex items-center justify-center py-5  hover:text-white">
            <div class="animate-fade-in-down hover:text-white ">
                <button id="add-sub"
                    class="group flex text-lg text-gray-800 gap-2 rounded-lg hover:bg-orange-500 p-3 bg-white shadow-md font-bold hover:text-white">
                    <i class="fa-solid fa-plus text-xl my-auto group-hover:text-white text-orange-500"></i>
                    <span> Create new subject</span>
                </button>
            </div>
        </div>
        <div class="overflow-y-auto max-h-[68vh]">
            @if (count($subjects) > 0)
                <div class="flex flex-nowrap md:flex-wrap p-5">
                    @foreach ($subjects as $subject)
                        <div class="w-1/4 p-5 relative">
                            <div
                                class="animate-no-fade-down hover:bg-orange-300 p-3 rounded-md cursor-pointer hover:animate-no-fade-up mb-5">
                                <div class="flex justify-end bg-orange-500 rounded-t-md">
                                    <button
                                        class="modify-btn flex text-lg text-gray-800 gap-2 rounded-lg px-5 font-bold">
                                        <i class="fa-solid fa-ellipsis text-3xl text-white"></i>
                                    </button>
                                    <div
                                        class="modify-sub hidden bg-yellow-200 items-center rounded-md shadow-lg mt-8 mr-3 z-40 absolute font-bold text-center">
                                        <button id="edit-sub-{{ $subject->subject_ID }}"
                                            class="block text-gray-800 hover:bg-gray-300 p-2 text-center w-full rounded-md"
                                            data-subject-id="{{ $subject->subject_ID }}"
                                            data-subject-name="{{ $subject->subject_name }}"
                                            data-subject-desc="{{ $subject->subject_desc }}"
                                            data-subject-image="{{ asset('images/' . $subject->subject_image) }}"
                                            data-subject-path="{{ $subject->subject_image }}">
                                            Edit
                                        </button>
                                        <button id="delete-sub-{{ $subject->subject_ID }}"
                                            class="block text-gray-800 rounded-md hover-bg-gray-300 text-center p-2 w-full hover:bg-gray-300"
                                            delete-subject-id="{{ $subject->subject_ID }}"
                                            delete-subject-name="{{ $subject->subject_name }}">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                <div id="genEd" class="" onclick="setSubjectAdmin(this)"
                                    data-subject-id="{{ $subject->subject_ID }}">
                                    <div class="px-5 pb-5  bg-orange-500 rounded-b-md shadow-md">
                                        <div class="flex">
                                            <img class="mx-auto animate-fade-in-up"
                                                src="{{ asset('images/' . $subject->subject_image) }}"
                                                style="height: 10rem; width: 10rem;">
                                        </div>
                                    </div>
                                    <h1 class="flex justify-center p-4 text-3xl font-bold text-blue-900">
                                        {{ $subject->subject_name }}
                                    </h1>
                                    <div class="text-blue-900 text-lg flex text-center">
                                        {{ $subject->subject_desc }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div
                    class="rounded-md mb-3 px-auto py-auto text-neutral-500 text-3xl  overflow-hidden font-bold overscroll-none flex justify-center items-center pt-44">
                    <p>No Subjects found!</p>
                </div>
            @endif
        </div>


        {{-- Add Subject --}}
        <div class="modal-add-sub hidden fixed inset-0 overflow-y-auto z-40">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0  ">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="z-40 absolute inset-0 bg-black opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
                <div
                    class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                    <div class="p-2">
                        <div class="flex justify-between">
                            <div class="flex gap-1">
                                <img class="" src="{{ URL('images/Subject.png') }}"
                                    style="height: 3rem; width: 3rem;">
                                <h1
                                    class="text-white text-4xl text-center  font-bold text-shadow-[0_4px_5px_#808080]  mt-1">
                                    Add Subject
                                </h1>
                            </div>
                            <i id="close-add"
                                class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl">
                            </i>
                        </div>
                    </div>
                    <div class="mx-5 mb-5">

                        <form id="subject-form" action="save-subject" method="POST">
                            @csrf
                            <div class="my-2">
                                <label for="subject_name" class="block  font-bold text-indigo-900">Subject
                                    Name:</label>
                                <input type="text" name="subject_name"
                                    class=" border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5  w-full dark:focus:ring-blue-500 dark:focus:border-blue-500 shadow-md"
                                    placeholder="Subject Name" autocomplete="off" required />
                                <span1 class="text-danger text-red-600">
                                    @error('subject_name')
                                        {{ $message }}
                                    @enderror
                                </span1>
                            </div>
                            <div class="my-2">
                                <label for="subject_desc" class="block  font-bold text-indigo-900">Subject
                                    Description</label>
                                <textarea type="text" name="subject_desc"
                                    class=" border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5  w-full dark:focus:ring-blue-500 dark:focus:border-blue-500 shadow-md"
                                    placeholder="Subject Description" autocomplete="off"> </textarea>
                                <span class="text-danger text-red-600">
                                    @error('subject_desc')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <div class="my-2">
                                <label for="ls_nm" class="block  font-bold text-indigo-900">Subject Image
                                </label>
                                <div class="mb-2 flex justify-center">

                                    <input type="file" name="file" id="file"
                                        class="block w-full bg-white border file:rounded-l-lg border-gray-300 file:text-sm file:bg-orange-500 file:text-white rounded-lg hover:file:bg-orange-700 file:py-2 file:px-3.5 cursor-pointer shadow-md"
                                        required>


                                </div>
                            </div>
                            <div class="mt-2 py-2 flex justify-end gap-2">
                                <button type="button" id="cancel-add"
                                    class="ml-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Add
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Subject --}}
        <div class="modal-edit-sub hidden fixed inset-0 overflow-y-auto z-40">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0  ">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="z-40 absolute inset-0 bg-black opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
                <div
                    class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                    <div class="p-2">
                        <div class="flex justify-between">
                            <div class="flex gap-1">
                                <img class="" src="{{ URL('images/Subject.png') }}"
                                    style="height: 3rem; width: 3rem;">
                                <h1
                                    class="text-white text-4xl text-center  font-bold text-shadow-[0_4px_5px_#808080]  mt-1">
                                    Edit Subject
                                </h1>
                            </div>
                            <i id="close-edit"
                                class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl">
                            </i>
                        </div>
                    </div>
                    <div class="mx-5 mb-5">
                        <form method="POST" action="" id="subject-update">
                            @csrf
                            <input type="hidden" name="subject_ID" id="subject_ID">
                            <div class="my-2">
                                <label for="subject_name" class="block  font-bold text-indigo-900">Subject
                                    Name:</label>
                                <input type="text" name="subject_name" id="subject_name"
                                    class=" border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5  w-full dark:focus:ring-blue-500 dark:focus:border-blue-500 shadow-md"
                                    placeholder="Subject Name" autocomplete="off" required />
                                <span1 class="text-danger text-red-600">
                                    @error('subject_name')
                                        {{ $message }}
                                    @enderror
                                </span1>
                            </div>
                            <div class="my-2">
                                <label for="subject_desc" class="block  font-bold text-indigo-900">Subject
                                    Description</label>
                                <textarea type="text" name="subject_desc" id="subject_desc"
                                    class=" border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5  w-full dark:focus:ring-blue-500 dark:focus:border-blue-500 shadow-md"
                                    placeholder="Subject Description" autocomplete="off"> </textarea>
                                <span class="text-danger text-red-600">
                                    @error('subject_desc')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <div class="my-2">
                                <label for="ls_nm" class="block  font-bold text-indigo-900">Previews Image
                                </label>
                                <div class="flex justify-center items-center">
                                    <img id="subject_image_preview" src="" alt="Image Preview"
                                        class="max-w-48 max-h-40 hidden" />
                                    <!-- Adjust max-w-48 (max width) as needed -->
                                </div>

                                <input type="text" name="subject_image" id="subject_image"
                                    class="w-full p-2.5 text-center bg-transparent" readonly>

                                <label for="ls_nm" class="block  font-bold text-indigo-900">Update Image
                                    (optional)
                                </label>
                                <input type="file" name="file" id="file"
                                    class="block w-full bg-white border file:rounded-l-lg border-gray-300 file:text-sm file:bg-orange-500 file:text-white rounded-lg hover:file:bg-orange-700 file:py-2 file:px-3.5 cursor-pointer">

                            </div>
                            <div class="mt-2 py-2 flex justify-end gap-2">
                                <button type="button" id="cancel-edit"
                                    class="ml-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Delete Subject --}}
        <div class="modal-delete-sub hidden fixed inset-0 overflow-y-auto z-40">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0  ">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="z-40 absolute inset-0 bg-black opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
                <div
                    class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                    <div class="p-2">
                        <div class="flex justify-between">
                            <div class="flex gap-1">
                                <img class="" src="{{ URL('images/Subject.png') }}"
                                    style="height: 3rem; width: 3rem;">
                                <h1
                                    class="text-white text-4xl text-center  font-bold text-shadow-[0_4px_5px_#808080]  mt-1">
                                    Delete Subject
                                </h1>
                            </div>
                            <i id="close-delete"
                                class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl">
                            </i>
                        </div>
                    </div>
                    <div class="mx-5 mb-5">
                        <form method="POST" id="subject-delete" action="" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="subject_id" id="subject_id">
                            <div class="my-2">

                                <input type="text" name="delete_subject_name" id="delete_subject_name"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500 shadow-md"
                                    placeholder="Subject Description" autocomplete="off" readonly>
                                <span class="text-danger text-red-600">
                                    @error('delete_subject_name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <div class="mt-2 py-2 flex justify-end gap-2">
                                <button type="button" id="cancel-delete"
                                    class="ml-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Delete
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @endif
    {{-- Empty Modal --}}
    <div class="modal-empty hidden fixed z-10 inset-0 overflow-y-auto" id="modal-empty">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-300 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="mx-5 mb-5 flex justify-center flex-col items-center">
                    <img class=" w-20 my-10 " src="{{ URL('images/Error.png') }}">
                    <h1 class="text-white text-4xl text-center mb-10 font-bold text-shadow-[0_4px_5px_#808080]">
                        This subject is empty</h1>
                    <button id="close-empty" class="p-3 rounded bg-purple-500 text-white">Okay</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
<script>
    function successCRUD(message) {
        Swal.fire({
            icon: 'success',
            title: "<h5 style='color:black'>" + message + "</h5>",
            showConfirmButton: false,
            timer: 1500
        });
    }
    var loginID = @json($loginID); // Pass the PHP value to JavaScript
    console.log('Login ID:', loginID);

    var roleNum = @json($roleNum); // Pass the PHP value to JavaScript
    console.log('Role:', roleNum);

    if (roleNum === 1) {
        $(document).ready(function() {
            const prevButton = $(".prev-button");
            const nextButton = $(".next-button");
            const carouselWrapper = $("#carousel-wrapper");

            let currentSubjectIndex = 0;
            const totalSubjects = {{ count($subjects) }};

            function updateButtonVisibility() {
                if (currentSubjectIndex === 0) {
                    prevButton.hide();
                } else {
                    prevButton.show();
                }

                if (currentSubjectIndex === totalSubjects - 1) {
                    nextButton.hide();
                } else {
                    nextButton.show();
                }
            }

            prevButton.click(function() {
                if (currentSubjectIndex > 0) {
                    currentSubjectIndex--;
                    updateButtonVisibility();
                    const translateX = -currentSubjectIndex * 25;
                    carouselWrapper.css("transform", "translateX(" + translateX + "%)");
                }
            });

            nextButton.click(function() {
                if (currentSubjectIndex < totalSubjects - 1) {
                    currentSubjectIndex++;
                    updateButtonVisibility();
                    const translateX = -currentSubjectIndex * 25;
                    carouselWrapper.css("transform", "translateX(" + translateX + "%)");
                }
            });


            $(window).resize(function() {

                updateButtonVisibility();
            });
        });

        var prevButton = document.querySelector(".prev-button");
        var nextButton = document.querySelector(".next-button");
        var carouselWrapper = document.querySelector(".carousel-wrapper");

        var currentIndex = 0;

        prevButton.addEventListener("click", () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        });

        nextButton.addEventListener("click", () => {
            const numItems = document.querySelectorAll(".carousel-item").length;
            if (currentIndex < numItems - 1) {
                currentIndex++;
                updateCarousel();
            }
        });

        function updateCarousel() {
            const itemWidth = document.querySelector(".carousel-item").offsetWidth;
            const translateX = -currentIndex * itemWidth;
            carouselWrapper.style.transform = `translateX(${translateX}px)`;
        }
    }

    if (roleNum === 2) {
        (async function() {

            const countSubjectsTaken = @json($countSubjectsTaken);

            if (countSubjectsTaken.length > 0) {
                const pieData = countSubjectsTaken.map((row) => row.countSubjectsTaken);
                const pieLabels = countSubjectsTaken.map((row) => row.subject_name);
                new Chart(document.getElementById("myPieChart"), {
                    type: "pie",
                    data: {
                        labels: pieLabels,
                        datasets: [{
                            label: "Subject Taken",
                            data: pieData,
                            backgroundColor: [
                                "#33658a", //dark blue
                                "#55dde0", //blue green
                                "#FFCE56", //pastel yellow
                                "#8A2BE2", //purple
                                "#3CB371", //green
                                "#FFA07A", //pastel orange
                                "#9370DB", //pastel purple
                                "#ff3333", //red
                                "#D8C99B", //brown
                                "#63C7B2", //pastel greeen
                                "#CD4631", //jasper
                                "#F46197", //pink
                                "#59FFA0" //spring green
                            ],
                        }],
                    },
                    options: {
                        maintainAspectRatio: true,
                        aspectRatio: 1,
                        layout: {
                            padding: {
                                top: 20,
                                bottom: 20,
                                left: 20,
                                right: 20,
                            },
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    font: {
                                        size: 14,
                                    },
                                },
                            },
                        },
                    },
                });
            } else {
                const noDataMessage = document.createElement("p");
                noDataMessage.textContent = "No data found!";
                document.getElementById("SubjectTakenPie").appendChild(noDataMessage);
            }
        })();
        const selectElement = document.getElementById("mySelect");
        var hctx = document.getElementById('myHighestBarChart').getContext('2d');
        var studentScores = @json($highestScores);
        var subjectName = @json($subjectName);

        console.log(studentScores);

        // Data for the bar chart
        var datasets = [{
            label: '',
            data: studentScores.map(function(score) {
                return score.max_score;
            }),
            backgroundColor: '#f57242',
            borderColor: '#f0470a',
            borderWidth: 1,
            barPercentage: 0.9, // Adjust bar width
            categoryPercentage: 1.0, // Adjust bar width
            borderRadius: 15, // Set the border radius for rounded edges
        }];

        // Data for the bar chart
        var data = {
            labels: subjectName, // Subjects on the x-axis
            datasets: datasets
        };
        // Bar chart configuration
        var options = {
            scales: {
                x: {
                    beginAtZero: true,
                    position: 'bottom', // Display x-axis labels at the bottom
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Scores', // Add a title for the y-axis
                    },
                }
            },
            plugins: {
                legend: {
                    labels: {
                        boxWidth: 0,
                    }
                }
            }
        };

        // Create a new bar chart instance
        var myBarChart = new Chart(hctx, {
            type: 'bar',
            data: data,
            options: options
        });


        //Lowest
        var lctx = document.getElementById('myLowestBarChart').getContext('2d');
        var studentScores = @json($lowestScores);
        var subjectName = @json($subjectName);

        console.log(studentScores);

        // Data for the bar chart
        var datasets = [{
            label: '',
            data: studentScores.map(function(score) {
                return score.min_score;
            }),
            backgroundColor: '#f57242',
            borderColor: '#f0470a',
            borderWidth: 1,
            barPercentage: 0.9, // Adjust bar width
            categoryPercentage: 1.0, // Adjust bar width
            borderRadius: 15, // Set the border radius for rounded edges
        }];

        // Data for the bar chart
        var data = {
            labels: subjectName, // Subjects on the x-axis
            datasets: datasets
        };
        // Bar chart configuration
        var options = {
            scales: {
                x: {
                    beginAtZero: true,
                    position: 'bottom', // Display x-axis labels at the bottom
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Scores', // Add a title for the y-axis
                    },
                }
            },
            plugins: {
                legend: {
                    labels: {
                        boxWidth: 0,
                    }
                }
            }
        };

        // Create a new bar chart instance
        var myBarChart = new Chart(lctx, {
            type: 'bar',
            data: data,
            options: options
        });

        // Function to handle the select change event
        function handleSelectChange() {
            // Get the selected value
            const selectedValue = selectElement.value;
            const highChart = document.getElementById('myHighestBarChart');
            const lowChart = document.getElementById('myLowestBarChart');
            highChart.style.display = "none";
            lowChart.style.display = "none";
            // Use if-else to perform actions based on the selected value
            if (selectedValue === "Highest") {
                // Do something when "Highest" is selected
                console.log("Highest is selected");
                highChart.style.display = "block";
                lowChart.style.display = "none";

            } else if (selectedValue === "Lowest") {
                // Do something when "Lowest" is selected
                console.log("Lowest is selected");
                highChart.style.display = "none";
                lowChart.style.display = "block";


            } else {
                // Do something for other cases
                console.log("Other option is selected");

            }
        }

        // Manually trigger the event listener to handle the default value
        handleSelectChange();

        // Add an event listener to listen for changes in the selected option
        selectElement.addEventListener("change", handleSelectChange);

    }

    if (roleNum === 3) {

        document.querySelectorAll('.modify-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const submenu = button.nextElementSibling; // Get the next element (modify-sub)

                // Toggle the display of the submenu
                submenu.classList.toggle('hidden');
            });
        });

        function modalAddSubject() {
            const modal_add_sub = document.querySelector('.modal-add-sub');
            const form = document.querySelector('#subject-form'); // Use the form id
            const btn = document.querySelector('#add-sub');
            const span = document.querySelector('#close-add');
            const cancelModalButton = document.getElementById('cancel-add');

            btn.addEventListener('click', function() {
                $("#side-bar").hide();
                modal_add_sub.classList.remove('hidden');
            });

            function closeModal() {
                $("#side-bar").show();
                modal_add_sub.classList.add('hidden');
            }

            span.addEventListener('click', closeModal);
            cancelModalButton.addEventListener('click', closeModal);
            cancelModalButton.addEventListener('click', playClickSound);
            btn.addEventListener("click", playClickSound);

            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(form);
                $.ajax({
                    type: 'POST',
                    url: '/save-subject', // Replace with your actual route URL
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Handle success (e.g., close modal, refresh page, or show a message)
                        console.log('Subject saved:', response);
                        successCRUD(response.message);
                        closeModal();
                        setTimeout(function() {
                            Subjects();
                        }, 2000);
                    },
                    error: function(xhr, status, error) {
                        // Handle errors (e.g., show an error message)
                        console.error('Error:', error);
                    }
                });
            });
        }
        modalAddSubject();



        function errorModalSubject(messages) {
            // Join the messages with line breaks
            // const message = messages.join('<br>');

            Swal.fire({
                iconHtml: '<img src="{{ URL('images/Error.png') }}">',
                title: "<h5 style='color:white'>" + messages + "</h5>",
                customClass: {
                    confirmButton: 'btn-black-text btn-white-background',
                    icon: 'border border-0'
                },
                confirmButtonText: 'Okay',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCancelButton: false,
                showCloseButton: false,
                focusConfirm: false,
                // allowHtml: true,
            });

            $(".swal2-modal").css('background-color', '#F2A65F');
        }



        function modalEditSubject() {
            const form = document.querySelector('#subject-update');
            const modal_edit_sub = document.querySelector('.modal-edit-sub');
            const span = document.querySelector('#close-edit');
            const cancelModalButton = document.getElementById('cancel-edit');

            function closeModal() {
                $("#side-bar").show();
                modal_edit_sub.classList.add('hidden');
            }

            span.addEventListener('click', closeModal);
            cancelModalButton.addEventListener('click', closeModal);

            // Add an event listener to each "Edit" button in the foreach loop
            const editButtons = document.querySelectorAll('[id^="edit-sub-"]');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Retrieve subject data from the clicked button's data attributes
                    const subjectName = button.getAttribute('data-subject-name');
                    const subjectDesc = button.getAttribute('data-subject-desc');
                    const subjectImage = button.getAttribute('data-subject-image');
                    const subjectPath = button.getAttribute('data-subject-path');
                    const subjectID = button.getAttribute('data-subject-id');

                    // Populate modal fields with subject data
                    document.querySelector('#subject_name').value = subjectName;
                    document.querySelector('#subject_desc').value = subjectDesc;
                    document.querySelector('#subject_image').value = subjectPath;
                    document.querySelector('#subject_ID').value = subjectID;
                    // Display the image preview
                    const imagePreview = document.getElementById('subject_image_preview');
                    imagePreview.src = subjectImage;
                    imagePreview.style.display = 'block';

                    // Set the hidden input for image path
                    document.querySelector('#subject_image_preview').value = subjectImage;
                    modal_edit_sub.classList.remove('hidden');
                    $("#side-bar").hide();
                });
            });
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(form);
                $.ajax({
                    type: 'POST',
                    url: '/update-subject', // Replace with your actual route URL
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('Subject Updated:', response);
                        form.reset();
                        successCRUD(response.message);
                        closeModal();
                        setTimeout(function() {
                            Subjects();
                        }, 2000);
                    },
                    error: function(xhr, status, error) {
                        // Handle errors (e.g., show an error message)
                        console.error('Error:', error);
                    }
                });
            });
        }
        modalEditSubject();

        function modalDeleteSubject() {
            const form = document.querySelector('#subject-delete');
            const modal_edit_sub = document.querySelector('.modal-delete-sub');
            const span = document.querySelector('#close-delete');
            const cancelModalButton = document.getElementById('cancel-delete');
            // Add an event listener to each delete button
            const deleteButtons = document.querySelectorAll('[id^="delete-sub-"]');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const subjectName = button.getAttribute('delete-subject-name');
                    const subjectID = button.getAttribute('delete-subject-id');
                    document.querySelector('#subject_id').value = subjectID;
                    document.querySelector('#delete_subject_name').value = subjectName;
                    const deleteSubjectNameInput = document.getElementById(
                        'delete_subject_name');

                    const prefixText = 'Do you want to delete this subject: ';
                    deleteSubjectNameInput.style.fontSize = '20px';
                    deleteSubjectNameInput.value = prefixText + subjectName;

                    // Show the modal
                    modal_edit_sub.classList.remove('hidden');
                    $("#side-bar").hide();
                });
            });


            function closeModal() {
                $("#side-bar").show();
                modal_edit_sub.classList.add('hidden');
            }

            span.addEventListener('click', closeModal);
            cancelModalButton.addEventListener('click', closeModal);

            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(form);
                $.ajax({
                    type: 'POST',
                    url: '/delete-subject', // Replace with your actual route URL
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('Subject Deleted:', response);
                        successCRUD(response.message);
                        closeModal();
                        setTimeout(function() {
                            Subjects();
                        }, 2000);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        }

        modalDeleteSubject();

        function setSubjectAdmin(element) {
            var subjectId = element.getAttribute("data-subject-id");
            console.log("Clicked subject_id: " + subjectId);

            // Store the subject ID in session storage
            sessionStorage.setItem('subjectId', subjectId);

            ManageQuesAns();
        }
    }

    function setSubject(element) {
        var subjectId = element.getAttribute("data-subject-id");

        console.log("Clicked subject_id: " + subjectId);
        sessionStorage.setItem('subjectId', subjectId);

        Difficulty();

    }
</script>
