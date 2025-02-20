@php
    $roleNum = session('role');
    $loginID = session('loginID');
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/fontawesome/css/all.min.css')
    @vite('resources/js/app.js')
</head>

<body>
    <div class="bg-red-900 w-full px-8 fixed top-0 z-50 dark:bg-[#161616] transition-all duration-300">
        <div class="flex md:justify-between justify-center items-center">
            <div class="flex gap-2">
                <img src="{{ URL('images/logo-bg.png') }}" alt="Logo" class="h-16 md:h-20">
                <div class="uppercase text-2xl md:text-2xl flex items-center justify-center text-white">
                    <span class="hidden sm:inline font-bold">E-class Record System</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
