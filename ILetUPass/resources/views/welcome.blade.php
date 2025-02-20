
<!DOCTYPE html>
<html lang="en">

<head>
    @vite('resources/fontawesome/css/all.min.css')
    @vite('resources/css/app.css')
    @vite('resources/js/jquery-3-7-1.js')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>I Let You Pass</title>
    <link rel="icon" type="image/x-icon" href="/images/LogoPNG.png">
</head>


<style>
    /* Define styles for screens smaller than 768px */
    @media (max-width: 800px) {
        #start_link {
            /* Adjust the button's margin, font size, and padding for smaller screens */
            margin-top: 200px;
            font-size: 1.5rem;
            padding: 10px 20px;
        }
    }
</style>


<body>
    <div class="flex justify-center flex-col items-center text-amber-200 animate-fade-in-up " style=" height: 100vh;">
        {{-- <a href="/display-game" id="start_link"
            class="btn text-gray-600 cursor-pointer bg-orange-100 p-2 rounded-full  hover:bg-orange-200 w-56 z-30 absolute flex justify-center text-2xl font-bold animate-fade-in-up1"
            style="margin-top: 450px; margin-left: 30px;">
            Start</a> --}}

        <a href="/display-game" id="start_link"
            class="btn text-gray-600 cursor-pointer bg-orange-100 p-2 rounded-full hover:bg-orange-200 z-30 absolute flex justify-center text-2xl font-bold animate-fade-in-up1 w-56"
            style="margin-top: 450px; margin-left: 30px;">
            Start
        </a>

    </div>
</body>


</html>

<script>
    const startLink = document.getElementById("start_link");
    var clickSound = document.getElementById("clickSound");
    // Function to play the click sound
    function playClickSound() {
        clickSound.play();
    }
    
    startLink.addEventListener("click", playClickSound);

   
</script>
