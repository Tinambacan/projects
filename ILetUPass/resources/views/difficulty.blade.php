@php
    $order = ['Easy', 'Medium', 'Hard'];
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Difficulty</title>
</head>

<style>
    @media (max-width: 800px) {
        .studs {
            display: none;
        }
    }
</style>

<body>
    <div class="flex justify-start mt-5 ml-5">
        <div class="flex gap-1">
            <i class="fas fa-circle-arrow-left cursor-pointer text-blue-700 hover:text-blue-600"
                style="font-size: 30px;" onclick="Subjects()"></i>
        </div>
    </div>
    <div class="items-center justify-center flex  animate-fade-in-up min-h-screen">
        <div class="animate-fade-in-up">
            <img class="my-auto animate-fade-in-up studs" src="{{ URL('images/Studs.png') }}"
                style="height: 40rem; width:40rem;">
        </div>
        <div class="mb-32">
            <div class="mb-10">
                @if ($tbl_subject->isNotEmpty())
                    <h2
                        class="text-indigo-900 md:text-6xl text-4xl font-bold  p-4 text-center rounded-lg drop-shadow-xl z-10 ml-10 w-72 sm:w-80 mr-5 sm:mr-0  text-shadow-[0_4px_5px_#808080]">
                        {{ $tbl_subject->first()->subject_name }}
                    </h2>
                @endif
            </div>
            @foreach ($order as $level)
                @if (in_array($level, $distinctLevels->toArray()))
                    <div class="flex items-center animate-no-fade-right hover:animate-no-fade-left cursor-pointer space-y-10"
                        onclick="setDifficulty('{{ $level }}')">
                        @if ($level === 'Easy')
                            <img class="absolute mt-6 mr-10 z-20" src="{{ URL('images/BulbPNG.png') }}"
                                style="height: 6rem; width: 6rem;">
                        @elseif ($level === 'Medium')
                            <img class="absolute mt-7 mr-10 z-20" src="{{ URL('images/BookPNG.png') }}"
                                style="height: 6rem; width: 6rem;">
                        @elseif ($level === 'Hard')
                            <img class="absolute mt-4 mr-10 z-20" src="{{ URL('images/BooksPNG.png') }}"
                                style="height: 6rem; width: 6rem;">
                        @endif
                        <div
                            class="bg-white text-indigo-900 text-3xl font-bold p-4 text-center rounded-lg drop-shadow-xl z-10 ml-10 w-72 sm:w-80 mr-5 sm:mr-0 uppercase ">
                            {{ $level }}
                        </div>
                    </div>
                @endif
            @endforeach
            @if (!$distinctLevels->isNotEmpty())
                <div class="flex justify-center items-center text-center ml-8">
                    <h1 class="text-4xl font-bold text-gray-500"> Empty Subject </h1>
                </div>
            @endif
        </div>
    </div>

</body>

</html>
<script>
    localStorage.setItem('numberQuestion', '0');

    function setDifficulty(difficulty) {

        sessionStorage.setItem('difficulty', difficulty);

        Question();
    }
</script>
