<!DOCTYPE html>
@extends('layout.AppLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin | Faculty Loads</title>
    @vite('resources/js/app.js')
    @vite('resources/js/faculty-loads.js')
    @vite('resources/css/dataTable.css')
</head>

@section('content')
    <x-loader modalLoaderId="loader-modal-submit" />
    <x-loader modalLoaderId="getJson" titleLoader="Please wait..." />

    <body>

        <div class="w-full mt-12">
            <div class="flex relative justify-between">
                <x-titleText>
                    Faculty Loads
                </x-titleText>
                <div class="absolute inset-0 flex justify-center items-center md:mt-0 mt-5">
                    <div class="flex justify-center text-lg">
                        <span class="text-red-900 font-bold text-2xl dark:text-[#CCAA2C]" id="semester-sy-text">

                        </span>
                    </div>
                </div>
                <div class="flex gap-1 dark:text-white  relative ">
                    <div id="insertJSON"
                        class="flex justify-end dark:text-[#CCAA2C] text-red-900  text-xl gap-1 hover:bg-gray-200 dark:hover:bg-[#161616] p-2 rounded-md cursor-pointer">
                        <i class="fa-solid fa-paper-plane"></i>
                        <p class="text-sm">Send Faculty loads</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-center w-full animate-fadeIn">
                <div class="flex flex-col w-full">
                    <div class="rounded-lg my-3">
                        <div
                            class="bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] dark:text-white text-black p-3 rounded-md animate-fadeIn shadow-lg">
                            <table id="facultySchedulesTable" class="display">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">Faculty Name</th>
                                        <th style="text-align: center">Faculty Code</th>
                                        <th style="text-align: center">Course Code</th>
                                        <th style="text-align: center">Course Title</th>
                                        <th style="text-align: center">Program Code</th>
                                        <th style="text-align: center">Day</th>
                                        <th style="text-align: center">Time</th>
                                        <th style="text-align: center">Year & Section</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>
@endsection
