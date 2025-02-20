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
    <title>I LET U PASS | User Type</title>
    <link rel="icon" type="image/x-icon" href="/images/LogoPNG.png">
</head>

<style>
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
    @if (session('status') === 'success')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function successModalLogin(message) {
                    Swal.fire({
                        icon: 'success',
                        title: "<h5 style='color:black'>" + message + "</h5>",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
                successModalLogin("{{ session('message') }}");
            });
        </script>
    @endif

    <audio autoplay style="display: none;">
        <source src="{{ URL('music/education.mp3') }}" type="audio/mpeg">
    </audio>

    <div class="flex flex-col lg:flex-row">
        <div class="p-3  min-h-screen">
            <div
                class=" lg:ml-32 animate-fade-in-right bg-gradient-to-tr from-indigo-200 via-red-200 to-yellow-100 rounded-2xl p-5  z-20 drop-shadow-2xl relative mt-0 sm:mt-16">

                <div class="mx-auto flex justify-center">
                    <img class="" src="{{ URL('images/LogoPNG.png') }}" style="height: 10rem; width: 10rem;">
                </div>

                <div class="p-3">
                    <h1 class="text-center text-xl my-5 uppercase font-bold text-gray-800">Select type of User</h1>
                    {{-- <hr class="w-full h-px my-4  border-2 border-rose-900"> --}}

                    <div class="flex  text-center my-8 lg:w-96">
                        <a class=" text-gray-800  p-2  rounded w-full shadow-xl font-bold hover:bg-yellow-500 bg-white"
                            href="/student-login">Student</a>
                    </div>

                    <div class="flex w-50 text-center my-8">
                        <a class="btn ttext-gray-800  p-2  rounded w-full shadow-xl font-bold hover:bg-yellow-500 bg-white"
                            href="/admin-login">Admin</a>
                    </div>



                    <div class="flex  w-50 text-center my-8">
                        <a class="btn text-gray-800 p-2  rounded w-full shadow-xl font-bold hover:bg-yellow-500 bg-white"
                            href="/prof-login">Faculty</a>
                    </div>
                </div>
            </div>
        </div>

        <img id="loginPic"class="animate-fade-in-left  absolute" src="{{ URL('images/StudLogin.png') }}">

    </div>
</body>

</html>
