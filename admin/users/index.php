<?php

session_start();
require_once "../../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("location: ../login.php");
}

//  Fecth all users
try {
    $stmt = $pdo->prepare("SELECT * FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<?php
$adminpanelpath = '../';
include "../components/header.php" ?>
<?php include "../components/navbar.php" ?>

<section class="dashboard">
    <div class="container" style="min-height: 100vh;">

        <div class="row">
            <div class="col-md-12">
                <div class="heading d-flex align-items-center justify-content-between py-5 border-bottom">
                    <div>
                        <h1>All the users</h1>
                        <p class="m-0"> All the signed up users </p>
                    </div>

                    <div class="d-flex gap-3">
                        <a style="background-color: #212529;" class=" px-4 py-2 text-decoration-none fw-medium d-flex align-items-center justify-content-center gap-2 text-white" href="../">
                            <i class="bi bi-house-door fs-5"></i> Home
                        </a>
 
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Location</th>
                            <th>Phone Number</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id'] ?></td>
                                <td><?php echo $user['username'] ?></td>
                                <td><?php echo $user['email'] ?></td>
                                <td><?php echo $user['location'] ?></td>
                                <td><?php echo $user['phone_number'] ?></td>
                                <td>
                                    <?php echo date('d F, Y', strtotime($user['created_at'])); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php include "../components/footer.php" ?>