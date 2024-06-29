<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matrix Effect</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: black;
            color: lime;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .matrix {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(10px, 1fr));
            grid-auto-rows: 10px;
            width: 100%;
            height: 100%;
        }
        .matrix-cell {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 10px;
            line-height: 10px;
            color: rgba(0, 255, 0, 0.8);
        }
    </style>
</head>
<body>
    <div class="matrix">
        @for ($i = 0; $i < 20000; $i++)
            <div class="matrix-cell">{{ rand(0, 1) }}</div>
        @endfor
    </div>
</body>
</html>
