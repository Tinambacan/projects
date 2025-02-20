<!DOCTYPE html>
<html lang="en">

<head>
    @vite('resources/fontawesome/css/all.min.css')
    @vite('resources/css/app.css')
    @vite('resources/js/jquery-3-7-1.js')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Game</title>
    <link rel="icon" type="image/x-icon" href="/images/LogoPNG.png">
</head>

<body>
    {{-- <div class="flex items-center flex-col justify-center z-10  my-60 relative md:mt-40 mt-20">
        <h1 class="text-3xl text-white font-semibold mb-2 text-shadow-md">Score Board</h1>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mx-auto">
                <thead class="text-xs text-gray-200 uppercase bg-orange-700 dark:text-orange-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Subject
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Level
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Score
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scores as $score)
                        <tr class="border-b bg-gray-200 dark:border-gray-300 hover:bg-orange-300 hover:text-black">
                            <td class="px-6 py-4 font-medium whitespace-nowrap text-gray-600">
                                {{ $score->created_at->format('F j, Y') }}
                            </td>
                            <td class="px-6 py-4 dark:text-gray-600">
                                {{ $score->registration->first_name }} {{ $score->registration->middle_name }}
                                {{ $score->registration->last_name }}
                            </td>
                            <td class="px-6 py-4 dark:text-gray-600">
                                {{ $score->subject->subject_name }}
                            </td>
                            <td class="px-6 py-4 dark:text-gray-600">
                                {{ $score->level }}
                            </td>
                            <td class="px-6 py-4 text-right dark:text-gray-600">
                                {{ $score->score }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button onclick="buttonReturn()"
            class="p-2 bg-white rounded-md text-blue-900 shadow-xl hover:bg-yellow-500 hover:text-white mt-5">Return</button>
    </div> --}}

    <div class="flex flex-col  md:mt-40 my-auto  mt-32 mx-5 md:rounded-lg">
        <h1
            class="text-3xl text-white font-semibold mb-2  flex items-center justify-center  text-shadow-[0_4px_5px_#808080]">
            Score Board
        </h1>
        <div class="rounded-lg  overflow-x-auto h-96">

            <table class="mx-auto  items-center justify-center p-5 shadow-xl  ">
                <thead class="text-xs text-gray-200 uppercase bg-orange-700 dark:text-orange-400">
                    <tr>

                        <th scope="col" class="px-6 py-3">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Subject
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Level
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Score
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-zinc-50  text-center text-black hover:bg-gray-400 cursor-pointer">
                    @foreach ($scores as $score)
                        <tr class="border-b bg-gray-200 dark:border-gray-300 hover:bg-orange-300 hover:text-black">
                            <td class="px-6 py-4 font-medium whitespace-nowrap text-gray-600">
                                {{ $score->created_at->format('F j, Y') }}
                            </td>
                            <td class="px-6 py-4 dark:text-gray-600">
                                {{ $score->registration->first_name }} {{ $score->registration->middle_name }}
                                {{ $score->registration->last_name }}
                            </td>
                            <td class="px-6 py-4 dark:text-gray-600">
                                {{ $score->subject->subject_name }}
                            </td>
                            <td class="px-6 py-4 dark:text-gray-600">
                                {{ $score->level }}
                            </td>
                            <td class="px-6 py-4 text-right dark:text-gray-600">
                                {{ $score->score }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex justify-center items-center">
            <button onclick="buttonReturn()"
                class="p-2 bg-white rounded-md text-blue-900 shadow-xl hover:bg-yellow-500 hover:text-white mt-5 ">Return</button>
        </div>
    </div>
</body>

<script>
    function buttonReturn() {
        window.location.href = "ILetYouPass";
        clickSound.play();

    }
</script>

</html>
