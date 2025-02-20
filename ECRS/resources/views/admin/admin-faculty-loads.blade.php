<!DOCTYPE html>
@extends('layout.LoadsLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin | Faculty Loads</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
    @vite('resources/js/reports.js')
    
</head>

@section('loadscontent')
    <x-loader modalLoaderId="loader-modal-submit" />
    <x-loader modalLoaderId="getJson" titleLoader="Please wait..." />
    <div style="" class="relative group flex justify-end cursor-pointer mb-3">
        @if ($adminBranch == 1)
            <div id="sendJson" class="flex justify-end dark:text-[#CCAA2C] text-red-900 rounded-lg text-xl gap-1 hover:bg-gray-200 p-2">
                <i class="fa-solid fa-paper-plane"></i>
                <p class="text-sm">Send Faculty loads</p>
            </div>
            
        @endif
    </div>
    <div class="text-2xl text-center text-red-900 font-bold dark:text-[#CCAA2C] mb-3">
        <p>SY: {{ $data['academic_year_start'] }} - {{ $data['academic_year_end'] }}</p>
        <p> 
            @if($data['semester'] == 1)
                1st Semester
            @elseif($data['semester'] == 2)
                2nd Semester
            @elseif($data['semester'] == 3)
                Summer Semester
            @else
                N/A
            @endif
        </p>
        
    </div>
    
    <body>
        <div class="bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] dark:text-white text-black p-3 rounded-md animate-fadeIn shadow-lg">
         
                    @if(isset($error))
                        <p class="text-danger">{{ $error }}</p>
                    @elseif(isset($data))
                        <table id="classRecordsTable" class="display">
                            <thead>
                                <tr>
                                    <th style="text-align: center">Professor</th>
                                    <th style="text-align: center">Year & Section</th>
                                    <th style="text-align: center">Program Code</th>
                                    <th style="text-align: center">Course Code</th>
                                    <th style="text-align: center">Course Title</th>
                                    <th style="text-align: center">Day</th>
                                    <th style="text-align: center">Time</th>
                                </tr>
                            </thead>
                            
                            <tbody> 
                                @foreach ($data['faculties'] as $faculty) 
                                    @foreach ($faculty['schedules'] as $schedule) 
                                        <tr class="border-b hover:bg-blue-100"> 
                                            <td style="padding: 12px; text-align: center;">{{ $faculty['last_name'] }},{{ $faculty['first_name'] }}</td> 
                                            <td style="padding: 12px; text-align: center;">{{ $schedule['year_level'] }} - {{ $schedule['section_name'] }}</td> 
                                            <td style="padding: 12px; text-align: center;">{{ $schedule['program_code'] }}</td> 
                                            <td style="padding: 12px; text-align: center;">{{ $schedule['course_details']['course_code'] }}</td> 
                                            <td style="padding: 12px; text-align: center;">{{ $schedule['course_details']['course_title'] }}</td> 
                                            <td style="padding: 12px; text-align: center;">{{ $schedule['day'] }}</td> 
                                            <td style="padding: 12px; text-align: center;">{{ $schedule['start_time'] }} - {{ $schedule['end_time'] }}</td> 
                                        </tr> 
                                    @endforeach 
                                @endforeach 
                            </tbody>
                            
                            
                        </table>
                    @else
                        <p>Loading class records...</p> <!-- Placeholder while data is being fetched -->
                    @endif
           
         
        </div>

        <button id="send-notification-btn" class="p-2 bg-red-200 ">Send</button>
    </body>

    </html>
@endsection
