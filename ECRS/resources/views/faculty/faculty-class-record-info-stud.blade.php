<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/js/app.js')
    @vite('resources/css/dataTable.css')
    @vite('resources/js/classrecord-info.js')
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
    <title>{{ $classRecords->course->courseCode }} | Student Information</title>
</head>

<body>
    @extends('layout.ClassRecordLayout')

    @section('classrecordcontent')
        <div class="flex justify-center w-full">
            <div class="flex flex-col w-full">
                <div id="student-info-section" class=" rounded-lg ">
                    <span id="isArchived" hidden>{{ $classRecords->isArchived }}</span>
                    @if ($classRecords->isArchived == 0)
                        <div class="flex justify-end items-center my-3">
                            <div class="text-xl  flex gap-1 ">
                                <div class="relative group flex justify-center items-center">
                                    <div class="flex justify-center items-center ">
                                        <i id="add-stud-btn"
                                            class="fa-solid fa-user-plus cursor-pointer z-10 hover:bg-gray-200 hover:rounded-md p-1 text-red-900 dark:text-[#CCAA2C]"></i>
                                    </div>
                                    <x-tooltips tooltipTitle="Add Student" />
                                </div>
                                <div class="relative group flex justify-center items-center">
                                    <div class="flex justify-center items-center ">
                                        <i id="add-stud-list-btn"
                                            class="fa-solid fa-file-circle-plus cursor-pointer z-10 hover:bg-gray-200 hover:rounded-md p-1 text-red-900 dark:text-[#CCAA2C]"></i>
                                    </div>
                                    <x-tooltips tooltipTitle="Import List" />
                                </div>
                                {{-- <div class="relative group flex justify-center items-center send-batch-stud-credentials">
                                    <input type="hidden" id="selectedStudIDs" name="selectedStudIDs" value="">
                                    <div class="flex justify-center items-center ">
                                        <i
                                            class="fa-solid fa-paper-plane text-red-900 hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer dark:text-[#CCAA2C]">
                                        </i>
                                    </div>
                                    <x-tooltips tooltipTitle="Send Credentials" />
                                </div> --}}
                            </div>
                        </div>
                    @else
                        <div></div>
                    @endif
                    <div class="bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] dark:text-white text-black p-3 rounded-md animate-fadeIn shadow-lg">
                        <table id="studInfoTable" class="display text-center">
                            <thead>
                                <tr>
                                    {{-- <th style="text-align: center">
                                        <input type="checkbox" class="rounded-full" name="select_all" value=""
                                            id="stud_select_all">
                                    </th> --}}
                                    <th style="text-align: center">Student Number</th>
                                    <th style="text-align: center">Last Name</th>
                                    <th style="text-align: center">First Name</th>
                                    <th style="text-align: center">Middle Name</th>
                                    <th style="text-align: center">Suffix</th>
                                    <th style="text-align: center">Email</th>
                                    {{-- <th style="text-align: center">Status</th> --}}
                                    <th style="text-align: center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- ${
                                    row.isSentCredentials
                                        ? `
                                    <div class="relative group flex justify-center items-center">
                                        <i class="fa-solid fa-paper-plane text-gray-500 p-1"></i>
                                          <div
                                class="absolute top-[-65px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                <div
                                    class="flex justify-center items-center text-center transition-all duration-300 relative">
                                    <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Credentials sent</span>
                                    <div
                                        class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                    </div>
                                </div>
                            </div>
                                    </div>
                                `
                                        : `
                                    <div class="relative group flex justify-center items-center">
                                        <i class="fa-solid fa-paper-plane text-red-500 hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer send-credentials" 
                                        data-fname="${row.studentFname}"
                                        data-mname="${row.studentMname}"
                                        data-lname="${row.studentLname}"
                                        data-email="${row.email}"
                                        data-studentno="${row.studentNo}"></i>

                                         <div
                                class="absolute top-[-65px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                <div
                                    class="flex justify-center items-center text-center transition-all duration-300 relative">
                                    <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Send credentials</span>
                                    <div
                                        class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                    </div>
                                </div>
                                    </div>
                                `
                                } --}}
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        <x-loader modalLoaderId="send-email-loader" titleLoader="Sending to email" />


        <x-modal title="Add Student Information" modalId="add-student-modal" closeBtnId="close-btn-add-stud">
            <div class="rounded-lg  transform transition-all w-full max-w-screen-sm">
                <div class="flex justify-center items-center mt-5 md:px-8 px-2">
                    <div class="flex flex-col w-full">
                        <form id="add-stud-form">
                            @csrf
                            <input type="hidden" name="classRecordID" id="classRecordID"
                                value="{{ $classRecords->classRecordID }}" />
                            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4 gap-2">
                                <div class=" items-center">
                                    <label for="studentNo" class="block font-bold">Student Number: <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="studentNo"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block md:p-2.5 p-1 w-full"
                                        placeholder="xxxx-xxxxx-TG-x" autocomplete="off" required />
                                </div>
                                <div class="items-center">
                                    <label for="studentFname" class="block font-bold">First Name: <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="studentFname"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block md:p-2.5 p-1 w-full"
                                        placeholder="Enter first name" autocomplete="off" required />
                                </div>
                                <div class=" items-center">
                                    <label for="studentLname" class="block font-bold">Last Name: <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="studentLname"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block md:p-2.5 p-1 w-full"
                                        placeholder="Enter last name" autocomplete="off" required />
                                </div>
                                <div class="items-center">
                                    <label for="studentMname" class="block font-bold">Middle Name:</label>
                                    <input type="text" name="studentMname"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block md:p-2.5 p-1 w-full"
                                        placeholder="Enter middle name" autocomplete="off" />
                                </div>

                                <div class=" items-center">
                                    <label for="Sname" class="block font-bold">Suffix:</label>
                                    <input type="text" name="Sname"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block md:p-2.5 p-1 w-full"
                                        placeholder="Enter suffix" autocomplete="off" />
                                </div>
                                <div class=" items-center">
                                    <label for="email" class="block font-bold">Email: <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="email"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block md:p-2.5 p-1 w-full"
                                        placeholder="example@gmail.com" autocomplete="off" required />
                                </div>
                                <div class="items-center">
                                    <label for="mobileNo" class="block font-bold">Mobile No.:</label>
                                    <input type="text" name="mobileNo"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block md:p-2.5 p-1 w-full"
                                        placeholder="Enter Student Mobile #" autocomplete="off" />
                                </div>
                                <div class=" items-center">
                                    <label for="remarks" class="block font-bold">Remarks:</label>
                                    <input type="text" name="remarks"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block md:p-2.5 p-1 w-full"
                                        placeholder="Enter Student Remarks" autocomplete="off" />
                                </div>
                            </div>
                            <div class="flex justify-center items-center p-5">
                                <button type="submit"
                                    class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                                    <span>Add Student</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </x-modal>

        <x-loader modalLoaderId="add-cre-send-email-loader"
            titleLoader="Adding student and sending credentials to email" />




        <x-modal title="Import Student List" modalId="add-student-list-modal" closeBtnId="close-btn-add-stud-list">
            <div class="rounded-lg  transform transition-all  w-full max-w-screen-sm">
                <div class="flex justify-center items-center gap-10 mt-5">
                    <div class="flex flex-col">
                        <form id="add-stud-list-form">
                            @csrf
                            <input type="hidden" name="classRecordID" id="classRecordID"
                                value="{{ $classRecords->classRecordID }}" />
                            <div class="flex gap-3 justify-center  items-center mt-6">
                                <div>
                                    <input type="file" name="file" id="file"
                                        class="block w-full  file:rounded-l-full shadow-lg  border-r-2 border-zinc-300 rounded-full  file:text-sm file:bg-amber-400 file:text-white rounded-l-lg hover:file:bg-amber-500 file:py-1.5 file:px-3.5 cursor-pointer"
                                        required>
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

        <x-modal title="Edit Student" modalId="edit-student-modal" closeBtnId="close-btn-edit-stud">
            <div class="rounded-lg  transform transition-all w-full max-w-screen-sm">
                <div class="flex justify-center items-center mt-5 md:px-8 px-2">
                    <div class="flex flex-col w-full">
                        <form id="edit-stud-form" class="">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4 gap-2 w-full ">
                                <input type="hidden" name="classRecordID" id="edit-classRecordID"
                                    value="{{ $classRecords->classRecordID }}" />
                                <input type="hidden" name="studentID" id="edit-studentID" />

                                {{-- <div class="md:my-2 flex flex-col">
                                    <label for="studentNo" class="block font-bold">Student Number:</label>
                                    <input type="text" name="studentNo" id="edit-studentNo"
                                        class="border border-gray-300 bg-gray-300 text-gray-900 rounded-lg block md:p-2.5 p-1 w-full"
                                        autocomplete="off" required readonly />
                                </div> --}}

                                <div class="md:my-2 flex flex-col">
                                    <label for="edit-studentNo" class="block font-bold">Student Number:</label>
                                    <p id="edit-studentNo"
                                        class="border border-gray-300 bg-gray-300 text-gray-900 rounded-lg block md:p-2.5 p-1 w-full">
                                    </p>
                                </div>



                                <div class="md:my-2 flex flex-col">
                                    <label for="studentFname" class="block font-bold">First Name:</label>
                                    <input type="text" name="studentFname" id="edit-studentFname"
                                        class="border border-gray-300 text-gray-900 rounded-lg block md:p-2.5 p-1 w-full"
                                        autocomplete="off" required />
                                </div>

                                <div class="md:my-2 flex flex-col">
                                    <label for="studentLname" class="block font-bold">Last Name:</label>
                                    <input type="text" name="studentLname" id="edit-studentLname"
                                        class="border border-gray-300 text-gray-900 rounded-lg block md:p-2.5 p-1 w-full"
                                        autocomplete="off" required />
                                </div>

                                <div class="md:my-2 flex flex-col">
                                    <label for="email" class="block font-bold">Email:</label>
                                    <input type="text" name="email" id="edit-email"
                                        class="border border-gray-300 text-gray-900 rounded-lg block md:p-2.5 p-1 w-full"
                                        autocomplete="off" required />
                                </div>

                                <div class="md:my-2 flex flex-col">
                                    <label for="mobileNo" class="block font-bold">Mobile No.</label>
                                    <input type="text" name="mobileNo" id="edit-mobileNo"
                                        class="border border-gray-300 text-gray-900 rounded-lg block md:p-2.5 p-1 w-full"
                                        autocomplete="off" />
                                </div>

                                <div class="md:my-2 flex flex-col">
                                    <label for="remarks" class="block font-bold">Remarks:</label>
                                    <input type="text" name="remarks" id="edit-remarks"
                                        class="border border-gray-300 text-gray-900 rounded-lg block md:p-2.5 p-1 w-full"
                                        autocomplete="off" />
                                </div>
                            </div>
                            <div class="flex justify-center items-center p-5">
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

        <x-modal title="View Student" modalId="view-student-modal" closeBtnId="close-btn-view-stud">
            <div class="rounded-lg transform transition-all w-full max-w-screen-sm">
                <div class="flex">
                    <div class="grid grid-cols-1 md:grid-cols-2 md:gap-5 gap-3 w-full md:px-8 px-2 py-5">
                        <div class="my-2 flex flex-col">
                            <label for="studentNo" class="block font-bold">Student Number:</label>
                            <span id="view-studentNo"></span>
                        </div>

                        <div class="my-2 flex flex-col">
                            <label for="studentFname" class="block font-bold">First Name:</label>
                            <span id="view-studentFname"></span>
                        </div>

                        <div class="my-2 flex flex-col">
                            <label for="studentLname" class="block font-bold">Last Name:</label>
                            <span id="view-studentLname"></span>
                        </div>

                        <div class="my-2 flex flex-col">
                            <label for="email" class="block font-bold">Email:</label>
                            <span id="view-email"></span>
                        </div>

                        <div class="my-2 flex flex-col">
                            <label for="mobileNo" class="block font-bold w-1/3">Mobile No.</label>
                            <span id="view-mobileNo"></span>
                        </div>

                        <div class="my-2 flex flex-col">
                            <label for="remarks" class="block font-bold w-1/3">Remarks:</label>
                            <span id="view-remarks"></span>
                        </div>
                    </div>
                </div>
            </div>
        </x-modal>

        <x-loader modalLoaderId="loader-modal-import" titleLoader="Importing" />
    @endsection
</body>

</html>
