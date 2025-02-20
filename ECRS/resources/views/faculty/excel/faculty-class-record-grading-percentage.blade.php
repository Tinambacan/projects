<table>
    <tbody>
        @foreach ($gradingDistribution as $term => $distributions)
            <tr>
                <th colspan="3">{{ $distributions->first()->gradingDistributionType }}</th>
                <th>{{ $distributions->sum('gradingDistributionPercentage') }}%</th>
            </tr>
            @php
                $classStanding = $grading[$term . '-class'] ?? collect();
                $examination = $grading[$term . '-exam'] ?? collect();
                $classStandingTotal = $classStanding->sum('percentage');
                $examinationTotal = $examination->sum('percentage');
            @endphp
            <tr>
                <td>Class standing</td>
                <td>{{ $classStandingTotal }}</td>
                <td>Examination</td>
                <td>{{ $examinationTotal }}</td>
            </tr>
            @foreach ($classStanding as $index => $class)
                <tr>
                    <td>{{ ucfirst($class->assessmentType) }}</td>
                    <td>{{ $class->percentage }}</td>
                    @if ($index < $examination->count())
                        <td>{{ ucfirst($examination[$index]->assessmentType) }}</td>
                        <td>{{ $examination[$index]->percentage }}</td>
                    @else
                        <td></td>
                        <td></td>
                    @endif
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>