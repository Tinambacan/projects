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


    @if (session('status') === 'warning')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function warningModalLogin(message) {
                    Swal.fire({
                        icon: 'warning',
                        title: "<h5 style='color:black'>" + message + "</h5>",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
                warningModalLogin("{{ session('message') }}");
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
                <div class="mb-5 mt-5 drop-shadow-md flex justify-between items-center">

                    <div class="flex gap-2 text-indigo-900 rounded-xl p-2 text-center font-bold hover:text-violet-500">
                        <i class="fa-solid fa-arrow-left my-auto"></i>
                        <a class="hover:underline"href="/">
                            Back
                        </a>
                    </div>
                    <div><a id="sign-Up"
                            class="bg-white text-indigo-900 rounded-xl p-2  text-center font-bold hover:bg-yellow-400"
                            href="/sign-up">
                            Activation Account
                        </a></div>

                </div>
                <div class="mx-auto flex justify-center">
                    <img class="" src="{{ URL('images/LogoPNG.png') }}" style="height: 10rem; width: 10rem;">
                </div>

                <div class="p-5">
                    <form action="/sign-in" method="POST">
                        @csrf
                        <div class="drop-shadow-md">
                            <label class="block  font-bold text-indigo-900">Student Number </label>
                            <input type="text" name="student_num" id="emailInput"
                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full lg:w-80 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Student Number" autocomplete="off" />
                            <span class="text-danger text-red-600">
                                @error('student_num')
                                    {{ $message }}
                                @enderror
                            </span>

                        </div>

                        <div class="mt-5 drop-shadow-md">
                            <label class="block font-bold text-indigo-900">Password</label>
                            <input type="password" name="password"
                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full lg:w-80 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Password" autocomplete="off" />

                            <i class="fa-solid fa-eye-slash" id="eye-icon"
                                style="font-size: 18px; color: #6b7280; position: absolute; right: 10px; top: 65%; transform: translateY(-50%);"></i>
                            <span class="text-danger text-red-600">
                                @error('password')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>


                        <a href="/student-forgot-pass"
                            class="flex  justify-end text-indigo-900 mt-4 hover:underline cursor-pointer underline"
                            id="send-email-btn"><strong>Forgot
                                your
                                password?</strong></a>
                        <div class="flex mt-8 justify-center drop-shadow-md">
                            <div class="flex flex-wrap text-center w-56">
                                <button type="submit" id="myLogin"
                                    class="btn text-indigo-900 btn-success bg-white p-2 border rounded-full hover:bg-orange-200 w-full lg:w-56 font-bold">Login</button>
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
        const login = document.getElementById("bcklogin");
        const sign = document.getElementById("sign-Up");

        const clickSound = document.getElementById("clickSound");

        // Function to play the click sound
        function playClickSound() {
            clickSound.play();
        }
        myLogin.addEventListener("click", playClickSound);
        // login.addEventListener("click", playClickSound);
        sign.addEventListener("click", playClickSound);

        function successModalLogin(message) {
            Swal.fire({
                icon: 'success',
                title: "<h5 style='color:black'>" + message + "</h5>",
                showConfirmButton: false,
                timer: 1500
            });
            // $(".swal2-modal").css('background-color', '#F2A65F');
        }

        // Use event delegation to capture the click event on a parent element
        $(document).on('click', '#myLogin', function(event) {
            event.preventDefault();
            const form = event.target.form;
            const formData = new FormData(form);

            $.ajax({
                url: '/sign-in',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        form.reset();
                        successModalLogin(response.message);
                        setTimeout(function() {
                            window.location.href = response.redirect_url;
                        }, 2000); // 2000 milliseconds (2 seconds) delay
                    } else if (response.status === 'error') {
                        form.reset();
                        errorModal(response.message);
                    } else {
                        console.error('Invalid response format:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    // Handle AJAX errors here
                }
            });
        });

        // function errorModal(messages) {
        //     var messageList =
        //         "<ul style='list-style-type: disc; color: white; font-size: 20px; font-weight: bold;'>"; // Start an unordered list

        //     for (var i = 0; i < messages.length; i++) {
        //         messageList += "<li>" + messages[i] + "</li>"; // Create list items
        //     }

        //     messageList += "</ul>"; // Close the unordered list

        //     Swal.fire({
        //         iconHtml: '<img src="{{ URL('images/Error.png') }}" class="">',
        //         // title: "Error",
        //         html: messageList,
        //         customClass: {
        //             confirmButton: 'btn-black-text btn-white-background', // Customize confirm button
        //             icon: 'border border-0', // Customize icon style
        //         },
        //         confirmButtonText: 'Okay',
        //         allowOutsideClick: false,
        //         allowEscapeKey: false,
        //         showCancelButton: false,
        //         showCloseButton: false,
        //         focusConfirm: false,
        //     });

        //     $(".swal2-modal").css('background-color', '#F2A65F');
        // }


        function errorModal(messages) {
            // Join the messages with line breaks
            // const message = messages.join('<br>');

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

        const passwordInput = document.querySelector('input[name="password"]');
        const eyeIcon = document.getElementById("eye-icon");

        eyeIcon.addEventListener("click", function() {
            // Toggle the password visibility and the eye icon class
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        });
    </script>

</body>

</html>
