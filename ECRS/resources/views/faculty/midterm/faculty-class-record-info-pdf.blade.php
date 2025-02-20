<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midterm Grades PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        .container {
            width: 100%;
        }

        .header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            /* Align items vertically centered */
            margin-bottom: 20px;
            /* Space below the header */
            padding: 1rem;
        }

        .logo {
            max-height: 110px;
            /* Adjust height as needed */
            float: left;
            margin-top: -10px;
        }

        .text-content {
            display: flex;
            /* Change to flex to align items horizontally */
            flex-direction: row;
            /* Align text in a row */
            justify-content: flex-start;
            /* Align text to the start */
        }

        p,
        h3 {
            margin: 0;
            padding: 0;
        }

        p {
            font-size: 18px;
        }

        ,
        .title-grade {
            text-align: center;
            padding: 5px;
        }

        ,
        .alignleft {
            float: left;
        }

        .alignright {
            float: right;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="file://{{ public_path('images/logo-bg.png') }}" alt="logo" class="logo">
            <div class="text-content">
                <p>Republic of the Philippines</p>
                <h3>POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</h3>
                <h3>OFFICE OF THE VICE PRESIDENT FOR BRANCHES AND SATELLITE CAMPUSES</h3>
                <h3>
                    {{ $registration->branch == 1 ? 'Taguig' : ($registration->branch == 2 ? 'Sta. Mesa' : 'Unknown') }}
                    Branch
                </h3>

            </div>
        </div>
    </div>
    <div id="textboxes">
        <p class="alignleft">{{ $registration->role == 1 ? 'Professor' : 'Unknown Role' }}
            {{ ucfirst($registration->Fname) }}
            @if ($registration->Mname)
                {{ ucfirst($registration->Mname) }}.
            @endif
            {{ ucfirst($registration->Lname) }}
        </p>
        <p class="alignright">School Year: {{ $schoolYear }}</p>
    </div>
    <div style="clear: both;"></div>
    <div id="textbox">
        <p class="alignleft">
            Program: {{ $programCode }}:
            @if ($yearLevel == 1)
                1-1
            @elseif($yearLevel == 2)
                2-1
            @elseif($yearLevel == 3)
                3-1
            @elseif($yearLevel == 4)
                4-1
            @else
                Unknown Year Level
            @endif
        <p>
        <p class="alignright"> Semester:
            @if ($semester == 1)
                1st Semester
            @elseif($semester == 2)
                2nd Semester
            @elseif($semester == 3)
                Summer Semester
            @else
                Unknown Semester
            @endif
        </p>
    </div>
    <div style="clear: both;"></div>

    <h3 class="title-grade">{{ $courseTitle }}</h3>
    <h3 class="title-grade">Midterm Grade</h3>


    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
            <tr class="border-b">
                <th>Assessment</th>
                @foreach ($assessmentData as $type => $data)
                    <th>{{ $type }}</th>
                @endforeach
                <th class="bg-red-900 text-white p-2">Total</th>
            </tr>
            <tr class="border-b">
                <th>Total Score</th>
                @foreach ($assessmentTypes as $type)
                    <th>{{ $totalItemsData[$type] ?? 0 }}</th>
                @endforeach
                <th>{{ array_sum($totalItemsData) }}</th>
            </tr>
            <tr class="border-b">
                <th>Weighted Percentage</th>
                @foreach ($assessmentData as $type => $data)
                    <th>{{ number_format($data->percentage, 0) }}%</th>
                @endforeach
                <th>{{ number_format($totalPercentage, 0) }}%</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($classRecords->students as $student)
                @php
                    $totalScoreSum = array_sum(
                        array_map(fn($type) => $studentScores[$type][$student->studentID] ?? 0, $assessmentTypes),
                    );
                @endphp
                <tr class="border-b">
                    <td>{{ $student->studentLname }} {{ $student->studentFname }}</td>
                    @foreach ($assessmentTypes as $type)
                        <td>{{ number_format($studentScores[$type][$student->studentID] ?? 0, 2) }}</td>
                    @endforeach
                    <td>{{ number_format($totalScoreSum, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="title-grade">Created at:</div>
</body>

</html>
