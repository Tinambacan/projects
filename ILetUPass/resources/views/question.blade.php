@php
    $loginID = session('login_ID');
@endphp
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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @foreach ($questions as $question)
            {{ $question->subject->subject_name }}
        @break
    @endforeach
</title>
</head>


<style>
.clickable-choice {
    cursor: pointer;
    z-index: 100;
}




.ques2 {
    height: 6rem;
    width: 6rem;
    margin-left: 1rem;
    display: none;
}

.ques1 {
    display: block;
}

.resumeGame {
    height: 10rem;
    width: 10rem;
}

.restartGame {
    height: 10rem;
    width: 10rem;
}

.homeGame {
    height: 10rem;
    width: 10rem;
}

@media (max-width: 800px) {
    #pencil {
        height: 2rem;
        width: 3rem;
        display: none;
    }

    .ques1 {
        display: none;
    }

    .resumeGame {
        height: 5rem;
        width: 5rem;
    }

    .restartGame {
        height: 5rem;
        width: 5rem;
    }

    .homeGame {
        height: 5rem;
        width: 5rem;
    }

    #sideEndPic {
        display: none;
    }

    #topEndPic {
        display: block;
    }
}

#end-button {
    display: none;
}
</style>



<body>
<div class="flex justify-end p-4 gap-3 ">
    <div>
        <img id="buttonMusic" class="cursor-pointer" src="{{ URL('images/speaker.png') }}"
            style="height: 3rem; width: 3rem;">
    </div>
    <div>
        <img id="gamePause" class="cursor-pointer" src="{{ URL('images/PauButton.png') }}"
            style="height: 3rem; width: 3rem;">
    </div>
    <button id="startButton" class="hidden">Start</button>
</div>



<div class="flex z-10 absolute  md:ml-10 ml-5 pr-5">
    <div class="ml-0  absolute md:ml-36">
        <div id="countdownTime"
            class="p-2 w-24 z-20 rounded-full border-blue-900 border-4 bg-blue-300 text-center relative text-blue-900 font-bold md:absolute">
            01:00
        </div>
        <img id="pencil" class="opacity-60 z-10 relative ml-3" src="{{ URL('images/PencilUp.png') }}"
            style="height: 40rem; width: 5rem;">
    </div>

    <div class="flex md:ml-80 ml-28 w-full justify-between md:mr-56 mr-0 ">
        <div class="flex">
            <div class="mx-0 md:mx-96 md:mt-10">
                <img id="questionM" class="md:ml-0 md:absolute ques1" src="{{ URL('images/QuesM.png') }}"
                    style="height: 7rem;
                width: 6rem;">
            </div>
        </div>
        <div
            class="p-2 w-20 md:w-32 rounded-2xl bg-white text-center drop-shadow-xl text-blue-900 font-bold ml-32 md:ml-24 relative  md:mb-10 mb-56">
            <h2 id="question-number"></h2>
        </div>
    </div>
</div>


<div class="flex items-center justify-center z-10 ml-4 md:ml-80 relative md:mt-40 mt-20">
    <div class="flex flex-col">
        <div id="question-container" class="items-center md:mr-56">
            <div id="question-text"
                class="p-2 rounded-2xl text-blue-900 text-center text-3xl mb-2 md:mb-5 font-bold animate-fade-in-up1">

            </div>
            <div id="choices-container" class="grid grid-cols-1 md:grid-cols-2 gap-5 p-5">

            </div>
        </div>
    </div>
</div>

<div class="hidden fixed z-20 inset-0 overflow-hidden bg-gradient-to-tr from-indigo-200 via-red-200 to-yellow-100 min-h-screen md:pt-20 pt-10"
    id="total-score">
    <div class="flex items-center justify-center px-4 text-center sm:block sm:p-0 flex-col">
        <h1 class="text-blue-900 font-bold mb-5 md:mt-20 md:text-5xl text-4xl">TOTAL SCORE</h1>
        <div class="flex bg-white gap-5 p-5 rounded-lg shadow-2xl  items-center justify-center mx-48 flex-row">

            <div class="flex flex-col justify-center items-center gap-5">
                <div id="topEndPic" class="ml-5 hidden">
                    <img class=" drop-shadow-md" src="{{ URL('images/endpic.png') }}"
                        style="height: 8rem; width: 8rem;">
                </div>
                <div class="flex items-center space-x-3">
                    <svg id="star1" class="w-8 h-8 star" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 20">
                        <path
                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                    </svg>
                    <svg id="star2" class="w-8 h-8 star" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 20">
                        <path
                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                    </svg>
                    <svg id="star3" class="w-8 h-8 star" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 20">
                        <path
                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                    </svg>
                    <svg id="star4" class="w-8 h-8 star" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 20">
                        <path
                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                    </svg>
                    <svg id="star5" class="w-8 h-8 star" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 20">
                        <path
                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                    </svg>
                </div>
                <label class="text-3xl text-yellow-300 font-semibold" id="scoreQuiz"></label>
                <label class="text-xl text-gray-500 font-semibold">YOU'RE GOOD TO GO PRE-SERVICE
                    TEACHER</label>

            </div>
            <div id="sideEndPic" class="ml-5">
                <img class=" drop-shadow-md" src="{{ URL('images/endpic.png') }}"
                    style="height: 15rem; width: 15rem;">
            </div>
        </div>
        <div class=" flex flex-col mt-5 justify-center md:gap-10 gap-5 md:flex-row">
            <button id="retry-button"
                class="p-2 w-40 rounded-md  hover:bg-yellow-500 hover:text-white bg-white text-center drop-shadow-xl text-blue-900 font-bold cursor-pointer z-10"
                onclick="retryGame()">Retry Quiz</button>
            <button id="end-button"
                class="p-2 w-40 rounded-md  hover:bg-yellow-500 hover:text-white bg-white text-center drop-shadow-xl text-blue-900 font-bold cursor-pointer z-10"
                onclick="endGame()">Go to Home</button>
            <button id=""
                class="p-2 w-40 rounded-md  hover:bg-yellow-500 hover:text-white bg-white text-center drop-shadow-xl text-blue-900 font-bold cursor-pointer z-10"
                onclick="Score()">Score Record</button>
        </div>
    </div>
</div>



<audio id="clickSound" src="{{ URL('music/click.mp3') }}"></audio>
<audio id="beatSound" src="{{ URL('music/hearbeat.mp3') }}"></audio>
<audio id="failSound" src="{{ URL('music/fail.mp3') }}"></audio>

<div id="genEdModal" class="genEdModal hidden fixed z-20 inset-0 overflow-hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-orange-200 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
        <div
            class=" md:w-1/2  min-h-screen animate-fade-in-down inline-block align-bottom bg-transparent  transform transition-all  sm:align-middle sm:max-w-full p-5">
            <div class="w-full">
                <div class="justify-center flex">
                    <img class="w-full " src="{{ URL('images/pauseLogo.png') }}"
                        style="height: 20rem; width: 20rem;">
                </div>
                <div class=" flex justify-between md:flex-row flex-col md:items-start items-center mb-10">
                    <div class=" group items-center">
                        <img id="resumeGame" class="cursor-pointer resumeGame"
                            src="{{ URL('images/resume.png') }}">
                        <p class="hidden group-hover:block relative text-xl p-2 rounded shadow mt-2 z-10"
                            style="color: rgb(37, 150, 190)">Resume</p>
                    </div>

                    <div class=" group items-center my-10 md:my-0">
                        <img id="restartGame" class="cursor-pointer restartGame"
                            src="{{ URL('images/restart.png') }}">
                        <p class="hidden group-hover:block relative text-xl p-2 rounded shadow mt-2 z-10"
                            style="color: rgb(37, 150, 190)">Restart</p>
                    </div>
                    <div class="group items-center ">
                        <img id="homeGame" class="cursor-pointer homeGame"
                            src="{{ URL('images/pause_home.png') }}">
                        <p class="hidden group-hover:block relative text-xl p-2 rounded shadow mt-2 z-10"
                            style="color: rgb(37, 150, 190)">Home</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="countDownDiv" class="countDownDiv fixed z-20 inset-0 overflow-y-auto flex justify-center items-center">
    <div class="flex items-center justify-center min-h-screen px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-black opacity-75">
                <div class=" flex justify-center h-full items-center">
                    <p id="countdown" class="text-8xl text-white top-1/2">3</p>

                </div>
            </div>
        </div>
    </div>
</div>

<audio id="countSound1" src="{{ URL('music/count.mp3') }}"></audio>
<audio id="countSound2" src="{{ URL('music/count.mp3') }}"></audio>
<audio id="countSound3" src="{{ URL('music/count.mp3') }}"></audio>
<audio id="goSound" src="{{ URL('music/go.mp3') }}"></audio>


{{-- play again modal --}}
<div id="modal-play" class=" hidden modal-play  fixed z-10 inset-0 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-black opacity-50"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
        <div
            class="p-3 animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="flex justify-center items-center flex-col">
                <img id="timesUp" class="" src="{{ URL('images/TimesUp.png') }}"
                    style="height: 15rem; width: 15rem;">
                <h1 class="text-3xl text-white text-shadow-[0_4px_5px_#808080]">Play again?</h1>
                <div class="flex flex-row gap-5 my-10">
                    <button onclick="retryButton()"
                        class="p-2 bg-white rounded-md text-blue-900 shadow-xl hover:bg-yellow-500 hover:text-white">Retry</button>
                    <button onclick="cancelButton()"
                        class="p-2 bg-white rounded-md text-blue-900 shadow-xl hover:bg-yellow-500 hover:text-white">Cancel</button>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- answer explanation modal --}}
<div id="modal-explanation" class=" hidden modal-explanation fixed z-10 inset-0 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-black opacity-50"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
        <div
            class="p-3 animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="flex justify-center items-center flex-col">
                <img id="timesUp" class="" src="{{ URL('images/BulbPNG.png') }}"
                    style="height: 10rem; width: 10rem;">
                    <p class="text-3xl my-3" id="answer_checking">
                        
                    </p>
                    <p class="text-2xl text-blue-950 text-shadow-[0_4px_5px_#808080] my-3 mx-auto" id="question_ans">
                        
                    </p>
                <h1 class="text-2xl text-white text-shadow-[0_4px_5px_#808080]">Brief Explanation:</h1>

                <p class="mt-1 mb-5 text-xl text-white text-shadow-[0_4px_5px_#808080]" id="question_exp">

                </p>
                <button id="nextQues"
                    class="p-2 bg-white rounded-md text-blue-900 shadow-xl hover:bg-yellow-500 hover:text-white">Okay</button>
            </div>

        </div>
    </div>
</div>

</body>

</html>
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

{{-- kay airon --}}
<script>
    var modalPlay = document.getElementById('modal-play');
    let timer;
    let timerRunning = false;
    const countSound = document.getElementById("countSound");
    const goSound = document.getElementById("goSound");
    const retryBtn = document.getElementById("retry-button");
    const pauseButton = document.getElementById("gamePause");
    const resumeButton = document.getElementById("resumeGame");
    const countTimer = document.getElementById("countdownTime");
    const modal_pause = document.getElementById("genEdModal");
    const restartAgain = document.getElementById("restartGame");
    const home = document.getElementById("homeGame");


    function retryButton() {
        localStorage.setItem('numberQuestion', '0');
        modalPlay.classList.add('hidden');
        window.location.reload();
    }

    function retryGame() {
        localStorage.setItem('numberQuestion', '0');
        modalPlay.classList.add('hidden');
        window.location.reload();
    }

    function cancelButton() {
        window.location.href = "ILetYouPass";
        clickSound.play();

    }

    function gameMinute() {
        let remainingTime = 60; // Initial time in seconds
        let flickerInterval;
        // const modal_pause = document.querySelector('genEdModal');

        function updateDisplay() {
            const minutes = Math.floor(remainingTime / 60);
            const seconds = remainingTime % 60;
            countTimer.textContent =
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }

        function startTimer() {
            if (!timerRunning) {
                timerRunning = true;
                timer = setInterval(function() {
                    if (remainingTime <= 0) {
                        clearInterval(timer);
                        countTimer.textContent = "00:00";
                        timerRunning = false;
                        pauseButton.disabled = true;
                        resumeButton.disabled = true;
                        beatSound.pause();
                        audioElement.pause();
                        failSound.play();
                        modalPlay.classList.remove('hidden');
                    } else {
                        remainingTime--;
                        updateDisplay();
                        if (remainingTime <= 10) {
                            const countdownTime = document.getElementById("countdownTime");
                            flickerInterval = setInterval(function() {
                                countdownTime.style.color = (countdownTime.style.color === 'red') ?
                                    'initial' : 'red';
                            }, 500); //
                            beatSound.play();
                        } else {
                            const countdownTime = document.getElementById("countdownTime");
                            clearInterval(flickerInterval); // This will clear the flicker interval
                            countdownTime.style.color = 'initial'; // Reset the color to its initial state
                            beatSound.pause();
                        }
                    }
                }, 1000);
                pauseButton.disabled = false;
                resumeButton.disabled = true;
            }
        }

        function pauseTimer() {
            modal_pause.classList.remove('hidden');
            if (timerRunning) {
                clearInterval(timer);
                timerRunning = false;
                pauseButton.disabled = true;
                resumeButton.disabled = false;
            }
        }

        function resumeTimer() {
            modal_pause.classList.add('hidden');
            clearInterval(timer); // Clear the previous timer
            timerRunning = false; // Reset timerRunning
            const countdownTime = document.getElementById("countdownTime");
            clearInterval(flickerInterval); // This will clear the flicker interval
            countdownTime.style.color = 'initial'; // Reset the color to its initial state
            beatSound.pause();
            startTimer(); // Start a new timer
        }

        function restartTimer() {
            clearInterval(timer); // Clear the previous timer
            remainingTime = 60; // Reset remaining time to 60 seconds
            updateDisplay(); // Update the timer display

            // Set timerRunning to false and then start the timer
            timerRunning = false;
            startTimer();
        }

        function retryAgainGame() {
            localStorage.setItem('numberQuestion', '0');
            modalPlay.classList.add('hidden');
            window.location.reload();
        }

        function homePage() {
            sessionStorage.removeItem('difficulty');
            sessionStorage.removeItem('subjectId');
            // Redirect to the Subjects page
            Subjects();
            window.location.reload();
        }
        // Start the timer when the page loads
        startTimer();
        pauseButton.addEventListener("click", pauseTimer);
        resumeButton.addEventListener("click", resumeTimer);
        restartAgain.addEventListener("click", retryAgainGame);
        home.addEventListener("click", homePage);


        if (localStorageValue > "1") {
            console.log('pasok dito');
            clearInterval(timer);
            restartTimer();
        }
    }

    var localStorageValue = localStorage.getItem("numberQuestion");
    console.log("the page: " + localStorageValue);

    if (localStorageValue === "0") {
        let count = 3;

        function playCountSound(count) {
            const soundElement = document.getElementById(`countSound${count}`);
            if (soundElement) {
                soundElement.play();
            }
        }

        function updateCountdown() {
            if (count > 0) {
                playCountSound(count);

                document.getElementById("countdown").innerHTML = count;

            } else if (count === 0) {
                goSound.play();
                document.getElementById("countdown").innerHTML = "Go!";
            } else {
                document.getElementById("countdown").style.display = "none"; // Hide the text
                document.getElementById("countDownDiv").style.display = "none"; // Hide the text
                clearInterval(countdownInterval);
                // startMinute();
                gameMinute();
            }
            count--;
        }
        const countdownInterval = setInterval(updateCountdown, 1000);
        updateCountdown(); // Initial update
        // }
    }

    var audioElement = document.getElementById("audioElement");
    var audioVolume = parseFloat(localStorage.getItem("audioVolume"));
    // Check if the value is a valid number between 0 and 1
    if (!isNaN(audioVolume) && audioVolume >= 0 && audioVolume <= 1) {
        // Set the volume of the audio element
        audioElement.volume = audioVolume;
    } else {
        // If the value is not valid, set a default volume
        audioElement.volume = 0.5; // You can change this to your preferred default volume
    }
    var buttonMusic = document.getElementById("buttonMusic");
    var music = parseInt(localStorage.getItem('audio') || 0);
    if (music === 1) {
        audioElement.muted = true; // Mute the audio
        buttonMusic.src = "<?php echo e(URL('images/speaker_muted.png')); ?>";
    } else {
        audioElement.muted = false; // Unmute the audio
        buttonMusic.src = "<?php echo e(URL('images/speaker.png')); ?>";
    }

    buttonMusic.addEventListener("click", function() {
        if (audioElement.muted) {
            audioElement.muted = false; // Unmute the audio
            buttonMusic.src = "<?php echo e(URL('images/speaker.png')); ?>";
            // Update the value in localStorage to unmuted (0)
            localStorage.setItem('audio', 0);
        } else {
            audioElement.muted = true; // Mute the audio
            buttonMusic.src = "<?php echo e(URL('images/speaker_muted.png')); ?>";
            // Update the value in localStorage to muted (1)
            localStorage.setItem('audio', 1);
        }
    });


    // Function to play the click sound
    function playClickSound() {
        clickSound.play();
    }
    buttonMusic.addEventListener("click", playClickSound);
    retryBtn.addEventListener("click", playClickSound);
</script>


{{-- kay jl --}}
<script>
    let questions = {!! json_encode($questions) !!};
    let currentQuestionIndex = 0;
    let canSelectChoice = true; // Flag to control choice selection
    let correctAnswerCount = 0; // Counter for correct answers
    let incorrectChoiceSelected = false; // Flag to track if an incorrect choice was selected
    let count = questions.length;
    var modalExp = document.getElementById('modal-explanation');
    function displayNextQuestion() {
        if (currentQuestionIndex < questions.length) {
            canSelectChoice = true;
            currentQuestionIndex++;
            displayQuestion();
            incorrectChoiceSelected = false;
        } else {
            console.log('End of questions.');
        }
    }


    function nextQues() {
            modalExp.classList.add('hidden');
            gameMinute();
            localStorageValue++;
            localStorage.setItem('numberQuestion', localStorageValue);
            console.log("dsad: " + localStorageValue);

            // Display the next question
            displayNextQuestion();
    }

    function displayQuestion() {
        console.log('displayQuestion() called');
        if (currentQuestionIndex < questions.length) {
            // Display the current question
            let currentQuestion = questions[currentQuestionIndex];
            console.log('Current Question:', currentQuestion.question_desc);
            document.getElementById('question-text').textContent = currentQuestion.question_desc;

            // Display the choices for the current question
            var modalExp = document.getElementById('modal-explanation');
            var questionExpElement = document.getElementById('question_exp');

            // Update the modal content with the current question's explanation
            questionExpElement.textContent = currentQuestion.question_exp;

            let choicesContainer = document.getElementById('choices-container');
            choicesContainer.innerHTML = '';
            let correctChoiceIndex = null; // Index of the correct choice

            currentQuestion.answers.forEach(function (answer, index) {
                let choiceDiv = document.createElement('div');
                choiceDiv.textContent = answer.choices_desc;
                choiceDiv.classList.add('clickable-choice', 'bg-white', 'p-2', 'w-full', 'rounded-2xl',
                    'text-center', 'drop-shadow-xl', 'font-bold', 'mb-5', 'animate-fade-in-up1',
                    'hover:bg-gray-100');
                document.getElementById('question-number').textContent = (currentQuestionIndex + 1) + ' / ' + count;

                // Attach a click event listener to each choice
                choiceDiv.addEventListener('click', function () {
                    if (timerRunning) {
                        clearInterval(timer);
                        timerRunning = false;
                    }
                    setTimeout(function () {
                        modalExp.classList.remove('hidden');
                     }, 1000);
                    // Check if choice selection is allowed (prevent multiple clicks)
                    if (!canSelectChoice) {
                        return;
                    }

                    console.log('Choice Clicked:', answer.choices_desc);
                    choiceDiv.classList.remove('bg-white');

                    // Check if the answer is correct and apply coloring
                    if (answer.answer === 1) {
                        choiceDiv.classList.add('selected-correct', 'text-green-900',
                            'bg-green-300'); 

                        choiceDiv.classList.remove('hover:bg-gray-100');
                        document.getElementById('answer_checking').classList.remove('text-red-500');
                        document.getElementById('answer_checking').textContent = "Your Answer is Correct!";
                        document.getElementById('answer_checking').classList.add('text-green-500');
                        correctAnswerCount++; 
                    } else {
                        choiceDiv.classList.add('selected-incorrect', 'text-red-900',
                            'bg-red-300'); 
                        choiceDiv.classList.remove(
                            'hover:bg-gray-100'); 
                        incorrectChoiceSelected = true;
                        // Automatically reveal the correct answer after a delay
                        if (correctChoiceIndex !== null) {
                            let correctChoiceDiv = choicesContainer.children[correctChoiceIndex];
                            correctChoiceDiv.classList.remove('bg-white');
                            correctChoiceDiv.classList.add('text-green-900', 'bg-green-300');
                            correctChoiceDiv.classList.remove('hover:bg-gray-100');
                        }
                        document.getElementById('answer_checking').textContent = "Your Answer is Incorrect!";
                        document.getElementById('answer_checking').classList.add('text-red-500');
                        
                    }
                    document.getElementById('question_ans').textContent = "Correct Answer: " + currentQuestion.answers.find(a => a.answer === 1).choices_desc;
                    canSelectChoice = false;
                });

                // Check if the answer is correct and save the correct choice index
                if (answer.answer === 1) {
                    correctChoiceIndex = index;
                }

                choicesContainer.appendChild(choiceDiv);
            });
            const nextQuestion = document.getElementById("nextQues");
            nextQuestion.addEventListener("click", nextQues);
        }
        else {

            let difficulty = sessionStorage.getItem('difficulty');
            console.log('Difficulty:', difficulty);

            let subjectId = sessionStorage.getItem('subjectId');
            console.log('Subject ID:', subjectId);

            let loginID = @json($loginID);
            console.log('Login ID:', loginID)

            let data = {
                correctAnswerCount: correctAnswerCount,
                difficulty: difficulty,
                subjectId: subjectId,
                loginID: loginID,
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                type: 'POST', // or 'GET' depending on your server route
                url: '/insert-score', // Replace with the actual endpoint URL
                data: data,
                success: function(response) {
                    // Handle the response from the server here
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });

            // Handle end of questions
            const starsToColor = calculateStarsToColor(correctAnswerCount, count);
            let endContainer = document.getElementById('question-container');
            var labelElement = document.getElementById("scoreQuiz");
            let totalScore = document.getElementById('total-score');
            totalScore.classList.remove("hidden");
            endContainer.classList.add("hidden");

            labelElement.innerHTML = correctAnswerCount + ' / ' + count;
            document.getElementById('end-button').style.display = 'block'; // or 'inline-block' based on your styling
            clearInterval(timer);
            timerRunning = false;
            beatSound.pause();
            for (let i = 1; i <= 5; i++) {
                const starElement = document.getElementById(`star${i}`);
                if (starElement) {
                    if (i <= starsToColor) {
                        starElement.classList.remove('text-yellow-300'); // Remove the default color
                        starElement.classList.add('text-yellow-500'); // Set the color to yellow
                    } else {
                        starElement.classList.remove('text-yellow-500'); // Remove yellow color
                        starElement.classList.add(
                            'text-gray-300'); // Set the color to gray or your desired default color
                    }
                }
            }

        }

    }

    function calculateStarsToColor(correctAnswerCount, totalQuestions) {
        const maxStars = 5; // Define the maximum number of stars
        const percentage = (correctAnswerCount / totalQuestions) * 100; // Calculate the percentage
        const starsToColor = Math.ceil((percentage / 100) * maxStars); // Calculate the number of stars to color
        return starsToColor;
    }
    // Initial display of the first question
    displayQuestion();

    function endGame() {
        // Destroy the sessions
        sessionStorage.removeItem('difficulty');
        sessionStorage.removeItem('subjectId');

        // Redirect to the Subjects page
        Subjects();
        window.location.reload();
    }
</script>
