<!DOCTYPE html>
@extends('layout.ReportsLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verified Reports</title>
</head>

@section('reportscontent')

    <body>
        {{-- <div class="text-2xl font-bold">Welcome {{ $fname }} {{ $lname }}</div> --}}
        <div class="shadow-xl p-2 rounded-lg bg-white">
            <table id="toVerifyTable" class="display">
                <thead>
                    <tr>
                        <th style="text-align: center">Professor</th>
                        <th style="text-align: center">Program</th>
                        <th style="text-align: center">Course</th>
                        <th style="text-align: center">Date Approved</th>
                        <th style="text-align: center">File Approved</th>
                        {{-- <th style="text-align: center">Action</th> --}}
                    </tr>
                </thead>
            
                <tbody>
                    @foreach ($submittedData as $data)
                        <tr class="text-center">
                            <td>{{ ucwords($data['professorName']) }}</td>
                            <td>{{ $data['programTitle'] }}</td>
                            <td>{{ $data['courseTitle'] }}</td>
                            <td>{{ date('m-d-Y', strtotime($data['updatedAt'])) }}</td>
                            <td>{{ $data['file'] }}</td>
                       
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
        </div>
    </body>

    </html>
@endsection