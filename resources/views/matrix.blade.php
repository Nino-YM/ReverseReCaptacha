<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>01101000 01100101 01101100 01101100 01101111</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: black;
            color: lime;
            margin: 0;
            padding: 0;
            overflow: hidden;
            white-space: pre;
        }
        .matrix {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            width: 100vw;
        }
    </style>
</head>
<body>
    <div class="matrix" id="matrix"></div>
    <script>
        function getRandomBinary() {
            return Math.random() > 0.5 ? '1' : '0';
        }

        function generateLine(length) {
            let line = '';
            for (let j = 0; j < length; j++) {
                line += getRandomBinary();
            }
            return line;
        }

        function generateMatrix(rows, cols) {
            const matrixContainer = document.getElementById('matrix');
            let matrixContent = '';

            for (let i = 0; i < rows; i++) {
                matrixContent += generateLine(cols) + '\n';
            }

            matrixContainer.innerText = matrixContent;
        }

        function adjustMatrixSize() {
            const width = window.innerWidth;
            const height = window.innerHeight;
            const fontSize = parseInt(window.getComputedStyle(document.body).fontSize, 10);
            const cols = Math.floor(width / fontSize);
            const rows = Math.floor(height / fontSize);

            generateMatrix(rows, cols);
        }

        function updateMatrix() {
            adjustMatrixSize();
            setInterval(adjustMatrixSize, 50);
        }

        window.addEventListener('resize', adjustMatrixSize);
        window.addEventListener('load', updateMatrix);
    </script>
</body>
</html>
