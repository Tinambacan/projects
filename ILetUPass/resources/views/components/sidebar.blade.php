@php
    $loginID = session('login_ID');
    $roleNum = session('role');
@endphp
@props(['info_students'])

<!DOCTYPE html>
<html lang="en">

<head>
    @vite('resources/fontawesome/css/all.min.css')
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div class="justify-end flex">

        @if ($roleNum == 1)
            <div class="mr-2">
                <img id="buttonShowStud" class="cursor-pointer animate-fade-in-right"
                    src="{{ URL('images/MenuIcon.png') }}" style="height: 3.5rem; width: 3.5rem;">
            </div>
            <div class="w-96 rounded-xl bg-orange-200 bg-opacity-70 hidden animate-fade-in-left">
                <div class="flex gap-2 h-screen flex-col ml-8 md:ml-5 ">
                    <div class="flex flex-row p-2">
                        <img id="buttonHideStud" class="cursor-pointer  animate-fade-in-left"
                            src="{{ URL('images/MenuIcon.png') }}" style="height: 3.5rem; width: 3.5rem;">
                        <h1 class="mt-2 text-4xl font-bold text-white ml-2"> MENU</h1>
                    </div>

                    <div class="flex flex-col space-y-10  justify-center ml-3">
                        <button id="buttonAccount"
                            class="flex flex-row hover:text-orange-400 hover:underline text-white items-center"><img
                                class="cursor-pointer"src="{{ URL('images/account.png') }}"
                                style="height: 4rem; width: 4rem;"><label
                                class="ml-2 text-4xl cursor-pointer">Profile</label>
                        </button>
                        <button id="buttonHome" onclick="HomePage()"
                            class="flex flex-row hover:text-orange-400 hover:underline text-white items-center"><img
                                class="cursor-pointer" src="{{ URL('images/home.png') }}"
                                style="height: 4rem; width: 4rem;"><label
                                class="ml-2 text-4xl cursor-pointer">Home</label>
                        </button>
                        <button id="buttonSound"
                            class="flex flex-row hover:text-orange-400 hover:underline text-white items-center"><img
                                class="cursor-pointer" src="{{ URL('images/speaker.png') }}"
                                style="height: 4rem; width: 4rem;"><label class=" ml-2 text-4xl cursor-pointer"> Sound
                                Settings</label> </button>
                        <a href="/logout"
                            class="flex flex-row hover:text-orange-400 hover:underline text-white items-center"><img
                                class="cursor-pointer" src="{{ URL('images/logout.png') }}"
                                style="height: 4rem; width: 4rem;"><label
                                class="ml-2 text-4xl cursor-pointer text-shadow-[0_4px_5px_#808080]"
                                onclick="logout()">Logout</label>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Account --}}
            <div class="modal-acc hidden fixed z-10 inset-0 min-h-screen">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                        <div class="absolute inset-0 bg-black opacity-75"></div>
                    </div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
                    <div
                        class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                        <div class="p-2">
                            <div class="flex justify-end">
                                {{-- <button type="button" id="edit-stud" onclick="editStud()"
                            class=" font-bold text-indigo-900  inline-flex items-center px-5 border border-gray-300 shadow-sm text-sm rounded-md  bg-white hover:text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Edit
                        </button> --}}
                                <i id="cancel-create1"
                                    class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl">
                                </i>
                            </div>
                        </div>
                        <div class="flex justify-center items-center mx-auto px-5">
                            <form id="edit-stud-form" method="POST" action="#">
                                @csrf

                                <div class="flex items-center justify-center mb-5">
                                    <img class="mx-auto rounded-full  animate-fade-in-down outline-4 outline-gray-700 outline-dotted"
                                        src="{{ asset('avatars/' . $info_students->profile_photo_path) }}"
                                        style="height: 5rem; width: 5rem;">
                                </div>
                                <div class="my-2 flex md:flex-row md:items-center items-start gap-2 flex-col">
                                    <label for="stud_num" class="block  font-bold text-indigo-900">Student Number:
                                    </label>
                                    <input type="text" name="stud_num"
                                        class=" border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Student Number" autocomplete="off" required disabled
                                        value="{{ $info_students->student_num }}" />
                                    <span3 class="text-danger text-red-600">
                                        @error('stud_num')
                                            {{ $message }}
                                        @enderror
                                    </span3>
                                </div>
                                <div class="my-2 flex md:flex-row md:items-center items-start gap-2 flex-col">
                                    <label for="fn_nm" class="block  font-bold text-indigo-900">First Name:</label>
                                    <input type="text" name="fn_nm"
                                        class="md:ml-10 ml-0 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="First Name" autocomplete="off" required disabled
                                        value="{{ $info_students->first_name }}" />
                                    <span1 class="text-danger text-red-600">
                                        @error('fn_nm')
                                            {{ $message }}
                                        @enderror
                                    </span1>
                                </div>
                                <div class="my-2 flex md:flex-row md:items-center items-start gap-2 flex-col">
                                    <label for="md_nm" class="block  font-bold text-indigo-900">Middle Name: </label>
                                    <input type="text" name="md_nm"
                                        class=" md:ml-5 ml-0 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Middle Name" autocomplete="off" disabled
                                        value="{{ $info_students->middle_name }}" />
                                    <span class="text-danger text-red-600">
                                        @error('md_nm')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div class="my-2 flex md:flex-row md:items-center items-start gap-2 flex-col">
                                    <label for="ls_nm" class="block  font-bold text-indigo-900">Last Name: </label>
                                    <input type="text" name="ls_nm"
                                        class="md:ml-10 ml-0 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Last Name" autocomplete="off" required disabled
                                        value="{{ $info_students->last_name }}" />
                                    <span2 class="text-danger text-red-600">
                                        @error('ls_nm')
                                            {{ $message }}
                                        @enderror
                                    </span2>
                                </div>
                                <div class="my-2 flex md:flex-row md:items-center items-start gap-2 flex-col">
                                    <label for="email" class="block  font-bold text-indigo-900">Email: </label>
                                    <input type="text" name="email"
                                        class=" md:ml-20 ml-0 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Email" autocomplete="off" required disabled
                                        value="{{ $info_students->email }}" />
                                    <span3 class="text-danger text-red-600">
                                        @error('email')
                                            {{ $message }}
                                        @enderror
                                    </span3>
                                </div>


                                {{-- <div class="mt-2 py-4 flex justify-between gap-2">
                                    <button type="button" id="cancel-add-stud"
                                        class=" font-bold text-indigo-900 ml-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm rounded-md  bg-white hover:text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                        Cancel
                                    </button>
                                    <button type="button" 
                                        class=" font-bold text-indigo-900 inline-flex items-center px-4 py-2 border border-transparent text-sm rounded-md bg-white hover:text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Okay
                                    </button>
                                </div> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- If Prof --}}
        @elseif($roleNum == 2)
            <div class="mr-2">
                <img id="buttonShowProf" class="cursor-pointer animate-fade-in-right"
                    src="{{ URL('images/MenuIcon.png') }}" style="height: 3.5rem; width: 3.5rem;">
            </div>
            <div class="w-96 rounded-xl bg-orange-200 bg-opacity-70 hidden animate-fade-in-left">
                <div class="flex gap-2 h-screen flex-col ml-8 md:ml-5 ">
                    <div class="flex flex-row p-2">
                        <img id="buttonHideProf" class="cursor-pointer  animate-fade-in-left"
                            src="{{ URL('images/MenuIcon.png') }}" style="height: 3.5rem; width: 3.5rem;">
                        <h1 class="mt-2 text-4xl font-bold text-white ml-2"> MENU</h1>
                    </div>

                    <div class="flex flex-col space-y-10  justify-center ml-3">
                        <a class="flex flex-row hover:text-orange-400 hover:underline text-white"><img
                                class="cursor-pointer" src="{{ URL('images/data.png') }}"
                                style="height: 4rem; width: 4rem;">
                            <label id="buttonDashboard"
                                class="my-auto ml-2 text-4xl cursor-pointer text-left text-shadow-[0_4px_5px_#808080]"
                                onclick="Subjects()">Dashboard</label>
                        </a>
                        <a class="flex flex-row hover:text-orange-400 hover:underline text-white items-center"><img
                                class="cursor-pointer"src="{{ URL('images/account.png') }}"
                                style="height: 4rem; width: 4rem;">
                            <label id="studInfo"
                                class="ml-2 text-4xl cursor-pointer text-left text-shadow-[0_4px_5px_#808080]"
                                onclick="ManageStudInfo()">Student Information</label>
                        </a>
                        <button id="buttonSound"
                            class="flex flex-row hover:text-orange-400 hover:underline text-white items-center"><img
                                class="cursor-pointer" src="{{ URL('images/speaker.png') }}"
                                style="height: 4rem; width: 4rem;"><label
                                class=" ml-2 text-4xl cursor-pointer text-shadow-[0_4px_5px_#808080]">
                                Sound Settings</label> </button>
                        <a href="/logout"
                            class="flex flex-row hover:text-orange-400 hover:underline text-white items-center"><img
                                class="cursor-pointer" src="{{ URL('images/logout.png') }}"
                                style="height: 4rem; width: 4rem;"><label
                                class="ml-2 text-4xl cursor-pointer text-shadow-[0_4px_5px_#808080]"
                                onclick="logout()">Logout</label>
                        </a>
                    </div>
                </div>
            </div>

            {{-- If Admin --}}
        @elseif($roleNum == 3)
            <div class="mr-2">
                <img id="buttonShowAdmin" class="cursor-pointer animate-fade-in-right"
                    src="{{ URL('images/MenuIcon.png') }}" style="height: 3.5rem; width: 3.5rem;">
            </div>
            <div class="w-96 rounded-xl bg-orange-200 bg-opacity-70 hidden animate-fade-in-left">
                <div class="flex gap-2 h-screen flex-col ml-8 md:ml-5 ">
                    <div class="flex flex-row p-2">
                        <img id="buttonHideAdmin" class="cursor-pointer  animate-fade-in-left"
                            src="{{ URL('images/MenuIcon.png') }}" style="height: 3.5rem; width: 3.5rem;">
                        <h1 class="mt-2 text-4xl font-bold text-white ml-2"> MENU</h1>
                    </div>

                    <div class="flex flex-col space-y-10  justify-center ml-3">
                        <a class="flex flex-row hover:text-orange-400 hover:underline text-white"><img
                                class="cursor-pointer" src="{{ URL('images/data.png') }}"
                                style="height: 4rem; width: 4rem;">
                            <label id="buttonDataManagement"
                                class="ml-2 text-4xl cursor-pointer text-left text-shadow-[0_4px_5px_#808080]"
                                onclick="Subjects()">Data Management</label>
                        </a>
                        <a class="flex flex-row hover:text-orange-400 hover:underline text-white items-center"><img
                                class="cursor-pointer"src="{{ URL('images/account.png') }}"
                                style="height: 4rem; width: 4rem;">
                            <label id="admAcc"
                                class="ml-2 text-4xl cursor-pointer text-left text-shadow-[0_4px_5px_#808080]"
                                onclick="Accounts()">Account Management</label>
                        </a>
                        <button id="buttonSound"
                            class="flex flex-row hover:text-orange-400 hover:underline text-white items-center"><img
                                class="cursor-pointer" src="{{ URL('images/speaker.png') }}"
                                style="height: 4rem; width: 4rem;"><label
                                class=" ml-2 text-4xl cursor-pointer text-shadow-[0_4px_5px_#808080]">
                                Sound Settings</label> </button>
                        <a href="/logout"
                            class="flex flex-row hover:text-orange-400 hover:underline text-white items-center"><img
                                class="cursor-pointer" src="{{ URL('images/logout.png') }}"
                                style="height: 4rem; width: 4rem;"><label
                                class="ml-2 text-4xl cursor-pointer text-shadow-[0_4px_5px_#808080]"
                                onclick="logout()">Logout</label>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-red-200 p-2 rounded-md mb-3">
                <h2 class="font-bold text-lg text-black">
                    No Subject Found</h2>
            </div>
        @endif
    </div>


    {{-- Sound Modal --}}
    <div class="modal-sound hidden fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="p-2">
                    <div class="flex justify-end">
                        <i id="cancel-create2"
                            class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl">
                        </i>
                    </div>
                </div>
                <div class="mx-5 mb-5">
                    <h1 class="text-white text-4xl text-center mb-5 font-bold text-shadow-[0_4px_5px_#808080]">
                        Sound
                        Settings</h1>

                    <div class="flex md:flex-row items-center mb-5 flex-col">
                        <h2 class="text-2xl font-semibold text-indigo-900">Music Background</h2>
                        <div class="py-3 my-auto">
                            <i id="volumeIcon" class="fa-solid fa-volume-high text-white fa-xl mx-4 mb-5"></i>
                            <input type="range" id="volumeRange" min="0" max="1" step="0.1"
                                value="1" oninput="adjustVolume(this.value)" class="bg-white">
                        </div>
                    </div>
                    <div class="flex md:flex-row items-center mb-5 flex-col">
                        <h2 class="text-2xl font-semibold text-indigo-900">Sound Effects</h2>
                        <div class="py-3 my-auto">
                            <i id="audioIcon" class="fa-solid fa-volume-high text-white fa-xl md:ml-16 mx-4 mr-5"></i>
                            <input type="range" id="audioRange" min="0" max="1" step="0.1"
                                value="1" oninput="adjustAudioVolume(this.value)" class="bg-white">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <audio id="clickSound" src="{{ URL('music/click.mp3') }}"></audio>

</body>

</html>

<script>
    // function editStud() {
    //     $("input[type='text']").prop("disabled", function(i, val) {
    //         return !val;
    //     });
    // }
    var roleNum = @json($roleNum); // Pass the PHP value to JavaScript
    console.log('Role:', roleNum);
    if (roleNum === 1) {
        var buttonShowStud = document.getElementById("buttonShowStud");
        var buttonHideStud = document.getElementById("buttonHideStud");
        var menuContainerStud = document.querySelector(".bg-orange-200 ");

        buttonShowStud.addEventListener("click", function() {
            menuContainerStud.classList.remove("hidden");
            buttonShowStud.style.display = "none";
            buttonHideStud.style.display = "block";
        });

        buttonHideStud.addEventListener("click", function() {
            menuContainerStud.classList.add("hidden");
            buttonHideStud.style.display = "none";
            buttonShowStud.style.display = "block";
        });

        function modalAcc() {
            const modal_acc = document.querySelector('.modal-acc');
            const form = document.querySelector('#importing-students-form');
            const btn = document.querySelector('#buttonAccount');
            const span = document.querySelector('#cancel-create1');
            const cancelModalButton = document.getElementById('cancel-create1');

            btn.addEventListener('click', function() {
                modal_acc.classList.remove('hidden');
            });

            function closeModal() {
                modal_acc.classList.add('hidden');
            }

            span.addEventListener('click', closeModal);
            cancelModalButton.addEventListener('click', closeModal);
            cancelModalButton.addEventListener('click', playClickSound);
            btn.addEventListener("click", playClickSound);
        }
    }

    if (roleNum === 2) {
        var buttonShowProf = document.getElementById("buttonShowProf");
        var buttonHideProf = document.getElementById("buttonHideProf");
        var menuContainerProf = document.querySelector(".bg-orange-200 ");

        buttonShowProf.addEventListener("click", function() {
            menuContainerProf.classList.remove("hidden");
            buttonShowProf.style.display = "none";
            buttonHideProf.style.display = "block";
        });

        buttonHideProf.addEventListener("click", function() {
            menuContainerProf.classList.add("hidden");
            buttonHideProf.style.display = "none";
            buttonShowProf.style.display = "block";
        });

        // function modalAcc() {
        //     const modal_acc = document.querySelector('.modal-acc');
        //     const form = document.querySelector('#importing-students-form');
        //     const btn = document.querySelector('#buttonAccount');
        //     const span = document.querySelector('#cancel-create');
        //     const cancelModalButton = document.getElementById('cancel-create1');

        //     btn.addEventListener('click', function() {
        //         modal_acc.classList.remove('hidden');
        //     });

        //     function closeModal() {
        //         modal_acc.classList.add('hidden');
        //     }

        //     span.addEventListener('click', closeModal);
        //     cancelModalButton.addEventListener('click', closeModal);
        //     cancelModalButton.addEventListener('click', playClickSound);
        //     btn.addEventListener("click", playClickSound);
        // }
    }


    if (roleNum === 3) {
        var buttonShowAdmin = document.getElementById("buttonShowAdmin");
        var buttonHideAdmin = document.getElementById("buttonHideAdmin");
        var menuContainerAdmin = document.querySelector(".bg-orange-200");

        buttonShowAdmin.addEventListener("click", function() {
            menuContainerAdmin.classList.remove("hidden");
            buttonShowAdmin.style.display = "none";
            buttonHideAdmin.style.display = "block";
        });

        buttonHideAdmin.addEventListener("click", function() {
            menuContainerAdmin.classList.add("hidden");
            buttonHideAdmin.style.display = "none";
            buttonShowAdmin.style.display = "block";
        });

    }

    function HomePage() {
        // Destroy the sessions
        sessionStorage.removeItem('difficulty');
        sessionStorage.removeItem('subjectId');

        // Redirect to the Subjects page
        Subjects();
        window.location.reload();
    }
    function modalSound() {
        const modal_sound = document.querySelector('.modal-sound');
        const btn = document.querySelector('#buttonSound');
        const cancelModalButton = document.getElementById('cancel-create2');

        btn.addEventListener('click', function() {
            modal_sound.classList.remove('hidden');
        });

        function closeModal() {
            modal_sound.classList.add('hidden');
        }
        cancelModalButton.addEventListener('click', closeModal);
        btn.addEventListener("click", playClickSound);
        cancelModalButton.addEventListener("click", playClickSound);
    }
</script>
<script>
    // document.getElementById('buttonLogout').addEventListener('click', function() {
    //     // Perform an AJAX request to the logout endpoint on the server
    //     // This assumes you have a route in your Laravel application that logs the user out
    //     // You can use the route helper function to generate the URL

    //     fetch("{{ route('logout') }}", {
    //             method: 'POST', // Use the appropriate HTTP method for your application
    //             headers: {
    //                 'X-CSRF-TOKEN': '{{ csrf_token() }}', // Include the CSRF token for security
    //             },
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.status === 'success') {
    //                 // Redirect to the desired page after logout
    //                 // window.location.href = data.redirect_url;
    //             } else {
    //                 // Handle the error, e.g., display an error message
    //                 console.error(data.message);
    //             }
    //         })
    //         .catch(error => {
    //             console.error("An error occurred during the logout process: " + error);
    //         });
    // });



    function logout() {
        localStorage.removeItem("selected-user-accounts");
        localStorage.removeItem("selected-Game");
        localStorage.removeItem("selected-SideBar");

        return true;
    }
</script>
