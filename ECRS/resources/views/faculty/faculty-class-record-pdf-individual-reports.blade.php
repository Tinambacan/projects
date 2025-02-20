<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
    <title>Individual Grades</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
    @vite('resources/js/file-submit.js')
</head>

<body>

    <div style="display: flex; flex-direction: column; margin-bottom: 1.25rem;">
        <div style="display: flex; justify-content: flex-end; align-items: center; font-size: 1.35rem; font-weight: bold; color: #000000; margin-top: 1.25rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; text-align: center; width: 100%;">
                <div>Individual Report</div>
                <div style="color: black">{{ strtoupper($student->studentLname) }}, {{ strtoupper($student->studentFname) }}</div>
            </div>
        </div>
    </div>
    
    
    @foreach($gradingDistributionTypes as $term => $termName)
        @if(isset($studentScores[$student->studentID]['rawScores'][$term]))
            <div style="margin-bottom: 1.25rem; background-color: #ffffff; border: 1px solid #d1d1d1; color: #000000; padding: 1.25rem; border-radius: 0.375rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                <h3 style="font-size: 1.3rem; font-weight: bold; text-align: center; margin-bottom: 1rem;">{{ strtoupper($termName) }}</h3>
                @foreach($studentScores[$student->studentID]['rawScores'][$term] as $type => $assessments)
                    <div style="">
                        <h4 style="font-size: 1.0rem; font-weight: 600; margin-bottom: 0.75rem;">{{ strtoupper(ucfirst($type)) }}</h4>
                        <div style="overflow-x: auto;">
                            <table style="min-width: 100%; background-color: #ffffff; border-collapse: collapse;">
                                <thead>
                                    <tr>
                                        <th style="padding: 0.5rem 1rem; border: 1px solid #d1d1d1; background-color: #7F1D1D; color: #ffffff;">Assessment Name</th>
                                        <th style="padding: 0.5rem 1rem; border: 1px solid #d1d1d1; background-color: #7F1D1D; color: #ffffff;">Score</th>
                                        @if($type !== 'attendance')
                                            <th style="padding: 0.5rem 1rem; border: 1px solid #d1d1d1; background-color: #7F1D1D; color: #ffffff;">Passing Scores</th>
                                        @endif
                                        <th style="padding: 0.5rem 1rem; border: 1px solid #d1d1d1; background-color: #7F1D1D; color: #ffffff;">Total Items</th>
                                        <th style="padding: 0.5rem 1rem; border: 1px solid #d1d1d1; background-color: #7F1D1D; color: #ffffff;">Assessment Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assessments as $assessment)
                                        <tr style="background-color: #f5f5f5; border-bottom: 1px solid #d1d1d1;">
                                            <td style="padding: 0.5rem 1rem; text-align: center; border: 1px solid #d1d1d1;">{{ $assessment['assessmentName'] }}</td>
                                            <td style="padding: 0.5rem 1rem; text-align: center; border: 1px solid #d1d1d1;">{{ $assessment['score'] }}</td>
                                            @if($type !== 'attendance')
                                                <td style="padding: 0.5rem 1rem; text-align: center; border: 1px solid #d1d1d1;">{{ $assessment['passingItem'] }}</td>
                                            @endif
                                            <td style="padding: 0.5rem 1rem; text-align: center; border: 1px solid #d1d1d1;">{{ $assessment['totalItem'] }}</td>
                                            <td style="padding: 0.5rem 1rem; text-align: center; border: 1px solid #d1d1d1;">{{ $assessment['assessmentDate'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="text-align: right;">
                            @php
                                $assessmentPercentage = isset($assessmentData[strtolower($type)]) 
                                    ? round(optional($assessmentData[strtolower($type)]->where('term', $term)->first())->percentage ?? 0, 2) 
                                    : 0;
                        
                                $studentScore = isset($studentScores[$student->studentID]['finalScores'][$term][$type]) 
                                    ? round($studentScores[$student->studentID]['finalScores'][$term][$type], 2) 
                                    : 0;
                            @endphp
                            <p class="text-md font-bold text-end">
                                {{ ucfirst($type) }} Grade Percentage:
                                {{ $studentScore }}% / {{ $assessmentPercentage }}%
                            </p>
                        </div>
                        
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach
    
</body>


</html>
