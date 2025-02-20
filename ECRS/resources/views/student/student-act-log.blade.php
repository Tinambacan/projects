<!DOCTYPE html>
@extends('layout.SettingsLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Account Settings | Activity Log</title>
    @vite('resources/js/app.js')
    @vite('resources/js/act-logs.js')
    @vite('resources/css/dataTable.css')
</head>

@section('settingscontent')

    <body>

        <div class="w-full">
            <div class="text-center pb-5 hidden md:block">
                <span class="md:text-2xl text-xl text-red-900 dark:text-[#CCAA2C] font-bold">Activity Log</span>
            </div>
            <div class="p-2 rounded-md">
                <table id="auditTableStudent" class="w-full display text-center justify-center">
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

    </body>

    </html>
@endsection
