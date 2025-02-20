<table>
    <thead>
        <tr>
            <th>Field</th>
            <th>Information</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Professor Name</td>
            <td>
                {{ $classRecord->login->registration->Fname }} 
                {{ $classRecord->login->registration->Mname }} 
                {{ $classRecord->login->registration->Lname }}
            </td>
        </tr>
        <tr>
            <td>School Year</td>
            <td>{{ $classRecord->schoolYear }}</td>
        </tr>
        <tr>
            <td>Branch</td>
            <td>{{ $branchDescription }}</td>
        </tr>        
        <tr>
            <td>Scheduled Days</td>
            <td>
                @foreach($classRecord->schedules as $schedule)
                    {{ $schedule->scheduleDay }}<br>
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Scheduled Time</td>
            <td>
                @foreach($classRecord->schedules as $schedule)
                    {{ $schedule->scheduleTime }}<br>
                @endforeach
            </td>
        </tr>
        
        <tr>
            <td>Semester</td>
            <td>
                @php
                    $semester = match ($classRecord->semester) {
                        1 => '1st Semester',
                        2 => '2nd Semester',
                        3 => 'Summer Semester',
                        default => 'Unknown Semester',
                    };
                @endphp
                {{ $semester }}
            </td>
        </tr>
        <tr>
            <td>Year Level</td>
            <td>{{ $classRecord->yearLevel }}</td>
        </tr>
        <tr>
            <td>Program</td>
            <td>{{ $classRecord->program->programCode ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Course</td>
            <td>{{ $classRecord->course->courseCode ?? 'N/A' }}</td>
        </tr>
    </tbody>
</table>
