<!DOCTYPE html>
@extends('layout.AppLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/js/app.js')
</head>

@section('content')

    <body>
        <div class="w-full sm:px-5 md:px-5 lg:px-32 xl:px-40 2xl:px-44">
            <div class="pt-16 md:pt-12">
                <x-titleText>
                    <span>Welcome,</span>
                    {{ ucwords($student->studentLname . ' ' . $student->studentFname . ' ' . $student->studentMname) }}
                </x-titleText>
                <div class="animate-fadeIn">
                    <x-student.class-record-data :classRecords="$classRecords" :student="$student" :studentGrades="$studentGrades" :studentClassRecords="$studentClassRecords" />
                </div>
            </div>
        </div>
        
        
    </body>

    </html>
@endsection
