<?php
// Include database connection file
require_once('../config/db.php');

// Get the property ID from the URL
$property_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($property_id > 0) {
    // Fetch property images from the database
    $query = "SELECT image_name FROM property_images WHERE property_id = :property_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':property_id', $property_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Delete images from the server
    foreach ($result as $row) {
        $image_path = '../uploads/' . $row['image_name'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    // Delete images from the database
    $query = "DELETE FROM property_images WHERE property_id = :property_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':property_id', $property_id, PDO::PARAM_INT);
    $stmt->execute();

    // Delete the property from the database
    $query = "DELETE FROM properties WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $property_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect to the properties list page
    header("Location: properties.php");
    exit();
} else {
    echo "Invalid property ID.";
}
?>