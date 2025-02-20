<!DOCTYPE html>
@extends('layout.ReportsLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To Verify Reports</title>
    @vite('resources/js/app.js')
    @vite('resources/js/reports.js')
</head>

@section('reportscontent')

    <body>
        <div class="flex flex-col">
            <div class="flex justify-between my-2">
                <div>
                    <a class="flex justify-start gap-2 relative z-10 hover:bg-gray-100 p-2 rounded-md"
                        href="/admin/reports/to-verify">
                        <i class="fa-solid fa-arrow-left bg-red-900 rounded-full p-2 text-white"></i>
                        <span class="flex justify-center items-center font-bold">Back to list of
                            files</span>
                    </a>
                </div>
                <form action="{{ route('update-file', ['fileID' => $submission->fileID]) }}" method="POST"
                    id="verify-print-form">
                    @csrf
                    <button type="button" id="verify-print-btn"
                        class="text-md flex gap-2 justify-center items-center text-red-900 rounded-lg p-2 shadow-lg border border-gray-300">
                        <i class="fa-solid fa-file-circle-plus cursor-pointer z-10 text-red-900"></i>
                        <div class="flex justify-center items-center">
                            <span class="text-md">Verify and Print</span>
                        </div>
                    </button>
                </form>


            </div>
            <div class="shadow-xl p-2 rounded-lg bg-white">
                <iframe src="{{ asset('grade_files/' . $submission->file) }}" class="w-full md:h-[520px]"></iframe>
            </div>
        </div>

    </body>

    </html>
@endsection
