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
    <title>Account Settings | Update Password</title>
    @vite('resources/js/app.js')
    @vite('resources/js/acc-settings.js')
</head>

@section('settingscontent')

    <body>
        @if ($roleNum == 1)
            <div>
                <form id="password-change-form" method="POST">
                    @csrf
                    <div class="w-full">
                        <div class="text-center pb-5 hidden md:block">
                            <span class="md:text-2xl text-xl text-red-900 dark:text-[#CCAA2C] font-bold">Change
                                Password</span>
                            <input type="hidden" id="loginID" name="loginID" value="{{ $loginID }}">
                        </div>
                        <div class="flex flex-col gap-4">
                            <div class=" dark:text-white relative md:w-2/4 w-full">
                                <label for="current-password" class="block font-bold">Current Password</label>
                                <input type="password" name="currentPassword" id="current-password"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    autocomplete="off" placeholder="Current Password" required />
                                <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-9 -translate-y-1/5 text-gray-600"
                                    toggle="#current-password"></i>
                            </div>
                            <div class=" dark:text-white relative md:w-2/4 w-full">
                                <label for="new-password" class="block font-bold">New Password</label>
                                <div id="password-requirements" class=" text-xs text-left text-gray-500 my-2">
                                    <div id="length-requirement" class="mr-2">
                                        &#8226; Password must be at least 8 characters long
                                    </div>
                                    <div id="uppercase-requirement" class="mr-2">
                                        &#8226; Password must include at least one (1) uppercase letter
                                    </div>
                                    <div id="special-char-requirement" class="mr-2">
                                        &#8226; Password must include at least one (1) special character
                                    </div>
                                    <div id="number-requirement" class="mr-2">
                                        &#8226; Password must include at least one (1) number
                                    </div>
                                </div>
                                <div class="relative">
                                    <input type="password" name="newPassword" id="new-password"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                        autocomplete="off" placeholder="New Password" required />
                                    <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-3  -translate-y-1/5 text-gray-600"
                                        toggle="#new-password"></i>
                                </div>
                            </div>
                            <div class=" dark:text-white relative md:w-2/4 w-full">
                                <label for="confirm-password" class="block font-bold">Confirm Password</label>
                                <input type="password" name="confirmPassword" id="confirm-password"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    autocomplete="off" placeholder="Re-type New Password" required />
                                <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-9 -translate-y-1/5 text-gray-600"
                                    toggle="#confirm-password"></i>
                            </div>
                        </div>
                        <div class="flex justify-end pt-5 gap-2">
                            <button id="fa-update-btn" type="button"
                                class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E] ">
                                Save changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @elseif ($roleNum == 2)
            <div>
                <form id="ad-password-change-form" method="POST">
                    @csrf
                    <div class="w-full">
                        <div class="text-center pb-5 hidden md:block">
                            <span class="md:text-2xl text-xl text-red-900 dark:text-[#CCAA2C] font-bold">Change
                                Password</span>
                            <input type="hidden" id="ad-loginID" name="loginID" value="{{ $loginID }}">
                        </div>
                        <div class="flex flex-col gap-4">
                            <div class=" dark:text-white relative md:w-2/4 w-full">
                                <label for="current-password" class="block font-bold">Current Password</label>
                                <input type="password" name="currentPassword" id="ad-current-password"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    autocomplete="off" placeholder="Current Password" required />
                                <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-9 -translate-y-1/5 text-gray-600"
                                    toggle="#ad-current-password"></i>
                            </div>
                            <div class=" dark:text-white relative md:w-2/4 w-full">
                                <label for="new-password" class="block font-bold">New Password</label>
                                <div id="password-requirements" class=" text-xs text-left text-gray-500 my-2">
                                    <div id="length-requirement" class="mr-2">
                                        &#8226; Password must be at least 8 characters long
                                    </div>
                                    <div id="uppercase-requirement" class="mr-2">
                                        &#8226; Password must include at least one (1) uppercase letter
                                    </div>
                                    <div id="special-char-requirement" class="mr-2">
                                        &#8226; Password must include at least one (1) special character
                                    </div>
                                    <div id="number-requirement" class="mr-2">
                                        &#8226; Password must include at least one (1) number
                                    </div>
                                </div>
                                {{-- <input type="password" name="newPassword" id="ad-new-password"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    autocomplete="off" placeholder="New Password" required />
                                <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-9 -translate-y-1/5 text-gray-600"
                                    toggle="#ad-new-password"></i> --}}

                                <div class="relative">
                                    <input type="password" name="newPassword" id="ad-new-password"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                        autocomplete="off" placeholder="New Password" required />
                                    <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-3  -translate-y-1/5 text-gray-600"
                                        toggle="#ad-new-password"></i>
                                </div>
                            </div>
                            <div class=" dark:text-white relative md:w-2/4 w-full">
                                <label for="confirm-password" class="block font-bold">Confirm Password</label>
                                <input type="password" name="confirmPassword" id="ad-confirm-password"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    autocomplete="off" placeholder="Re-type New Password" required />
                                <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-9 -translate-y-1/5 text-gray-600"
                                    toggle="#ad-confirm-password"></i>
                            </div>
                        </div>
                        <div class="flex justify-end pt-5 gap-2">
                            <button id="ad-update-btn" type="button"
                                class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E] ">
                                Save changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @elseif ($roleNum == 4)
            <div>
                <form id="sa-password-change-form" method="POST">
                    @csrf
                    <div class="w-full">
                        <div class="text-center pb-5 hidden md:block">
                            <span class="md:text-2xl text-xl text-red-900 dark:text-[#CCAA2C] font-bold">Change
                                Password</span>
                            <input type="hidden" id="sa-loginID" name="loginID" value="{{ $loginID }}">
                        </div>
                        <div class="flex flex-col gap-4">
                            <div class=" dark:text-white relative md:w-2/4 w-full">
                                <label for="current-password" class="block font-bold">Current Password</label>
                                <input type="password" name="currentPassword" id="sa-current-password"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    autocomplete="off" placeholder="Current Password" required />
                                <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-9 -translate-y-1/5 text-gray-600"
                                    toggle="#sa-current-password"></i>
                            </div>
                            <div class=" dark:text-white relative md:w-2/4 w-full">
                                <label for="new-password" class="block font-bold">New Password</label>
                                <div id="password-requirements" class=" text-xs text-left text-gray-500 my-2">
                                    <div id="length-requirement" class="mr-2">
                                        &#8226; Password must be at least 8 characters long
                                    </div>
                                    <div id="uppercase-requirement" class="mr-2">
                                        &#8226; Password must include at least one (1) uppercase letter
                                    </div>
                                    <div id="special-char-requirement" class="mr-2">
                                        &#8226; Password must include at least one (1) special character
                                    </div>
                                    <div id="number-requirement" class="mr-2">
                                        &#8226; Password must include at least one (1) number
                                    </div>
                                </div>
                                {{-- <input type="password" name="newPassword" id="sa-new-password"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    autocomplete="off" placeholder="New Password" required />
                                <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-9 -translate-y-1/5 text-gray-600"
                                    toggle="#sa-new-password"></i> --}}

                                <div class="relative">
                                    <input type="password" name="newPassword" id="sa-new-password"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                        autocomplete="off" placeholder="New Password" required />
                                    <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-3  -translate-y-1/5 text-gray-600"
                                        toggle="#sa-new-password"></i>
                                </div>
                            </div>
                            <div class=" dark:text-white relative md:w-2/4 w-full">
                                <label for="confirm-password" class="block font-bold">Confirm Password</label>
                                <input type="password" name="confirmPassword" id="sa-confirm-password"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    autocomplete="off" placeholder="Re-type New Password" required />
                                <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-9 -translate-y-1/5 text-gray-600"
                                    toggle="#sa-confirm-password"></i>
                            </div>
                        </div>
                        <div class="flex justify-end pt-5 gap-2">
                            <button id="sa-update-btn" type="button"
                                class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E] ">
                                Save changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <div>
                <form id="st-password-change-form" method="POST">
                    @csrf
                    <div class="w-full">
                        <div class="text-center pb-5 hidden md:block">
                            <span class="md:text-2xl text-xl text-red-900 dark:text-[#CCAA2C] font-bold">Change
                                Password</span>
                            <input type="hidden" id="st-loginID" name="loginID" value="{{ $loginID }}">
                        </div>
                        <div class="flex flex-col gap-4">
                            <div class=" dark:text-white relative md:w-2/4 w-full">
                                <label for="current-password" class="block font-bold">Current Password</label>
                                <input type="password" name="currentPassword" id="st-current-password"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    autocomplete="off" placeholder="Current Password" required />
                                <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-9 -translate-y-1/5 text-gray-600"
                                    toggle="#st-current-password"></i>
                            </div>
                            <div class=" dark:text-white relative md:w-2/4 w-full">
                                <label for="new-password" class="block font-bold">New Password</label>
                                <div id="password-requirements" class=" text-xs text-left text-gray-500 my-2">
                                    <div id="length-requirement" class="mr-2">
                                        &#8226; Password must be at least 8 characters long
                                    </div>
                                    <div id="uppercase-requirement" class="mr-2">
                                        &#8226; Password must include at least one (1) uppercase letter
                                    </div>
                                    <div id="special-char-requirement" class="mr-2">
                                        &#8226; Password must include at least one (1) special character
                                    </div>
                                    <div id="number-requirement" class="mr-2">
                                        &#8226; Password must include at least one (1) number
                                    </div>
                                </div>
                                {{-- <input type="password" name="newPassword" id="st-new-password"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    autocomplete="off" placeholder="New Password" required />
                                <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-9 -translate-y-1/5 text-gray-600"
                                    toggle="#st-new-password"></i> --}}

                                <div class="relative">
                                    <input type="password" name="newPassword" id="st-new-password"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                        autocomplete="off" placeholder="New Password" required />
                                    <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-3  -translate-y-1/5 text-gray-600"
                                        toggle="#st-new-password"></i>
                                </div>
                            </div>
                            <div class=" dark:text-white relative md:w-2/4 w-full">
                                <label for="confirm-password" class="block font-bold">Confirm Password</label>
                                <input type="password" name="confirmPassword" id="st-confirm-password"
                                    class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                    autocomplete="off" placeholder="Re-type New Password" required />
                                <i class="fas fa-eye text-xl cursor-pointer toggle-password absolute right-4 top-9 -translate-y-1/5 text-gray-600"
                                    toggle="#st-confirm-password"></i>
                            </div>
                        </div>
                        <div class="flex justify-end pt-5 gap-2">
                            <button id="st-update-btn" type="button"
                                class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E] ">
                                Save changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </body>


    </html>
@endsection
