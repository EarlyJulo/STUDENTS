<?php
include 'db.php';

if (isset($_GET['updateid'])) {
    $id = $_GET['updateid'];
    $sql = "SELECT * FROM admin WHERE id=$id";

    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            $username = $row['username'];
            $email = $row['email'];
            $mobile = $row['mobile'];
            $password = $row['password'];
        } else {
            die("Record not found.");
        }
    } else {
        die(mysqli_error($conn));
    }
} else {
    die("ID not specified.");
}

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['phone']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);

    // Construct the SQL update query
    if (!empty($new_password)) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE admin SET username='$username', email='$email', mobile='$mobile', password='$hashed_password' WHERE id=$id";
    } else {
        $sql = "UPDATE admin SET username='$username', email='$email', mobile='$mobile' WHERE id=$id";
    }
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "Record updated successfully";
        header('Location: dashboard.php');
    } else {
        die(mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin Records</title>
    <link rel="icon" type="image/x-icon" href="/images/Agile Tech.png">
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
        <h1 class="text-center">Edit Admin Records</h1>
        <form action="edit_admin.php?updateid=<?php echo $id; ?>" method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="username" placeholder="Username" required value="<?php echo $username; ?>">
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email" required value="<?php echo $email; ?>">
            </div>
            <div class="mb-3">
                <input type="tel" class="form-control" name="phone" placeholder="Phone Number" required value="<?php echo $mobile; ?>">
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="new_password" placeholder="New Password">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary w-100" value="Update" name="submit">
            </div>
        </form>
    </div>
</body>
</html>
