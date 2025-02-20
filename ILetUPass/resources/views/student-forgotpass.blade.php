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
    <title>Forgot Password</title>
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
    <audio autoplay style="display: none;">
        <source src="{{ URL('music/education.mp3') }}" type="audio/mpeg">
    </audio>

    <div class="flex flex-col lg:flex-row">
        <div class="p-3  min-h-screen">
            <div
                class=" lg:ml-32 animate-fade-in-right bg-yellow-200 rounded-2xl p-5 z-20 drop-shadow-2xl relative mt-0 sm:mt-16">
                <div class="mb-5 mt-5 drop-shadow-md">
                    <a id="sign-Up"
                        class=" text-indigo-900 rounded-xl p-2  text-center font-bold hover:bg-yellow-400"
                        href="/student-login">
                        <i class="fa-solid fa-arrow-left my-auto"></i>
                    </a>
                </div>
                <div class="flex justify-center items-center flex-col">
                    {{-- <img class="" src="{{ URL('images/LogoPNG.png') }}" style="height: 10rem; width: 10rem;"> --}}
                    <h1 class="text-indigo-900 font-bold text-xl mb-5">Forgot Password</h1>
                    <h3 class="text-indigo-900 font-bold mb-5 text-center">If you forgot your password or <br> having a trouble logging in, we can <br> email you a password reset link.</h3>
                </div>

                <div class="p-5">
                    <form action="/forgot-pass" method="POST">
                        @csrf
                        <div class="drop-shadow-md">
                            <label class="block  font-bold text-indigo-900">Email address </label>
                            <input type="email" name="email" id="emailInput"
                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full lg:w-80 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Enter your email address" autocomplete="off" />
                            <span class="text-danger text-red-600">
                                @error('email')
                                    {{ $message }}
                                @enderror
                            </span>

                        </div>
                        <div class="flex mt-8 justify-center drop-shadow-md">
                            <div class="flex flex-wrap text-center w-56">
                                <button type="submit" id="myLogin"
                                    class="btn text-indigo-900 btn-success bg-white p-2 border rounded-full hover:bg-orange-200 w-full lg:w-56 font-bold">Reset Password</button>
                                <audio id="clickSound" src="{{ URL('music/click.mp3') }}"></audio>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <img id="loginPic"class="animate-fade-in-left  absolute" src="{{ URL('images/StudLogin.png') }}">

    </div>


    <script type="module">
        const myLogin = document.getElementById("myLogin");
        const sign = document.getElementById("sign-Up");

        const clickSound = document.getElementById("clickSound");

        // Function to play the click sound
        function playClickSound() {
            clickSound.play();
        }
        myLogin.addEventListener("click", playClickSound);
        sign.addEventListener("click", playClickSound);

        // login.addEventListener("click", playClickSound);

        const signUpButton = document.getElementById("signUp");

        $("button").click(function(event) {
            event.preventDefault();
            const form = event.target.form;
            const formData = new FormData(form);

            $.ajax({
                url: '/forgot-pass',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        form.reset();
                        successModalSend(response.message);
                    } else {
                        form.reset();
                        errorModal(response.message);

                        if (response.errors) {
                            var errors = response.errors;

                            if (errors.email) {
                                $('input[name="email"]').addClass('error-border');
                                $('span3.text-danger').html(errors.email[0]);
                            } else {
                                $('span3.text-danger').empty();
                            }


                        }
                    }
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                }
            });
        });

        function successModalSend(message) {
            Swal.fire({
                icon: 'success',
                title: "<h5 style='color:black'>" + message + "</h5>",
                showConfirmButton: false,
                timer: 1500
            });
            // $(".swal2-modal").css('background-color', '#F2A65F');
        }


        function errorModal(messages) {
            Swal.fire({
                iconHtml: '<img src="{{ URL('images/Error.png') }}">',
                title: "<h5 style='color:white'>" + messages + "</h5>",
                customClass: {
                    confirmButton: 'btn-black-text btn-white-background',
                    icon: 'border border-0'
                },
                confirmButtonText: 'Okay',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCancelButton: false,
                showCloseButton: false,
                focusConfirm: false,
                // allowHtml: true,
            });

            $(".swal2-modal").css('background-color', '#F2A65F');
        }
    </script>

</body>

</html>
