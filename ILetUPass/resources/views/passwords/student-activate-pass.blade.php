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
    <title>Change Password</title>
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
                    <a id="sign-Up" class=" text-indigo-900 rounded-xl p-2  text-center font-bold hover:bg-yellow-400"
                        href="/student-login">
                        <i class="fa-solid fa-arrow-left my-auto"></i>
                    </a>
                </div>
                <div class="flex justify-center items-center flex-col">
                    {{-- <img class="" src="{{ URL('images/LogoPNG.png') }}" style="height: 10rem; width: 10rem;"> --}}
                    <h1 class="text-indigo-900 font-bold text-xl mb-2">Change Password</h1>
                    <h3 class="text-indigo-900 font-bold mb-2 text-center"> We
                        are excited to welcome you to I LET U PASS.<br> To ensure the security of your account<br> and
                        to
                        complete the activation process,<br> we
                        require you to change<br> your initial password.</h3>
                </div>

                <div class="p-5">
                    <form action="/change-pass" method="POST">
                        @csrf
                        <div class="drop-shadow-md">
                            <label class="block font-bold text-indigo-900">Student Number</label>

                            <input type="hidden" name="student-num" id="emailInput" value="" />
                            <div id="displayStudentNum"
                                class="border border-gray-300 bg-white text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full lg:w-80 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <!-- This will display the student number to the user -->
                            </div>
                        </div>

                        <div class="drop-shadow-md">
                            <label class="block  font-bold text-indigo-900">New Password </label>
                            <input type="password" name="password" id="password"
                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full lg:w-80 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Enter your password" autocomplete="off" />
                            <i class="fa-solid fa-eye-slash" id="eye-icon"
                                style="font-size: 18px; color: #6b7280; position: absolute; right: 15px; top: 65%; transform: translateY(-50%);"></i>
                            <span class="text-danger text-red-600">
                                @error('password')
                                    {{ $message }}
                                @enderror
                            </span>

                        </div>
                        <div class="drop-shadow-md">
                            <label class="block  font-bold text-indigo-900">Confirm Password</label>
                            <input type="password" name="confirm-password" id="confirm-password"
                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full lg:w-80 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Confirm your password" autocomplete="off" />
                            <i class="fa-solid fa-eye-slash" id="eye-iconTwo"
                                style="font-size: 18px; color: #6b7280; position: absolute; right: 15px; top: 65%; transform: translateY(-50%);"></i>
                            <span class="text-danger text-red-600">
                                @error('confirm-password')
                                    {{ $message }}
                                @enderror
                            </span>

                        </div>
                        <div class="flex mt-8 justify-center drop-shadow-md">
                            <div class="flex flex-wrap text-center w-56">
                                <button type="submit" id="myLogin"
                                    class="btn text-indigo-900 btn-success bg-white p-2 border rounded-full hover:bg-orange-200 w-full lg:w-56 font-bold">Change
                                    Password</button>
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
        const urlParams = new URLSearchParams(window.location.search);

        // Get the value of the 'student_num' parameter from the URL
        const studentNum = urlParams.get('student_num');
        const displayStudentNum = document.getElementById('displayStudentNum');
        // Find the input element by its id
        const emailInput = document.getElementById('emailInput');

        // Set the value of the input field to the student number
        if (studentNum) {
            emailInput.value = studentNum;
            displayStudentNum.textContent = studentNum;
        }



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
                url: '/change-pass',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        form.reset();
                        successModalSend(response.message);
                        setTimeout(function() {
                            window.location.href = response.redirect_url;
                        }, 2000); // 2000 milliseconds (2 seconds) delay
                    } else {
                        form.reset();
                        errorModal(response.message);

                        if (response.errors) {
                            var errors = response.errors;

                            if (errors.password) {
                                $('input[name="password"]').addClass('error-border');
                                $('span3.text-danger').html(errors.password[0]);
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
        const passwordInput = document.querySelector('input[name="password"]');
        const eyeIcon = document.getElementById("eye-icon");
        const eyeIcon2 = document.getElementById("eye-iconTwo");
        const passwordInputTwo  = document.getElementById("confirm-password");

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
        eyeIcon2.addEventListener("click", function() {
            // Toggle the password visibility and the eye icon class
            if (passwordInputTwo.type === 'password') {
                passwordInputTwo.type = 'text';
                eyeIcon2.classList.remove('fa-eye-slash');
                eyeIcon2.classList.add('fa-eye');
            } else {
                passwordInputTwo.type = 'password';
                eyeIcon2.classList.remove('fa-eye');
                eyeIcon2.classList.add('fa-eye-slash');
            }
        });
    </script>

</body>

</html>
