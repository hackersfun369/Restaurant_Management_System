<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "restraurent";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$restaurants = []; // Array to hold restaurant data

$sql = "SELECT r.name AS resname, r.rating AS resrating, r.address AS resadd, r.avg_cost_for_two AS avgcost, d.name AS dishname, d.price AS price 
        FROM dishes d 
        JOIN restaurants r ON r.id = d.restaurant_id 
        ORDER BY r.name";

if ($stmt = $conn->prepare($sql)) {
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        // Group dishes by restaurant
        $restaurants[$row['resname']]['info'] = [
            'rating' => $row['resrating'],
            'address' => $row['resadd'],
            'avgcost' => $row['avgcost'],
        ];
        $restaurants[$row['resname']]['dishes'][] = [
            'name' => $row['dishname'],
            'price' => $row['price']
        ];
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
    <title>Restaurant Search Results</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <style>
        .result-item {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            margin: 10px 0;
            width:100%;
            background-color: #f9f9f9;
            display: flex;
            flex-direction:row;
            height:fit-content;
        }
        .dish-item {
            padding-left: 20px;
            border:2px solid lightgray;
            margin:20px;
            float:left;
            padding:10px;
            border-radius:10px;
            box-shadow: 1px 1px 1px;
            width:30%;
        }
        .dishes{
            display:none;
        }
        .dish-item img{
            width:100px;
            border-radius:10px;
        }
        h4{
            margin-top:120px
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
    <a href="./myreservations.php">My_Reservations</a>
    </div>
  </nav>
    <h4>Restaurant and Dish Listings</h4>
    <div class="results">
        <?php if (empty($restaurants)) : ?>
            <p>No results found.</p>
        <?php else : ?>
            <?php foreach ($restaurants as $resname => $resdata) : ?>
                <div class="result-item" id="result-item">
                    <div class="info" id="info">
                    <h2><?php echo htmlspecialchars($resname); ?></h2>
                    <p><strong>Rating:</strong> <?php echo htmlspecialchars($resdata['info']['rating']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($resdata['info']['address']); ?><a 
                    href="https://www.google.com/maps/search/?api=1&query=<?php echo htmlspecialchars($resname); ?></strong> <?php echo htmlspecialchars($resdata['info']['address']); ?>"> 
                    <i class="fa-solid fa-location-dot"></i></a></p>
                    <p><strong>Average Cost for Two:</strong> ₹<?php echo htmlspecialchars($resdata['info']['avgcost']); ?></p>
                    </div>
                   <div class="dishes" id="dishes">
                   <h3>Dishes:</h3>
                    <?php foreach ($resdata['dishes'] as $dish) : ?>
                        <div class="dish-item" id="dish-item">
                            <img src="https://image.pollinations.ai/prompt/<?php echo htmlspecialchars($dish['name']); ?>" alt="">
                            <p><strong><?php echo htmlspecialchars($dish['name']); ?></strong> - ₹<?php echo htmlspecialchars($dish['price']); ?></p>
                        </div>
                    <?php endforeach; ?>
                   </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        const resultItems = document.querySelectorAll(".result-item");

resultItems.forEach(resultItem => {
    resultItem.addEventListener('click', () => {
        const dishItems = resultItem.querySelectorAll(".dishes");
        dishItems.forEach(dishItem => {
            if (dishItem.style.display === "block") {
                dishItem.style.display = "none";
            } else {
                dishItem.style.display = "block";
            }
        });
    });
});

    </script>
</body>
</html>
