<!DOCTYPE html>
@extends('layout.AppLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ECRS | Class Record</title>
    @vite('resources/js/app.js')
    <script>
        
    </script>
</head>

@section('content')

    <body>
        <div class="w-full  sm:px-5 md:px-5 lg:px-32 xl:px-40 2xl:px-44">
            <div class="w-full">
                {{-- @include('components.faculty.class-record-data') --}}
                <div class="animate-fadeIn">
                    <x-faculty.class-record-data :classRecords="$classRecords" />
                </div>
            </div>
        </div>
    </body>

    </html>
@endsection
