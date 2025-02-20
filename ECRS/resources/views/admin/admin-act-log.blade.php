<!DOCTYPE html>
@extends('layout.AppLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin | Activity Log</title>
    @vite('resources/js/app.js')
    @vite('resources/js/act-logs.js')
    @vite('resources/css/dataTable.css')
</head>

@section('content')

    <body>
        <div class="w-full  mt-12">
            <x-titleText>
                Activity Log
            </x-titleText>
            <div class="flex justify-center w-full animate-fadeIn">
                <div class="flex flex-col w-full">
                    <div class="rounded-lg my-3">
                        <div class="bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] dark:text-white text-black p-3 rounded-md animate-fadeIn shadow-lg">
                            <table id="auditTableAdmin" class="w-full display text-center justify-center">
                                <thead>
                                    <tr>
                                        <th style="text-align: center">Action</th>
                                        <th style="text-align: center">Description</th>
                                        <th style="text-align: center">Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>
@endsection
