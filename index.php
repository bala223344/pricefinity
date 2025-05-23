<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pricefinity</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
        }
        body, svg, text, tspan {
    user-select: none;
    -webkit-user-select: none;
    -ms-user-select: none;
    outline: none;
    cursor: default;
}
        svg {
            width: 500px;
            height: 200px;
        }
        #goodbyeText {
            font-family: 'Arial', sans-serif;
            font-size: 90px;
            fill: #121212;
            opacity: 1;
            transition: opacity 1s;
        }
        #cursor {
            opacity: 1;
            animation: blink 1s steps(1) infinite;
        }
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }
    </style>
</head>
<body>
    <svg viewBox="0 0 600 200">
        <text id="goodbyeText" x="50%" y="50%" dominant-baseline="middle" text-anchor="middle">
            <tspan id="textContent"></tspan><tspan id="cursor">|</tspan>
        </text>
    </svg>
    <script>
        const words = [
            "Pricefinity",
            "LOGO3",
            "DAWG FUEL",
            "Caitlin’s",
            "Forever 22",
            "W Collection",
            "Forever 22",
            "LoveFrom"
        ];

        const textElem = document.getElementById('goodbyeText');
        const textContent = document.getElementById('textContent');
        const cursor = document.getElementById('cursor');
        let wordIndex = 0;

        function showWord(word, cb) {
            textElem.style.opacity = 1;
            textContent.textContent = '';
            cursor.style.display = 'inline';

            // Blink cursor alone for 3 seconds
            setTimeout(() => {
                let i = 0;
                function showNextLetter() {
                    if (i <= word.length) {
                        textContent.textContent = word.slice(0, i);
                        i++;
                        setTimeout(showNextLetter, 400);
                    } else {
                        cursor.style.display = 'none'; // Hide cursor after word is complete
                        setTimeout(cb, 4500);
                    }
                }
                showNextLetter();
            }, 3000);
        }

        function fadeOut(cb) {
            textElem.style.opacity = 0;
            setTimeout(cb, 1000);
        }

        function animateWords() {
            showWord(words[wordIndex], function() {
                fadeOut(function() {
                    wordIndex = (wordIndex + 1) % words.length;
                    animateWords();
                });
            });
        }

        animateWords();
    </script>
</body>
</html>
