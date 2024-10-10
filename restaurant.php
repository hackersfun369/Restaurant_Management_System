<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "restraurent";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search_results = [];
$error_message = "";
$dishname = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["dish_name"])) {
        $dishname = trim($_POST["dish_name"]);

        if (!empty($dishname)) {
            $sql = "SELECT r.name AS resname, r.rating AS resrating, r.address AS resadd, r.avg_cost_for_two AS avgcost, d.name AS dishname, d.price AS price 
                    FROM restaurants r 
                    JOIN dishes d ON r.id = d.restaurant_id 
                    WHERE d.name LIKE ?";

            if ($stmt = $conn->prepare($sql)) {
                $likedish = "%" . $dishname . "%";
                $stmt->bind_param("s", $likedish);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $search_results[] = $row;
                    }
                } else {
                    $error_message = "No results found. Search Using Any Other Keywords";
                }
                $stmt->close();
            } else {
                $error_message = "Failed to prepare the SQL statement.";
            }
        } else {
            $error_message = "Please enter a dish name to search.";
        }
    } else {
        $error_message = "Dish name not set.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food | Search</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <style>
        h4{
            margin-top:100px;
        }
        .result-item {
            border: 1px solid #ccc;
            border-radius: 20px;
            padding: 15px;
            margin-left: 30px;
            margin-bottom:10px;
            background-color: #f9f9f9;
            display:flex;
            width:70%;
            flex-direction:row;

        }
        .result-item:nth-child(odd) {
            background-color: #f0f0f0;
            margin-left:20%;
            margin-right:0
}
        .result-item:nth-child(1){
            margin-top:120px
        }

        .result-item img{
            width:300px;
            height:100%;
            border-radius:20px;
            margin-left:50px;
            margin-right:100px
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
    <a href="./ressearch.php">Restaurant</a>
    <a href="./index.html#contact">Contact Us</a>
    <a href="./myreservations.php">My_Reservations  </a>
    </div>
  </nav>

    <div class="result">
        <?php if (!empty($error_message)) : ?>
            <h4><?php echo htmlspecialchars($error_message); ?></h4>
        <?php elseif (!empty($search_results)) : ?>
            <?php foreach ($search_results as $result) : ?>
                <div class="result-item">
                <img src="https://image.pollinations.ai/prompt/<?php echo urlencode($result['dishname']); ?>" alt="">
                    <div class="div-para">
                    <h3><?php echo htmlspecialchars($result["dishname"]); ?></h3>
                    <p><strong>Rating:</strong> <?php echo htmlspecialchars($result["resrating"]); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($result["resadd"]); ?></p>
                    <p><strong>Average Cost for Two:</strong> ₹<?php echo htmlspecialchars($result["avgcost"]); ?></p>
                    <p><strong>Restaurant Name:</strong> <?php echo htmlspecialchars($result["resname"]); ?></p>
                    <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($result["price"]); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
