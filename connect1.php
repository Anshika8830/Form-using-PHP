<?php
// Connect to the database
$conn = new mysqli('localhost', 'username', 'password', 'clothing_recommendation');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Simulated user preferences (ratings)
$user_id = 1; // Replace with the actual user's ID
$user_ratings = [
    ['product_id' => 1, 'rating' => 5],
    ['product_id' => 2, 'rating' => 4],
];

// Calculate clothing recommendations
$recommendations = [];

foreach ($user_ratings as $rating) {
    $product_id = $rating['product_id'];
    $rating = $rating['rating'];

    $sql = "SELECT name, AVG(rating) as avg_rating FROM user_preferences 
            INNER JOIN products ON user_preferences.product_id = products.id
            WHERE product_id != $product_id
            GROUP BY name
            HAVING avg_rating >= $rating
            ORDER BY avg_rating DESC";
    
    $result = $conn->query($sql);
    
    while ($row = $result->fetch_assoc()) {
        $recommendations[] = $row['name'];
    }
}

// Remove duplicates from recommendations
$recommendations = array_unique($recommendations);

// Display recommendations
echo "Recommended Clothing: ";
foreach ($recommendations as $product) {
    echo $product . ", ";
}

// Close the database connection
$conn->close();
?>
