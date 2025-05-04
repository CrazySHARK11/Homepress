<?php
require_once "../config/db.php";
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("location: ./login.php");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $title = trim($_POST['title']);
    $selling_method = $_POST['selling_method'];
    $category = trim($_POST['category']);
    $location = trim($_POST['location']);
    $deleted_price = trim($_POST['deleted_price']);
    $current_price = trim($_POST['current_price']);
    $garage = trim($_POST['garage']);
    $rooms = trim($_POST['rooms']);
    $built_in = trim($_POST['built_in']);
    $square_ft = trim($_POST['square_ft']);
    $bathrooms = trim($_POST['bathrooms']);
    $animities = trim($_POST['animities']);
    $description = trim($_POST['description']);
    $locationlink = trim($_POST['locationlink']);

    // Form Validation
    if (empty($title)) {
        $errors[] = "Title is required.";
    }
    if (empty($selling_method)) {
        $errors[] = "Selling method is required.";
    }
    if (empty($category)) {
        $errors[] = "Category is required.";
    }
    if (empty($location)) {
        $errors[] = "Location is required.";
    }
    if (empty($deleted_price) || !is_numeric($deleted_price)) {
        $errors[] = "Valid deleted price is required.";
    }
    if (empty($current_price) || !is_numeric($current_price)) {
        $errors[] = "Valid current price is required.";
    }
    if (empty($garage) || !is_numeric($garage)) {
        $errors[] = "Valid garage count is required.";
    }
    if (empty($rooms) || !is_numeric($rooms)) {
        $errors[] = "Valid rooms count is required.";
    }
    if (empty($built_in) || !is_numeric($built_in)) {
        $errors[] = "Valid built year is required.";
    }
    if (empty($square_ft) || !is_numeric($square_ft)) {
        $errors[] = "Valid square footage is required.";
    }
    if (empty($bathrooms) || !is_numeric($bathrooms)) {
        $errors[] = "Valid bathrooms count is required.";
    }
    if (empty($animities)) {
        $errors[] = "Amenities are required.";
    }
    if (empty($description)) {
        $errors[] = "Description is required.";
    }
    if (empty($locationlink)) {
        $errors[] = "Google Maps link is required.";
    }
    // Form Validation

    // Image adding to db
    if (empty($errors)) {
        $allowed_formats = ['jpg', 'jpeg', 'png', 'gif'];
        $images = $_FILES['images'];
        $image_paths = [];
        $temp_files = [];

        foreach ($images['name'] as $key => $image_name) {
            $image_tmp_name = $images['tmp_name'][$key];
            $image_size = $images['size'][$key];
            $image_error = $images['error'][$key];
            $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

            if ($image_error !== UPLOAD_ERR_OK) {
                $errors[] = "Error uploading image $image_name.";
                break;
            }

            if (!in_array($image_ext, $allowed_formats)) {
                $errors[] = "Invalid image format for $image_name. Only jpg, jpeg, png, and gif are allowed.";
                break;
            }

            if ($image_size > 1048576) { // 1MB = 1048576 Bytes
                $errors[] = "Image $image_name is too large. Maximum allowed size is 1MB.";
                break;
            }

            $unique_image_name = uniqid('', true) . '.' . $image_ext;
            $image_paths[] = "$unique_image_name";
            $temp_files[] = $image_tmp_name;
        }

        if (empty($errors)) {
            foreach ($image_paths as $key => $path) {
                $destination = "../uploads/$path";
                if (!move_uploaded_file($temp_files[$key], $destination)) {
                    $errors[] = "Failed to move uploaded file for " . $_FILES['images']['name'][$key];
                    break;
                }
            }
        } else {
            $errors[] = "Failed to move uploaded file for $image_name.";
        }
    }
    // Image adding to database

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO properties (title, selling_method, category, location, deleted_price, current_price, garage, bedrooms, built_year, square_ft, bathrooms, amenities, description, google_maps_src) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $selling_method, $category, $location, $deleted_price, $current_price, $garage, $rooms, $built_in, $square_ft, $bathrooms, $animities, $description, $locationlink]);

            $property_id = $pdo->lastInsertId();

            foreach ($image_paths as $path) {
                $stmt = $pdo->prepare("INSERT INTO property_images (property_id, image_name) VALUES (?, ?)");
                $stmt->execute([$property_id,  $path]);
            }

            header("Location: ./");
            $_SESSION['property_success_code'] = "Property added successfully.";
            exit;
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<?php
$adminpanelpath = './';
include "./components/header.php" ?>
<?php include "./components/navbar.php" ?>

<section class="dashboard">
    <div class="container" style="min-height: 100vh;">

        <div class="heading d-flex align-items-center justify-content-between py-5 border-bottom">
            <div>
                <h1>Create a Property</h1>
                <p class="m-0">create a property and list today !</p>
            </div>

            <div class="d-flex gap-3">
                <a style="background-color: #212529;" class=" px-4 py-2 text-decoration-none fw-medium d-flex align-items-center justify-content-center gap-2 text-white" href="./">
                    <i class="bi bi-house-door fs-5"></i> Home
                </a>
                <a style="background-color: #001eff;" class=" px-4 py-2 text-decoration-none fw-medium d-flex align-items-center justify-content-center gap-2 text-white" href="./properties.php">
                    <i class="bi bi-house-door fs-5"></i> Properties
                </a>
            </div>
        </div>


        <form action="addproperty.php" method="post" enctype="multipart/form-data" class="row flex-column align-items-center gap-3 mt-5">

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
                <label for="title" class="form-label fs-5">Title</label>
                <input type="text" name="title" id="title" class="border border-secondary border-1 px-3 py-3" required>
            </span>

            <div class="row align-items-end prices w-75">
                <label for="selling_method" class="form-label fs-5 p-0 ">Selling Method</label>
                <select name="selling_method" id="selling_method" class="border p-3 border-secondary border-1 rounded-0 d-flex form-select col flex-column" required>
                    <option value="all" selected>All</option>
                    <option value="for_rent">For Rent</option>
                    <option value="for_sale">For Sale</option>
                    <option value="for_auction">For Auction</option>
                    <option value="for_mortage">Mortage</option>
                </select>
            </div>

            <div class="row prices w-75 gap-3">
                <div class="col px-0">
                    <span class="d-flex flex-column">
                        <label for="category" class="form-label fs-5">Category</label>
                        <input type="text" name="category" id="category" class="border border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
                <div class="col px-0">
                    <span class="d-flex flex-column">
                        <label for="images" class="form-label fs-5">Images</label>
                        <input type="file" name="images[]" id="images" class="form-control rounded-0 border border-secondary border-1 px-3 py-3" multiple required>
                    </span>
                </div>
            </div>


            <span class="d-flex flex-column w-75">
                <label for="location" class="form-label fs-5">Location</label>
                <input type="text" name="location" id="location" class="border border-secondary border-1 px-3 py-3" required>
            </span>

            <div class="row prices w-75 gap-3">
                <div class="col px-0">
                    <span class="d-flex flex-column">
                        <label for="deleted_price" class="form-label fs-5">Deleted Price</label>
                        <input type="number" name="deleted_price" id="deleted_price" class="border flex-grow-1 border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>

                <div class="col px-0">
                    <span class="d-flex flex-column">
                        <label for="current_price" class="form-label fs-5">Current Price</label>
                        <input type="number" name="current_price" id="current_price" class="border flex-grow-1 border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
            </div>

            <div class="row w-75 gap-3">
                <div class="col px-0">
                    <span class="input-group">
                        <label for="garage" class=" fs-5 rounded-0 border border-black input-group-text" id="basic-addon1">
                            <i class="bi bi-car-front"></i>
                        </label>
                        <input type="number" placeholder="Garage" name="garage" id="garage" aria-describedby="basic-addon1" class="border flex-grow-1 border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
                <div class="col px-0">
                    <span class="input-group">
                        <label for="rooms" class=" fs-5 rounded-0 border border-black input-group-text" id="basic-addon1">
                            <i class="bi bi-door-open"></i>
                        </label>
                        <input type="number" placeholder="Rooms" name="rooms" id="rooms" aria-describedby="basic-addon1" class="border flex-grow-1 border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
                <div class="col px-0">
                    <span class="input-group">
                        <label for="built_in" class=" fs-5 rounded-0 border border-black input-group-text" id="basic-addon1">
                            <i class="bi bi-bricks"></i>
                        </label>
                        <input type="number" placeholder="Built in" name="built_in" id="built_in" aria-describedby="basic-addon1" class="border flex-grow-1 border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
                <div class="col px-0">
                    <span class="input-group">
                        <label for="square_ft" class=" fs-5 rounded-0 border border-black input-group-text" id="basic-addon1">
                            <i class="bi bi-rulers"></i>
                        </label>
                        <input type="number" placeholder="Square ft." name="square_ft" id="square_ft" aria-describedby="basic-addon1" class="border flex-grow-1 border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
                <div class="col px-0">
                    <span class="input-group">
                        <label for="bathrooms" class="fs-5 rounded-0 border border-black input-group-text" id="basic-addon1">
                            <img src="./assets/img/bathroom.svg" width="30px" alt="">
                        </label>
                        <input type="number" placeholder="Bathrooms" name="bathrooms" id="bathrooms" aria-describedby="basic-addon1" class="border flex-grow-1 border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>

            </div>

            <span class="d-flex flex-column w-75">
                <label for="animities" class="form-label fs-5"> Animities </label>
                <input type="text" placeholder="Seperate animities by | for multiple options" name="animities" id="animities" class="border border-secondary border-1 px-3 py-3" required>
            </span>

            <span class="d-flex description flex-column w-75">
                <label for="description" class="form-label fs-5">Description</label>
                <textarea type="text" rows="10" name="description" id="description" class="border border-secondary border-1 px-3 py-3" required></textarea>
            </span>

            <span class="d-flex flex-column w-75">
                <label for="locationlink" class="form-label fs-5">G - Map Link</label>
                <input type="text" name="locationlink" id="locationlink" class="border border-secondary border-1 px-3 py-3" required>
            </span>

            <div class="row justify-content-center w-100 gap-3">
                <button type="submit" style="background-color: blue;" class="py-3 w-25 my-4 text-white px-5 border-0">
                    Create Property
                </button>
            </div>


        </form>

    </div>
</section>


<?php include "./components/footer.php" ?>