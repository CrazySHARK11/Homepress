<?php
require '../config/db.php';
if (isset($_GET['id'])) {
    $property_id = $_GET['id'];

    try {
        // Get current featured status
        $stmt = $pdo->prepare("SELECT featured FROM properties WHERE id = :id");
        $stmt->execute([':id' => $property_id]);
        $property = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($property) {
            // Toggle the featured status
            $new_status = $property['featured'] ? 0 : 1;
            $updateStmt = $pdo->prepare("UPDATE properties SET featured = :featured WHERE id = :id");
            $updateStmt->execute([':featured' => $new_status, ':id' => $property_id]);

            // Redirect back with a success message
            header("Location: properties.php");
            exit;
        } else {
            echo "Property not found.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
