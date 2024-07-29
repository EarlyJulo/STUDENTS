<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
      .sidebar {
        background-color: #241749;
        padding: 20px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        width: 250px;
        border: 2px solid #fff; /* White border */
        border-radius: 10px; /* Rounded corners */
        box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for better visual effect */
      }
      .content {
        margin-left: 270px; /* Add space for the sidebar */
        padding: 20px;
      }
      .nav-link {
        color: white; /* Ensure text is readable on blue background */
      }
      .nav-link.active {
        color: lightblue; /* Different color for active link */
      }
      .card-label {
        font-size: 3rem; /* Huge font size */
        font-weight: bold; /* Bold text */
      }

      .custom-card {
        background-color: #241749; /* Orange background color */
        color: white; /* White text color */
        border: none; /* Remove border */
        border-radius: 10px; /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Light shadow */
      }

      @media (max-width: 992px) {
        .content {
          margin-left: 0; /* Reset margin for smaller screens */
        }
        .card {
          width: 100%; /* Ensure cards take full width on smaller screens */
          margin-bottom: 20px; /* Add spacing between cards */
        }
      }

      .table-container {
        max-width: 40%; /* Set a fixed maximum width for the table container */
        overflow-x: auto; /* Add horizontal scroll if content overflows */
        margin-top: 20px; /* Add some space above the table */
      }

      table {
        width: 100%; /* Ensure the table takes the full width of its container */
      }
    </style>
  </head>
  <body>
    <?php
      // Database connection
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "school";

      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);

      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      // Get the number of students
      $sql = "SELECT COUNT(*) as student_count FROM students";
      $result = $conn->query($sql);
      $student_count = 0;

      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $student_count = $row['student_count'];
      } else {
        echo "0 results";
      }

      $conn->close();
    ?>

    <div class="sidebar">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link" href="#"><i class="fa fa-bars" aria-hidden="true"></i>  Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" onclick="loadStudents()"><i class="fa fa-users" aria-hidden="true"></i> Students</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"> <i class="fa fa-book" aria-hidden="true"></i> Updates</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-user-secret" aria-hidden="true"></i> System Admins</a>
        </li>
      </ul>
    </div>

    <div class="content container-fluid">
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card custom-card">          
            <div class="card-body">
              <h5 class="card-title">Students</h5>
              <label class="card-label"><i class="fa fa-users" aria-hidden="true"></i>  <?php echo $student_count; ?></label>
              <p class="card-text">Registered students</p>
            </div>
          </div>
        </div>
       
        <div class="col-md-4">
          <div class="card custom-card">
            <div class="card-body">
              <h5 class="card-title"> System Admins</h5>
              <label class="card-label"><i class="fa fa-user-secret" aria-hidden="true"></i> <?php echo $student_count; ?></label>
              <p class="card-text">Admins</p>
            </div>
          </div>
        </div>
        
      </div>
      <div id="student-table" class="mt-4 table-container"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script>
      function loadStudents() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("student-table").innerHTML = this.responseText;
          }
        };
        xhttp.open("GET", "fetch_students.php", true);
        xhttp.send();
      }
    </script>
  </body>
</html>
