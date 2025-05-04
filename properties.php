<?php
require_once "./config/db.php";

$conditions = [];
$params = [];

if (!empty($_GET['sort_title'])) {
    $conditions[] = "(p.title LIKE :title OR p.location LIKE :title OR p.description LIKE :title)";
    $params[':title'] = '%' . $_GET['sort_title'] . '%';
}   

if (!empty($_GET['sort_location'])) {
    $conditions[] = "p.location LIKE :location";
    $params[':location'] = '%' . $_GET['sort_location'] . '%';
}

if (!empty($_GET['exampleRadios'])) {
    if ($_GET['exampleRadios'] == 'option2') {
        // Buy
        $conditions[] = "p.selling_method = 'for_sale'";
    } elseif ($_GET['exampleRadios'] == 'option3') {
        // Rent
        $conditions[] = "p.selling_method = 'for_rent'";
    }
}

if (!empty($_GET['price'])) {
    switch ($_GET['price']) {
        case 'high_to_low':
            $orderBy = "ORDER BY p.current_price DESC";
            break;
        case 'low_to_high':
            $orderBy = "ORDER BY p.current_price ASC";
            break;
        case 'under_1000':
            $conditions[] = "p.current_price < 1000";
            break;
        case 'under_1500':
            $conditions[] = "p.current_price < 1500";
            break;
        case 'under_2000':
            $conditions[] = "p.current_price < 2000";
            break;
        default:
            $orderBy = "";
            break;
    }
}

$where = '';

if (count($conditions) > 0) {
    $where = 'WHERE ' . implode(' AND ', $conditions);
}

$orderBy = $orderBy ?? '';

try {
    $stmt = $pdo->prepare("
        SELECT p.*, pi.image_url 
        FROM properties p
        LEFT JOIN (
            SELECT property_id, MIN(image_name) as image_url
            FROM property_images
            GROUP BY property_id
        ) pi ON p.id = pi.property_id
        $where
        $orderBy
    ");
    $stmt->execute($params);
    $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}



?>

<?php $basepath = "./";
include "./components/header.php" ?>
<?php $navtextcolor = "black";
$mega_nav_position = "static";
include "./components/navbar.php" ?>

<main>
    <div class="container mt-3 pb-5" style="min-height: 100vh;">
        <div class="row gap-5 justify-content-center gap-xl-3 ">
            <div class="col-lg-3 col-12 d-xl-flex d-none">
                <div class="sticky-lg-top pe-3 " style="top: 30px; max-height: 500px;" class="sticky-lg-top pe-4 border-end-0 border-end-lg-secondary">
                    <form action="properties.php" method="get">
                        <input placeholder="Title" type="text" name="sort_title" style="width: 100%;" class="border border-dark-subtle border-1 px-3 py-3">
                        <input placeholder="Location" type="text" name="sort_location" style="width: 100%;" class="border mt-4 border-dark-subtle border-1 px-3 py-3">

                        <select name="price" style="width: 100%; -webkit-appearance: none;" class="border border-dark-subtle mt-4 border-1 px-3 py-3">
                            <option value="all_price">All Prices</option>
                            <option value="high_to_low">Price High to Low</option>
                            <option value="low_to_high">Price Low to High</option>
                            <option value="under_1000">Under 1000 $</option>
                            <option value="under_1500">Under 1500 $</option>
                            <option value="under_2000">Under 2000 $</option>
                        </select>
                        <div class="buyorrent d-flex gap-3 mt-4">
                            <div class="form-check">
                                <input class="form-check-input" style="box-shadow: none;" type="radio" name="exampleRadios" id="exampleRadiosall" value="option1" checked>
                                <label class="form-check-label text-black fw-normal" for="exampleRadiosall">
                                    All
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" style="box-shadow: none;" type="radio" name="exampleRadios" id="exampleRadios1" value="option2" >
                                <label class="form-check-label text-black fw-normal" for="exampleRadios1">
                                    Buy
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" style="box-shadow: none;" type="radio" name="exampleRadios" id="exampleRadios2" value="option3">
                                <label class="form-check-label text-black fw-normal" for="exampleRadios2">
                                    Rent
                                </label>
                            </div>

                        </div>
                        <button type="submit" style="background-color: blue;" class="py-3 mt-4 px-4 text-white px-3 border-0">
                            Submit
                        </button>

                    </form>
                </div>
            </div>

            <div class="col-12 d-xl-none">
                <form action="properties.php" method="get" class="d-flex align-items-center gap-3 flex-wrap">
                    <input placeholder="Title" type="text" name="sort_title" class="border border-dark-subtle border-1 p-2">
                    <input placeholder="Location" type="text" name="sort_location" class="border border-dark-subtle border-1 p-2">

                    <select name="price" style="appearance: none; -webkit-appearance: none;" class="border border-dark-subtle border-1 p-2">
                        <option value="all_price">All Prices</option>
                        <option value="high_to_low">Price High to Low</option>
                        <option value="low_to_high">Price Low to High</option>
                        <option value="under_1000">Under 1000 $</option>
                        <option value="under_1500">Under 1500 $</option>
                        <option value="under_2000">Under 2000 $</option>
                    </select>   

                    <div class="buyorrent d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" style="box-shadow: none;" type="radio" name="exampleRadios" id="exampleRadiosall"  value="option1" checked>
                            <label class="form-check-label text-black fw-normal" for="exampleRadiosall">
                                All
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" style="box-shadow: none;" type="radio" name="exampleRadios" id="exampleRadios1" value="option2">
                            <label class="form-check-label text-black fw-normal" for="exampleRadios1">
                                Buy
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" style="box-shadow: none;" type="radio" name="exampleRadios" id="exampleRadios2" value="option3">
                            <label class="form-check-label text-black fw-normal" for="exampleRadios2">
                                Rent
                            </label>
                        </div>

                    </div>
                    <button type="submit" style="background-color: blue;" class="py-3 px-4 text-white border-0">
                        Submit
                    </button>

                </form>
            </div>

            <div class="col-xl-9 col-12 row justify-content-center gap-3 ">
                <?php foreach ($properties as $property) : ?>
                    <div class="col-12 col-md-5 col-xl-3 flex-grow-1 rounded shadow-sm overflow-hidden px-0" style="max-width: 300px; background-color:rgb(244, 244, 244); min-height:461px;">
                        <img src="./uploads/<?php echo htmlspecialchars($property['image_url']) ?>" class="object-fit-cover" width="100%" height="50%" alt="">
                        <div class="p-4 d-flex gap-3 flex-column">
                            <a href="property.php?id=<?php echo htmlspecialchars($property['id']) ?>" class="fs-5 text-black text-decoration-none m-0 fw-normal line-clamp-2">
                                <?php echo htmlspecialchars($property['title']) ?>
                            </a>

                            <div class="priceandinteraction d-flex justify-content-between mt-3 align-items-center">
                                <p class="m-0 fs-5 fw-medium" style="color: #292929;">$ <?php echo number_format(htmlspecialchars($property['current_price']), 2, '.', ','); ?></p>
                                <i class="bi fs-4 bi-eye" style="color: #8e8e8e;"></i>
                            </div>

                            <hr class="m-0">

                            <div class="details d-flex align-items-center justify-content-between">
                                <p style="color: #8e8e8e;" class="m-0 d-flex align-items-center gap-2"><img src="./assets/img/opendoor.png" width="30" alt=""> <?php echo htmlspecialchars($property['bedrooms']) ?></p>
                                <p style="color: #8e8e8e;" class="m-0 d-flex align-items-center gap-2"><img src="./assets/img/bathtub.png" width="30" alt=""><?php echo htmlspecialchars($property['bathrooms']) ?></p>
                                <p style="color: #8e8e8e;" class="m-0 d-flex align-items-center gap-2"><img src="./assets/img/car.png" width="30" alt=""><?php echo htmlspecialchars($property['garage']) ?></p>
                                <p style="color: #8e8e8e;" class="m-0 d-flex align-items-center gap-2"><img src="./assets/img/Construction Worker.png" width="30" alt=""><?php echo htmlspecialchars($property['built_year']) ?></p>
                            </div>


                        </div>
                    </div>
                <?php endforeach; ?>
            </div>


</main>

<?php include "./components/footer.php" ?>