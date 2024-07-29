<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST["login"])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    require_once "db.php";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Retrieve the hashed password from the database
        $hashed_password = $user["password"];

        // Verify the entered password against the hashed password
        if (password_verify($password, $hashed_password)) {
            // Store user information in session
            $_SESSION["user"] = [
                'id' => $user['admin_id'],
                'username' => $user['username'],
                'email' => $user['email']
            ];
            // Redirect to dashboard.php upon successful login
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Password does not match</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Email does not exist</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 30px 20px rgba(22, 22, 22, 0.1);
            padding: 20px;
            background-color: #241749;
            border: 2px solid #007bff;
            border-radius: 15px;
        }
        .form-container {
            margin-top: 20px;
        }
        .highlight-text {
            font-weight: bold;
            color: #ffc107;
        }
        .back-container {
            margin-top: 20px;
            display: flex;
            align-items: center;
        }
        .back-container .highlight-text {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center highlight-text">Admin Login</h2>

        <form action="login.php" method="POST" class="form-container">
            <div class="form-group mb-3">
                <input type="email" placeholder="Enter Email" name="email" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <input type="password" placeholder="Enter Password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Login" name="login" class="btn btn-primary w-100">
            </div>
        </form>
        <div class="back-container">
            <label class="highlight-text">Do you want to go back?</label>
            <a href="/STUDENTS/students/registration.php" class="btn btn-primary btn-sm">Back</a>
        </div>
    </div>
</body>
</html>
