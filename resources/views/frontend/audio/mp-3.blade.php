<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Link to Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .audio-player-container {
            width: 100%;
            max-width: 400px;
            background-color: #2c3e50;
            border-radius: 8px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .audio-player-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .play-btn, .pause-btn {
            background-color: #1abc9c;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .play-btn:hover, .pause-btn:hover {
            background-color: #16a085;
        }

        .volume-control {
            -webkit-appearance: none;
            width: 100px;
            height: 5px;
            background: #34495e;
            border-radius: 5px;
            outline: none;
            cursor: pointer;
        }

        .auto-scale-image {
            animation: scaleAnimation 1s infinite ease-in-out;
        }

        @keyframes scaleAnimation {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05); 
            }
            100% {
                transform: scale(1);
            }
        }

        .volume-control::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 15px;
            width: 15px;
            border-radius: 50%;
            background: #16a085;
            cursor: pointer;
        }

        .volume-control::-moz-range-thumb {
            height: 15px;
            width: 15px;
            border-radius: 50%;
            background: #16a085;
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-light">
    @if(!empty($product->mp3_path))
    <div class="container d-flex flex-column align-items-center text-center p-4">
        <h2 class="font-weight-bold text-dark mb-4">{{  $product->name ?? "Không tồn tại" }}</h2>
        <img src="{{ asset($images[0]->image_path) ?? "Không có hình ảnh" }}" alt="Product Image" class="auto-scale-image hover:scale-105 transition-transform product-image img-fluid rounded-lg shadow-lg mb-4" style="max-width: 80%; height: auto;">
        <div class="audio-player-container">
            <audio id="audio" class="hidden">
                <source src="{{ asset($product->mp3_path) ?? "Không có file" }}" type="audio/mp3">
            </audio>
            <div class="audio-player-controls w-100">
                <button id="playBtn" class="play-btn btn">Play</button>
                <button id="pauseBtn" class="pause-btn btn d-none">Pause</button>
                <input type="range" id="volumeControl" class="volume-control w-50" value="100" max="100">
            </div>
            <div class="audio-time d-flex gap-2">
                <span id="currentTime" class="text-white">00:00</span> / <span id="duration" class="text-white">00:00</span>
            </div>
        </div>
    </div>
@else
    <div class="container d-flex flex-column align-items-center text-center p-4">
        <h2 class="font-weight-bold text-dark mb-4">{{  $product->name ?? "Không tồn tại" }}</h2>
        <img src="{{ asset($images[0]->image_path) ?? "Không có hình ảnh" }}" alt="Product Image" class="auto-scale-image hover:scale-105 transition-transform product-image img-fluid rounded-lg shadow-lg mb-4" style="max-width: 80%; height: auto;">
        <p class="text-dark">Không có file âm thanh cho sản phẩm này.</p> <!-- Message for no audio file -->
    </div>
@endif


    
    <!-- Link to Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        const audio = document.getElementById('audio');
        const playBtn = document.getElementById('playBtn');
        const pauseBtn = document.getElementById('pauseBtn');
        const volumeControl = document.getElementById('volumeControl');
        const currentTimeDisplay = document.getElementById('currentTime');
        const durationDisplay = document.getElementById('duration');
        audio.addEventListener('loadedmetadata', function() {
            const duration = audio.duration;
            durationDisplay.textContent = formatTime(duration);
        });

        audio.addEventListener('timeupdate', function() {
            const currentTime = audio.currentTime;
            currentTimeDisplay.textContent = formatTime(currentTime); 
        });

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secondsRemaining = Math.floor(seconds % 60);
            return `${minutes < 10 ? '0' : ''}${minutes}:${secondsRemaining < 10 ? '0' : ''}${secondsRemaining}`;
        }

        playBtn.addEventListener('click', function() {
            audio.play();
            playBtn.classList.add('d-none');
            pauseBtn.classList.remove('d-none');
        });

        pauseBtn.addEventListener('click', function() {
            audio.pause();
            playBtn.classList.remove('d-none');
            pauseBtn.classList.add('d-none');
        });

        volumeControl.addEventListener('input', function() {
            audio.volume = volumeControl.value / 100;
        });
    </script>
</body>
</html>
