<!DOCTYPE html>
@extends('layout.AppLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

@section('content')

    <body>
        <div class="flex w-full">
            <div class="flex flex-col w-full transition-all duration-300  mt-12">
                <div class="animate-fadeIn">
                    <x-superadmin.branches-data />
                </div>
            </div>
        </div>
    </body>

    </html>
@endsection
