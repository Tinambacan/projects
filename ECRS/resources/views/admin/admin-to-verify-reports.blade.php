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
        <div class="shadow-xl p-2 rounded-lg bg-white">
            <table id="toVerifyTable" class="display">
                <thead>
                    <tr>
                        <th style="text-align: center">Professor</th>
                        <th style="text-align: center">Program</th>
                        <th style="text-align: center">Course</th>
                        <th style="text-align: center">Date Submitted</th>
                        <th style="text-align: center">File Submitted</th>
                        <th style="text-align: center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($submittedData as $data)
                        <tr class="text-center">
                            <td>{{ ucwords($data['professorName']) }}</td>
                            <td>{{ $data['programTitle'] }}</td>
                            <td>{{ $data['courseTitle'] }}</td>
                            <td>{{ date('m-d-Y', strtotime($data['createdAt'])) }}</td>
                            <td>{{ $data['file'] }}</td>
                            <td class="text-2xl flex justify-center items-center gap-2">
                                <form id="report-form-{{ $data['fileID'] }}" action="/store-report-id" method="POST">
                                    @csrf
                                    <input type="hidden" name="fileID" value="{{ $data['fileID'] }}">
                                    <button type="button" class="submit-button" data-file-id="{{ $data['fileID'] }}">
                                        <i class="fa-solid fa-eye text-blue-500"></i>
                                    </button>
                                </form>
                                <i class="fa-solid fa-square-xmark text-red-500"></i>
                                <i class="fa-solid fa-circle-exclamation text-yellow-500"></i>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </body>

    </html>
@endsection
