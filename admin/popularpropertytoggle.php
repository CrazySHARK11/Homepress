<?php
require '../config/db.php'; // Include your database connection file

if (isset($_GET['id'])) {
    $property_id = $_GET['id'];

    try {
        // Get current popular status
        $stmt = $pdo->prepare("SELECT popular FROM properties WHERE id = :id");
        $stmt->execute([':id' => $property_id]);
        $property = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($property) {
            // Toggle the popular status
            $new_status = $property['popular'] ? 0 : 1;
            $updateStmt = $pdo->prepare("UPDATE properties SET popular = :popular WHERE id = :id");
            $updateStmt->execute([':popular' => $new_status, ':id' => $property_id]);
        }

        // Redirect back to the property listing page
        header("Location: properties.php");
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die("Invalid request.");
}
?>
