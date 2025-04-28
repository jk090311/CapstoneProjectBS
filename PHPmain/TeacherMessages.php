<?php
session_start();
if (!isset($_SESSION['messages'])) {
    $_SESSION['messages'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['message'])) {
    $message = htmlspecialchars($_POST['message']);
    $_SESSION['messages'][] = ['user' => 'Teacher', 'text' => $message];
}
?>
<?php include "teacherNavbar.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Messages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .chat-container {
            width: 500px;
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .chat-box {
            height: 400px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
        }
        .message {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color:rgb(43, 32, 107);
            width: 100%;
            text-align: left;
        }
        .message .user {
            font-weight: bold;
            color: #fff;
        }
        .message .text {
            margin-left: 10px;
            color: #fff;
        }
        .input-container {
            margin-top: 10px;
            text-align: center;
        }
        .input-container input[type="text"] {
            width: 80%;
            padding: 5px;
        }
        .input-container input[type="submit"] {
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <h2>Teacher Messages</h2>
        <div class="chat-box" id="chat-box">
            <!-- Messages will be displayed here -->
        </div>
        <div class="input-container">
            <form id="chat-form" method="POST" action="TeacherMessages.php">
                <input type="text" name="message" id="message" placeholder="Type your message here..." required>
                <input type="submit" value="Send">
            </form>
        </div>
    </div>

    <?php
    if (!empty($_SESSION['messages'])) {
        echo '<script>';
        echo 'var chatBox = document.getElementById("chat-box");';
        foreach ($_SESSION['messages'] as $msg) {
            echo 'chatBox.innerHTML += "<div class=\"message\"><span class=\"user\">' . $msg['user'] . ':</span><span class=\"text\">' . $msg['text'] . '</span></div>";';
        }
        echo 'chatBox.scrollTop = chatBox.scrollHeight;';
        echo '</script>';
    }
    ?>
</body>
</html>