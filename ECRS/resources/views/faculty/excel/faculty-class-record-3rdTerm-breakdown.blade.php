<table>
    <thead>
        <tr>
            <th>Student Name</th>
            <th>Student Number</th>
            @foreach ($assessmentTitles as $type => $titles)
                @foreach ($titles as $assessmentTitle)
                    <th>{{ ucfirst($assessmentTitle) }}</th>
                @endforeach
                <th>{{ ucfirst($type) }} Total</th>
                <th>{{ ucfirst($type) }} %</th>
            @endforeach
            <th>Final Score</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($students as $student)
            <tr>
                <td>{{ $student->studentLname }}, {{ $student->studentFname }}</td>
                <td>{{ $student->studentNo }}</td>

                @foreach ($assessmentTitles as $type => $titles)
                    @php
                        $typeRawScore = 0;
                        $scoreKey = strtolower(trim($type));
                    @endphp
                    @foreach ($titles as $index => $assessmentTitle)
                        @php
                            $scores = $studentScores[$student->studentID]['rawScores'][$scoreKey] ?? [];
                            $rawScore = isset($scores[$index]) ? $scores[$index] : 0; 
                            $typeRawScore += $rawScore;
                        @endphp
                        <td>{{ $rawScore }}</td>
                    @endforeach
                    <td>{{ $typeRawScore }}</td>
                    @php
                        $totalItems = $combinedTotalItems[$scoreKey] ?? 1;
                        $typePercentage = ($totalItems > 0) ? ($typeRawScore / $totalItems) * ($assessmentData[$scoreKey]->percentage ?? 0) : 0;
                    @endphp
                    <td>{{ number_format($typePercentage, 2) }}%</td>
                @endforeach

                <td>{{ number_format($studentScores[$student->studentID]['finalScore'] ?? 0, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
