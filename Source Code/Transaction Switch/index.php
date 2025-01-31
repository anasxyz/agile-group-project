<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
        position: relative; /* Allow positioning of buttons at the top right */
        }

        .container {
        display: grid;
        gap: 20px;
        width: 300px;
        text-align: center;
        }

        h1 {
        font-size: 26px;
        font-weight: bold;
        color: #333;
        text-align: center;
        }

        .option {
        align-items: center;
        justify-content: center;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s;
        }

        .option:hover {
        background-color: #f0f0f0;
        transform: scale(1.02);
        }

        .option img {
        width: 80px;
        height: 80px;
        margin-bottom: 10px;
        }

        .option span {
        font-size: 1.2em;
        color: #333;
        }

        /* Inactive Button Styling */
        .option.inactive {
            background-color: #b0b0b0;
            color: #7a7a7a;
            cursor: not-allowed;
            box-shadow: none;
        }

        .option.inactive:hover {
            background-color: #b0b0b0;
            transform: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="option" onclick="redirectToDownloadLogs('switch')">
            <span>Download Transaction Switch Logs</span>
        </div>
  </div>

  <script>
    function redirectToDownloadLogs(logType) {
      window.location.href = `download_log.php?file=${encodeURIComponent(logType)}`;
    }
  </script>
</body>
</html>