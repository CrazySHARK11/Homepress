<?php
require_once "../config/db.php";

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("location: ./login.php");
}

// Fetch total users
$userQuery = "SELECT COUNT(*) as total_users FROM users";
$userStmt = $pdo->query($userQuery);
$userCount = $userStmt->fetch(PDO::FETCH_ASSOC)['total_users'];

// Fetch total properties
$propertyQuery = "SELECT COUNT(*) as total_properties FROM properties";
$propertyStmt = $pdo->query($propertyQuery);
$propertyCount = $propertyStmt->fetch(PDO::FETCH_ASSOC)['total_properties'];

// Fetch total enquiries
$enquiryQuery = "SELECT COUNT(*) as total_enquiries FROM property_enq";
$enquiryStmt = $pdo->query($enquiryQuery);
$enquiryCount = $enquiryStmt->fetch(PDO::FETCH_ASSOC)['total_enquiries'];
?>

<?php 
$adminpanelpath = './';
include "./components/header.php" ?>
<?php include "./components/navbar.php" ?>

<section class="dashboard">
    <div class="container" style="min-height: 800px;">
        <div class="row py-5 border-bottom">
            <div class="col">
                <div class="card d-flex align-items-center flex-row justify-content-between">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text"><?php echo $userCount ?></p>
                    </div>

                    <a href="./users" class="p-3">
                        <i class="bi fs-6 bi-box-arrow-up-right"></i>
                    </a>
                </div>
            </div>
            <div class="col">
                <div class="card d-flex align-items-center flex-row justify-content-between">
                    <div class="card-body">
                        <h5 class="card-title">Total Properties</h5>
                        <p class="card-text"><?php echo $propertyCount ?></p>
                    </div>

                    <a href="./properties.php" class="p-3">
                        <i class="bi fs-6 bi-box-arrow-up-right"></i>
                    </a>
                </div>
            </div>
            <div class="col">
                <div class="card  d-flex align-items-center flex-row justify-content-between">
                    <div class="card-body">
                        <h5 class="card-title">Total Enquiries</h5>
                        <p class="card-text"><?php echo $enquiryCount ?></p>
                    </div>

                    <a href="./enquiries.php" class="p-3">
                        <i class="bi fs-6 bi-box-arrow-up-right"></i>
                    </a>
                </div>
            </div>
           
        </div>

        <div class="dashboard-functionalities">
            <div class="row mt-5 flex-wrap gap-4">

                <div class="col-3 flex-grow-1 px-4 d-flex align-items-center justify-content-between py-4 rounded shadow-sm" style="background-color: color-mix(in srgb, #fff 80%, #001eff 20%);">
                    <div>
                        <h2 class="text-black">Add a Property</h2>
                        <p class="text-secondary m-0">add a property today</p>
                    </div>
                    <a href="./addproperty.php" class="p-3">
                        <i class="bi fs-6 bi-box-arrow-up-right"></i>
                    </a>
                </div>
                <div class="col-3 flex-grow-1 px-4 d-flex align-items-center justify-content-between py-4 rounded shadow-sm" style="background-color: color-mix(in srgb, #fff 80%, #001eff 20%);">
                    <div>
                        <h2 class="text-black">Edit Admin details</h2>
                        <p class="text-secondary m-0">Edit admin details</p>
                    </div>
                    <a href="./aboutadmin" class="p-3">
                        <i class="bi fs-6 bi-box-arrow-up-right"></i>
                    </a>
                </div>

            </div>
        </div>


</section>

<?php include "./components/footer.php" ?>
<?php
if (isset($_SESSION['admin_login_success'])) {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.mixin({
                toast: true,
                position: "top",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                  toast.onmouseenter = Swal.stopTimer;
                  toast.onmouseleave = Swal.resumeTimer;
                }
            }).fire({
               icon: "success",
               title: "Signed in successfully"
             });
        });
    </script>';
    unset($_SESSION['admin_login_success']); // Remove session message after showing
}
?>
<?php
if (isset($_SESSION['property_success_code'])) {
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