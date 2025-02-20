@php
    $roleNum = session('role');
@endphp

<!DOCTYPE html>
@extends('layout.SettingsLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
    @vite('resources/js/acc-settings.js')
</head>

@section('settingscontent')

    <body>
        @if ($roleNum == 1)
            <div class="w-full">
                <div class="text-center pb-5">
                    <span class="text-2xl text-red-900 dark:text-[#CCAA2C] font-bold">Personal Information</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" id="registrationID" name="registrationID"
                        class="hidden border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5 w-full"
                        value="{{ $user->registrationID ?? '' }}" autocomplete="off" required />
                    <div class="items-center dark:text-gray-700">
                        <label for="Fname" class="block font-bold">First Name</label>
                        <input type="text" name="Fname" id="edit-facultyFname"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->Fname ?? '' }}" autocomplete="off" required />
                    </div>
                    <div class="items-center dark:text-gray-700">
                        <label for="Mname" class="block font-bold">Middle Name</label>
                        <input type="text" name="Mname" id="edit-facultyMname"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->Mname ?? '' }}" autocomplete="off" required />
                    </div>
                    <div class="items-center dark:text-gray-700">
                        <label for="Lname" class="block font-bold">Last Name</label>
                        <input type="text" name="Lname" id="edit-facultyLname"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->Lname ?? '' }}" autocomplete="off" required />
                    </div>

                    <div class="items-center dark:text-gray-700">
                        <label for="Sname" class="block font-bold">Suffix</label>
                        <input type="text" name="Sname" id="edit-facultySname"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->Sname ?? '' }}" autocomplete="off" />
                    </div>

                    <div class="items-center dark:text-gray-700">
                        <label for="email" class="block font-bold">Email</label>
                        <input type="email" name="email" id="edit-facultyLEmail"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->login->email ?? '' }}" autocomplete="off" required disabled />
                    </div>

                    <div class="items-center dark:text-gray-700">
                        <label for="schoolIDNo" class="block font-bold">Faculty Number</label>
                        <input type="text" name="schoolIDNo" id="edit-facultyschoolIDNo" disabled
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->schoolIDNo ?? '' }}" autocomplete="off" required />
                    </div>
                </div>
                <div class="flex justify-end pt-5 gap-2">
                    <button id="save-btn" class="text-black rounded-lg py-1 px-3 shadow-lg border border-gray-300">
                        Save changes
                    </button>
                </div>
            </div>
        @elseif ($roleNum == 2)
            <div class="w-full">
                <div class="text-center pb-2">
                    <span class="text-2xl text-red-900 dark:text-[#CCAA2C] font-bold">School Year and Semester</span>
                </div>
                <input type="hidden" id="adminID" name="adminID" value="{{ $user->adminID ?? '' }}" autocomplete="off"
                    required />

                <p class="p-1 mb-2 text-sm">
                    <span class="text-justify text-red-900 dark:text-[#CCAA2C] font-bold">Description:</span>
                    <span class="text-gray-700 dark:text-white">
                        Select the appropriate academic year and semester to ensure that faculty and students see the most
                        up-to-date class records.
                    </span>
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="items-center  dark:text-white">
                        <label for="schoolYear" class="block font-bold">School Year</label>
                        <select name="schoolYear" id="edit-schoolYear"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            required>
                            <option value="">Select School Year</option> <!-- Default option -->
                        </select>
                    </div>


                    <div class="items-center  dark:text-white">
                        <label for="edit-semester" class="block font-bold">School Semester</label>
                        <select name="edit-semester" id="edit-semester"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            required>
                            <option value="">Select Semester</option> <!-- Default option -->
                            <option value="1">1st Semester</option>
                            <option value="2">2nd Semester</option>
                            <option value="3">Summer Semester</option>
                        </select>
                    </div>

                </div>
                <div class="flex justify-end pt-5 gap-2">
                    <button id="save-schoolSem-btn"
                        class="text-black rounded-lg px-5 py-2  shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                        Save changes
                    </button>
                </div>
            </div>
        @else
            <div class="w-full">
                <div class="text-center pb-5">
                    <span class="text-2xl text-red-900 dark:text-[#CCAA2C] font-bold">Personal Information</span>
                </div>
                <input type="text" name="Fname" id="registrationID" name="registrationID"
                    class="hidden border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5 w-full"
                    value="{{ $user->registrationID ?? '' }}" autocomplete="off" required />
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="items-center dark:text-gray-700">
                        <label for="Fname" class="block font-bold">First Name</label>
                        <input type="text" name="Fname" id="edit-facultyFname"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->Fname ?? '' }}" autocomplete="off" required />
                    </div>
                    <div class="items-center dark:text-gray-700">
                        <label for="Mname" class="block font-bold">Middle Name</label>
                        <input type="text" name="Mname" id="edit-facultyMname"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->Mname ?? '' }}" autocomplete="off" required />
                    </div>
                    <div class="items-center dark:text-gray-700">
                        <label for="Lname" class="block font-bold">Last Name</label>
                        <input type="text" name="Lname" id="edit-facultyLname"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->Lname ?? '' }}" autocomplete="off" required />
                    </div>

                    <div class="items-center dark:text-gray-700">
                        <label for="Sname" class="block font-bold">Suffix</label>
                        <input type="text" name="Sname" id="edit-facultySname"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->Sname ?? '' }}" autocomplete="off" required />
                    </div>

                    <div class="items-center dark:text-gray-700">
                        <label for="email" class="block font-bold">Email</label>
                        <input type="email" name="email" id="edit-facultyLEmail"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->login->email ?? '' }}" autocomplete="off" required />
                    </div>

                    <div class="items-center dark:text-gray-700">
                        <label for="schoolIDNo" class="block font-bold">Student Number</label>
                        <input type="text" name="schoolIDNo" id="edit-facultyschoolIDNo"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->schoolIDNo ?? '' }}" autocomplete="off" required />
                    </div>


                </div>
                <div class="flex justify-end pt-5 gap-2">
                    <button id="save-btn" class="text-black rounded-lg py-1 px-3 shadow-lg border border-gray-300">
                        Save changes
                    </button>
                </div>
            </div>
        @endif
    </body>

    </html>
@endsection
