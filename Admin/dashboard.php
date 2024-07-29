<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard</title>
  <link rel="icon" type="image/x-icon" href="/images/Agile Tech.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <style>
    .sidebar {
      background-color: #241749;
      padding: 20px;
      height: 60vh;
      position: fixed;
      top: 70px;
      left: 0;
      width: 270px;
      border: 2px solid #fff;
      border-radius: 10px;
      box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
    }
    .content {
      margin-left: 270px;
      padding: 20px;
      position: fixed;
      width: calc(100% - 270px);
      overflow-y: auto;
      height: 100vh;
    }
    .nav-link {
      color: white;
    }
    .nav-link.active {
      color: lightblue;
    }
    .card-label {
      font-size: 3rem;
      font-weight: bold;
    }
    .custom-card {
      background-color: #241749;
      color: white;
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      cursor: pointer; /* Added cursor pointer for better UX */
    }
    @media (max-width: 992px) {
      .content {
        margin-left: 0;
      }
      .card {
        width: 100%;
        margin-bottom: 20px;
      }
    }
    .table-container {
      max-width: 100%;
      overflow-x: auto;
      margin-top: 20px;
    }
    table {
      width: 100%;
    }
    .logout-link {
      position: fixed;
      bottom: 20px;
      left: 20px;
      color: #333;
      text-decoration: none;
    }
    .cards-container {
      max-width: 800px; /* Adjust this value as needed */
    }
    .navbar-custom {
      background-color: #241749;
    }
  </style>
</head>
<body>
  <?php
  session_start(); // Ensure the session is started

  // Check if the user is logged in
  if (!isset($_SESSION["user"])) {
      header("Location: login.php");
      exit;
  }

  // Retrieve user data from session
  $username = htmlspecialchars($_SESSION["user"]['username']);
  $session_id = $_SESSION["user"]['id'];

  // Database connection
  $servername = "localhost";
  $dbusername = "root";
  $dbpassword = "";
  $dbname = "school";

  $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Fetch student count
  $sql = "SELECT COUNT(*) as student_count FROM students";
  $result = $conn->query($sql);
  $student_count = 0;
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $student_count = $row['student_count'];
  }

  // Fetch admin data
  $sql_admins = "SELECT * FROM admin";
  $result_admins = $conn->query($sql_admins);
  $admins = [];
  if ($result_admins->num_rows > 0) {
    while ($row = $result_admins->fetch_assoc()) {
      $admins[] = $row;
    }
  }

  $conn->close();
  ?>

  <nav class="navbar navbar-dark navbar-custom">
    <div class="container-fluid">
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-warning" type="submit">Search</button>
      </form>
      <div class="d-flex align-items-center">
        <i class="fas fa-user-circle fa-2x text-white me-2"></i>
        <span class="text-white"><?php echo $username; ?></span>
      </div>
    </div>
  </nav>

  <div class="sidebar">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="dashboard.php"><i class="fa fa-bars" aria-hidden="true"></i> Dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#" id="show-students-link"><i class="fa fa-users" aria-hidden="true"></i> Students</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><i class="fa fa-book" aria-hidden="true"></i> Updates</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#" id="show-admins-link"><i class="fa fa-user-secret" aria-hidden="true"></i> System Admins</a>
      </li>
    </ul>
    <a href="logout.php" class="btn btn-warning">Log out</a>
  </div>
  
  <div class="content container-fluid">
    <div class="cards-container row g-4">
      <div class="col-md-6">
        <div class="card custom-card" id="show-students-card">
          <div class="card-body">
            <h5 class="card-title">Students</h5>
            <label class="card-label"><i class="fa fa-users" aria-hidden="true"></i> <?php echo $student_count; ?></label>
            <p class="card-text">Registered students</p>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card custom-card" id="show-admins-card">
          <div class="card-body">
            <h5 class="card-title">System Admins</h5>
            <label class="card-label"><i class="fa fa-user-secret" aria-hidden="true"></i> <?php echo count($admins); ?></label>
            <p class="card-text">Admins</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Student Table Container -->
    <div id="student-table-container" class="table-container" style="display: none;">
      <h5 class="mt-4">Students</h5>
      <div id="student-table"></div>
    </div>
    
    <!-- Admin Table Container -->
    <div id="admin-table-container" class="table-container" style="display: none;">
      <h5 class="mt-4">System Admins</h5>
      <div id="admin-table"></div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script>
    function loadStudents() {
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("student-table").innerHTML = this.responseText;
        }
      };
      xhttp.open("GET", "fetch_students.php", true); // PHP script to fetch students
      xhttp.send();
    }

    function loadAdmins() {
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("admin-table").innerHTML = this.responseText;
        }
      };
      xhttp.open("GET", "fetch_admins.php", true); // PHP script to fetch admins
      xhttp.send();
    }

    document.getElementById('show-students-link').addEventListener('click', function() {
      document.getElementById('student-table-container').style.display = 'block';
      document.getElementById('admin-table-container').style.display = 'none';
      loadStudents(); // Call the function to load students
    });

    document.getElementById('show-admins-link').addEventListener('click', function() {
      document.getElementById('admin-table-container').style.display = 'block';
      document.getElementById('student-table-container').style.display = 'none';
      loadAdmins(); // Call the function to load admins
    });

    document.getElementById('show-students-card').addEventListener('click', function() {
      document.getElementById('student-table-container').style.display = 'block';
      document.getElementById('admin-table-container').style.display = 'none';
      loadStudents(); // Call the function to load students
    });

    document.getElementById('show-admins-card').addEventListener('click', function() {
      document.getElementById('admin-table-container').style.display = 'block';
      document.getElementById('student-table-container').style.display = 'none';
      loadAdmins(); // Call the function to load admins
    });
  </script>
</body>
</html>
