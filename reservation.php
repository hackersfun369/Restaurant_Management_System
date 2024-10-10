<?php
$servername = "localhost";
$username = "root";   
$password = "";          
$dbname = "restraurent";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $restaurant_name = $conn->real_escape_string($_POST['restaurant_name']);
    $guest_name = $conn->real_escape_string($_POST['guest_name']);
    $reservation_time = $conn->real_escape_string($_POST['reservation_time']);
    $guest_contact = $conn->real_escape_string($_POST['guest_contact']);
    $party_size = (int)$_POST['party_size'];
    $table_number = (int)$_POST['table_number'];

    $stmt = $conn->prepare("INSERT INTO RestaurantReservations (restaurant_name, guest_name, reservation_time, guest_contact, party_size, table_number) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $restaurant_name, $guest_name, $reservation_time, $guest_contact, $party_size, $table_number);

    if ($stmt->execute()) {
        echo "<h2>Reservation successfully created!</h2>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Reservation Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="datetime-local"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        h2{
            color:green;
        }
        .reserves{
            text-decoration:none;
            position:absolute;
            top:50px;
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
<h3>Restaurant Reservation Form</h3>
<a class="reserves" href="./myreservations.php">My_Reservations</a>
<form action="" method="POST">
    <label for="restaurant_name">Restaurant Name:</label>
    <select id="restaurant" name="restaurant_name" required>
        <option value="">--Select a Restaurant--</option>
        <option value="Bay 6">Bay 6</option>
        <option value="Sea Tales Restaurant">Sea Tales Restaurant</option>
        <option value="Palmshore Restaurant Medavakkam">Palmshore Restaurant Medavakkam</option>
        <option value="Kipling Cafe">Kipling Cafe</option>
        <option value="Delhi Highway Restaurant">Delhi Highway Restaurant</option>
        <option value="Famous Theory">Famous Theory</option>
        <option value="The Farm Restaurant">The Farm Restaurant</option>
        <option value="Seasonal Tastes">Seasonal Tastes</option>
        <option value="Dindigul Thalappakatti<">Dindigul Thalappakatti</option>
        <option value="Southern Spice">Southern Spice</option>
    </select>

    <label for="guest_name">Guest Name:</label>
    <input type="text" id="guest_name" name="guest_name" required>

    <label for="reservation_time">Reservation Time:</label>
    <input type="datetime-local" id="reservation_time" name="reservation_time" required>

    <label for="guest_contact">Contact Information:</label>
    <input type="text" id="guest_contact" name="guest_contact" required>

    <label for="party_size">Number of People:</label>
    <input type="number" id="party_size" name="party_size" min="1" required>

    <label for="table_number">Table Number:</label>
    <input type="number" id="table_number" name="table_number" required>

    <input type="submit" value="Submit Reservation">
</form>

</body>
</html>