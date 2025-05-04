<?php
require_once "../config/db.php";

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("location: ./login.php");
}

try {
    $stmt = $pdo->prepare("SELECT * FROM properties ORDER BY created_at DESC");
    $stmt->execute();
    $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<?php $adminpanelpath = './';
include "./components/header.php" ?>
<?php include "./components/navbar.php" ?>

<section class="dashboard">
    <div class="container" style="min-height: 800px;">

        <div class="heading d-flex align-items-center justify-content-between py-5 border-bottom">
            <div>
                <h1>All Properties</h1>
                <p class="m-0">All Your Properties </p>
            </div>

            <div class="d-flex gap-3">
                <a style="background-color: #212529;" class=" px-4 py-2 text-decoration-none fw-medium d-flex align-items-center justify-content-center gap-2 text-white" href="./">
                    <i class="bi bi-house-door fs-5"></i> Home
                </a>
            </div>
        </div>


        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th style="width: 300px;">Name</th>
                    <th style="width: 300px;">Location</th>
                    <th class="text-center">Featured</th>
                    <th class="text-center">Popular</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($properties as $property): ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($property['id']); ?></th>
                        <td>
                            <p style="width: 240px;" class="m-0 text-truncate"> <?php echo htmlspecialchars($property['title']); ?> </p>
                        </td>
                        <td>
                            <p style="width: 240px;" class="m-0 text-truncate"> <?php echo htmlspecialchars($property['location']); ?> </p>
                        </td>
                        <td class="text-center"> <a href="feturedpropertytoggle.php?id=<?php echo htmlspecialchars($property['id']); ?>" class="text-decoration-none"><i class="bi bi-star-fill" style="color: <?php echo $property['featured'] ? 'gold' : 'gray' ?> ;"></i></a> </td>
                        <td class="text-center"> <a href="popularpropertytoggle.php?id=<?php echo htmlspecialchars($property['id']); ?>" class="text-decoration-none"><i class="bi bi-eye-fill" style="color: <?php echo $property['popular'] ? 'green' : 'gray' ?> ;"></i></a> </td>
                        <td>
                            <?php echo number_format(htmlspecialchars($property['current_price']), 2, '.', ','); ?>
                        </td>

                        <td class="d-flex align-items-center gap-3">
                            <a href="edit_property.php?id=<?php echo $property['id']; ?>" class="text-decoration-none fw-bold">Edit</a>
                            <span class="text-secondary">|</span>
                            <a href="delete_property.php?id=<?php echo $property['id']; ?>" class="text-decoration-none text-danger fw-bold" onclick="event.preventDefault(); confirmDelete(<?php echo $property['id']; ?>);">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


    </div>
</section>

<script>
    function confirmDelete(propertyId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'delete_property.php?id=' + propertyId;
            }
        });
    }
</script>

<?php include "./components/footer.php" ?>


<?php
if (isset($_SESSION['property_edited_success_message'])) {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: "success",
                title: "Edit Successfull !",
                text: "Property Updated successfully.",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            });
        });
    </script>';
    unset($_SESSION['property_edited_success_message']);
}
?>

<?php
if (isset($_SESSION['property_addded_success_code'])) {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: "success",
                title: "Property added Successfully !",
                text: "Property has been added successfully.",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            });
        });
    </script>';
    unset($_SESSION['property_success_code']); // Remove session message after showing
}
?>