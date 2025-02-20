<!DOCTYPE html>
@extends('layout.ReportsLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin | Class Record Reports</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
    @vite('resources/js/reports.js')
</head>

@section('reportscontent')
    <x-loader modalLoaderId="loader-modal-submit" />
    <x-loader modalLoaderId="getJson" titleLoader="Please wait..." />

    <body>
        {{-- <div style="" class="relative group flex justify-end cursor-pointer mb-3">
            @if ($adminBranch == 1)
                <div id="sendJson" class="hidden flex justify-end dark:text-[#CCAA2C] text-red-900 rounded-lg text-xl gap-1 hover:bg-gray-200 p-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <p class="text-sm">Faculty loads</p>
                </div>
                <div id="showJson" class="flex justify-end dark:text-[#CCAA2C] text-red-900 rounded-lg text-xl gap-1 hover:bg-gray-200 p-2"> 
                    <i class="fa-solid fa-paper-plane"></i>
                    <p class="text-sm">Show Faculty Loads</p>
                </div>
            @endif
        </div> --}}
        <div class="bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] dark:text-white text-black p-3 rounded-md animate-fadeIn shadow-lg">
            <table id="toVerifyTable" class="display">
                <thead>
                    <tr>
                        <th style="text-align: center">Professor</th>
                        <th style="text-align: center">Program</th>
                        <th style="text-align: center">Course</th>
                        <th style="text-align: center">Status</th>
                        <th style="text-align: center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    {{-- @foreach ($submittedData as $data)
                        <tr>
                            <td class="text-center">{{ ucwords($data['professorName']) }}</td>
                            <td class="text-center">{{ $data['programTitle'] }}</td>
                            <td class="text-center">{{ $data['courseTitle'] }}</td>
                            <td class="text-center">
                                @if ($data['status'] === 'Submitted')
                                    <span class="bg-green-500 text-white p-2 rounded-md">Submitted</span>
                                @else
                                    <span class="bg-red-500 text-white p-2 rounded-md">Unsubmitted</span>
                                @endif
                            </td>
                            <td class="text-2xl flex justify-center items-center gap-2">

                                @if ($data['status'] === 'Submitted')
                                    <i class="fa-solid fa-bell text-gray-500 p-1">
                                    </i>
                                @else
                                    <div class="relative group flex justify-center items-center">
                                        <i class="fa-solid fa-bell text-yellow-500 notif-prof hover:bg-gray-200 p-1 rounded-md cursor-pointer"
                                            data-prof-id="{{ $data['profID'] }}" data-course="{{ $data['courseTitle'] }}"
                                            data-prof-name="{{ ucwords($data['professorName']) }}"
                                            data-class-record="{{ $data['classRecordID'] }}">
                                        </i>
                                        <div
                                            class="absolute top-[-65px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                            <div
                                                class="flex justify-center items-center text-center transition-all duration-300 relative">
                                                <span
                                                    class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Notify
                                                    Professor</span>
                                                <div
                                                    class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif



                                @if ($data['status'] === 'Submitted')
                                    <div class="relative group flex justify-center items-center">
                                        <a href="{{ route('download.file', ['id' => $data['fileID']]) }}">
                                            <i
                                                class="fa-solid fa-download text-green-500 hover:bg-gray-200 p-1 rounded-md cursor-pointer"></i>
                                        </a>
                                        <div
                                            class="absolute top-[-65px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                            <div
                                                class="flex justify-center items-center text-center transition-all duration-300 relative">
                                                <span
                                                    class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Download
                                                    File</span>
                                                <div
                                                    class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-500">
                                        <i class="fa-solid fa-download p-1"></i>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>
        </div>

        {{-- <button id="send-notification-btn" class="p-2 bg-red-200 ">Send</button> --}}
    </body>

    </html>
@endsection
