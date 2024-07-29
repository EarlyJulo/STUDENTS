<?php
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

$sql = "SELECT * FROM admin";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo '<table class="table table-bordered" style="table-layout: fixed; width: 100%;">';
  echo '<thead class="table-dark"><tr><th style="width: 25%;">Username</th><th style="width: 30%;">Email</th><th style="width: 20%;">Mobile</th><th style="width: 25%;">Actions</th></tr></thead>';
  echo '<tbody>';
  
  while($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $row['username'] . '</td>';
    echo '<td>' . $row['email'] . '</td>';
    echo '<td>' . $row['mobile'] . '</td>';
    echo '<td>
    <button class="btn btn-primary btn-sm text_light">
      <a href="edit_admin.php?updateid=' .$row['id']. '" class="text-white"><i class="fas fa-edit"></i></a>
    </button> 
    <button class="btn btn-danger btn-sm">
      <a href="delete_admin.php?deleteid=' .$row['id']. '" class="text-white"><i class="fas fa-trash-alt"></i></a>
    </button>
  </td>';
    echo '</tr>';
  }

  echo '</tbody>';
  echo '</table>';

  // Add Admin button
  echo '<div class="text-center mt-3">';
  echo '<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal"><a href="add_admin.php"><i class="fas fa-plus"></i> Add Admin</button></a>';
  echo '</div>';

} else {
  echo '<p>No admins found.</p>';
}

$conn->close();
?>
