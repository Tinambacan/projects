<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/js/admin-accounts.js')
    @vite('resources/css/dataTable.css')
</head>

<body>
    <div class="flex justify-center w-full">
        <div class="flex flex-col w-full">
            <div id="prof-info-section" class="">
                <div class="flex justify-between items-center">
                    <x-titleText>
                        Accounts
                    </x-titleText>
                    <div class="text-xl text-red-900 flex gap-1 top-4">
                        <div class="relative group flex justify-center items-center">
                            <div class="flex justify-center items-center">
                                <i id="add-prof-btn"
                                    class="fa-solid fa-user-plus cursor-pointer z-10 hover:bg-gray-200 dark:hover:bg-[#161616] p-[5px] duration-300 hover:rounded-md  dark:text-[#CCAA2C]"></i>
                            </div>
                            <x-tooltips tooltipTitle="Add Professor" />
                        </div>

                        <div class="relative group flex justify-center items-center">
                            <div class="flex justify-center items-center ">
                                <i id="add-prof-list-btn"
                                    class="fa-solid fa-file-circle-plus cursor-pointer z-10 hover:bg-gray-200 hover:rounded-md p-1 dark:text-[#CCAA2C]">
                                </i>
                            </div>
                            <x-tooltips tooltipTitle="Import" />
                        </div>

                        {{-- <div class="relative group flex justify-center items-center send-batch-prof-credentials">
                            <input type="hidden" id="selectedProfIDs" name="selectedProfIDs" value="">

                            <div class="flex justify-center items-center ">
                                <i
                                    class="fa-solid fa-paper-plane text-red-900 hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer dark:text-[#CCAA2C]">
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
                                        id="prof_select_all">
                                </th>
                                <th style="text-align: center">First Name</th>
                                <th style="text-align: center">Last Name</th>
                                <th style="text-align: center">Email</th>
                                <th style="text-align: center">Status</th>
                                <th style="text-align: center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($registrations as $index => $registration)
                                <tr>
                                    <td class="text-md" style="text-align: center">
                                        <input type="checkbox" class="prof_checkbox text-center"
                                            data-prof-id="{{ $registration->login->loginID }}"
                                            @if ($registration->isSentCredentials == 1) disabled @endif>
                                    </td>
                                    <td style="text-align: center">{{ ucwords($registration->Fname) }}</td>
                                    <td style="text-align: center">{{ ucwords($registration->Lname) }}</td>
                                    <td style="text-align: center">{{ $registration->login->email ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        @if ($registration->isActive == 1)
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
                                                        data-salutation="{{ $registration->salutation }}"
                                                        data-schoolID="{{ $registration->schoolIDNo }}"
                                                        data-fname="{{ $registration->Fname }}"
                                                        data-lname="{{ $registration->Lname }}"
                                                        data-mname="{{ $registration->Mname }}"
                                                        data-sname="{{ $registration->Sname }}"
                                                        data-email="{{ $registration->login->email }}">
                                                    </i>
                                                </div>
                                                <x-tooltips tooltipTitle="View Info" />
                                            </div>
                                            <div class="relative group flex justify-center items-center">
                                                <div class="flex justify-center items-center ">
                                                    <i class="fa-solid fa-pen-to-square text-green-500 edit-button hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer"
                                                        data-registrationID="{{ $registration->registrationID }}"
                                                        data-salutation="{{ $registration->salutation }}"
                                                        data-schoolID="{{ $registration->schoolIDNo }}"
                                                        data-fname="{{ $registration->Fname }}"
                                                        data-lname="{{ $registration->Lname }}"
                                                        data-mname="{{ $registration->Mname }}"
                                                        data-sname="{{ $registration->Sname }}"
                                                        data-email="{{ $registration->login->email }}">
                                                    </i>
                                                </div>
                                                <x-tooltips tooltipTitle="Edit Info" />
                                            </div>
                                            @if ($registration->isSentCredentials == 1)
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
                                                            data-salutation="{{ $registration->salutation }}"
                                                            data-schoolID="{{ $registration->schoolIDNo }}"
                                                            data-fname="{{ $registration->Fname }}"
                                                            data-lname="{{ $registration->Lname }}"
                                                            data-email="{{ $registration->login->email }}">
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
                    </table>
                    

                     ${
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
                                }
                    
                    
                    --}}

                    <table id="myTable" class="table-auto w-full display  text-center justify-center">
                        <thead>
                            <tr>
                                {{-- <th style="text-align: center">
                                    <input type="checkbox" class="rounded-full" name="select_all" value=""
                                        id="prof_select_all">
                                </th> --}}
                                <th style="text-align: center">Faculty Number</th>
                                <th style="text-align: center">Last Name</th>
                                <th style="text-align: center">First Name</th>
                                <th style="text-align: center">Email </th>
                                {{-- <th style="text-align: center">Status</th> --}}
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
    <x-loader modalLoaderId="send-email-loader" titleLoader="Sending to email" />



    <x-modal title="Add Professor Information" modalId="add-prof-modal" closeBtnId="close-btn-add-prof">
        <div class="bg-white rounded-lg transform transition-all w-full max-w-screen-sm dark:bg-[#161616]">
            <div class="flex justify-center items-center mt-5 px-8">
                <div class="flex flex-col w-full">
                    <form id="add-prof-form">
                        @csrf
                        {{-- <input type="hidden" name="classRecordID" id="classRecordID"
                            value="{{ $classRecords->classRecordID }}" /> --}}
                        <div class="my-2  items-center">
                            <label for="schoolIDNo" class="block font-bold">Faculty Number:</label>
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

                        </div>

                        <div class="flex justify-center items-center p-5">
                            <button type="submit"
                                class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                                <span>Add Professor</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-modal>

    <x-loader modalLoaderId="add-cre-send-email-loader"
        titleLoader="Adding faculty and sending credentials to email" />

    <x-modal title="Import Professor List" modalId="add-prof-list-modal" closeBtnId="close-btn-add-prof-list">
        <div class="bg-white rounded-lg transform transition-all  w-full max-w-screen-sm dark:bg-[#161616]">
            <div class="flex justify-center items-center gap-10 mt-5">
                <div class="flex flex-col">
                    <form id="add-prof-list-form">
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
                                class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                                <span>Import</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-modal>

    <x-loader modalLoaderId="loader-modal-import" titleLoader="Importing" />


    <x-modal title="View Professor Information" modalId="view-prof-modal" closeBtnId="close-btn-view-prof">
        <div class="bg-white rounded-lg  transform transition-all w-full max-w-screen-sm dark:bg-[#161616]">
            <div class="flex">
                <div class="grid grid-cols-2 gap-5 w-full px-8 py-5">
                    <div class="my-2 flex flex-col">
                        <label for="view-schoolIDNo" class="block font-bold">Faculty Number:</label>
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
                        <label for="view-Mname" class="block font-bold">Middle Name</label>
                        <span id="view-Mname"></span>
                    </div>

                    <div class="my-2 flex flex-col">
                        <label for="view-Sname" class="block font-bold">Suffix</label>
                        <span id="view-Sname"></span>
                    </div>

                    <div class="my-2 flex flex-col">
                        <label for="view-salutation" class="block font-bold">Salutation:</label>
                        <span id="view-salutation"></span>
                    </div>

                    <div class="my-2 flex flex-col">
                        <label for="view-email" class="block font-bold">Email:</label>
                        <span id="view-email"></span>
                    </div>

                </div>
            </div>
        </div>
    </x-modal>



    <x-modal title="Edit Professor Information" modalId="edit-prof-modal" closeBtnId="close-btn-edit-prof">
        <div class="bg-white rounded-lg  transform transition-all w-full max-w-screen-sm dark:bg-[#161616]">
            <div class="flex justify-center items-center mt-5 px-8">
                <div class="flex flex-col w-full">
                    <form id="edit-prof-form">
                        @csrf
                        <input type="hidden" name="registrationID" id="edit-registrationID" />
                        <div class="my-2 items-center">
                            <label for="schoolIDNo" class="block font-bold">Faculty Number:</label>
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

</body>

</html>
