<?php
session_start(); // Start the session
include 'db_connect.php'; // Include your database connection file

$error_message = ''; // Variable to hold error messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the SQL statement to check the user's credentials
    $stmt = $conn->prepare("SELECT id, password, first_name, is_verified FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result(); // Store the result

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $first_name, $is_verified);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Check if the account is verified
            if ($is_verified) {
                // Store user details in session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['first_name'] = $first_name;

                // Redirect to a protected page (e.g., dashboard or home page)
                header("Location: index.php");
                exit();
            } else {
                // Account not verified
                $error_message = "Your account is not verified. Please check your email for the OTP.";
            }
        } else {
            // Invalid password
            $error_message = "Invalid email or password.";
        }
    } else {
        // Email not found
        $error_message = "Invalid email or password.";
    }


    
    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
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

    .error {
      color: red;
      text-align: center;
      margin-top: 10px;
    }


    .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            right: 15px;
            top: 25px;
        }

  </style>
</head>

<body>
    <div class="container">
        <header>Login</header>
        <?php if ($error_message): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form action="" method="post" class="form">
            <div class="input-box">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter email address" required />
            </div>
            <div class="input-box">
                <label for="password">Password</label>
                <div class="input-with-icon">
                    <input type="password" id="password" name="password" placeholder="Enter password" required />
                    <i class="far fa-eye" id="togglePassword1" style="cursor: pointer;"></i>
                </div>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>

    <script>
        const togglePassword1 = document.getElementById('togglePassword1');
        const passwordInput1 = document.getElementById('password');

        togglePassword1.addEventListener('click', function() {
            const type = passwordInput1.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput1.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>

</body>
</html>
</html>