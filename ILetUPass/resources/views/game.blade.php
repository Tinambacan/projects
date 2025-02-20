@php
    $loginID = session('login_ID');
    $roleNum = session('role');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    @vite('resources/fontawesome/css/all.min.css')
    @vite('resources/css/app.css')
    @vite('resources/js/jquery-3-7-1.js')
    @vite('resources/js/app.js')
    @vite('resources/js/chart.js')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Game</title>
    <link rel="icon" type="image/x-icon" href="/images/LogoPNG.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>



<body class="overflow-hidden">
    <audio id="audioElement" autoplay loop style="display: none;">
        <source src="{{ URL('music/education.mp3') }}" type="audio/mpeg">
    </audio>
    <div class="">
        <div class="flex flex-col flex-1  w-full relative">
            {{-- Side Bar --}}
            <div class="justify-end flex">
                <div class="absolute z-20" id="side-bar">
                    <x-sidebar :info_students="$info_students" />
                </div>
            </div>
            {{-- Id of the Content --}}
            <div id="div-game"
                class="z-10 bg-gradient-to-tr from-indigo-200 via-red-200 to-yellow-100 min-h-screen w-full">

            </div>
        </div>
    </div>
</body>

</html>
<script type="module"></script>
<script>
    localStorage.setItem('numberQuestion', '0');
    document.addEventListener('DOMContentLoaded', function() {
        const page = localStorage.getItem('selected-Game')
        console.log(page);
        if (page == "0") {
            Subjects();
        } else if (page == "1") {
            Difficulty();
        } else if (page == "2") {
            Question();
        } else if (page == "3") {
            Score();
        } else if (page == "4") {
            Accounts();
        } else if (page == "5") {
            ManageQuesAns();
        } else if (page == "6") {
            ManageStudInfo();
        } else {
            Subjects();
        }
    });

    if (roleNum === 1) {
        buttonShowStud.addEventListener("click", playClickSound);
        buttonHideStud.addEventListener("click", playClickSound);
        modalAcc();


        function Subjects() {
            localStorage.setItem('selected-Game', 0)
            $.ajax({
                url: '/display-subjects',
                type: 'GET',
                dataType: 'json',
                complete: function(data) {
                    $("#div-game").html(data.responseText);
                },
            });
        }

    }
    if (roleNum === 2) {
        buttonShowProf.addEventListener("click", playClickSound);
        buttonHideProf.addEventListener("click", playClickSound);
        // modalAcc();

        const profDash = document.getElementById("buttonDashboard");
        const profStudInfo = document.getElementById("studInfo");

        function Subjects() {
            localStorage.setItem('selected-Game', 0)
            $.ajax({
                url: '/display-subjects',
                type: 'GET',
                dataType: 'json',
                complete: function(data) {
                    $("#div-game").html(data.responseText);
                },
            });

            profDash.classList.add("text-orange-500");
            profStudInfo.classList.remove("text-orange-500");

            menuContainerProf.classList.add("hidden");
            buttonHideProf.style.display = "none";
            buttonShowProf.style.display = "block";
        }

        function ManageStudInfo() {

            localStorage.setItem('selected-Game', 6)
            $.ajax({
                url: '/search',
                type: 'GET',
                dataType: 'json',
                complete: function(data) {
                    $("#div-game").html(data.responseText);
                },
            });

            profDash.classList.remove("text-orange-500");
            profStudInfo.classList.add("text-orange-500");

            menuContainerProf.classList.add("hidden");
            buttonHideProf.style.display = "none";
            buttonShowProf.style.display = "block";
        }

    }

    if (roleNum === 3) {
        buttonShowAdmin.addEventListener("click", playClickSound);
        buttonHideAdmin.addEventListener("click", playClickSound);

        const userAcc = document.getElementById("admAcc");
        const dataMan = document.getElementById("buttonDataManagement");

        function Subjects() {

            localStorage.setItem('selected-Game', 0)
            $.ajax({
                url: '/display-subjects',
                type: 'GET',
                dataType: 'json',
                complete: function(data) {
                    $("#div-game").html(data.responseText);
                },
            });

            dataMan.classList.add("text-orange-500");
            userAcc.classList.remove("text-orange-500");

            menuContainerAdmin.classList.add("hidden");
            buttonHideAdmin.style.display = "none";
            buttonShowAdmin.style.display = "block";


        }
    }




    function Difficulty() {

        const myValue = sessionStorage.getItem('subjectId');


        localStorage.setItem('selected-Game', 1)
        $.ajax({
            url: '/display-difficulty/' + myValue,
            type: 'GET',
            dataType: 'json',
            complete: function(data) {
                $("#div-game").html(data.responseText);
            },
        });
    }

    function Question() {
        localStorage.setItem('selected-Game', 2)
        $("#side-bar").hide();
        const difficulty = sessionStorage.getItem('difficulty');
        console.log('Difficulty:', difficulty);

        var subjectId = sessionStorage.getItem('subjectId');
        console.log('Subject ID:', subjectId);

        var requestData = {
            difficulty: difficulty,
            subjectId: subjectId
        };
        $.ajax({
            url: '/pass-question',
            type: 'GET',
            data: requestData,
            dataType: 'json',
            complete: function(data) {
                $("#div-game").html(data.responseText);
            },
        });
    }

    function Score() {
        localStorage.setItem('selected-Game', 3)
        $.ajax({
            url: '/display-score-record',
            type: 'GET',
            dataType: 'json',
            complete: function(data) {
                $("#div-game").html(data.responseText);
            },
        });
    }

    function Accounts() {
        const userAcc = document.getElementById("admAcc");
        const dataMan = document.getElementById("buttonDataManagement");

        localStorage.setItem('selected-Game', 4)
        $.ajax({
            url: '/display-acc',
            type: 'GET',
            dataType: 'json',
            complete: function(data) {
                $("#div-game").html(data.responseText);
            },
        });
        userAcc.classList.add("text-orange-500");
        dataMan.classList.remove("text-orange-500");


        menuContainerAdmin.classList.add("hidden");
        buttonHideAdmin.style.display = "none";
        buttonShowAdmin.style.display = "block";
    }

    function ManageQuesAns() {

        const myValue = sessionStorage.getItem('subjectId');

        const userAcc = document.getElementById("admAcc");
        const dataMan = document.getElementById("buttonDataManagement");
        localStorage.setItem('selected-Game', 5)
        $.ajax({
            url: '/display-manage-ques-answ/' + myValue,
            type: 'GET',
            dataType: 'json',
            complete: function(data) {
                $("#div-game").html(data.responseText);
            },
        });
        userAcc.classList.remove("text-orange-500");
        dataMan.classList.add("text-orange-500");
    }

    var audio = document.getElementById('audioElement');
    // Get the volume range input
    var volumeRange = document.getElementById('volumeRange');
    // Get the volume icon
    var volumeIcon = document.getElementById('volumeIcon');
    // Initialize volume and icon
    volumeRange.value = localStorage.getItem('audioVolume') || 1;
    audio.volume = volumeRange.value;
    updateVolumeIcon();

    function adjustVolume(volume) {
        audio.volume = volume;
        localStorage.setItem('audioVolume', volume);
        updateVolumeIcon();
    }

    function updateVolumeIcon() {
        if (audio.volume === 0) {
            volumeIcon.classList.remove('fa-volume-high');
            volumeIcon.classList.add('fa-volume-xmark');
            // Set local storage variable to 1 when muted
            localStorage.setItem('audio', 1);
        } else {
            volumeIcon.classList.remove('fa-volume-xmark');
            volumeIcon.classList.add('fa-volume-high');
            // Remove local storage variable when unmuted
            localStorage.removeItem('audio', 0);
        }
    }

    var clickEffect = document.getElementById('clickSound');
    // Get the volume range input
    var audioRange = document.getElementById('audioRange');
    // Get the volume icon
    var audioIcon = document.getElementById('audioIcon');
    // Initialize volume and icon
    audioRange.value = localStorage.getItem('clickEffect') || 1;
    clickEffect.volume = audioRange.value;
    updateAudioIcon();

    function adjustAudioVolume(volume) {
        clickEffect.volume = volume;
        localStorage.setItem('clickEffect', volume);
        updateAudioIcon();
    }

    function updateAudioIcon() {
        if (clickEffect.volume === 0) {
            audioIcon.classList.remove('fa-volume-high');
            audioIcon.classList.add('fa-volume-xmark');
            // Set local storage variable to 1 when muted
            localStorage.setItem('audioEffect', 1);
        } else {
            audioIcon.classList.remove('fa-volume-xmark');
            audioIcon.classList.add('fa-volume-high');
            // Remove local storage variable when unmuted
            localStorage.removeItem('audioEffect', 0);
        }
    }

    var genEd = document.getElementById('genEd');

    function playClickSound() {
        clickSound.play();
    }

    var roleNum = @json($roleNum); // Pass the PHP value to JavaScript
    console.log('Role:', roleNum);


    modalSound();
</script>

</html>
