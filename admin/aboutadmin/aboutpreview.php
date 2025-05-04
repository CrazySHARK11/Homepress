<?php

session_start();
require_once "../../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("location: ../login.php");
}

$errors = [];

try {
    $stmt = $pdo->prepare("SELECT * FROM admin_details WHERE id = 1");
    $stmt->execute();
    $adminDetails = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $errors[] = "An error occurred: " . $e->getMessage();
}



?>

<?php
$adminpanelpath = '../';
include "../components/header.php" ?>
<?php include "../components/navbar.php" ?>

<section>
    <div class="container" style="min-height: 200px;">
        <div class="heading d-flex align-items-center justify-content-between py-5 border-bottom">
            <div>
                <h1>Edit Admin Details</h1>
                <p class="m-0"> Edit the about details page </p>
            </div>

            <div class="d-flex gap-5 align-items-center">
                <a style="background-color: #212529;" class=" px-4 py-2 text-decoration-none fw-medium d-flex align-items-center justify-content-center gap-2 text-white" href="../">
                    <i class="bi bi-house-door fs-5"></i> Home
                </a>
                <a href="./" class="fs-5">Edit</a>
            </div>
        </div>

</section>

<section class="aboutme bg-white py-md-2 py-0 " style="min-height: 430px;">
    <div class="container">
        <div class="row py-5 gap-3 justify-content-center align-items-center">

            <img src="../../uploads/adminassets/<?php echo htmlspecialchars($adminDetails['profile_image'])?>" class="col-5 rounded-circle object-fit-cover p-3" style="background-color: #e1e1e1 ; width: 500px; height: 500px;" alt="">

            <div class="col-12 flex-grow-1 d-flex flex-column align-items-center align-items-xl-center gap-3">
                <h2 class="fs-1 fw-semibold " style="color: #18162d;">
                    <?php echo htmlspecialchars($adminDetails['name'])?>
                </h2>

                <p class="d-flex align-items-center gap-3">
                    <span class="d-flex align-items-center gap-2"> <i class="bi fs-4 bi-book-half" style="color: #001eff;"></i> <?php echo htmlspecialchars($adminDetails['university'])?> </span> <span class="text-secondary">|</span>
                    <span class="d-flex align-items-center gap-2"> <i class="bi fs-4 bi-shield-shaded" style="color: #001eff;"></i> <?php echo htmlspecialchars($adminDetails['name'])?> </span> <span class="text-secondary">|</span>
                    <span class="d-flex align-items-center gap-2"> <i class="bi fs-4 bi-house-check" style="color: #001eff;"></i> <?php echo htmlspecialchars($adminDetails['properties_sold'])?> + Properties Sold </span>
                </p>

                <p class="lh-lg fw-normal text-center w-100" style="word-spacing: .5em; color:rgb(102, 102, 102);">
                   <?php echo htmlspecialchars($adminDetails['description'])?>
                </p>

                <div class="links d-flex gap-4">
                    <a href="./properties.php" class="fs-6 fw-semibold">Properties</a>
                    <a href="./properties.php" class="fs-6 fw-semibold">Consult</a>
                    <a href="./properties.php" class="fs-6 fw-semibold">Services</a>
                </div>

                <div class="socials mt-3">
                    <p class="fw-bold d-flex align-items-center gap-3">
                        Follow :
                        <a href="<?php echo htmlspecialchars($adminDetails['instagram'])?>" style="color: #8e8e8e;">
                            <i class="bi fs-4 bi-instagram"></i>
                        </a>
                        <a href="<?php echo htmlspecialchars($adminDetails['facebook'])?>" style="color: #8e8e8e;">
                            <i class="bi fs-4 bi-facebook"></i>
                        </a>
                        <a href="<?php echo htmlspecialchars($adminDetails['twitter_x'])?>" style="color: #8e8e8e;">
                            <i class="bi fs-4 bi-twitter-x"></i>
                        </a>
                        <a href="<?php echo htmlspecialchars($adminDetails['linkedin'])?>" style="color: #8e8e8e;">
                            <i class="bi fs-4 bi-linkedin"></i>
                        </a>
                    </p>
                </div>

                

            </div>

        </div>
    </div>
</section>



<?php include "../components/footer.php" ?>