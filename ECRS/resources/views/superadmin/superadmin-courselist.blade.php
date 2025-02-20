<!DOCTYPE html>
@extends('layout.RecordsLayout')
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Course Lists</title>
</head>
@section('recordscontent')

    <body>
        <div class="flex flex-col">
            <div class="text-3xl text-red-900 font-bold my-3 dark:text-[#CCAA2C]">
                Course Lists
            </div>
            <x-superadmin.course-list-data :programs="$programs" />

        </div>
    </body>


    </html>
@endsection
