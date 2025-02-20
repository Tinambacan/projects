@php
    $loginID = session('login_ID');
    $roleNum = session('role');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/x-icon" href="/images/LogoPNG.png">
    @vite('resources/fontawesome/css/all.min.css')
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('resources/js/jquery-3-7-1.js')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>I LET U PASS</title>

</head>

<style>
    #boardBg {
        background-image: url("images/BoardLogo.png");
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        transition: ease-in;
    }
</style>

<body id="boardBg" class="overflow-hidden bg-amber-300">
    <audio id="audioElement" autoplay loop style="display: none;">
        <source src="{{ URL('music/education.mp3') }}" type="audio/mpeg">
    </audio>
    <div class="">
        <div class="flex flex-col flex-1  w-full relative">
            {{-- Side Bar --}}
            <div class="justify-end flex">
                <div class="z-20 absolute">
                    <x-sidebar :info_students="$info_students"/>
                </div>
            </div>
            {{-- Id of the Content --}}
            <div id="div-student" class="z-10">

            </div>
        </div>
    </div>
</body>

</html>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const page = localStorage.getItem('selected-SideBar')
        console.log(page);
        if (page == "0") {
            Start();
        } else {
            Start();
        }
    });


    function Start() {
        localStorage.removeItem("selected-Game");
        localStorage.setItem('selected-SideBar', 0)
        $.ajax({
            url: '/display-start',
            type: 'GET',
            dataType: 'json',
            complete: function(data) {
                $("#div-student").html(data.responseText);
            },
        });
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


    // const startLink = document.getElementById("start_link");
    var clickSound = document.getElementById("clickSound");
    // Function to play the click sound
    function playClickSound() {
        clickSound.play();
    }
    if (roleNum === 1) {
        buttonShowStud.addEventListener("click", playClickSound);
        buttonHideStud.addEventListener("click", playClickSound);
        modalAcc();

    }
    if (roleNum === 2) {
        buttonShowProf.addEventListener("click", playClickSound);
        buttonHideProf.addEventListener("click", playClickSound);
        modalAcc();

    }

    if (roleNum === 3) {
        buttonShowAdmin.addEventListener("click", playClickSound);
        buttonHideAdmin.addEventListener("click", playClickSound);
    }

    modalSound();

    var loginID = @json($loginID); // Pass the PHP value to JavaScript
    console.log('Login ID:', loginID);

    var roleNum = @json($roleNum); // Pass the PHP value to JavaScript
    console.log('Role:', roleNum);
</script>
