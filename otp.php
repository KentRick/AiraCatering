<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = implode("", $_POST['otp']); // Combine array into string
    $user_id = $_SESSION['user_id'];

    // Fetch OTP and expiry from database
    $stmt = $conn->prepare("SELECT otp, otp_expiry FROM user_otp WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($stored_otp, $otp_expiry);
    $stmt->fetch();
    $stmt->close();

    // Validate OTP and check if expired
    if ($stored_otp == $entered_otp && strtotime($otp_expiry) > time()) {
        // Mark user as verified
        $update_stmt = $conn->prepare("UPDATE users SET is_verified = 1 WHERE id = ?");
        $update_stmt->bind_param('i', $user_id);
        $update_stmt->execute();
        $update_stmt->close();

        echo "<p style='color:green;'>OTP verified successfully! You can now log in.</p>";
    } else {
        echo "<p style='color:red;'>Invalid or expired OTP. Please try again.</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f3f4f6;
            font-family: Arial, sans-serif;
        }
        .otp-container {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            text-align: center;
        }
        .otp-container input {
            width: 2em;
            padding: 10px;
            margin: 5px;
            text-align: center;
            font-size: 1.2em;
            border: 1px solid #ddd;
            border-radius: 4px;
            -moz-appearance: textfield;
        }
        .otp-container input::-webkit-outer-spin-button,
        .otp-container input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .otp-container button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1em;
            cursor: pointer;
        }
        .otp-container button:disabled {
            background-color: #ccc;
        }
        .resend-timer {
            margin-top: 10px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <h2>Verify your account</h2>
        <p>Enter your 6-digit OTP sent to your email address</p>
        <form method="post" action="">
            <input type="text" id="otp1" name="otp[]" pattern="[0-9]*" maxlength="1" required oninput="moveToNext(this, 'otp2')" onkeydown="moveToPrev(event, 'otp1', null)">
            <input type="text" id="otp2" name="otp[]" pattern="[0-9]*" maxlength="1" required oninput="moveToNext(this, 'otp3')" onkeydown="moveToPrev(event, 'otp2', 'otp1')">
            <input type="text" id="otp3" name="otp[]" pattern="[0-9]*" maxlength="1" required oninput="moveToNext(this, 'otp4')" onkeydown="moveToPrev(event, 'otp3', 'otp2')">
            <input type="text" id="otp4" name="otp[]" pattern="[0-9]*" maxlength="1" required oninput="moveToNext(this, 'otp5')" onkeydown="moveToPrev(event, 'otp4', 'otp3')">
            <input type="text" id="otp5" name="otp[]" pattern="[0-9]*" maxlength="1" required oninput="moveToNext(this, 'otp6')" onkeydown="moveToPrev(event, 'otp5', 'otp4')">
            <input type="text" id="otp6" name="otp[]" pattern="[0-9]*" maxlength="1" required oninput="validateLastField(this)" onkeydown="moveToPrev(event, 'otp6', 'otp5')">
            <button id="verify-btn" type="submit">Verify</button>
        </form>
        <div class="resend-timer">
            <span>Didn't receive the OTP? </span>
            <button id="resend-btn">Resend OTP</button>
        </div>
    </div>

    <script>
        function moveToNext(current, nextFieldID) {
            if (!/^\d$/.test(current.value)) {
                current.value = ""; // Clear invalid input
                return;
            }
            if (current.value.length === 1) {
                const nextField = document.getElementById(nextFieldID);
                if (nextField) nextField.focus();
            }
        }

        function moveToPrev(event, currentFieldID, prevFieldID) {
            if (event.key === "Backspace") {
                const currentField = document.getElementById(currentFieldID);
                if (currentField.value === "" && prevFieldID !== null) {
                    const prevField = document.getElementById(prevFieldID);
                    if (prevField) prevField.focus();
                }
            }
        }

        function validateLastField(current) {
            if (!/^\d$/.test(current.value)) {
                current.value = ""; // Clear invalid input
            }
        }
    </script>
</body>

</html>
