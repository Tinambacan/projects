<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    @vite('resources/js/superadmin-accounts.js')
    @vite('resources/css/dataTable.css')
</head>

<body>
    <div class="flex justify-center w-full">
        <div class="flex flex-col w-full">
            <div id="admin-info-section" >
                <div class="flex justify-between items-center ">
                    <x-titleText>
                        Accounts
                    </x-titleText>
                    <div class="text-xl text-red-900 flex gap-1 top-4">
                        <div class="relative group flex justify-center items-center">
                            <div class="flex justify-center items-center">
                                <i id="add-admin-btn"
                                    class="fa-solid fa-user-plus cursor-pointer z-10 hover:bg-gray-200 hover:rounded-md p-1 dark:text-[#CCAA2C]"></i>
                            </div>
                            <x-tooltips tooltipTitle="Add Admin" />
                        </div>

                        {{-- <div class="relative group flex justify-center items-center">
                            <div class="flex justify-center items-center ">
                                <i id="add-admin-list-btn"
                                    class="fa-solid fa-file-circle-plus cursor-pointer z-10 hover:bg-gray-200 hover:rounded-md p-1 dark:text-[#CCAA2C]">
                                </i>
                            </div>
                            <div
                                class="absolute top-[-48px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                <div
                                    class="flex justify-center items-center text-center transition-all duration-300 relative">
                                    <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Import</span>
                                    <div
                                        class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="relative group flex justify-center items-center send-batch-admin-credentials">
                            <input type="hidden" id="selectedAdminIDs" name="selectedAdminIDs" value="">
                            <div class="flex justify-center items-center ">
                                <i
                                    class="fa-solid fa-paper-plane text-red-900 dark:text-[#CCAA2C] hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer">
                                </i>
                            </div>
                            <x-tooltips tooltipTitle="Send Credentials" />
                        </div> --}}

                    </div>
                </div>
                <div class="bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] dark:text-white text-black p-3 rounded-md animate-fadeIn shadow-lg my-3">
                    {{-- <table id="myTable" class="display">
                        <thead>
                            <tr>
                                <th style="text-align: center">
                                    <input type="checkbox" class="rounded-full" name="select_all" value=""
                                        id="admin_select_all">
                                </th>
                                <th style="text-align: center">First Name</th>
                                <th style="text-align: center">Last Name</th>
                                <th style="text-align: center">Email</th>
                                <th style="text-align: center">Status</th>
                                <th style="text-align: center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admins as $index => $admin)
                                <tr>
                                    <td class="text-md" style="text-align: center">
                                        <input type="checkbox" class="admin_checkbox text-center"
                                            data-admin-id="{{ $admin->login->loginID }}"
                                            @if ($admin->isSentCredentials == 1) disabled @endif>
                                    </td>
                                    <td style="text-align: center">{{ ucwords($admin->Fname) }}</td>
                                    <td style="text-align: center">{{ ucwords($admin->Lname) }} </td>
                                    <td style="text-align: center">{{ $admin->login->email ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        @if ($admin->isActive == 1)
                                            <span class="bg-green-500 text-white p-2 rounded-md">Active</span>
                                        @else
                                            <span class="bg-red-500 text-white p-2 rounded-md">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center text-2xl">
                                        <div class="flex justify-center items-center gap-1">
                                            <div class="relative group flex justify-center items-center">
                                                <div class="flex justify-center items-center ">
                                                    <i class="fa-solid fa-eye text-blue-500 hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer"
                                                        data-salutation="{{ $admin->salutation }}"
                                                        data-schoolID="{{ $admin->schoolIDNo }}"
                                                        data-fname="{{ $admin->Fname }}"
                                                        data-lname="{{ $admin->Lname }}"
                                                        data-mname="{{ $admin->Mname }}"
                                                        data-sname="{{ $admin->Sname }}"
                                                        data-branch="{{ $admin->branch }}"
                                                        data-branch-description="{{ $admin->branch }}"
                                                        data-email="{{ $admin->login->email }}">
                                                    </i>
                                                </div>
                                                <x-tooltips tooltipTitle="View Info" />
                                            </div>

                                            <div class="relative group flex justify-center items-center">
                                                <div class="flex justify-center items-center ">
                                                    <i class="fa-solid fa-pen-to-square text-green-500 edit-button hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer"
                                                        data-adminID="{{ $admin->adminID }}"
                                                        data-salutation="{{ $admin->salutation }}"
                                                        data-schoolID="{{ $admin->schoolIDNo }}"
                                                        data-fname="{{ $admin->Fname }}"
                                                        data-lname="{{ $admin->Lname }}"
                                                        data-mname="{{ $admin->Mname }}"
                                                        data-sname="{{ $admin->Sname }}"
                                                        data-email="{{ $admin->login->email }}">
                                                    </i>
                                                </div>
                                                <x-tooltips tooltipTitle="Edit Info" />
                                            </div>
                                            @if ($admin->isSentCredentials == 1)
                                                <div class="relative group flex justify-center items-center">
                                                    <div class="flex justify-center items-center ">
                                                        <i class="fa-solid fa-paper-plane text-gray-500 p-1">
                                                        </i>
                                                    </div>
                                                    <x-tooltips tooltipTitle="Credentials sent" />
                                                </div>
                                            @else
                                                <div class="relative group flex justify-center items-center">
                                                    <div class="flex justify-center items-center ">
                                                        <i class="fa-solid fa-paper-plane text-red-500 hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer send-credentials"
                                                            data-salutation="{{ $admin->salutation }}"
                                                            data-schoolID="{{ $admin->schoolIDNo }}"
                                                            data-fname="{{ $admin->Fname }}"
                                                            data-lname="{{ $admin->Lname }}"
                                                            data-email="{{ $admin->login->email }}">
                                                        </i>
                                                    </div>
                                                    <x-tooltips tooltipTitle="Send credentials" />
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table> --}}

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
                            <i class="fa-solid fa-paper-plane text-red-500 hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer send-credentials" data-salutation="${row.salutation}" data-schoolID="${row.schoolIDNo}" data-fname="${row.Fname}" data-lname="${row.Lname}" data-email="${row.email}"></i>

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

                    <table id="myTable" class="table-auto w-full display  text-center justify-center">
                        <thead>
                            <tr>
                                {{-- <th style="text-align: center">
                                    <input type="checkbox" class="rounded-full" name="select_all" value=""
                                        id="admin_select_all">
                                </th> --}}
                                <th style="text-align: center">First Name</th>
                                <th style="text-align: center">Last Name</th>
                                <th style="text-align: center">Email</th>
                                {{-- <th style="text-align: center">Status</th> --}}
                                <th style="text-align: center">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

  

    <x-loader modalLoaderId="send-email-loader" titleLoader="Sending to email" />




    <x-modal title="Add Admin Information" modalId="add-admin-modal" closeBtnId="close-btn-add-admin">
        <div class="bg-white rounded-lg transform transition-all w-full max-w-screen-sm dark:bg-[#161616]">
            <div class="flex justify-center items-center mt-5 px-8">
                <div class="flex flex-col w-full">
                    <form id="add-admin-form">
                        @csrf
                        {{-- <input type="hidden" name="classRecordID" id="classRecordID"
                            value="{{ $classRecords->classRecordID }}" /> --}}
                        <div class="my-2  items-center">
                            <label for="schoolIDNo" class="block font-bold">Admin Number:</label>
                            <input type="text" name="schoolIDNo"
                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                placeholder="xxxx-xxxxx-xxx" autocomplete="off" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="my-1  items-center">
                                <label class="block font-bold">First Name:
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="Fname"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    placeholder="Enter first name" autocomplete="off" required />
                            </div>

                            <div class="my-1 items-center">
                                <label class="block font-bold">Last Name:
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="Lname"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    placeholder="Enter last name" autocomplete="off" required />
                            </div>

                            <div class="my-1  items-center">
                                <label for="Mname" class="block font-bold">Middle Name</label>
                                <input type="text" name="Mname"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    placeholder="Enter middle name" autocomplete="off" />
                            </div>

                            <div class="my-1 items-center">
                                <label for="Sname" class="block font-bold">Suffix</label>
                                <input type="text" name="Sname"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    placeholder="Enter suffix" autocomplete="off" />
                            </div>

                            <div class="my-1  items-center">
                                <label class="block font-bold">Email:
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="email"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    placeholder="Enter email" autocomplete="off" required />
                            </div>

                            <div class="my-1 items-center">
                                <label class="block font-bold">Salutation:
                                    <span class="text-red-500">*</span></label>
                                <select name="salutation" id="salutation"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full">
                                    <option value="">Select salutation</option>
                                    <option value="Mr.">Mr.</option>
                                    <option value="Ms.">Ms.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Dr.">Dr.</option>
                                    <option value="Prof.">Prof.</option>
                                    <option value="Rev.">Rev.</option>
                                    <option value="Engr.">Engr.</option>
                                </select>
                            </div>

                            <div class="my-1 items-center">
                                <label class="block font-bold">Branch:
                                    <span class="text-red-500">*</span></label>
                                <select id="branch" required name="branch"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full">
                                    <option value="">Select PUP Branch</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-center items-center p-5">
                            <x-button type="submit" id="add-admin-btn-form">
                                <span>Add Admin</span>
                            </x-button>
                        </div>
                    </form>

                    {{-- <button type="submit"
                                class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E] ">
                                <span>Add Admin</span>
                            </button> --}}
                </div>
            </div>
        </div>
    </x-modal>

    <x-loader modalLoaderId="add-cre-send-email-loader" titleLoader="Adding admin and sending credentials to email" />

    <x-modal title="Import Admin List" modalId="add-admin-list-modal" closeBtnId="close-btn-add-admin-list">
        <div class="bg-white rounded-lg transform transition-all  w-full max-w-screen-sm dark:bg-[#161616]">
            <div class="flex justify-center items-center gap-10 mt-5">
                <div class="flex flex-col">
                    <form id="add-admin-list-form">
                        @csrf
                        <div class="flex gap-3 justify-center  items-center mt-6">
                            <div>
                                <input type="file" name="file" id="file"
                                    class="block w-full  file:rounded-l-full shadow-lg  border-r-2 border-zinc-300 rounded-full  file:text-sm file:bg-amber-400 file:text-white rounded-l-lg hover:file:bg-amber-500 file:py-1.5 file:px-3.5 cursor-pointer"
                                    required>
                            </div>
                        </div>
                        <div class="flex justify-center items-center p-5">
                            <button type="submit"
                                class="text-black rounded-lg p-3 shadow-lg border border-gray-300 dark:text-white">
                                <span>Import</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-modal>

    <x-loader modalLoaderId="loader-modal-import" titleLoader="Importing" />


    <x-modal title="View Admin Information" modalId="view-admin-modal" closeBtnId="close-btn-view-admin">
        <div class="bg-white rounded-lg  transform transition-all w-full max-w-screen-sm dark:bg-[#161616]">
            <div class="flex">
                <div class="grid grid-cols-2 gap-5 w-full px-8 py-5">
                    <div class="my-2 flex flex-col">
                        <label for="view-schoolIDNo" class="block font-bold">Admin Number:</label>
                        <span id="view-schoolIDNo"></span>
                    </div>

                    <div class="my-2 flex flex-col">
                        <label for="view-Fname" class="block font-bold">First Name</label>
                        <span id="view-Fname"></span>
                    </div>

                    <div class="my-2 flex flex-col">
                        <label for="view-Lname" class="block font-bold">Last Name</label>
                        <span id="view-Lname"></span>
                    </div>

                    <div class="my-2 flex flex-col">
                        <label for="view-Fname" class="block font-bold">Middle Name</label>
                        <span id="view-Mname"></span>
                    </div>

                    <div class="my-2 flex flex-col">
                        <label for="view-Lname" class="block font-bold">Suffix</label>
                        <span id="view-Sname"></span>
                    </div>

                    <div class="my-2 flex flex-col">
                        <label for="view-email" class="block font-bold">Email:</label>
                        <span id="view-email"></span>
                    </div>

                    <div class="my-2 flex flex-col">
                        <label for="view-salutation" class="block font-bold">Salutation:</label>
                        <span id="view-salutation"></span>
                    </div>

                    <div class="my-2 flex flex-col">
                        <label for="view-branch" class="block font-bold">Branch:</label>
                        <span id="view-branch"></span>
                    </div>
                </div>
            </div>
    </x-modal>


    <x-modal title="Edit Admin Information" modalId="edit-admin-modal" closeBtnId="close-btn-edit-admin">
        <div class="bg-white rounded-lg transform transition-all w-full max-w-screen-sm dark:bg-[#161616]">
            <div class="flex justify-center items-center mt-5 px-8">
                <div class="flex flex-col w-full">
                    <form id="edit-admin-form">
                        @csrf
                        <input type="hidden" name="adminID" id="edit-adminID"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full" />
                        <div class="my-2 items-center">
                            <label for="schoolIDNo" class="block font-bold">Admin Number:</label>
                            <input type="text" name="schoolIDNo" id="edit-schoolIDNo"
                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                placeholder="xxxx-xxxxx-xxx" autocomplete="off" required />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="my-1 items-center">
                                <label for="Fname" class="block font-bold">First Name</label>
                                <input type="text" name="Fname" id="edit-Fname"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    placeholder="Enter first name" autocomplete="off" required />
                            </div>
                            <div class="my-1 items-center">
                                <label for="Lname" class="block font-bold">Last Name</label>
                                <input type="text" name="Lname" id="edit-Lname"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    placeholder="Enter last name" autocomplete="off" required />
                            </div>
                            <div class="my-1 items-center">
                                <label for="Mname" class="block font-bold">Middle Name</label>
                                <input type="text" name="Mname" id="edit-Mname"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    placeholder="Enter middle name" autocomplete="off" />
                            </div>
                            <div class="my-1 items-center">
                                <label for="Sname" class="block font-bold">Suffix</label>
                                <input type="text" name="Sname" id="edit-Sname"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    placeholder="Enter suffix" autocomplete="off" />
                            </div>
                            <div class="my-1 items-center">
                                <label for="email" class="block font-bold">Email</label>
                                <input type="text" name="email" id="edit-email"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    placeholder="Enter email" autocomplete="off" required />
                            </div>
                            <div class="my-1 items-center">
                                <label for="salutation" class="block font-bold">Salutation</label>
                                <select name="salutation" id="edit-salutation"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full">
                                    <option value="">Select salutation</option>
                                    <option value="Mr.">Mr.</option>
                                    <option value="Ms.">Ms.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Dr.">Dr.</option>
                                    <option value="Prof.">Prof.</option>
                                    <option value="Rev.">Rev.</option>
                                    <option value="Engr.">Engr.</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-center items-center p-5">
                            {{-- <button type="submit"
                                class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                                <span>Save</span>
                            </button> --}}

                            <x-button type="submit">
                                <span>Save</span>
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-modal>

</body>

</html>
