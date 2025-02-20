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
    <title>Account Settings | Account Information</title>
    @vite('resources/js/app.js')
    @vite('resources/js/acc-settings.js')
</head>

@section('settingscontent')

    <body>
        @if ($roleNum == 1)
        <div class="w-full">
            <div class="text-center pb-5 hidden md:block">
                <span class="md:text-2xl  text-xl text-red-900 dark:text-[#CCAA2C] font-bold">Personal Information</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="hidden">
                    <span>{{ $user->registrationID ?? '' }}</span>
                </div>
                <div class="items-center dark:text-white">
                    <label for="Fname" class="block font-bold">First Name</label>
                    <span>{{ $user->Fname ?? '' }}</span>
                </div>
                <div class="items-center dark:text-white">
                    <label for="Mname" class="block font-bold">Middle Name</label>
                    <span>{{ $user->Mname ?? '' }}</span>
                </div>
                <div class="items-center dark:text-white">
                    <label for="Lname" class="block font-bold">Last Name</label>
                    <span>{{ $user->Lname ?? '' }}</span>
                </div>
        
                <div class="items-center dark:text-white">
                    <label for="Sname" class="block font-bold">Suffix</label>
                    <span>{{ $user->Sname ?? '' }}</span>
                </div>
                
                <div class="items-center dark:text-white">
                    <label for="salutation" class="block font-bold">Salutation</label>
                    <span>{{ $user->salutation ?? '' }}</span>
                </div>
        
                <div class="items-center dark:text-white">
                    <label for="schoolIDNo" class="block font-bold">Faculty Number</label>
                    <span>{{ $user->schoolIDNo ?? '' }}</span>
                </div>

                <div class="items-center dark:text-white">
                    <label for="email" class="block font-bold">Email</label>
                    <span>{{ $user->login->email ?? '' }}</span>
                </div>
            </div>
            {{-- <div class="flex justify-end pt-5 gap-2">
                    <button id="save-btn" class="text-black rounded-lg py-1 px-3 shadow-lg border border-gray-300">
                        Save changes
                    </button>
                </div> --}}
        </div>
        
        @elseif ($roleNum == 2)
            <div class="w-full">
                <div class="text-center pb-5 hidden md:block">
                    <span class="md:text-2xl  text-xl text-red-900 dark:text-[#CCAA2C] font-bold">Personal Information</span>
                </div>
                <input type="hidden" id="adminID" name="adminID" value="{{ $user->adminID ?? '' }}" autocomplete="off"
                    required />
                <input type="hidden" id="ad-loginID" name="loginID" value="{{ $loginID }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="items-center dark:text-white">
                        <label for="Fname" class="block font-bold">First Name</label>
                        <input type="text" name="Fname" id="edit-facultyFname"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->Fname ?? '' }}" autocomplete="off" required />
                    </div>
                    <div class="items-center dark:text-white">
                        <label for="Mname" class="block font-bold">Middle Name</label>
                        <input type="text" name="Mname" id="edit-facultyMname"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->Mname ?? '' }}" autocomplete="off" required />
                    </div>
                    <div class="items-center dark:text-white">
                        <label for="Lname" class="block font-bold">Last Name</label>
                        <input type="text" name="Lname" id="edit-facultyLname"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->Lname ?? '' }}" autocomplete="off" required />
                    </div>

                    <div class="items-center dark:text-white">
                        <label for="Sname" class="block font-bold">Suffix</label>
                        <input type="text" name="Sname" id="edit-facultySname"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->Sname ?? '' }}" autocomplete="off" required />
                    </div>

                    <div class="items-center dark:text-white">
                        <label for="Sname" class="block font-bold">Salutation</label>
                        <input type="text" name="Sname" id="edit-salutation"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->salutation ?? '' }}" autocomplete="off" required />
                    </div>

                    <div class="items-center dark:text-white">
                        <label for="email" class="block font-bold">Email</label>
                        <input type="email" name="email" id="edit-adminEmail"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->login->email ?? '' }}" autocomplete="off" required />
                    </div>

                    <div class="items-center dark:text-white">
                        <label for="schoolIDNo" class="block font-bold">Admin Number</label>
                        <input type="text" name="schoolIDNo" id="edit-facultyschoolIDNo"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->schoolIDNo ?? '' }}" autocomplete="off" required />
                    </div>

                    {{-- <div class="items-center dark:text-white">
                        <label for="salutation" class="block font-bold">Salutation</label>
                        <select name="salutation" id="edit-adminSalutation"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full">
                            <option value="">Select Salutation</option>
                            <option value="Mr." {{ $user->salutation == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                            <option value="Ms." {{ $user->salutation == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                            <option value="Mrs." {{ $user->salutation == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                            <option value="Dr." {{ $user->salutation == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                            <option value="Prof." {{ $user->salutation == 'Prof.' ? 'selected' : '' }}>Prof.</option>
                            <option value="Rev." {{ $user->salutation == 'Rev.' ? 'selected' : '' }}>Rev.</option>
                        </select>
                    </div> --}}

                </div>
                <div class="flex justify-end pt-5 gap-2">
                    <button id="save-btn" class="text-black rounded-lg px-5 py-2  shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                        Save changes
                    </button>
                </div>
            </div>
        @elseif ($roleNum == 4)
            <div class="w-full">
                <div class="text-center pb-5 hidden md:block">
                    <span class="md:text-2xl text-xl text-red-900 dark:text-[#CCAA2C] font-bold">Personal Information</span>
                </div>
                <input type="hidden" id="superadminID" name="superadminID" value="{{ $user->superadminID ?? '' }}"
                    autocomplete="off" required />
                <input type="hidden" id="sa-loginID" name="loginID" value="{{ $loginID }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ">
                    <div class="items-center  dark:text-white">
                        <label for="Fname" class="block font-bold">First Name</label>
                        <input type="text" name="Fname" id="edit-facultyFname"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->Fname ?? '' }}" autocomplete="off" required />
                    </div>
                    <div class="items-center dark:text-white">
                        <label for="Mname" class="block font-bold">Middle Name</label>
                        <input type="text" name="Mname" id="edit-facultyMname"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->Mname ?? '' }}" autocomplete="off" required />
                    </div>
                    <div class="items-center dark:text-white">
                        <label for="Lname" class="block font-bold">Last Name</label>
                        <input type="text" name="Lname" id="edit-facultyLname"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->Lname ?? '' }}" autocomplete="off" required />
                    </div>

                    <div class="items-center dark:text-white">
                        <label for="Sname" class="block font-bold">Suffix</label>
                        <input type="text" name="Sname" id="edit-facultySname"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->Sname ?? '' }}" autocomplete="off" required />
                    </div>

                    <div class="items-center dark:text-white">
                        <label for="Sname" class="block font-bold">Salutation</label>
                        <input type="text" name="Sname" id="edit-salutation"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->salutation ?? '' }}" autocomplete="off" required />
                    </div>

                    <div class="items-center dark:text-white">
                        <label for="email" class="block font-bold">Email</label>
                        <input type="email" name="email" id="edit-adminEmail"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->login->email ?? '' }}" autocomplete="off" required />
                    </div>

                    <div class="items-center dark:text-white">
                        <label for="schoolIDNo" class="block font-bold">Admin Number</label>
                        <input type="text" name="schoolIDNo" id="edit-facultyschoolIDNo"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            value="{{ $user->schoolIDNo ?? '' }}" autocomplete="off" required />
                    </div>
                </div>
                <div class="flex justify-end pt-5 gap-2">
                    <button id="save-btn" class="text-black rounded-lg px-5 py-2  shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                        Save changes
                    </button>
                </div>
            </div>
        @else
            <div class="w-full">
                <div class="text-center pb-5 hidden md:block">
                    <span class="md:text-2xl text-xl text-red-900 dark:text-[#CCAA2C] font-bold">Personal Information</span>
                </div>
                <div class="hidden">
                    <span>{{ $user->registrationID ?? '' }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="items-center dark:text-white">
                        <label for="Fname" class="block font-bold">First Name</label>
                        <span>{{ $user->Fname ?? '' }}</span>
                    </div>
                    <div class="items-center dark:text-white">
                        <label for="Mname" class="block font-bold">Middle Name</label>
                        <span>{{ $user->Mname ?? '' }}</span>
                    </div>
                    <div class="items-center dark:text-white">
                        <label for="Lname" class="block font-bold">Last Name</label>
                        <span>{{ $user->Lname ?? '' }}</span>
                    </div>
            
                    <div class="items-center dark:text-white">
                        <label for="Sname" class="block font-bold">Suffix</label>
                        <span>{{ $user->Sname ?? '' }}</span>
                    </div>
            
                    <div class="items-center dark:text-white">
                        <label for="email" class="block font-bold">Email</label>
                        <span>{{ $user->login->email ?? '' }}</span>
                    </div>
            
                    <div class="items-center dark:text-white">
                        <label for="schoolIDNo" class="block font-bold">Student Number</label>
                        <span>{{ $user->schoolIDNo ?? '' }}</span>
                    </div>
                </div>
                {{-- <div class="flex justify-end pt-5 gap-2">
                    <button id="save-btn" class="text-black rounded-lg py-1 px-3 shadow-lg border border-gray-300">
                        Save changes
                    </button>
                </div> --}}
            </div>
        
        @endif
    </body>

    </html>
@endsection
