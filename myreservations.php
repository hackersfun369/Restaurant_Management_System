<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "restraurent";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM RestaurantReservations";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>All Reservations</h2>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr>
            <th>Reservation ID</th>
            <th>Restaurant Name</th>
            <th>Guest Name</th>
            <th>Reservation Time</th>
            <th>Contact Information</th>
            <th>Party Size</th>
            <th>Table Number</th>
          </tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['reservation_id'] . "</td>
                <td>" . htmlspecialchars($row['restaurant_name']) . "</td>
                <td>" . htmlspecialchars($row['guest_name']) . "</td>
                <td>" . htmlspecialchars($row['reservation_time']) . "</td>
                <td>" . htmlspecialchars($row['guest_contact']) . "</td>
                <td>" . $row['party_size'] . "</td>
                <td>" . $row['table_number'] . "</td>
              </tr>";
    }
    
    echo "</table>";
} else {
    echo "<h2>No reservations found.</h2>";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My_Reservations</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <style>
        h2{
            margin-top:120px;
            margin-left:5%;
        }
        table{
            margin-top:20px;
            width:90%;
            margin-left:5%;
        }
        .reserve{
            text-decoration:none;
            position:absolute;
            top:120px;
            right:10%;
            padding:10px;
            border:2px solid green;
            background:green;
            border-radius:2px;
            color:white;
            z-index: 0;
        }
    </style>
</head>
<body>
<form action="restaurant.php" method="post">
    <input type="text" placeholder="Tandoori Chicken, Paneer Tikka..." name="dish_name">
    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
  </form>
<nav>
    <i class="fa-solid fa-bowl-food"></i>
    <div class="nav-a">
    <a href="./index.html">Home</a>
    <a href="">Restaurant</a>
    <a href="./index.html#contact">Contact Us</a>

    </div>
  </nav>
  <a class="reserve" href="./reservation.php">Add Reservation</a>
</body>
</html>