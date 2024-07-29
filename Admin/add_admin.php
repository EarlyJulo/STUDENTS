<?php
session_start();

// Initialize error message variable
$error_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "db.php";

    // Form input variables
    $username = $_POST['username'];
    $email = $_POST['email'];
    $mobile = $_POST['phone']; // Use 'phone' from form input for 'mobile' column in database
    $password = $_POST['password'];
    $passwordRepeat = $_POST['repeat_password'];

    // Array to store validation errors
    $errors = array();

    // Validate inputs
    if (empty($username) || empty($email) || empty($mobile) || empty($password)) {
        array_push($errors, "All fields are required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid");
    }
    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters long");
    }
    if ($password !== $passwordRepeat) {
        array_push($errors, "Passwords do not match");
    }

    // Check if email already exists
    $sql = "SELECT * FROM admin WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $rowCount = $result->num_rows;
    if ($rowCount > 0) {
        array_push($errors, "This email already exists!");
    }

    // If no errors, insert data into the database
    if (count($errors) === 0) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into the database
        $sql = "INSERT INTO admin (username, email, mobile, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $mobile, $hashed_password);
        
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>You have successfully registered a new admin.</div>";
            // Optionally redirect to a login page or provide a link to continue
            // header("Location: login.php");
            // exit();
        } else {
            echo "<div class='alert alert-danger'>Registration failed. Please try again.</div>";
        }
    } else {
        // Display validation errors
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .container {
            max-width: 600px;
            margin: 20px auto;
            box-shadow: 0 30px 20px rgba(22, 22, 22, 0.1);
            padding: 20px;
            background-color: #ffffff;
            border: 2px solid #007bff;
            border-radius: 15px;
            text-align: center; /* Center align content */
        }
        .form-btn {
            margin-top: 20px;
        }
        .highlight-text {
            font-weight: bold;
            color: #ffc107;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Register Admin</h1>
        <form action="add_admin.php" method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="tel" class="form-control" name="phone" placeholder="Phone Number" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="repeat_password" placeholder="Confirm Password" required>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary w-100" value="Register" name="submit">
            </div>
        </form>
    </div>

    <!-- Back Button -->
    <div class="container text-center mt-3"> <!-- Added a new container for centering -->
        <a href="dashboard.php" class="btn btn-primary">Back</a>
    </div>
</body>
</html>
