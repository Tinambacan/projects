<!DOCTYPE html>
<html lang="en">

<head>
    @vite('resources/fontawesome/css/all.min.css')
    @vite('resources/css/app.css')
    @vite('resources/js/jquery-3-7-1.js')
    @vite('resources/js/app.js')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>I LET U PASS | Login</title>
    <link rel="icon" type="image/x-icon" href="/images/LogoPNG.png">
</head>

<style>
     #board {
            background-image: url("images/BoardPNG.png");
            background-position: center;
            margin-top: 50px;
            background-repeat: no-repeat;
            background-size: cover;
            transition: ease-in;
        }
    @media (max-width: 810px) {
        #loginPic {
            display: none;
        }

        #board {
            background-image: url("images/BoardPNG.png");
            background-position: center;
            margin-top: 50px;
            background-repeat: no-repeat;
            background-size: cover;
            transition: ease-in;
        }
    }
</style>

<body id="board" class=" bg-purple-100 overflow-hidden">
    <audio autoplay style="display: none;">
        <source src="{{ URL('music/education.mp3') }}" type="audio/mpeg">
    </audio>

    <div class="flex flex-col lg:flex-row">
        <div class="p-3  min-h-screen">
            <div
                class="left-96 lg:ml-32 animate-fade-in-right bg-gradient-to-tr from-indigo-200 via-red-200 to-yellow-100 rounded-2xl p-5  z-20 drop-shadow-2xl relative mt-0 sm:mt-16">
                <div class="mb-5 mt-5 drop-shadow-md ">

                    <div class="flex gap-2 text-indigo-900 rounded-xl p-2 text-center font-bold hover:text-violet-500">
                        <i class="fa-solid fa-arrow-left my-auto"></i>
                        <a class="hover:underline"href="/">
                            Back
                        </a>
                    </div>
                </div>
                <div class="mx-auto flex justify-center">
                    <img class="" src="{{ URL('images/LogoPNG.png') }}" style="height: 5rem; width: 5rem;">
                </div>
                <div class="p-5 flex flex-col">
                    <h1 class="font-bold text-2xl">Account Disabled Notice:</h1>

                    <h1>Dear Player,</h1>
                    <label>Your account has been temporarily disabled due to certain circumstances. To reactivate your
                    account, please contact our development team immediately.</label>

                    <h1 class="font-bold">Contact our development team at:</h1>
                    <label>Email: iletupass123@gmail.com</label>
                </div>
            </div>
        </div>

        {{-- <img id="loginPic"class="animate-fade-in-left  absolute" src="{{ URL('images/StudLogin.png') }}"> --}}

    </div>


</body>

</html>
