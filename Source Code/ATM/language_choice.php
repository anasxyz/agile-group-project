<?php
session_start();

if (isset($_GET['lang'])) {
    $_SESSION['language'] = $_GET['lang']; // Store language selection in session
    header("Location: index.php"); // Redirect to the next page
    exit();
}
?>

<!DOCTYPE html>
<html lang="<?= $language ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Language Choice</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }

        .container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            width: 600px;
            text-align: center;
        }

        h1 {
            font-size: 26px;
            font-weight: bold;
            color: #333;
            text-align: center;
            grid-column: span 2;
        }

        .option {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            width: 100%;
            box-sizing: border-box;
        }

        .option:hover {
            background-color: #f0f0f0;
            transform: scale(1.02);
        }

        .option span {
            font-size: 1.5em;
            color: #333;
        }
    </style>
    <script>
        function setLanguage(lang) {
            window.location.href = "language_choice.php?lang=" + lang;
        }
    </script>
</head>

<body>
    <div class="container">
        <h1>Select Your Preferred Language</h1>

        <div class="option" onclick="setLanguage('en')">
            <span>English</span>
        </div>

        <div class="option" onclick="setLanguage('fr')">
            <span>Français</span>
        </div>

        <div class="option" onclick="setLanguage('es')">
            <span>Español</span>
        </div>

        <div class="option" onclick="setLanguage('de')">
            <span>Deutsch</span>
        </div>

        <div class="option" onclick="setLanguage('ar')">
            <span>العربية</span>
        </div>

        <div class="option" onclick="setLanguage('it')">
            <span>Italiano</span>
        </div>
    </div>
</body>

</html>