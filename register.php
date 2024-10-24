<?php
session_start();
include 'db_connect.php'; // Include your database connection file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
    $first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
    $middle_name = filter_var($_POST['middle_name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone_number = filter_var($_POST['phone_number'], FILTER_SANITIZE_NUMBER_INT);
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
    $province = filter_var($_POST['province'], FILTER_SANITIZE_STRING);
    $postal_code = filter_var($_POST['postal_code'], FILTER_SANITIZE_STRING);

    // Password validation
    $password = $_POST['password'];
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        echo "<p style='color:red;'>Password must contain at least one uppercase letter, one number, and be at least 8 characters long!</p>";
        exit();
    }

    $password_hashed = password_hash($password, PASSWORD_BCRYPT);

    // Insert user data into the database
    $stmt = $conn->prepare("INSERT INTO users (last_name, first_name, middle_name, email, phone_number, birth_date, gender, address, city, province, postal_code, password, is_verified) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
    $stmt->bind_param('ssssssssssss', $last_name, $first_name, $middle_name, $email, $phone_number, $birth_date, $gender, $address, $city, $province, $postal_code, $password_hashed);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['first_name'] = $first_name;
        $_SESSION['email'] = $email;

        // Generate a random 6-digit OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $otp_expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        // Insert OTP into the database
        $otp_stmt = $conn->prepare("INSERT INTO user_otp (user_id, otp, otp_expiry) VALUES (?, ?, ?)");
        $otp_stmt->bind_param('iss', $_SESSION['user_id'], $otp, $otp_expiry);
        $otp_stmt->execute();
        $otp_stmt->close();

        // Send OTP to user's email
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'airacater.otp@gmail.com'; // Your SMTP username
        $mail->Password = 'znmx iqvy exgp lycs'; // Your SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('your_email@example.com', 'Your Name');
        $mail->addAddress($email);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Your OTP code is: $otp. It will expire in 10 minutes.";

        if ($mail->send()) {
            $_SESSION['registered'] = true;
            header("Location: otp.php");
            exit();
        } else {
            echo "<p style='color:red;'>Email could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
        }
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playball&display=swap" rel="stylesheet" />

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet" />
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet" />
    <link href="lib/owlcarousel/owl.carousel.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/ionicons.min.css">

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #fff5ec;
        }

        .container {
            position: relative;
            max-width: 700px;
            width: 100%;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .container header {
            font-size: 1.5rem;
            color: #333;
            font-weight: 500;
            text-align: center;
        }

        .container .form {
            margin-top: 30px;
        }

        .form .input-box {
            width: 100%;
            margin-top: 20px;
        }

        .input-box label {
            color: #333;
        }

        .form :where(.input-box input, .select-box) {
            position: relative;
            height: 50px;
            width: 100%;
            outline: none;
            font-size: 1rem;
            color: #707070;
            margin-top: 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 0 15px;
        }

        .input-box input:focus {
            box-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
        }

        .form .column {
            display: flex;
            column-gap: 15px;
        }

        .form .gender-box {
            margin-top: 20px;
        }

        .gender-box h3 {
            color: #333;
            font-size: 1rem;
            font-weight: 400;
            margin-bottom: 8px;
        }

        .form :where(.gender-option, .gender) {
            display: flex;
            align-items: center;
            column-gap: 50px;
            flex-wrap: wrap;
        }

        .form .gender {
            column-gap: 5px;
        }

        .gender input {
            accent-color: #275437;
        }

        .form :where(.gender input, .gender label) {
            cursor: pointer;
        }

        .gender label {
            color: #707070;
        }

        .address :where(input, .select-box) {
            margin-top: 15px;
        }

        .select-box select {
            height: 100%;
            width: 100%;
            outline: none;
            border: none;
            color: #707070;
            font-size: 1rem;
        }

        .form button {
            height: 55px;
            width: 100%;
            color: #fff;
            font-size: 1rem;
            font-weight: 400;
            margin-top: 30px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #275437;
        }

        .form button:hover {
            background: #fff;
            color: #333;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            right: 15px;
            top: 25px;
        }

        .password-requirements {
            color: red;
            /* Change the text color */
            font-size: 0.9rem;
            /* Adjust the font size */
            margin-top: 5px;
            /* Add some space above the text */
        }

        /*Responsive*/
        @media screen and (max-width: 500px) {
            .form .column {
                flex-wrap: wrap;
            }

            .form :where(.gender-option, .gender) {
                row-gap: 15px;
            }
        }

        .centered-header {
            display: flex;
            justify-content: center;
        }
    </style>


</head>

<body>
    <section class="container">
        <div class="centered-header">
            <header class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">
                Registration Form
            </header>
        </div>
        <form action="" method="post" class="form">
            <div class="column">
                <div class="input-box">
                    <label for="last_name">Last Name</label>
                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        placeholder="Enter last name"
                        required
                        pattern="[A-Za-z ]+"
                        title="Last Name should only contain letters and spaces" />
                </div>
                <div class="input-box">
                    <label for="first_name">First Name</label>
                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        placeholder="Enter first name"
                        required
                        pattern="[A-Za-z ]+"
                        title="First Name should only contain letters and spaces" />
                </div>
                <div class="input-box">
                    <label for="middle_name">Middle Name</label>
                    <input
                        type="text"
                        id="middle_name"
                        name="middle_name"
                        placeholder="Enter middle name (optional)"
                        pattern="[A-Za-z ]+"
                        title="Middle Name should only contain letters and spaces" />
                </div>
            </div>

            <div class="input-box">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter email address" required />
            </div>
            <div class="input-box">
                <label for="password">Password</label>
                <div class="input-with-icon">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter password"
                        required
                        minlength="8" />
                    <i class="far fa-eye" id="togglePassword1" style="cursor: pointer;"></i>
                </div>
                <span class="password-requirements" style="color: red; font-size: 0.7rem;">
                    Password must contain at least one uppercase letter, one number, and be at least 8 characters long!
                </span>
            </div>
            <div class="input-box">
                <label for="retype_password">Retype Password</label>
                <div class="input-with-icon">
                    <input type="password" id="retype_password" name="retype_password" placeholder="Retype password" required />
                    <i class="far fa-eye" id="togglePassword2" style="cursor: pointer;"></i>
                </div>
            </div>

            <div class="column">
                <div class="input-box">
                    <label for="phone_number">Phone Number</label>
                    <input
                        type="tel"
                        id="phone_number"
                        name="phone_number"
                        placeholder="Enter phone number"
                        required
                        pattern="\d{11}"
                        maxlength="11"
                        title="Please enter exactly 11 digits."
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
                </div>
                <div class="input-box">
                    <label for="birth_date">Birth Date</label>
                    <input
                        type="date"
                        id="birth_date"
                        name="birth_date"
                        required
                        onchange="checkAge()" />
                </div>
            </div>
            <div class="gender-box">
                <h3>Gender</h3>
                <div class="gender-option">
                    <div class="gender">
                        <input type="radio" id="check-male" name="gender" value="male" checked />
                        <label for="check-male">Male</label>
                    </div>
                    <div class="gender">
                        <input type="radio" id="check-female" name="gender" value="female" />
                        <label for="check-female">Female</label>
                    </div>
                    <div class="gender">
                        <input type="radio" id="check-other" name="gender" value="prefer not to say" />
                        <label for="check-other">Prefer not to say</label>
                    </div>
                </div>
            </div>
            <div class="input-box address">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" placeholder="Enter street address" required />
                <div class="city">
                    <input type="text" id="city" name="city" placeholder="Enter your city" required />
                </div>
                <div class="column">
                    <input type="text" id="province" name="province" placeholder="Enter your province" required />
                    <input type="text" id="postal_code" name="postal_code" placeholder="Enter postal code" required />
                </div>
            </div>
            <button type="submit">Submit</button>
        </form>


        <script>
            document.querySelector('form').addEventListener('submit', function(event) {
                const password = document.getElementById('password').value;
                const retypePassword = document.getElementById('retype_password').value;

                // Check if passwords match
                if (password !== retypePassword) {
                    event.preventDefault(); // Prevent form submission
                    alert("Passwords do not match!");
                    return;
                }

                // Check password criteria
                if (!isValidPassword(password)) {
                    event.preventDefault(); // Prevent form submission
                    alert("Password must contain at least one uppercase letter and one number!");
                }
            });
            // Function to validate password
            function isValidPassword(password) {
                const hasUpperCase = /[A-Z]/.test(password);
                const hasNumber = /\d/.test(password);
                const hasMinimumLength = password.length >= 8; // Check for minimum length
                return hasUpperCase && hasNumber && hasMinimumLength;
            }


            // Existing code for toggling password visibility and age check
            const togglePassword1 = document.getElementById('togglePassword1');
            const togglePassword2 = document.getElementById('togglePassword2');
            const passwordInput1 = document.getElementById('password');
            const passwordInput2 = document.getElementById('retype_password');

            togglePassword1.addEventListener('click', function() {
                const type = passwordInput1.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput1.setAttribute('type', type);
                this.classList.toggle('fa-eye-slash');
            });

            togglePassword2.addEventListener('click', function() {
                const type = passwordInput2.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput2.setAttribute('type', type);
                this.classList.toggle('fa-eye-slash');
            });

            function checkAge() {
                const birthDateInput = document.getElementById('birth_date');
                const birthDate = new Date(birthDateInput.value);
                const today = new Date();

                // Calculate the age
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDifference = today.getMonth() - birthDate.getMonth();
                if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                // Check if age is less than 18
                if (age < 18) {
                    alert("You must be at least 18 years old to register.");
                    birthDateInput.value = ""; // Clear the input
                }
            }
        </script>


</body>

</html>