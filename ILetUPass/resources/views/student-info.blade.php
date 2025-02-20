<!DOCTYPE html>
<html lang="en">

<head>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    @vite('resources/js/jquery.dataTables.min.js')
    @vite('resources/css/jquery.dataTables.min.css')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Document</title>
    <style>
        .custom-purple {
            background-color: rgb(183, 147, 231);
            color: white;
        }
    </style>
</head>

<body>
    <div class="min-h-screen  items-center  p-3 justify-center flex animate-fade-in-down">
        <div class=" w-full mx-auto p-6  max-w-3xl space-y-4">

            <div class="flex items-center justify-center mb-5 gap-5">
                <div>
                    <img class="mx-auto animate-fade-in-down" src="{{ URL('images/StudGirl.png') }}"
                        style="height: 8rem; width: 8rem;">
                </div>
                <div>
                    <img class="mx-auto animate-fade-in-down" src="{{ URL('images/StudBoy.png') }}"
                        style="height: 8rem; width: 8rem;">
                </div>
            </div>

            <h2 class=" ml-4 font-bold text-4xl  text-indigo-800 text-shadow-[0_4px_5px_#808080] flex justify-center">
                Student Information Record
            </h2>
            <div class="w-full p-4 bg-white rounded-md shadow-md max-w-3xl mx-auto">
                <div class="relative">
                    <form method="POST" action="{{ route('student.search.submit') }}" id="search-form">
                        @csrf
                        <input type="text" name="stud_num"
                            class=" border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            placeholder="Student Number" autocomplete="off" />
                        <i
                            class="fa-solid fa-magnifying-glass absolute top-1/2 transform -translate-y-1/2 right-3  text-2xl text-indigo-800"></i>
                    </form>
                </div>
            </div>
            {{-- <h2 class=" ml-4 font-bold text-3xl  text-gray-300 items-center flex justify-center pt-28">
                    Search a Student
                </h2> --}}
            <!-- Add a container to display search results -->
            <div class=" w-full bg-white rounded-md shadow-md max-w-3xl mx-auto h-72">
                <div id="search-results" class="hidden">

                </div>

            </div>
        </div>

    </div>
    {{-- student detail --}}
    <div id="modal-student" class="modal-question hidden fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
            <div
                class="animate-fade-in-down inline-block align-bottom bg-fuchsia-50 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="p-2">
                    <div class="flex justify-end">
                        <i id="student-close-add-ques"
                            class=" fa-solid fa-x text-gray hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl">
                        </i>
                    </div>
                </div>
                <div class="text-center flex flex-col items-center justify-center mb-5">
                    <img id="image" class="animate-fade-in-up rounded-full border-4 border-yellow-400"
                        style="height: 5rem; width: 5rem;">
                    <h1 class="text-2xl text-shadow-[0_4px_5px_#808080] text-black font-bold mb-3" value="Student Name">
                    </h1>
                    <input type="text" value="Student Number"
                        class="custom-purple text-center shadow-md border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-1 w-11/12 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        readonly>
                    <div class="flex mt-3 mb-3 w-11/12">
                        <input type="text" value="Subject"
                            class="custom-purple text-center shadow-md border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-1 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            readonly>
                        <input type="text" value="Level"
                            class="custom-purple text-center shadow-md border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-1 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            readonly>
                    </div>
                    <input type="text" value="Email"
                        class="custom-purple text-center shadow-md border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-1 w-11/12 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        readonly>
                    <div class="flex mt-3 w-11/12">
                        <input type="text" value="Score"
                            class="custom-purple text-center shadow-md border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-1 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            readonly>
                        <input type="text" value="Date"
                            class="custom-purple text-center shadow-md border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-1 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            readonly>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>

<script>
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
        });

        $(".swal2-modal").css('background-color', '#F2A65F');
    }

    $(document).ready(function() {
        $("#search-form").submit(function(e) {
            e.preventDefault(); // Prevent the form from submitting normally

            var studNum = $("input[name='stud_num']").val();

            // Get the CSRF token value from the meta tag
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Send an AJAX request to the server with the CSRF token included in the headers
            $.ajax({
                type: "POST",
                url: "{{ route('student.search.submit') }}",
                data: {
                    stud_num: studNum
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the headers
                },
                success: function(data) {
                    // Check if the user(s) exist
                    if (data.exists) {
                        // Clear any previous data
                        $("#search-results").empty();
                        var usersContainer = $("<div class='overflow-y-auto h-72 p-5'></div>");
                        $("#search-results").append(usersContainer);

                        // Loop through the array of users and add their information to the HTML
                        for (var i = 0; i < data.users.length; i++) {
                            var user = data.users[i];
                            var userContainer = $(
                                "<div class='flex  gap-2 userContainer cursor-pointer hover:bg-gray-200 p-4 rounded-lg animate-fade-in-up'>");

                            // Create an image element for the user's photo
                            var image = $(
                                "<img class=' rounded-full border-4 border-yellow-400'>"
                                );
                            image.attr("src", user.profile_photo_path);
                            image.css({
                                "height": "7rem",
                                "width": "7rem",
                                "background": "#F8D086"
                            });

                            // Create a container for user information
                            var infoContainer = $(
                                "<div class='flex flex-col ml-3 mt-3 clickable-name'>");

                            // Add user information to the info container and store user data in the data attribute
                            infoContainer.data('user', user);
                            infoContainer.append(
                                "<h1 class='text-2xl uppercase font-bold'>" + user
                                .first_name + " " + user.last_name + "</h1>");
                            infoContainer.append("<h3>" + user.student_num + "</h3>");
                            infoContainer.append("<h3>" + user.subject_name + "</h3");
                            infoContainer.append("<h3 class='hidden'>" + user.level +
                                "</h3>");
                            infoContainer.append("<h3 class='hidden'>" + user.score +
                                "</h3>");
                            infoContainer.append("<h3 class='hidden'>" + user.email +
                                "</h3>");
                            infoContainer.append("<h3 class='hidden'>" + user.created_at +
                                "</h3>");

                            // Append the image and info containers to the user container
                            userContainer.append(image);
                            userContainer.append(infoContainer);

                            // Append the user container to the usersContainer
                            usersContainer.append(userContainer);

                            // Attach the click event to clickable-name within each userContainer
                            infoContainer.on("click", function() {
                                var userData = $(this).data(
                                'user'); // Get the user data
                                studentModal(userData);
                            });
                        }

                        // Show the search results container
                        $("#search-results").removeClass("hidden");
                    } else {
                        // Handle the case where no users are found
                        errorModal("User not found");
                    }
                }

            });
        });
    });

    function formatDate(dateString) {
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        const formattedDate = new Date(dateString).toLocaleDateString(undefined, options);
        return formattedDate;
    }

    function studentModal(userData) {
        console.log("studentModal function called"); // Add this line
        const studentDetail = document.querySelector("#modal-student");
        const form = document.querySelector('#student-question-form');
        const span = document.querySelector('#student-close-add-ques');
        const cancelModalButton = document.getElementById('student-cancel-add-ques');

        studentDetail.classList.remove('hidden'); // Remove the 'hidden' class to show the modal
        $("#side-bar").hide();

        // Loop through the userData object and log its properties
        for (var prop in userData) {
            if (userData.hasOwnProperty(prop)) {
                console.log(prop + ": " + userData[prop]);
            }
        }

        const studentNameInput = document.querySelector("#modal-student #image");
        const studentName = document.querySelector("#modal-student h1");
        studentName.textContent = userData.first_name + ' ' + userData.last_name;

        const studentNumberInput = document.querySelector("#modal-student input[value='Student Number']");
        const subjectInput = document.querySelector("#modal-student input[value='Subject']");
        const levelInput = document.querySelector("#modal-student input[value='Level']");
        const emailInput = document.querySelector("#modal-student input[value='Email']");
        const scoreInput = document.querySelector("#modal-student input[value='Score']");
        const dateInput = document.querySelector("#modal-student input[value='Date']");
        const formattedDate = formatDate(userData.created_at);

        dateInput.value = formattedDate;

        studentNameInput.src = userData.profile_photo_path;
        studentNumberInput.value = userData.student_num;
        subjectInput.value = userData.subject_name;
        levelInput.value = userData.level;
        emailInput.value = userData.email;
        scoreInput.value = userData.score;

        function closeModal() {
            $("#side-bar").show();
            studentDetail.classList.add('hidden');
        }

        span.addEventListener('click', closeModal);
        cancelModalButton.addEventListener('click', closeModal);
        cancelModalButton.addEventListener('click', playClickSound);
    }
</script>
