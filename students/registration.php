<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
}

$error_message = ""; // Initialize error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "db.php";

    if (isset($_POST["submit"])) {
        $fname = $_POST['fname'];
        $sname = $_POST['sname'];
        $lname = $_POST['lname'];
        $gender = $_POST['gender'];
        $registration = $_POST['registration'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $year = $_POST['year'];
        $password = $_POST['password'];
        $passwordRepeat = $_POST['repeat_password'];

        $errors = array();

        if (empty($fname) || empty($sname) || empty($lname) || empty($gender) || empty($registration) || empty($mobile) || empty($email) || empty($year) || empty($password)) {
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
        $sql = "SELECT * FROM students WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $rowCount = $result->num_rows;
        if ($rowCount > 0) {
            array_push($errors, "This email already exists");
        }

        if (count($errors) === 0) {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert data into the database
            $sql = "INSERT INTO students (fname, sname, lname, gender, registration, mobile, email, password, year) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssss", $fname, $sname, $lname, $gender, $registration, $mobile, $email, $hashed_password, $year);
            
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>You are successfully registered.</div>";
            } else {
                echo "<div class='alert alert-danger'>Registration failed. Please try again.</div>";
            }
        } else {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
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
    <title>Student Registration and Admin Login</title>
    <link rel="icon" type="image/x-icon" href="/images/Agile Tech.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 600px;
            margin: 20px auto;
            box-shadow: 0 30px 20px rgba(22, 22, 22, 0.1);
            padding: 20px;
            background-color: #ffffff;
            border: 2px solid #007bff;
            border-radius: 15px;
            background-color: #241749;
        }
        .form-btn {
            margin-top: 20px;
        }
        .highlight-text {
            font-weight: bold;
            color: #ffc107;
        }
        .centered-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center highlight-text">Student Registration Form</h1>
        <form action="registration.php" method="POST">
            <div class="form-group">
                <input type="text" class="form-control" name="fname" placeholder="First Name" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="sname" placeholder="Middle Name" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="lname" placeholder="Last Name" required>
            </div>
            <div class="form-group">
                <select class="form-control" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="registration" placeholder="Registration Number" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="mobile" placeholder="Mobile Number" required>
            </div>
            <div class="form-group">
                <select id="year" name="year" class="form-control" required>
                    <option value="">Select Year of Study</option>
                    <option value="First year">First Year</option>
                    <option value="Second year">Second Year</option>
                    <option value="Third year">Third Year</option>
                    <option value="Fourth year">Fourth Year</option>
                    <option value="Fifth year">Fifth Year</option>
                    <option value="Sixth year">Sixth Year</option>
                </select>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Confirm Password" required>
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>
        <div>
            <p class="highlight-text">Already registered? <a href="login.php">Login here</a></p>
        </div>
    </div>

    <div class="container centered-container">
        <h1 class="text-center highlight-text">Admin Login</h1>
        <a href="/STUDENTS/Admin/login.php" class="btn btn-primary btn-lg">Admin Login</a>
    </div>
</body>
</html>
