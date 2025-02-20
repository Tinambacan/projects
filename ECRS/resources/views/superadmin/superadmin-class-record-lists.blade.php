<!DOCTYPE html>
@extends('layout.ReportsLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Class Record Reports</title>
    @vite('resources/js/app.js')
    @vite('resources/js/reports.js')
</head>

@section('reportscontent')

    <body>
        <div class="shadow-xl p-2 rounded-lg bg-white">
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
                    @foreach ($submittedData as $data)
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
                                    <i class="fa-solid fa-bell text-gray-500 p-1 ">
                                    </i>
                                @else
                                    <i class="fa-solid fa-bell text-yellow-500 notif-prof hover:bg-gray-200 p-1 rounded-md cursor-pointer"
                                        data-prof-id="{{ $data['profID'] }}" data-course="{{ $data['courseTitle'] }}"
                                        data-prof-name="{{ ucwords($data['professorName']) }}"
                                        data-class-record="{{ $data['classRecordID'] }}">
                                    </i>
                                @endif

                                @if ($data['status'] === 'Submitted')
                                    <div class="relative group flex justify-center items-center">
                                        <a href="{{ route('download.file', ['id' => $data['fileID']]) }}">
                                            <i
                                                class="fa-solid fa-download text-green-500 hover:bg-gray-200 p-1 rounded-md cursor-pointer"></i>
                                        </a>
                                        <div
                                            class="absolute top-[-55px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                            <div
                                                class="flex justify-center items-center text-center transition-all duration-300 relative">
                                                <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Edit
                                                    Info</span>
                                                <div
                                                    class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <i class="fa-solid fa-bell text-gray-500 ">
                                    </i>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </body>

    </html>
@endsection
