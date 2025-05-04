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

    if (!$adminDetails) {
        throw new Exception("Admin details not found.");
    }
} catch (Exception $e) {
    $errors[] = "An error occurred: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $ownername = $_POST['ownername'];
        $university = $_POST['category'];
        $description = $_POST['description'];
        $property_sold = $_POST['propertiessold'];
        $tagline = $_POST['tagline'];
        $facebook = $_POST['facebook'];
        $instagram = $_POST['instagram'];
        $twitter = $_POST['Twitter'];
        $linkedin = $_POST['linkedin'];
        $phonenumber = $_POST['phonenumber'];
        $officephnumber = $_POST['officephnumber'];
        $email = $_POST['email'];
        $location = $_POST['location'];
        $google_map_src = $_POST['google_map_src'];
        $openingtime = $_POST['openingtime'];
        $closingtime = $_POST['closingtime'];

        // Image upload
        if (isset($_FILES['images']) && $_FILES['images']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $file_name = $_FILES['images']['name'];
            $file_size = $_FILES['images']['size'];
            $file_tmp = $_FILES['images']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (in_array($file_ext, $allowed) && $file_size <= 1048576) {
            $unique_name = uniqid() . '.' . $file_ext;
            $upload_dir = '../../uploads/adminassets/' . $unique_name;
            if (move_uploaded_file($file_tmp, $upload_dir)) {
                // Delete the existing image
                $stmt = $pdo->prepare("SELECT profile_image FROM admin_details WHERE id = 1");
                $stmt->execute();
                $existing_image = $stmt->fetchColumn();
                if ($existing_image && file_exists('../../uploads/adminassets/' . $existing_image)) {
                unlink('../../uploads/adminassets/' . $existing_image);
                }
                $image_path = $upload_dir;
            } else {
                $errors[] = "Failed to upload image.";
            }
            } else {
            $errors[] = "Invalid file type or size. Only JPG and PNG files under 1MB are allowed.";
            }
        } 


        if (empty($errors)) {
            $stmt = $pdo->prepare("UPDATE admin_details SET name = ?, university = ?, description = ?, properties_sold = ?, small_tagline = ?, facebook = ?, instagram = ?, twitter_x = ?, linkedin = ?, phone_number = ?, office_number = ?, email = ?, location = ?, google_map_src = ?, open_hours = ?, closing_hours = ?" . (isset($image_path) ? ", profile_image = ?" : "") . " WHERE id = 1");

            $params = [$ownername, $university, $description, $property_sold, $tagline, $facebook, $instagram, $twitter, $linkedin, $phonenumber, $officephnumber, $email, $location, $google_map_src, $openingtime, $closingtime];
            if (isset($image_path)) {
                $params[] = $unique_name;
            }

            $stmt->execute($params);
            header("Location: success.php");
            
        }
    } catch (Exception $e) {
        $errors[] = "An error occurred: " . $e->getMessage();
    }
}


?>

<?php
$adminpanelpath = '../';
include "../components/header.php" ?>
<?php include "../components/navbar.php" ?>

<section>
    <div class="container" style="min-height: 900px;">
        <div class="heading d-flex align-items-center justify-content-between py-5 border-bottom">
            <div>
                <h1>Edit Admin Details</h1>
                <p class="m-0"> Edit the about details page </p>
            </div>

            <div class="d-flex gap-5 align-items-center">
                <a style="background-color: #212529;" class=" px-4 py-2 text-decoration-none fw-medium d-flex align-items-center justify-content-center gap-2 text-white" href="../">
                    <i class="bi bi-house-door fs-5"></i> Home
                </a>
                <a href="./aboutpreview.php" class="fs-5">Preview</a>
            </div>
        </div>
        
        <form action="index.php" method="post" enctype="multipart/form-data" class="row flex-column align-items-center gap-4 mt-5">
        <div class=" w-75">
        <img src="../../uploads/adminassets/<?php echo htmlspecialchars($adminDetails['profile_image']) ?>" class="object-fit-cover rounded-circle" width="300px" height="300px" alt="">
        </div>


            <?php if (!empty($errors)): ?>
                <div class="bg-transparent w-75 rounded-pill alert px-3 p-2 d-flex align-items-center justify-content-between alert-danger mt-4 alert-dismissible fade show" role="alert">
                    <strong>
                        <?php foreach ($errors as $error): ?>
                            <p class="m-0"><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </strong>
                    <i class="bi bi-x-circle cursor-pointer" data-bs-dismiss="alert" aria-label="Close"></i>
                </div>
            <?php endif; ?>

            <span class="d-flex flex-column w-75">
                <label for="ownername" class="form-label fs-5">Name</label>
                <input type="text" value="<?php echo htmlspecialchars($adminDetails['name']) ?>" name="ownername" id="ownername" placeholder="Owner's Name" class="border border-secondary border-1 px-3 py-3" required>
            </span>

            <div class="row prices w-75 gap-3">
                <div class="col px-0">
                    <span class="d-flex flex-column">
                        <label for="category" class="form-label fs-5">University Studied in </label>
                        <input type="text" value="<?php echo htmlspecialchars($adminDetails['university']) ?>" name="category" placeholder="University" id="category" class="border border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
                <div class="col px-0">
                    <span class="d-flex flex-column">
                        <label for="images" class="form-label fs-5">Admin Image</label>
                        <input type="file" name="images" id="images" class="form-control rounded-0 border border-secondary border-1 px-3 py-3" multiple>
                    </span>
                </div>
            </div>

            <span class="d-flex flex-column w-75">
                <label for="description" class="form-label fs-5">Description</label>
                <textarea type="text" name="description" rows="10" id="description" placeholder="Owner's Name" class="border border-secondary border-1 px-3 py-3"><?php echo htmlspecialchars($adminDetails['description']) ?>
                </textarea>
            </span>

            <div class="row prices w-75 gap-3">
                <div class="col px-0">
                    <span class="d-flex flex-column">
                        <label for="propertiessold" class="form-label fs-5">Approx property sold</label>
                        <input type="text" value="<?php echo htmlspecialchars($adminDetails['properties_sold']) ?>" name="propertiessold" placeholder="100 + property solved " id="propertiessold" class="border border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
                <div class="col px-0">
                    <span class="d-flex flex-column">
                        <label for="tagline" class="form-label fs-5">Tagline</label>
                        <input type="text" value="<?php echo htmlspecialchars($adminDetails['small_tagline']) ?>" placeholder="Best seller at properties" name="tagline" id="tagline" class="form-control rounded-0 border border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
            </div>

            <div class="row w-75 justify-content-center gap-3 my-3">
                <div class="col-5 px-0">
                    <span class="input-group">
                        <label for="facebook" class=" fs-5 rounded-0 border border-black input-group-text" id="basic-addon1">
                            <i class="bi bi-facebook"></i>
                        </label>
                        <input type="text" value="<?php echo htmlspecialchars($adminDetails['facebook']) ?>" placeholder="Facebook link" name="facebook" id="facebook" aria-describedby="basic-addon1" class="border flex-grow-1 border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
                <div class="col-5 px-0">
                    <span class="input-group">
                        <label for="instagram" class=" fs-5 rounded-0 border border-black input-group-text" id="basic-addon1">
                            <i class="bi bi-instagram"></i>
                        </label>
                        <input type="text" value="<?php echo htmlspecialchars($adminDetails['instagram']) ?>" placeholder="Instagram Link" name="instagram" id="instagram" aria-describedby="basic-addon1" class="border flex-grow-1 border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
                <div class="col-5 px-0">
                    <span class="input-group">
                        <label for="Twitter" class=" fs-5 rounded-0 border border-black input-group-text" id="basic-addon1">
                            <i class="bi bi-twitter-x"></i>
                        </label>
                        <input type="text" value="<?php echo htmlspecialchars($adminDetails['twitter_x']) ?>" placeholder="X link" name="Twitter" id="Twitter" aria-describedby="basic-addon1" class="border flex-grow-1 border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
                <div class="col-5 px-0">
                    <span class="input-group">
                        <label for="linkedin" class=" fs-5 rounded-0 border border-black input-group-text" id="basic-addon1">
                            <i class="bi bi-linkedin"></i>
                        </label>
                        <input type="text" value="<?php echo htmlspecialchars($adminDetails['linkedin']) ?>" placeholder="Linkedin Link" name="linkedin" id="linkedin" aria-describedby="basic-addon1" class="border flex-grow-1 border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>


            </div>

            <div class="row prices w-75 gap-3">
                <div class="col px-0">
                    <span class="d-flex flex-column">
                        <label for="phonenumber" class="form-label fs-5">Phone Number</label>
                        <input type="number" name="phonenumber" value="<?php echo htmlspecialchars($adminDetails['phone_number']) ?>" placeholder="Ph number" id="phonenumber" class="border border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
                <div class="col px-0">
                    <span class="d-flex flex-column">
                        <label for="officephnumber" class="form-label fs-5">Office Number</label>
                        <input type="number" placeholder="Office Ph number" value="<?php echo htmlspecialchars($adminDetails['office_number']) ?>" name="officephnumber" id="officephnumber" class="form-control rounded-0 border border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
            </div>


            <div class="row prices w-75 gap-3">
                <div class="col px-0">
                    <span class="d-flex flex-column">
                        <label for="email" class="form-label fs-5">E-mail</label>
                        <input type="email" value="<?php echo htmlspecialchars($adminDetails['email']) ?>" name="email" placeholder="info@example.com" id="email" class="border border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
                <div class="col px-0">
                    <span class="d-flex flex-column">
                        <label for="location" class="form-label fs-5">Office Address</label>
                        <input type="text" value="<?php echo htmlspecialchars($adminDetails['location']) ?>" placeholder="office address" name="location" id="location" class="form-control rounded-0 border border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
            </div>

            <span class="d-flex flex-column w-75">
                <label for="google_map_src" class="form-label fs-5">Google Map Link</label>
                <input type="text" name="google_map_src" value="<?php echo htmlspecialchars($adminDetails['google_map_src']) ?>" id="google_map_src" placeholder="Google Map Link" class="border border-secondary border-1 px-3 py-3" required>
            </span>

            <div class="row prices w-75 gap-3">
                <div class="col px-0">
                    <span class="d-flex flex-column">
                        <label for="openingtime" class="form-label fs-5">Open Time</label>
                        <input type="text" value="<?php echo htmlspecialchars($adminDetails['open_hours']) ?>" name="openingtime" id="openingtime" class="border border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
                <div class="col px-0">
                    <span class="d-flex flex-column">
                        <label for="closingtime" class="form-label fs-5">Closing Time</label>
                        <input type="text" value="<?php echo htmlspecialchars($adminDetails['closing_hours']) ?>" name="closingtime" id="closingtime" class="form-control rounded-0 border border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
            </div>

            <div class="row justify-content-center w-100 gap-3">
                <button type="submit" style="background-color: blue;" class="py-3 w-25 my-4 text-white px-5 border-0">
                    Add Admin Details
                </button>
            </div>

        </form>

    </div>
</section>

<?php include "../components/footer.php" ?>