<!DOCTYPE html>
@extends('layout.RecordsLayout')
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Program Lists</title>
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
</head>
@section('recordscontent')
    <body>
        <div class="flex flex-col">
            <div class="text-3xl text-red-900 font-bold my-3 dark:text-[#CCAA2C]">
                Program Lists
            </div>
            <x-superadmin.program-list-data :programs="$programs" />

        </div>
    </body>
@endsection

</html>
