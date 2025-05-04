<?php
require_once "../config/db.php";

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("location: ./login.php");
}

$errors = [];
$property_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($property_id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM properties WHERE id = :id");
        $stmt->execute(['id' => $property_id]);
        $property = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($property) {
            $stmt_images = $pdo->prepare("SELECT * FROM property_images WHERE property_id = :property_id");
            $stmt_images->execute(['property_id' => $property_id]);
            $property_images = $stmt_images->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $errors[] = "Property not found.";
        }
    } catch (PDOException $e) {
        $errors[] = "Error fetching property: " . $e->getMessage();
    }
} else {
    $errors[] = "Invalid property ID.";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $selling_method = trim($_POST['selling_method']);
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
    $google_maps_src = trim($_POST['locationlink']);

    if (empty($title) || empty($selling_method) || empty($animities) || empty($category) || empty($location) || empty($deleted_price) || empty($current_price) || empty($garage) || empty($rooms) || empty($built_in) || empty($square_ft) || empty($bathrooms) || empty($description) || empty($google_maps_src)) {
        $errors[] = "All fields are required.";
    } else {


        $upload_dir = '../uploads/';
        $allowed_formats = ['jpg', 'jpeg', 'png'];
        $max_size = 1 * 1024 * 1024; // 1MB
        $validated_images = [];

        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['name'] as $key => $image_name) {
                $image_tmp_name = $_FILES['images']['tmp_name'][$key];
                $image_size = $_FILES['images']['size'][$key];
                $image_error = $_FILES['images']['error'][$key];
                $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

                if ($image_error !== UPLOAD_ERR_OK) {
                    $errors[] = "Error uploading image: $image_name.";
                    break;
                }

                if (!in_array($image_ext, $allowed_formats)) {
                    $errors[] = "Invalid format for $image_name. Allowed: jpg, jpeg, png";
                    break;
                }

                if ($image_size > $max_size) {
                    $errors[] = "Image $image_name exceeds 1MB size limit.";
                    break;
                }

                // Store for later upload
                $unique_image_name = uniqid() . '.' . $image_ext;
                $validated_images[] = ['tmp' => $image_tmp_name, 'new_name' => $unique_image_name];
            }
        }

        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare("UPDATE properties SET title = :title, selling_method = :selling_method, category = :category, location = :location, deleted_price = :deleted_price, current_price = :current_price, garage = :garage, bedrooms = :rooms, built_year = :built_in, square_ft = :square_ft, bathrooms = :bathrooms, amenities = :animities, description = :description, google_maps_src = :google_maps_src WHERE id = :id");
                $stmt->execute([
                    'title' => $title,
                    'selling_method' => $selling_method,
                    'category' => $category,
                    'location' => $location,
                    'deleted_price' => $deleted_price,
                    'current_price' => $current_price,
                    'garage' => $garage,
                    'rooms' => $rooms,
                    'built_in' => $built_in,
                    'square_ft' => $square_ft,
                    'bathrooms' => $bathrooms,
                    'animities' => $animities,
                    'description' => $description,
                    'google_maps_src' => $google_maps_src,
                    'id' => $property_id
                ]);

                if (empty($errors)) {
                    foreach ($validated_images as $image) {
                        $image_path = $upload_dir . $image['new_name'];
                        if (move_uploaded_file($image['tmp'], $image_path)) {
                            $stmt = $pdo->prepare("INSERT INTO property_images (property_id, image_name) VALUES (:property_id, :image_name)");
                            $stmt->execute([
                                'property_id' => $property_id,
                                'image_name' => $image['new_name']
                            ]);
                        } else {
                            $errors[] = "Failed to upload image: " . $image['new_name'];
                        }
                    }
                }

                header("Location: ./properties.php");
                $_SESSION['property_edited_success_message'] = "Property updated successfully.";
                exit;
            } catch (PDOException $e) {
                $errors[] = "Error updating property: " . $e->getMessage();
            }
        }
    }
}

?>

<?php
$adminpanelpath = './';
include "./components/header.php" ?>
<?php include "./components/navbar.php" ?>

<section class="dashboard">
    <div class="container mt-5" style="min-height: 800px;">

        <?php if (!empty($property_images)): ?>
            <div class="row ">
                <?php foreach ($property_images as $image): ?>
                    <div class="col position-relative">
                        <a href="delete_image.php?id=<?php echo $image['id']; ?>&property_id=<?php echo $property_id; ?>" class="position-absolute m-2 bg-white d-flex align-items-center justify-content-center rounded-circle shadow" style="width: 30px; height: 30px;"><i class="bi text-danger bi-trash"></i></a>
                        <img src="../uploads/<?php echo $image['image_name']; ?>" class=" object-fit-cover" width="200px" height="200px" alt="Property Image">
                    </div>

                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="edit_property.php?id=<?php echo intval($_GET['id']) ?>" method="post" enctype="multipart/form-data" class="row flex-column align-items-center gap-3 mt-5">

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
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($property['title']) ?>" class="border border-secondary border-1 px-3 py-3" required>
            </span>

            <div class="row align-items-end prices w-75">
                <label for="selling_method" class="form-label fs-5 p-0 ">Selling Method</label>
                <select name="selling_method" id="selling_method" class="border p-3 border-secondary border-1 rounded-0 d-flex form-select col flex-column">
                    <option value="for_rent" <?php echo ($property['selling_method'] == 'for_rent') ? 'selected' : ''; ?>>For Rent</option>
                    <option value="for_sale" <?php echo ($property['selling_method'] == 'for_sale') ? 'selected' : ''; ?>>For Sale</option>
                    <option value="for_auction" <?php echo ($property['selling_method'] == 'for_auction') ? 'selected' : ''; ?>>For Auction</option>
                    <option value="for_mortage" <?php echo ($property['selling_method'] == 'for_mortage') ? 'selected' : ''; ?>>Mortage</option>
                </select>
            </div>

            <div class="row prices w-75 gap-3">
                <div class="col px-0">
                    <span class="d-flex flex-column">
                        <label for="category" class="form-label fs-5">Category</label>
                        <input type="text" name="category" id="category" value="<?php echo htmlspecialchars($property['category']) ?>" class="border border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
                <div class="col px-0">
                    <span class="d-flex flex-column">
                        <label for="images" class="form-label fs-5">Images</label>
                        <input type="file" name="images[]" id="images" class="form-control rounded-0 border border-secondary border-1 px-3 py-3" multiple>
                    </span>
                </div>
            </div>


            <span class="d-flex flex-column w-75">
                <label for="location" class="form-label fs-5">Location</label>
                <input type="text" name="location" id="location" value="<?php echo htmlspecialchars($property['location']) ?>" class="border border-secondary border-1 px-3 py-3" required>
            </span>

            <div class="row prices w-75 gap-3">
                <div class="col px-0">
                    <span class="d-flex flex-column">
                        <label for="deleted_price" class="form-label fs-5">Deleted Price</label>
                        <input type="number" name="deleted_price" value="<?php echo htmlspecialchars($property['deleted_price']) ?>" id="deleted_price" class="border flex-grow-1 border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>

                <div class="col px-0">
                    <span class="d-flex flex-column">
                        <label for="current_price" class="form-label fs-5">Current Price</label>
                        <input type="number" name="current_price" value="<?php echo htmlspecialchars($property['current_price']) ?>" id="current_price" class="border flex-grow-1 border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
            </div>

            <div class="row w-75 gap-3">
                <div class="col px-0">
                    <span class="input-group">
                        <label for="garage" class=" fs-5 rounded-0 border border-black input-group-text" id="basic-addon1">
                            <i class="bi bi-car-front"></i>
                        </label>
                        <input type="number" placeholder="Garage" name="garage" id="garage" aria-describedby="basic-addon1" value="<?php echo htmlspecialchars($property['garage']) ?>" class="border flex-grow-1 border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
                <div class="col px-0">
                    <span class="input-group">
                        <label for="rooms" class=" fs-5 rounded-0 border border-black input-group-text" id="basic-addon1">
                            <i class="bi bi-door-open"></i>
                        </label>
                        <input type="number" placeholder="Rooms" name="rooms" id="rooms" aria-describedby="basic-addon1" value="<?php echo htmlspecialchars($property['bedrooms']) ?>" class="border flex-grow-1 border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
                <div class="col px-0">
                    <span class="input-group">
                        <label for="built_in" class=" fs-5 rounded-0 border border-black input-group-text" id="basic-addon1">
                            <i class="bi bi-bricks"></i>
                        </label>
                        <input type="number" placeholder="Built in" name="built_in" id="built_in" aria-describedby="basic-addon1" value="<?php echo htmlspecialchars($property['built_year']) ?>" class="border flex-grow-1 border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
                <div class="col px-0">
                    <span class="input-group">
                        <label for="square_ft" class=" fs-5 rounded-0 border border-black input-group-text" id="basic-addon1">
                            <i class="bi bi-rulers"></i>
                        </label>
                        <input type="number" placeholder="Square ft." name="square_ft" id="square_ft" aria-describedby="basic-addon1" value="<?php echo htmlspecialchars($property['square_ft']) ?>" class="border flex-grow-1 border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>

                <div class="col px-0">
                    <span class="input-group">
                        <label for="bathrooms" class=" fs-5 rounded-0 border border-black input-group-text" id="basic-addon1">
                            <img src="./assets/img/bathroom.svg" width="30px" alt="">
                        </label>
                        <input type="number" placeholder="Built in" name="bathrooms" id="bathrooms" aria-describedby="basic-addon1" value="<?php echo htmlspecialchars($property['bathrooms']) ?>" class="border flex-grow-1 border-secondary border-1 px-3 py-3" required>
                    </span>
                </div>
            </div>

            <span class="d-flex flex-column w-75">
                <label for="animities" class="form-label fs-5"> Animities </label>
                <input type="text" placeholder="Seperate animities by | for multiple options" value="<?php echo htmlspecialchars($property['amenities']) ?>" name="animities" id="animities" class="border border-secondary border-1 px-3 py-3" required>
            </span>

            <span class="d-flex description flex-column w-75">
                <label for="description" class="form-label fs-5">Description</label>
                <textarea type="text" rows="10" name="description" id="description" class="border border-secondary border-1 px-3 py-3" required><?php echo htmlspecialchars($property['description']) ?></textarea>
            </span>

            <span class="w-75">
                <iframe class="mt-4" src="<?php echo htmlspecialchars($property['google_maps_src']) ?>" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </span>

            <span class="d-flex flex-column w-75">
                <label for="locationlink" class="form-label fs-5">G - Map Link</label>
                <input type="text" name="locationlink" id="locationlink" class="border border-secondary border-1 px-3 py-3" value="<?php echo htmlspecialchars($property['google_maps_src']) ?>" required>
            </span>

            <div class="row justify-content-center w-100 gap-3">
                <button type="submit" style="background-color: blue;" class="py-3 w-25 my-4 text-white px-5 border-0">
                    Edit Property
                </button>
            </div>


        </form>


    </div>
</section>

<?php include "./components/footer.php" ?>