
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading Spinner</title>
    <style>
    .preloader {
        background-color: rgba(255, 255, 255, 0.5);
        height: 100vh;
        width: 100vw;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 100;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #container {
        width: 100px;
        height: 125px;
        margin: auto auto;
    }

    .loading-title {
        display: block;
        text-align: center;
        font-size: 20;
        font-family: 'Inter', sans-serif;
        font-weight: bold;
        padding-bottom: 15px;
        color: #888;
    }

    .loading-circle {
        display: block;
        border-left: 5px solid;
        border-top-left-radius: 100%;
        border-top: 5px solid;
        margin: 5px;
        animation-name: Loader_611;
        animation-duration: 1500ms;
        animation-timing-function: linear;
        animation-delay: 0s;
        animation-iteration-count: infinite;
        animation-direction: normal;
        animation-fill-mode: forwards;
    }

    .sp1 {
        border-left-color: #F44336;
        border-top-color: #F44336;
        width: 40px;
        height: 40px;
    }

    .sp2 {
        border-left-color: #FFC107;
        border-top-color: #FFC107;
        width: 30px;
        height: 30px;
    }

    .sp3 {
        width: 20px;
        height: 20px;
        border-left-color: #8bc34a;
        border-top-color: #8bc34a;
    }

    @keyframes Loader_611 {
        0% {
            transform: rotate(0deg);
            transform-origin: right bottom;
        }

        25% {
            transform: rotate(90deg);
            transform-origin: right bottom;
        }

        50% {
            transform: rotate(180deg);
            transform-origin: right bottom;
        }

        75% {
            transform: rotate(270deg);
            transform-origin: right bottom;
        }

        100% {
            transform: rotate(360deg);
            transform-origin: right bottom;
        }
    }
    </style>
</head>

<body>
    <div class="preloader" id="preloader">
        <div id="container">
            <label class="loading-title">Loading ...</label>
            <span class="loading-circle sp1">
                <span class="loading-circle sp2">
                    <span class="loading-circle sp3"></span>
                </span>
            </span>
        </div>
    </div>

    <script>
    // Function to hide the preloader
    function hidePreloader() {
        var preloader = document.getElementById('preloader');
        if (preloader) {
            preloader.style.display = 'none';
        }
    }

    // Set a minimum display time for the preloader (in milliseconds)
    var minimumDisplayTime = 1000; // 2000 milliseconds = 2 seconds

    // Function to hide the preloader after the minimum display time has passed
    function hidePreloaderAfterDelay() {
        var currentTime = new Date().getTime();
        var timeElapsed = currentTime - startTime;
        var delay = Math.max(0, minimumDisplayTime - timeElapsed);
        setTimeout(hidePreloader, delay);
    }

    // Get the start time when the page starts loading
    var startTime = new Date().getTime();

    // Add an event listener for the 'load' event of the window
    window.addEventListener('load', function() {
        // Call the function to hide the preloader after the minimum display time
        hidePreloaderAfterDelay();
    });
    </script>
</body>

</html>