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

  // Fetch students data
  $sql = "SELECT * FROM students";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    echo "<div style='overflow-x:auto;'>"; // Ensure horizontal scroll if needed
    echo "<style>
            .table td, .table th {
                padding: 0.75rem; /* Adjust the padding as per your preference */
                font-size: 14px; /* Adjust the font size as per your preference */
            }
          </style>";
    echo "<table class='table table-bordered table-striped' style='width: 100%;'>"; // Adjust width as per your requirement
    echo "<thead><tr><th style='width: 20%;'>First Name</th><th style='width: 20%;'>Last Name</th><th style='width: 10%;'>Year of Study</th><th style='width: 30%;'>Email</th><th style='width: 10%;'>Edit</th><th style='width: 10%;'>Delete</th></tr></thead><tbody>";
    while($row = $result->fetch_assoc()) {
      echo "<tr><td>" . $row["fname"]. "</td><td>" . $row["lname"]."</td><td>" . $row["year"]. "</td><td>" . $row["email"]. "</td>";
      echo "<td><a href='edit_admin.php?id=" . $row["email"] . "' class='btn btn-primary btn-sm'>Edit</a></td>"; // Edit button linking to edit.php with student id as parameter
      echo "<td><a href='delete_admin.php?id=" . $row["email"] . "' class='btn btn-danger btn-sm'>Delete</a></td>"; // Delete button linking to delete.php with student id as parameter
      echo "</tr>";
    }
    echo "</tbody></table>";
    echo "</div>";
  } else {
    echo "0 results";
  }

  $conn->close();
?>
