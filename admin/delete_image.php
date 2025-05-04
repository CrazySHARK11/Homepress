 <?php
   require_once '../config/db.php';

   if (isset($_GET['id'])) {
    $image_id = intval($_GET['id']);
    try {
        $stmt = $pdo->prepare("SELECT image_name FROM property_images WHERE id = :id");
        $stmt->execute(['id' => $image_id]);
        $image = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($image) {
            $image_path = "../uploads/" . $image['image_name'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }

            $stmt_delete = $pdo->prepare("DELETE FROM property_images WHERE id = :id");
            $stmt_delete->execute(['id' => $image_id]);

            header("Location: ./edit_property.php?id=" . $_GET['property_id']);
            exit;
        } else {
            $errors[] = "Image not found.";
        }
    } catch (PDOException $e) {
        $errors[] = "Error deleting image: " . $e->getMessage();
    }
 } else{
    echo "No id exist ";
 }
?>