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
    <title>Sign Up</title>
    <link rel="icon" type="image/x-icon" href="/images/LogoPNG.png">
    {{-- <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script> --}}
</head>

<style>
    .board {
        width: 2000px;
        /* margin-left: 10rem; */
        object-fit: cover;
    }

    .icon-style {
        width: 100px;
        /* mix-blend-mode: multiply; */
        border: 0;
    }

    .btn-black-text {
        color: black !important;
    }

    .btn-white-background {
        background-color: white !important;
    }

    .error-border {
        border: 2px solid red;
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

<body id="board"class=" bg-purple-100 overflow-hidden">
    <audio autoplay style="display: none;">
        <source src="{{ URL('music/education.mp3') }}" type="audio/mpeg">
    </audio>

    <div class="flex flex-col lg:flex-row">
        <div class="p-3  min-h-screen">
            <div
                class=" lg:ml-32 animate-fade-in-right bg-gradient-to-tr from-indigo-200 via-red-200 to-yellow-100 rounded-2xl p-5  z-20 drop-shadow-2xl relative mt-0 sm:mt-16">

                <div id="bcklogin"
                    class="flex gap-2 text-indigo-900 rounded-xl p-2 text-center font-bold hover:text-violet-500 w-full">
                    <i class="fa-solid fa-arrow-left my-auto"></i>
                    <a class="hover:underline"href="/prof-login">
                        Back to Login
                    </a>
                </div>

                <div class="px-5 animate-fade-in-up1">
                    <form method="POST" action="/sent-prof-email">
                        @csrf
                        <h1 class="mt-10 mb-5 text-3xl font-semibold text-center text-indigo-900">Faculty Activation
                        </h1>
                        <div class="my-2">
                            <label for="email" class="block  font-bold text-indigo-900">Email: </label>
                            <input type="text" name="email"
                                class=" border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Email" autocomplete="off" required />
                            <span3 class="text-danger text-red-600">
                                @error('email')
                                    {{ $message }}
                                @enderror
                            </span3>
                        </div>
                        <div class="my-2">
                            <div class="captcha">
                                <label for="email" class="block  font-bold text-indigo-900">Captcha: </label>
                                <span
                                    class="border-gray-300 p-3 w-full flex justify-center">{!! captcha_img('flat') !!}</span>
                                <div class="flex">
                                    <input type="text" name="captcha"
                                        class=" border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-3/4 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Enter captcha" autocomplete="off" required />
                                    <button onclick="reloadCaptcha()" type="button"
                                        class=" right-10 absolute w-20 text-xl bg-white border-r-2 border-t border-gray-300 rounded-r-lg p-2 text-gray-600">&#x21bb;</button>
                                </div>
                            </div>
                            <span3 class="text-danger text-red-600">
                                @error('captcha')
                                    {{ $message }}
                                @enderror
                            </span3>
                        </div>

                        <div class="flex my-8 justify-center">
                            <div class="flex flex-wrap w-50 text-center">
                                <button type="submit" id="signUp"
                                    class=" text-indigo-900 bg-white p-2 border rounded-full  hover:bg-orange-200 w-56 font-bold">Activate</button>
                                <audio id="clickSound" src="{{ URL('music/click.mp3') }}"></audio>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <img id="loginPic"class="animate-fade-in-left  absolute" src="{{ URL('images/StudLogin.png') }}">
    </div>

    <script>
        function reloadCaptcha() {
            console.log('dsad');
            $.ajax({
                type: 'GET',
                url: '/reload-captcha',
                success: function(data) {
                    console.log("hi");
                    $(".captcha span").html(data.captcha)
                }
            })
        }
    </script>


    <script type="module">
        const login = document.getElementById("bcklogin");
        const clickSound = document.getElementById("clickSound");


        function playClickSound() {
            clickSound.play();
        }

        login.addEventListener("click", playClickSound);

        const signUpButton = document.getElementById("signUp");

        $("#signUp").click(function(event) {
            event.preventDefault();
            const form = event.target.form;
            const formData = new FormData(form);
            const emailInput = $('input[name="email"]');
            const currentEmailValue = emailInput.val();
            
            $.ajax({
                url: '/sent-prof-email',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        form.reset();
                        successModalSend(response.message);
                        location.reload();
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
                        emailInput.val(currentEmailValue);
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
            reloadCaptcha();
            $(".swal2-modal").css('background-color', '#F2A65F');
        }
    </script>

</body>

</html>
