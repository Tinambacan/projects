<table>
    <thead>
        <tr>
            <th>Student Name</th>
            <th>Student Number</th>
            @foreach ($gradingDistributions as $distribution)
                @if ($distribution->term == 1)
                    <th>{{ $distribution->gradingDistributionType }} Grade</th>
                @endif
                @if ($distribution->term == 2)
                    <th>{{ $distribution->gradingDistributionType }} Grade</th>
                @endif
                @if ($distribution->term == 3)
                    <th>{{ $distribution->gradingDistributionType }} Grade</th>
                @endif
            @endforeach
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
                @if ($gradingDistributions->contains('term', 1))
                    <td>{{ $grades[$student->studentID]['term1Grade'] ?? 'No Grade' }}</td>
                @endif
                @if ($gradingDistributions->contains('term', 2))
                    <td>{{ $grades[$student->studentID]['term2Grade'] ?? 'No Grade' }}</td>
                @endif
                @if ($gradingDistributions->contains('term', 3))
                    <td>{{ $grades[$student->studentID]['term3Grade'] ?? 'No Grade' }}</td>
                @endif
                <td>{{ $grades[$student->studentID]['semestralGrade'] ?? 'No Grade' }}</td>
                <td>{{ $grades[$student->studentID]['pointGrade'] ?? 'No Grade' }}</td>
                <td>{{ $grades[$student->studentID]['gwa'] ?? 'No Grade' }}</td>
                <td>{{ $grades[$student->studentID]['remarks'] ?? 'No Grade' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
