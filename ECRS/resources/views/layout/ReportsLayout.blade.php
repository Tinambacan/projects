@php
    $roleNum = session('role');
@endphp

<!DOCTYPE html>
@extends('layout.AppLayout')
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/js/app.js')
    @vite('resources/js/reports.js')
    @vite('resources/css/dataTable.css')
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
</head>

<body>
    @section('content')
        @if ($roleNum == 2)
            <div class="flex w-full flex-col mt-12">
                <x-titleText>
                    Class Record Reports
                </x-titleText>
                <div class="flex flex-col w-full my-3">
                    {{-- <div class="flex gap-2">
                        <div class="flex gap-5">
                            <div class="font-bold text-xl mt-4 cursor-pointer p-2 rounded-t-lg border-red-900 border-2 {{ request()->routeIs('admin.class-record-report') ? 'bg-red-900 text-white' : 'hover:text-gray-400' }}"
                                onclick="navigateClassRecordReports()">
                                Class Record Lists
                            </div>
                        </div>
                        <div class="flex gap-5">
                            <div class="font-bold text-xl mt-4 cursor-pointer p-2 rounded-t-lg border-red-900 border-2 {{ request()->routeIs('admin.to-verify-report') || request()->routeIs('admin.view-to-verify-report') ? 'bg-red-900 text-white' : 'hover:text-gray-400' }}"
                                onclick="navigateToVerifyReports()">
                                To Verify
                            </div>
                        </div>
                        <div class="flex gap-5">
                            <div class="font-bold text-xl mt-4 cursor-pointer p-2 rounded-t-lg border-red-900 border-2 {{ request()->routeIs('admin.verified-report') ? 'bg-red-900 text-white' : 'hover:text-gray-400' }}"
                                onclick="navigateVerifiedReports()">
                                Verified
                            </div>
                        </div>
                    </div> --}}
                    <main class="animate-fadeIn">
                        @yield('reportscontent')
                    </main>
                </div>
            </div>
        @else
            <div class="flex w-full flex-col  mt-12">
                <x-titleText>
                    Class Record Reports
                </x-titleText>
                <div class="flex flex-col w-full">
                    <div class="flex gap-2">
                        <div class="flex gap-5">
                            <div class="font-bold text-xl mt-4 cursor-pointer p-2 rounded-t-lg border-red-900 border-2 {{ request()->routeIs('faculty.submitted-report') ? 'bg-red-900 text-white' : 'hover:text-gray-400' }}"
                                onclick="navigateToSubmittedReports()">
                                Submitted
                            </div>
                        </div>
                        <div class="flex gap-5">
                            <div class="font-bold text-xl mt-4 cursor-pointer p-2 rounded-t-lg border-red-900 border-2 {{ request()->routeIs('faculty.verified-report') ? 'bg-red-900 text-white' : 'hover:text-gray-400' }}"
                                onclick="navigateFacultyVerifiedReports()">
                                Verified
                            </div>
                        </div>
                    </div>
                    <main>
                        @yield('reportscontent')
                    </main>
                </div>
            </div>
        @endif
        <script>
            function navigateToVerifyReports() {
                window.location.href = "{{ route('admin.to-verify-report') }}";
            }

            function navigateVerifiedReports() {
                window.location.href = "{{ route('admin.verified-report') }}";
            }

            function navigateClassRecordReports() {
                window.location.href = "{{ route('admin.class-record-report') }}";
            }


            function navigateToSubmittedReports() {
                window.location.href = "{{ route('faculty.submitted-report') }}";
            }

            function navigateFacultyVerifiedReports() {
                window.location.href = "{{ route('faculty.verified-report') }}";
            }
        </script>
    @endsection
</body>

</html>
