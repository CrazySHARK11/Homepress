<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('location: ../login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['property_id'])) {
    $user_id = $_SESSION['user_id'];
    $property_id = $_GET['property_id'];

    try {
        // Check if the property is already liked by the user
        $query = "SELECT id FROM liked_properties WHERE user_id = :user_id AND property_id = :property_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['user_id' => $user_id, 'property_id' => $property_id]);

        if ($stmt->rowCount() > 0) {
            // Unlike: Remove the like from the database
            $query = "DELETE FROM liked_properties WHERE user_id = :user_id AND property_id = :property_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['user_id' => $user_id, 'property_id' => $property_id]);
        } else {
            // Like: Insert the like into the database
            $query = "INSERT INTO liked_properties (user_id, property_id) VALUES (:user_id, :property_id)";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['user_id' => $user_id, 'property_id' => $property_id]);
        }

        header('location: ../property.php?id=' . $property_id);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
