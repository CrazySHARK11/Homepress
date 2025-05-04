<?php
require_once "../config/db.php";

$errors = [];

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("location: ./login.php");
}

try {
    $sql = "SELECT * FROM contact_messages";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $messages = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Something went wrong: " . $e->getMessage();
}

?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>



<?php
$adminpanelpath = './';
include "./components/header.php" ?>
<?php include "./components/navbar.php" ?>

<section class="dashboard">
    <div class="container " style="min-height: 800px;">

        <div class="heading d-flex align-items-center justify-content-between py-5 border-bottom">
            <div>
                <h1>Create a Property</h1>
                <p class="m-0">create a property and list today !</p>
            </div>

            <div class="d-flex gap-3">
                <a style="background-color: #212529;" class=" px-4 py-2 text-decoration-none fw-medium d-flex align-items-center justify-content-center gap-2 text-white" href="./">
                    <i class="bi bi-house-door fs-5"></i> Home
                </a>
            </div>
        </div>

        <div class="">
            <table class="table table-  table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th class="text-center"> Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $message): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($message['id']) ?></td>
                            <td><?php echo htmlspecialchars($message['username']) ?></td>
                            <td><?php echo htmlspecialchars($message['phone']) ?></td>
                            <td><?php echo htmlspecialchars($message['email']) ?></td>
                            <td style="width: 350px;">
                                <p style="width: 300px;" class="m-0 text-truncate"><?php echo htmlspecialchars($message['message']) ?></p>
                            </td>
                            <td class="text-center">
                                <?php if ($message['responded'] == 1):  ?>
                                    <i class="bi bi-shield-fill-check " style="color: #1ba309;"></i>
                                <?php else: ?>
                                    <i class="bi bi-dash-circle-fill " style="color:#c0c000;"></i>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="update_enq.php?id=<?php echo htmlspecialchars($message['id']) ?>" class="edit_query text-decoration-none"><i class="bi  bi-pencil-square"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</section>

<?php include "./components/footer.php"; ?>