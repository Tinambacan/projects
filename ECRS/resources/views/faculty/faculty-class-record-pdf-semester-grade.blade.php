<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semestral Grades PDF</title>
    <style>
        @page {
            margin-top: 250px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        h1 {
            text-align: center;
        }

        .header {
            position: fixed;
            top: -220px;
            width: 100%;
        }

        .headers {

            padding: 1rem;
        }

        .logo {
            max-height: 110px;
            /* Adjust height as needed */
            float: left;
            margin-top: -10px;
        }

        p,
        h3 {
            margin: 0;
            padding: 0;
        }

        .alignleft {
            float: left;
            padding-left: 2rem;
        }

        .alignright {
            float: right;
            padding-right: -10rem;
        }

        .title-report {
            text-align: center;

        }

        #textbox {
            margin-bottom: 2rem;
        }

        .signature-section {
            margin-top: 20px;
            font-family: Arial, sans-serif;
            font-weight: bold;
            text-align: center;
        }

        .prepared-by-section {
            display: inline-block;
            width: 45%;
            /* Adjust width to ensure they fit side by side */
            vertical-align: top;
            /* Align them to the top */
            margin: 0 2%;
            text-align: center;
        }

        .prepared-by-title {
            font-size: 16px;
            text-decoration: underline;
            margin-bottom: -1rem;
        }

        .separator {
            width: 100px;
            border: none;
            border-bottom: 2px solid red;
            margin-bottom: -1rem;
        }

        .prepared-by-name,
        .prepared-by-role {
            margin: 0;
            text-align: center;
        }

        .sub_div {
            position: absolute;
            bottom: 0px;
            margin-left: auto;
            margin-right: auto;
            left: 0;
            right: 0;
            text-align: center;
            margin-top: 50px;
        }

        .signature-container {
            width: 200px;
            /* Fixed width for consistency */
            height: 50px;
            margin: 0 auto;
            /* Center the container */
            background: none;
            /* Ensure no background */
        }

        .signature-image {
            margin-top: 1rem;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            background: none;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="headers">
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
        <h3 class="title-report">Summary Report</h3>
        <div id="textboxes">
            <p class="alignleft">Course: {{ $courseTitle }}</p>
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
    </div>


    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Student Number</th>
                <th>Midterm Grade</th>
                <th>Finals Grade</th>
                <th>Semestral Grade</th>
                <th>Point Grade</th>
                <th>GWA</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($classRecords->students as $student)
                <tr>
                    <td>{{ $student->studentLname }}, {{ $student->studentFname }}</td>
                    <td>{{ $student->studentNo }}</td>
                    <td>{{ $grades[$student->studentID]['midtermGrade'] ?? 'No Grade' }}</td>
                    <td>{{ $grades[$student->studentID]['finalGrade'] ?? 'No Grade' }}</td>
                    <td>{{ $grades[$student->studentID]['semestralGrade'] ?? 'No Grade' }}</td>
                    <td>{{ $grades[$student->studentID]['pointGrade'] ?? 'No Grade' }}</td>
                    <td>{{ $grades[$student->studentID]['gwa'] ?? 'No Grade' }}</td>
                    <td>{{ $grades[$student->studentID]['remarks'] ?? 'No Grade' }}</td>
                </tr>
            @endforeach
        </tbody>

    </table>

    <div class="sub_div">
        <div class="signature-section">
            <div class="prepared-by-section">
                <p class="prepared-by-title">PREPARED BY:</p>
                {{-- <div class="signature-container">
                    <img src="{{ public_path($registration->signature) }}" alt="Signature" class="signature-image">
                </div> --}}

                <hr class="separator">
                <p class="prepared-by-name">
                    @if ($registration->role == 1)
                        {{ ucfirst($registration->Fname) }}
                        @if ($registration->Mname)
                            {{ ucfirst($registration->Mname) }}.
                        @endif
                        {{ ucfirst($registration->Lname) }}
                    @endif
                </p>
                <p class="prepared-by-role">
                    {{ $registration->role == 1 ? 'Faculty' : '' }}
                </p>
            </div>
            <div class="prepared-by-section">
                <p class="prepared-by-title">Noted BY:</p>
                <div class="signature-container">
                    {{-- @if ($displaySignature)
                        <img src="{{ public_path($admin->signature) }}" alt="Admin Signature" class="signature-image">
                    @endif --}}
                </div>
                <hr class="separator">

                <p class="prepared-by-name">
                    {{ ucfirst($admin->Fname) }}
                    @if ($admin->Mname)
                        {{ ucfirst($admin->Mname) }}.
                    @endif
                    {{ ucfirst($admin->Lname) }}
                </p>

                <p class="prepared-by-role">
                    Admin
                </p>
            </div>

        </div>
    </div>

</body>

</html>
