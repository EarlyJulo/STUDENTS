<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        // Ensure 'db.php' file has the database connection setup correctly
        require_once "db.php";

        if (isset($_POST["Login"])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Use prepared statement to prevent SQL injection
            $sql = "SELECT * FROM students WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user) {
                // Verify password
                if (password_verify($password, $user["password"])) {
                    // Redirect to dashboard.php upon successful login
                    header("Location: dashboard.php");
                    exit(); // Ensure no further output is sent
                } else {
                    echo "<div class='alert alert-danger'> Password does not match </div>";
                }
            } else {
                echo "<div class='alert alert-danger'> Email does not exist </div>";
            }
        }
        ?>
        <h2 class="text-center highlight-text">Student Login</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <input type="email" placeholder="Enter Email" name="email" class="form-control">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter Password" name="password" class="form-control">
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit" name="Login">Login</button>
            </div>
            <div class="back-container">
                <label class="highlight-text">Not registered?</label>
                <a href="/STUDENTS/students/registration.php">Register</a>
            </div>
        </form>
    </div>
</body>
</html>