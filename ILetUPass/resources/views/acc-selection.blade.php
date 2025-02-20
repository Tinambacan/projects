<!DOCTYPE html>
<html lang="en">

<head>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    @vite('resources/js/jquery.dataTables.min.js')
    @vite('resources/css/jquery.dataTables.min.css')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>


<body>
    <div class="ml-10 pt-5 w-full animate-fade-in-up">
        <div class="mr-6 mb-5 ">
            <h2 class=" ml-4 font-bold text-4xl  pt-10 text-indigo-800 text-shadow-[0_4px_5px_#808080]">
                Account Management
            </h2>
        </div>

        <div class="flex justify-between">
            <div class="mx-5 flex gap-5 mt-1 flex-row">
                <a class="p-2 rounded-t-xl cursor-pointer justify-center w-40  " id="studAccs" onclick="studentAcc()">
                    <p class="gap-3 text-base font-bold justify-center text-center">
                        Student
                    </p>
                </a>
                <a class="p-2 rounded-t-xl cursor-pointer w-40 " id="profAccs" onclick="profAcc()">
                    <p class="gap-3 text-base font-bold justify-center text-center">
                        Professor
                    </p>
                </a>
                <button id="add-stud" class="my-auto">
                    <i class="fa-solid fa-user-plus text-2xl text-gray-700 cursor-pointer hover:text-gray-600"></i>
                    </buton>

                    <button id="add-prof" class="my-auto">
                        <i class="fa-solid fa-user-plus text-2xl text-gray-700 cursor-pointer hover:text-gray-600"></i>
                        </buton>

                        <button id="stud-import" class="group flex gap-2 rounded-lg  font-bold">
                            <i class="fa-solid fa-file-csv  my-auto hover:text-gray-600 text-gray-700 text-2xl"></i>
                        </button>

                        <button id="prof-import" class="group flex gap-2 rounded-lg  font-bold">
                            <i class="fa-solid fa-file-csv  my-auto hover:text-gray-600 text-gray-700 text-2xl"></i>
                        </button>

            </div>
            <div id="buttonDevs" class=" justify-end  animate-fade-in-up gap-3 hidden flex pr-24">
                <button id="batch-react-btn"
                    class=" relative p-2 border border-transparent text-sm rounded-md text-white bg-green-800 hover:bg-green-700  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Enable
                    {{-- <span class="tooltip">Double click to Reactivate</span> --}}
                </button>

                <button id="batch-deact-btn"
                    class="relative p-2 border border-transparent text-sm rounded-md text-white bg-red-800 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Disable
                    {{-- <span class="tooltip">Double click to Deactivate</span> --}}
                </button>

                <button id="batch-delete-btn"
                    class="relative p-2 border border-transparent text-sm rounded-md text-white bg-orange-800 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Delete
                    {{-- <span class="tooltip">Double click to Deactivate</span> --}}
                </button>
            </div>

            <div id="buttonDevsProf" class=" justify-end  animate-fade-in-up gap-3 hidden flex pr-24">
                <button id="batch-react-btn-prof"
                    class=" relative p-2 border border-transparent text-sm rounded-md text-white bg-green-800 hover:bg-green-700  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Enable
                    {{-- <span class="tooltip">Double click to Reactivate</span> --}}
                </button>

                <button id="batch-deact-btn-prof"
                    class="relative p-2 border border-transparent text-sm rounded-md text-white bg-red-800 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Disable
                    {{-- <span class="tooltip">Double click to Deactivate</span> --}}
                </button>

                <button id="batch-delete-btn-prof"
                    class="relative p-2 border border-transparent text-sm rounded-md text-white bg-orange-800 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Delete
                    {{-- <span class="tooltip">Double click to Deactivate</span> --}}
                </button>
            </div>

        </div>


    </div>

    <div class="" id="userAccounts">

    </div>

    {{-- Adding Student --}}
    <div class="modal-create-acc-stud hidden fixed z-10 inset-0 overflow-y-auto" id="modal-create-acc">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="flex justify-end px-3 py-2">
                    <i id="close-add-stud"
                        class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl  ">
                    </i>
                </div>

                <h1 class="text-white text-3xl text-center mb-5 font-bold text-shadow-[0_4px_5px_#808080]">
                    Add Student Account
                </h1>
                <div class="flex justify-center items-center mx-auto">
                    <form id="add-stud-form" method="POST" action="/save-stud-info">
                        @csrf
                        <div class="my-2 flex flex-row items-center gap-2">
                            <label for="stud_num" class="block  font-bold text-indigo-900">Student Number: </label>
                            <input type="text" name="stud_num"
                                class=" border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Student Number" autocomplete="off" required />
                            <span3 class="text-danger text-red-600">
                                @error('stud_num')
                                    {{ $message }}
                                @enderror
                            </span3>
                        </div>
                        <div class="my-2 flex flex-row items-center gap-2">
                            <label for="fn_nm" class="block  font-bold text-indigo-900">First Name:</label>
                            <input type="text" name="fn_nm"
                                class="ml-10 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="First Name" autocomplete="off" required />
                            <span1 class="text-danger text-red-600">
                                @error('fn_nm')
                                    {{ $message }}
                                @enderror
                            </span1>
                        </div>
                        <div class="my-2 flex flex-row items-center">
                            <label for="md_nm" class="block  font-bold text-indigo-900">Middle Name: </label>
                            <input type="text" name="md_nm"
                                class=" ml-8 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Middle Name" autocomplete="off" />
                            <span class="text-danger text-red-600">
                                @error('md_nm')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                        <div class="my-2 flex flex-row items-center">
                            <label for="ls_nm" class="block  font-bold text-indigo-900">Last Name </label>
                            <input type="text" name="ls_nm"
                                class=" ml-14 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Last Name" autocomplete="off" required />
                            <span2 class="text-danger text-red-600">
                                @error('ls_nm')
                                    {{ $message }}
                                @enderror
                            </span2>
                        </div>
                        <div class="my-2 flex justify-between items-center">
                            <label for="email" class="block  font-bold text-indigo-900">Email: </label>
                            <input type="text" name="email"
                                class=" ml-20 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Email" autocomplete="off" required />
                            <span3 class="text-danger text-red-600">
                                @error('email')
                                    {{ $message }}
                                @enderror
                            </span3>
                        </div>


                        <div class="mt-2 py-4 flex justify-end gap-2">
                            <button type="button" id="cancel-add-stud"
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

    {{-- Adding Prof --}}
    <div class="modal-create-acc-prof hidden fixed z-10 inset-0 overflow-y-auto" id="modal-create-acc-prof">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="flex justify-end px-3 py-2">
                    <i id="close-add-prof"
                        class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl  ">
                    </i>
                </div>

                <h1 class="text-white text-3xl text-center mb-5 font-bold text-shadow-[0_4px_5px_#808080]">
                    Add Professor Account
                </h1>
                <div class="flex justify-center items-center mx-auto">
                    <form id="add-prof-form" method="POST" action="/save-prof-info">
                        @csrf

                        <div class="my-2 flex flex-row items-center gap-2">
                            <label for="fn_nm" class="block  font-bold text-indigo-900">First Name:</label>
                            <input type="text" name="fn_nm"
                                class="ml-10 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="First Name" autocomplete="off" required />
                            <span1 class="text-danger text-red-600">
                                @error('fn_nm')
                                    {{ $message }}
                                @enderror
                            </span1>
                        </div>
                        <div class="my-2 flex flex-row items-center">
                            <label for="md_nm" class="block  font-bold text-indigo-900">Middle Name: </label>
                            <input type="text" name="md_nm"
                                class=" ml-8 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Middle Name" autocomplete="off" />
                            <span class="text-danger text-red-600">
                                @error('md_nm')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                        <div class="my-2 flex flex-row items-center">
                            <label for="ls_nm" class="block  font-bold text-indigo-900">Last Name </label>
                            <input type="text" name="ls_nm"
                                class=" ml-14 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Last Name" autocomplete="off" required />
                            <span2 class="text-danger text-red-600">
                                @error('ls_nm')
                                    {{ $message }}
                                @enderror
                            </span2>
                        </div>
                        <div class="my-2 flex justify-between items-center">
                            <label for="email" class="block  font-bold text-indigo-900">Email: </label>
                            <input type="text" name="email"
                                class=" ml-20 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Email" autocomplete="off" required />
                            <span3 class="text-danger text-red-600">
                                @error('email')
                                    {{ $message }}
                                @enderror
                            </span3>
                        </div>


                        <div class="mt-2 py-4 flex justify-end gap-2">
                            <button type="button" id="cancel-add-prof"
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

    {{-- Import Student --}}
    <div class="modal-import hidden fixed z-10 inset-0 overflow-y-auto ">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div id="">
                    <form method="POST" action="/stud-import" id="importing-students-form">
                        @csrf

                        <div class="flex justify-end px-3 py-2">
                            <i id="close-import"
                                class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl  ">
                            </i>
                        </div>

                        <h1 class="text-white text-3xl text-center mb-5 font-bold text-shadow-[0_4px_5px_#808080]">
                            Import File
                        </h1>

                        <div class="mb-2 flex justify-center mx-5">

                            <input type="file" name="file" id="file"
                                class="block w-full bg-white border file:rounded-l-lg border-gray-300 file:text-sm file:bg-orange-500 file:text-white rounded-lg hover:file:bg-orange-700 file:py-2 file:px-3.5 cursor-pointer shadow-md"
                                required>
                        </div>

                        <div class="mt-2 p-4 flex justify-end gap-2">

                            <button type="button" id="cancel-import"
                                class="ml-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Import
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Import Prof --}}
    <div class="modal-import-prof hidden fixed z-10 inset-0 overflow-y-auto ">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>

            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div id="">
                    <form method="POST" action="/prof-import" id="importing-prof-form">
                        @csrf

                        <div class="flex justify-end px-3 py-2">
                            <i id="close-import-prof"
                                class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl  ">
                            </i>
                        </div>

                        <h1 class="text-white text-3xl text-center mb-5 font-bold text-shadow-[0_4px_5px_#808080]">
                            Import File
                        </h1>

                        <div class="mb-2 flex justify-center mx-5">

                            <input type="file" name="file" id="file"
                                class="block w-full bg-white border file:rounded-l-lg border-gray-300 file:text-sm file:bg-orange-500 file:text-white rounded-lg hover:file:bg-orange-700 file:py-2 file:px-3.5 cursor-pointer shadow-md"
                                required>
                        </div>

                        <div class="mt-2 p-4 flex justify-end gap-2">

                            <button type="button" id="cancel-import-prof"
                                class="ml-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Import
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Disable Student --}}
    <div class="modal-disable-acc hidden fixed z-10 inset-0 overflow-y-auto ">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>

            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div id="">
                    <form method="POST" action="/stud-disable" id="disable-form">
                        @csrf

                        <div class="flex justify-end px-3 py-2">
                            <i id="disable-close"
                                class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl  ">
                            </i>
                        </div>

                        <h1 class="text-white text-3xl text-center mb-5 font-bold text-shadow-[0_4px_5px_#808080]">
                            Disable Account
                        </h1>

                        <div class="mb-2 flex justify-center mx-5 flex-col">

                            <div class="w-full mb-2 hidden">
                                <input type="hidden" name="id_text_deact" id="id_input_deac" value="">
                                <p class="font-bold">STUD ID:<span id="id_text_deac"></span></p>
                            </div>


                            <div class="my-2 flex flex-row items-center gap-2">
                                <label id="name_input_deac" for="fn_nm"
                                    class="block  font-bold text-indigo-900">Student Name:</label>
                                <span id="name_text_deac" type="hidden" name="name_text_deact"
                                    class="ml-5 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full bg-white"></span>
                            </div>


                            <div class="my-2 flex flex-row items-center gap-2">
                                <label id="studNum_input_deac" for="fn_nm"
                                    class="block  font-bold text-indigo-900">Student Number:</label>
                                <span id="studNum_text_deac" type="hidden" name="studNum_text_deac"
                                    class=" border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full bg-white"></span>
                            </div>
                        </div>

                        <div class="mt-2 p-4 flex justify-end gap-2">

                            <button type="button" id="disable-cancel"
                                class="ml-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Disable
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>




    {{-- Enable Student --}}
    <div class="modal-enable-acc hidden fixed z-10 inset-0 overflow-y-auto ">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>

            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div id="">
                    <form method="POST" action="/stud-enable" id="enable-form">
                        @csrf

                        <div class="flex justify-end px-3 py-2">
                            <i id="enable-close"
                                class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl  ">
                            </i>
                        </div>

                        <h1 class="text-white text-3xl text-center mb-5 font-bold text-shadow-[0_4px_5px_#808080]">
                            Enable Account
                        </h1>

                        <div class="mb-2 flex justify-center mx-5 flex-col">

                            <div class="w-full mb-2 hidden">
                                <input type="hidden" name="id_text_reac" id="id_input_reac" value="">
                                <p class="font-bold">STUD ID:<span id="id_text_reac"></span></p>
                            </div>


                            <div class="my-2 flex flex-row items-center gap-2">
                                <label id="name_input_reac" for="fn_nm"
                                    class="block  font-bold text-indigo-900">Student Name:</label>
                                <span id="name_text_reac" type="hidden" name="name_text_reac"
                                    class="ml-5 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full bg-white"></span>
                            </div>


                            <div class="my-2 flex flex-row items-center gap-2">
                                <label id="studNum_input_reac" for="fn_nm"
                                    class="block  font-bold text-indigo-900">Student Number:</label>
                                <span id="studNum_text_reac" type="hidden" name="studNum_text_reac"
                                    class=" border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full bg-white"></span>
                            </div>
                        </div>

                        <div class="mt-2 p-4 flex justify-end gap-2">

                            <button type="button" id="enable-cancel"
                                class="ml-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Enable
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Batch Delete Student --}}
    <div class="modal-delete-acc hidden fixed z-10 inset-0 overflow-y-auto ">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>

            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div id="">
                    <form method="POST" action="/stud-delete-batch" id="delete-form-stud-batch">
                        @csrf

                        <div class="flex justify-end px-3 py-2">
                            <i id="delete-close"
                                class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl  ">
                            </i>
                        </div>

                        <h1 class="text-white text-3xl text-center mb-5 font-bold text-shadow-[0_4px_5px_#808080]">
                            Delete Account
                        </h1>

                        <div class="mb-2 flex justify-center mx-5 flex-col">

                            <div class="w-full mb-2 hidden">
                                <input type="hidden" name="id_text_delete" id="id_input_delete" value="">
                                <p class="font-bold">STUD ID:<span id="id_text_delete"></span></p>
                            </div>


                            <div class="my-2 flex flex-row items-center gap-2">
                                <label id="name_input_delete" for="fn_nm"
                                    class="block  font-bold text-indigo-900">Student Name:</label>
                                <span id="name_text_delete" type="hidden" name="name_text_delete"
                                    class="ml-5 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full bg-white"></span>
                            </div>


                            <div class="my-2 flex flex-row items-center gap-2">
                                <label id="studNum_input_delete" for="fn_nm"
                                    class="block  font-bold text-indigo-900">Student Number:</label>
                                <span id="studNum_text_delete" type="hidden" name="studNum_text_delete"
                                    class=" border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full bg-white"></span>
                            </div>
                        </div>

                        <div class="mt-2 p-4 flex justify-end gap-2">

                            <button type="button" id="delete-cancel"
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


    {{-- Enable Prof --}}
    <div class="modal-enable-acc-prof hidden fixed z-10 inset-0 overflow-y-auto ">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>

            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div id="">
                    <form method="POST" action="/prof-enable" id="enable-form-prof">
                        @csrf

                        <div class="flex justify-end px-3 py-2">
                            <i id="enable-close-prof"
                                class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl  ">
                            </i>
                        </div>

                        <h1 class="text-white text-3xl text-center mb-5 font-bold text-shadow-[0_4px_5px_#808080]">
                            Enable Account
                        </h1>

                        <div class="mb-2 flex justify-center mx-5 flex-col">

                            <div class="w-full mb-2 hidden">
                                <input type="hidden" name="id_text_reac" id="id_input_reac_prof" value="">
                                <p class="font-bold">PROF ID:<span id="id_text_reac_prof"></span></p>
                            </div>


                            <div class="my-2 flex flex-row items-center gap-2">
                                <label id="name_input_reac_prof" for="fn_nm"
                                    class="block  font-bold text-indigo-900">Professor's Name:</label>
                                <span id="name_text_reac_prof" type="hidden" name="name_text_reac_prof"
                                    class="ml-5 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full bg-white"></span>
                            </div>
                        </div>

                        <div class="mt-2 p-4 flex justify-end gap-2">
                            <button type="button" id="enable-cancel-prof"
                                class="ml-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Enable
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Disable Prof --}}
    <div class="modal-disable-acc-prof hidden fixed z-10 inset-0 overflow-y-auto ">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>

            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div id="">
                    <form method="POST" action="/prof-disable" id="disable-form-prof">
                        @csrf

                        <div class="flex justify-end px-3 py-2">
                            <i id="disable-close-prof"
                                class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl  ">
                            </i>
                        </div>

                        <h1 class="text-white text-3xl text-center mb-5 font-bold text-shadow-[0_4px_5px_#808080]">
                            Disable Account
                        </h1>

                        <div class="mb-2 flex justify-center mx-5 flex-col">

                            <div class="w-full mb-2 hidden">
                                <input type="hidden" name="id_text_deact" id="id_input_deac_prof" value="">
                                <p class="font-bold">PROF ID:<span id="id_text_deac_prof"></span></p>
                            </div>


                            <div class="my-2 flex flex-row items-center gap-2">
                                <label id="name_input_deac_prof" for="fn_nm"
                                    class="block  font-bold text-indigo-900">Professor's Name:</label>
                                <span id="name_text_deac_prof" type="hidden" name="name_text_deact_prof"
                                    class="ml-5 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full bg-white"></span>
                            </div>

                        </div>

                        <div class="mt-2 p-4 flex justify-end gap-2">

                            <button type="button" id="disable-cancel-prof"
                                class="ml-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Disable
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Batch Delete Prof --}}
    <div class="modal-delete-acc-prof hidden fixed z-10 inset-0 overflow-y-auto ">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>

            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div id="">
                    <form method="POST" action="/prof-delete-batch" id="delete-form-prof-batch">
                        @csrf

                        <div class="flex justify-end px-3 py-2">
                            <i id="delete-close-prof"
                                class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl  ">
                            </i>
                        </div>

                        <h1 class="text-white text-3xl text-center mb-5 font-bold text-shadow-[0_4px_5px_#808080]">
                            Delete Account
                        </h1>

                        <div class="mb-2 flex justify-center mx-5 flex-col">
                            <div class="w-full mb-2 hidden">
                                <input type="hidden" name="id_text_ddelete" id="id_input_delete_prof"
                                    value="">
                                <p class="font-bold">PROF ID:<span id="id_text_delete_prof"></span></p>
                            </div>


                            <div class="my-2 flex flex-row items-center gap-2">
                                <label id="name_input_delete_prof" for="fn_nm"
                                    class="block  font-bold text-indigo-900">Professor's Name:</label>
                                <span id="name_text_delete_prof" type="hidden" name="name_text_delete_prof"
                                    class="ml-5 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full bg-white"></span>
                            </div>
                        </div>

                        <div class="mt-2 p-4 flex justify-end gap-2">

                            <button type="button" id="delete-cancel-prof"
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

</body>



</html>
<script>
   



    function successCRUD(message) {
        Swal.fire({
            icon: 'success',
            title: "<h5 style='color:black'>" + message + "</h5>",
            showConfirmButton: false,
            timer: 1300
        });
    }

    function errorModal(messages) {
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


    if (localStorage.getItem('selected-user-accounts') == '1') {
        studentAcc();
    } else if (localStorage.getItem('selected-user-accounts') == '2') {
        profAcc();
    } else {
        studentAcc();
    }

    function studentAcc() {
        const studentTab = document.getElementById("studAccs");
        const professorTab = document.getElementById("profAccs");
        localStorage.setItem('selected-user-accounts', 1)
        $.ajax({
            url: '/stud-acc',
            type: 'GET',
            dataType: 'json',
            complete: function(data) {
                $("#userAccounts").html(data.responseText);
            },
        });
        $("#add-prof").hide();
        $("#add-stud").show();
        $("#stud-import").show();
        $("#prof-import").hide();
        // $("#buttonDevsProf").hide();
        // $("#buttonDevs").show();


        studentTab.classList.add("bg-orange-500");
        studentTab.classList.add("text-white");

        professorTab.classList.remove("bg-orange-500");
        professorTab.classList.remove("text-white");

        professorTab.classList.add("bg-white");
        professorTab.classList.add("text-gray-800");

        studentTab.classList.remove("bg-white");
        studentTab.classList.remove("text-gray-800");

    }

    function profAcc() {
        const studentTab = document.getElementById("studAccs");
        const professorTab = document.getElementById("profAccs");
        localStorage.setItem('selected-user-accounts', 2)
        $.ajax({
            url: '/prof-acc',
            type: 'GET',
            dataType: 'json',
            complete: function(data) {
                $("#userAccounts").html(data.responseText);
            },
        });

        $("#add-stud").hide();
        $("#add-prof").show();
        $("#stud-import").hide();
        $("#prof-import").show();
        // $("#buttonDevsProf").show();
        // $("#buttonDevs").hide();

        professorTab.classList.add("bg-orange-500");
        professorTab.classList.add("text-white");

        studentTab.classList.remove("bg-orange-500");
        studentTab.classList.remove("text-white");

        professorTab.classList.remove("bg-white");
        professorTab.classList.remove("text-gray-800");

        studentTab.classList.add("bg-white");
        studentTab.classList.add("text-gray-800");

    }

    function addUserStudent() {
        const createStudUser = document.querySelector('.modal-create-acc-stud');
        const form = document.querySelector('#add-stud-form'); // Use the form id
        const btn = document.querySelector('#add-stud');
        const span = document.querySelector('#close-add-stud');
        const cancelModalButton = document.getElementById('cancel-add-stud');

        btn.addEventListener('click', function() {
            $("#side-bar").hide();
            createStudUser.classList.remove('hidden');
        });

        function closeModal() {
            $("#side-bar").show();
            createStudUser.classList.add('hidden');
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
                url: '/save-stud-info', // Replace with your actual route URL
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        form.reset();
                        successCRUD(response.message);
                        closeModal();
                        studentAcc();
                    } else if (response.status === 'error') {
                        if (response.messages && Array.isArray(response.messages)) {
                            response.messages.forEach(function(message) {
                                errorModal(response.message);
                            });
                        } else {
                            errorModal(response.message);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    }
    addUserStudent();


    function addUserProf() {
        const createStudUser = document.querySelector('.modal-create-acc-prof');
        const form = document.querySelector('#add-prof-form'); // Use the form id
        const btn = document.querySelector('#add-prof');
        const span = document.querySelector('#close-add-prof');
        const cancelModalButton = document.getElementById('cancel-add-prof');

        btn.addEventListener('click', function() {
            $("#side-bar").hide();
            createStudUser.classList.remove('hidden');
        });

        function closeModal() {
            $("#side-bar").show();
            createStudUser.classList.add('hidden');
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
                url: '/save-prof-info', // Replace with your actual route URL
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        form.reset();
                        successCRUD(response.message);
                        closeModal();
                        profAcc();
                    } else if (response.status === 'error') {
                        if (response.messages && Array.isArray(response.messages)) {
                            response.messages.forEach(function(message) {
                                errorModal(response.message);
                            });
                        } else {
                            errorModal(response.message);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    }
    addUserProf();


    function studentImport() {
        const importUser = document.querySelector('.modal-import');
        const form = document.querySelector('#importing-students-form'); // Use the form id
        const btn = document.querySelector('#stud-import');
        const span = document.querySelector('#close-import');
        const cancelModalButton = document.getElementById('cancel-import');

        btn.addEventListener('click', function() {
            $("#side-bar").hide();
            importUser.classList.remove('hidden');
        });

        function closeModal() {
            $("#side-bar").show();
            importUser.classList.add('hidden');
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
                url: '/stud-import', // Replace with your actual route URL
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        form.reset();
                        successCRUD(response.message);
                        // setTimeout(function() {
                        //     closeModal();
                        // }, 2000);
                        closeModal();
                        studentAcc();
                    } else if (response.status === 'error') {
                        if (response.messages && Array.isArray(response.messages)) {
                            response.messages.forEach(function(message) {
                                errorModal(response.message);
                            });
                        } else {
                            errorModal(response.message);
                        }
                        form.reset();
                        // setTimeout(function() {
                        //     closeModal();
                        // }, 2000);
                        closeModal();
                        studentAcc();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    errorModal(response.message);
                }
            });
        });
    }
    studentImport();


    function profImport() {
        const importUser = document.querySelector('.modal-import-prof');
        const form = document.querySelector('#importing-prof-form'); // Use the form id
        const btn = document.querySelector('#prof-import');
        const span = document.querySelector('#close-import-prof');
        const cancelModalButton = document.getElementById('cancel-import-prof');

        btn.addEventListener('click', function() {
            $("#side-bar").hide();
            importUser.classList.remove('hidden');
        });

        function closeModal() {
            $("#side-bar").show();
            importUser.classList.add('hidden');
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
                url: '/prof-import', // Replace with your actual route URL
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        form.reset();
                        successCRUD(response.message);
                        // setTimeout(function() {
                        //     closeModal();
                        // }, 2000);
                        closeModal();
                        profAcc();
                    } else if (response.status === 'error') {
                        if (response.messages && Array.isArray(response.messages)) {
                            response.messages.forEach(function(message) {
                                errorModal(response.message);
                            });
                        } else {
                            errorModal(response.message);
                        }
                        form.reset();
                        // setTimeout(function() {
                        //     closeModal();
                        // }, 2000);
                        closeModal();
                        studentAcc();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    errorModal(response.message);
                }
            });
        });
    }
    profImport();
</script>
