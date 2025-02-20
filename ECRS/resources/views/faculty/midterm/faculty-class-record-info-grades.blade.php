<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
    <title>Grades</title>
</head>

<body>
    @extends('layout.ClassRecordLayout')

    @section('classrecordcontent')
        <div class="flex justify-end w-full p-2">
            <i class="fa-solid fa-print text-red-900 cursor-pointer text-2xl"
                onclick="window.open('{{ route('midterm-grades.pdf') }}', '_blank')"></i>
        </div>
        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead>
                <tr class="border-b">
                    <th class="p-2 border-collapse"></th>
                    <th class="p-2"></th>
                    <th class="p-2"></th>
                    <th class="bg-red-900 text-white p-2">Title</th>
                    @foreach ($assessmentData as $type => $data)
                        <th class="p-2">{{ ucwords($type) }}</th>
                    @endforeach
                    <th class="p-2">Total</th>
                </tr>
                <tr class="border-b">
                    <th class="p-2"></th>
                    <th class="p-2"></th>
                    <th class="p-2"></th>
                    <th class="bg-red-900 text-white p-2">Total Score</th>
                    @foreach ($assessmentTypes as $type)
                        <th class="p-2">{{ $totalItemsData[$type] ?? 0 }}</th>
                    @endforeach
                    <th class="p-2">{{ array_sum($totalItemsData) }}</th>
                </tr>
                <tr class="border-b">
                    <th class="p-2"></th>
                    <th class="p-2"></th>
                    <th class="p-2"></th>
                    <th class="bg-red-900 text-white p-2">Weighted Percentage</th>
                    @foreach ($assessmentData as $type => $data)
                        <th class="p-2">
                            {{ number_format($data->percentage, 2) == '0.00' ? '0%' : number_format($data->percentage, 0) . '%' }}
                        </th>
                    @endforeach
                    <th class="p-2">
                        {{ number_format($totalPercentage, 2) == '0.00' ? '0%' : number_format($totalPercentage, 0) . '%' }}
                    </th>


                </tr>
            </thead>
            <tbody>
                <tr class="border-b">
                    <td class="p-2"><input type="checkbox" class="form-checkbox">ALL</td>
                    <td class="p-2 text-center" colspan="2">Student Name</td>
                </tr>
                @foreach ($classRecords->students as $student)
                    @php
                        $totalScoreSum = array_sum(
                            array_map(fn($type) => $studentScores[$type][$student->studentID] ?? 0, $assessmentTypes),
                        );
                        $totalScoreSum = number_format($totalScoreSum, 2);
                    @endphp
                    <tr class="border-b">
                        <td class="p-2"><input type="checkbox" class="form-checkbox"></td>
                        <td class="text-center">{{ $student->studentLname }}</td>
                        <td class="text-center">{{ $student->studentFname }}</td>
                        <td></td>
                        @foreach ($assessmentTypes as $type)
                            @php
                                // Get the total score for the student for the current assessment type
                                $totalScore = $studentScores[$type][$student->studentID] ?? 0;
                                // Format the total score to 2 decimal places
                                $totalScore = number_format($totalScore, 2);
                            @endphp
                            <td class="text-center">{{ $totalScore }}</td>
                        @endforeach
                        <td class="text-center">{{ $totalScoreSum }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    @endsection
</body>

</html>
