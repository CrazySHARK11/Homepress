<?php

require_once 'config/db.php';

$id = $_GET['id'];

if (!isset($id) || empty($id)) {
    header('Location: ./');
    exit;
}

try {
    $query = "SELECT * FROM properties WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $imagesQuery = "SELECT image_name FROM property_images WHERE property_id = :id";
    $imagesStmt = $pdo->prepare($imagesQuery);
    $imagesStmt->bindParam(':id', $id, PDO::PARAM_INT);
    $imagesStmt->execute();
    $images = $imagesStmt->fetchAll(PDO::FETCH_ASSOC);

    $property = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$property_id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] ==  "POST") {
    $action = $_POST['action'];
    $email = $_POST['email'];
    $phone_number = $_POST['phonenumber'];
    $message = $_POST['message'];

    try {
        $stmt = $pdo->prepare("INSERT INTO property_enq (property_id, action, email, phone_number, message) VALUES ( :property_id, :action, :email, :phone_number, :message)");
        $stmt->execute([
            ':property_id' => $property_id,
            ':action' => $action,
            ':email' => $email,
            ':phone_number' => $phone_number,
            ':message' => $message
        ]);
        echo "Message sent successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<?php $basepath = "./";
include "./components/header.php" ?>
<?php $navtextcolor = "black";
$mega_nav_position = "static";
include "./components/navbar.php" ?>

<main>
    <div class="container pb-5" style="min-height: 900px;">
        <div class="d-flex gap-2 align-items-center "> <a href="./" class="text-decoration-none">Home</a> <span><i class="bi text-secondary bi-chevron-right" style="color: #8e8e8e;"></i></span> <span style="color: #8e8e8e;"> <?php echo htmlspecialchars($property['location']) ?> </span> </div>

        <div class="swiper">
            <div class="swiper-wrapper">
                <?php foreach ($images as $image): ?>
                    <img src="./uploads/<?php echo htmlspecialchars($image['image_name']); ?>" width="100%" class="swiper-slide rounded my-4 object-fit-cover" style="height: 800px !important;" alt="">
                <?php endforeach; ?>
            </div>
            <!-- Navigation Buttons (Optional) -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <!-- Pagination (Optional) -->
        </div>

        <div class="row gap-2 gap-lg-5">
            <div class="col-12 col-lg-8">

                <div class="small-details-and-iteraction pb-4 d-flex gap-4 flex-column flex-md-row justify-content-between" style="color: #8e8e8e; border-bottom: 1px solid rgba(69, 69, 69, 0.31);">
                    <div class="date-and-selling-type d-flex gap-2 justify-content-between">
                        <p class="m-0 p-1 px-2 text-white fw-bold rounded" style="background-color: #0150b5;">
                            <?php if ($property['selling_method'] === 'for_sale'): ?>
                                For Sale
                            <?php elseif ($property['selling_method'] === 'for_rent'): ?>
                                For Rent
                            <?php elseif ($property['selling_method'] === 'for_auction'): ?>
                                For Aunction
                            <?php elseif ($property['selling_method'] === 'for_mortage'): ?>
                                For Mortgage
                            <?php endif; ?>

                        </p>
                        <p class="m-0 d-flex align-items-center gap-2"><i class="bi bi-clock"></i> <span>
                                <?php echo date('F j, Y', strtotime($property['created_at'])); ?>
                            </span></p>
                    </div>

                    <div class="interaction d-flex gap-3">
                    <?php 
                         // Add this after the existing property query
                        $isLiked = false;
                        if (isset($_SESSION['user_id'])) {
                            $likeQuery = "SELECT * FROM liked_properties WHERE property_id = :property_id AND user_id = :user_id";
                            $likeStmt = $pdo->prepare($likeQuery);
                            $likeStmt->execute([
                                ':property_id' => $id,
                                ':user_id' => $_SESSION['user_id']
                            ]);
                            $isLiked = $likeStmt->rowCount() > 0;
                        }
                        ?>
                        <a href="./components/liking.php?property_id=<?php echo $property_id ?>" class="like text-decoration-none d-flex align-items-center gap-2" style="color: #8e8e8e;">
                        <i class="bi bi-heart<?php echo $isLiked ? '-fill" style="color: #ff00fa;"' : '"' ?>"></i> <?php echo $isLiked ? 'Liked' : 'Like' ?> 
                        </a>
                        <a href="#" class="share text-decoration-none d-flex align-items-center gap-2" style="color: #8e8e8e;"><i class="bi bi-share"></i> Share</a>
                    </div>
                </div>

                <div class="maindeatails d-flex flex-column gap-4 py-5" style=" border-bottom: 1px solid rgba(69, 69, 69, 0.31);">
                    <div class="titleandprice d-flex flex-column gap-4 justify-content-between align-items-start">
                        <h1 class="text-dark"> <?php echo htmlspecialchars($property['title']) ?> </h1>

                        <div class="d-flex gap-3 mt-2 align-items-center">
                            <del class="fs-5" style="color: #8e8e8e;">$ <?php echo number_format(htmlspecialchars($property['deleted_price']), 2, '.', ',') ?> <?php if ($property['selling_method'] === 'for_rent') echo "/mo"; ?> </del>
                            <h2 class="fs-3 m-0">$ <?php echo number_format(htmlspecialchars($property['current_price']), 2, '.', ',') ?> <?php if ($property['selling_method'] === 'for_rent') echo "/mo"; ?> </h2>
                        </div>
                    </div>

                    <div class="features d-flex gap-3 gap-lg-4 mt-3 flex-wrap ">
                        <div class="bed d-flex gap-2 align-items-center">
                            <img src="./assets/img/Emptybed.png" alt="">
                            <span style="color: #8e8e8e;"><?php echo htmlspecialchars($property['bedrooms']) ?> Beds</span>
                        </div>

                        <div class="garage d-flex gap-2 align-items-center">
                            <img src="./assets/img/car.png" alt="">
                            <span style="color: #8e8e8e;"><?php echo htmlspecialchars($property['garage']) ?> Garage</span>
                        </div>

                        <div class="builtin d-flex gap-2 align-items-center">
                            <img src="./assets/img/Construction Worker.png" alt="">
                            <span style="color: #8e8e8e;"><?php echo htmlspecialchars($property['built_year']) ?></span>
                        </div>

                        <div class="squareft d-flex gap-2 align-items-center">
                            <img src="./assets/img/ruler.png" alt="">
                            <span style="color: #8e8e8e;"><?php echo htmlspecialchars($property['square_ft']) ?> Sqft</span>
                        </div>

                        <div class="bathtub d-flex gap-2 align-items-center">
                            <img src="./assets/img/bathtub.png" alt="">
                            <span style="color: #8e8e8e;"><?php echo htmlspecialchars($property['bathrooms']) ?> Bathrooms </span>
                        </div>

                    </div>

                </div>

                <div class="detailed-description py-4">
                    <h2 class="fw-normal">About the Property</h2>

                    <p class="m-0 mt-4 fs-6" style="letter-spacing: 0.01em; line-height: 35px; color: rgba(30, 30, 30, 0.75);">
                        <?php echo htmlspecialchars($property['description']) ?>
                    </p>

                </div>

                <div class="Location py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="fw-normal">Location</h2>
                        <p style="color: #8e8e8e;" class="d-flex gap-3 align-items-center"><i style="color: #0150b5;" class="bi fs-5 bi-pin-map"></i> <?php echo htmlspecialchars($property['location']) ?></p>
                    </div>

                    <iframe class="mt-4" src="<?php echo htmlspecialchars($property['google_maps_src']) ?>" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>


                <div class="emicalculator py-4">
                    <h2 class="fw-normal">EMI Calculator</h2>
                    <div class="w-100 w-lg-50 pt-3">

                        <div class="d-flex flex-column mt-4">
                            <input placeholder="Loan Amount" id="loanAmount" value="<?php echo htmlspecialchars($property['current_price']) ?>" type="number" name="username" class="inputnumber border border-secondary-subtle border-1 px-3 py-2" required>
                        </div>

                        <div class="d-flex flex-column mt-4">
                            <input placeholder="Interest Rate (% per year)" id="interestRate" type="number" name="email" class="inputnumber border border-secondary-subtle border-1 px-3 py-2" required>
                        </div>

                        <div class="d-flex flex-column mt-4">
                            <input placeholder="Duration (Months)" id="duration" type="number" name="phonenumber" class="inputnumber border border-secondary-subtle border-1 px-3 py-2" required>
                        </div>

                        <div class="mt-4">
                            <p>Emi Results : <span class="fw-bold" id="emiResult"></span> </p>
                        </div>

                        <button type="submit" style="background-color: blue;" onclick="calculateEMI()" class="py-2 my-2 text-white px-4 border-0">
                            Submit
                        </button>

                    </div>
                </div>

                <div class="property-specifications py-4">
                    <h2 class="fw-normal">Property Sepcification</h2>
                    <div class="row gap-2 mt-4">
                        <?php
                        if (isset($property['amenities'])) {
                            $animities = explode('|', $property['amenities']);
                            foreach ($animities as $animity) {
                                echo '<p class="m-0 col-12 d-flex align-items-center gap-2" style="list-style: none;"><i class="bi bi-check2-square" style="color: #0150b5;"></i> <span style="color: #8e8e8e;">' . htmlspecialchars(trim($animity)) . '</span> </p>';
                            }
                        }
                        ?>
                    </div>
                </div>

                <div class="contact-the-realtor py-4 align-items-center row gap-5 mt-3">
                    <div class="col-lg-4 col-12 d-flex align-items-center flex-column ">
                        <img src="./uploads/adminassets/<?php echo htmlspecialchars($adminDetails['profile_image']) ?>" width="100%" height="350px" class="object-fit-cover" alt="">
                        <div class="d-flex w-75 flex-column gap-2">
                            <div class="d-flex justify-content-between mt-3">
                                <i class="bi bi-telephone" style="color:#0150b5;"></i>
                                <p class="m-0">+1 <?php echo htmlspecialchars($adminDetails['phone_number']) ?></p>
                            </div>
                            <p class="m-0 text-center" style="color: #8e8e8e;"><?php echo htmlspecialchars($adminDetails['small_tagline']) ?></p>
                            <div class="d-flex social gap-3 justify-content-center">
                                <a href="#" class="fs-4 text-decoration-none" style="color: #8e8e8e;"> <i class="bi social-hover bi-facebook"></i> </a>
                                <a href="#" class="fs-4 text-decoration-none" style="color: #8e8e8e;"> <i class="bi social-hover bi-twitter-x"></i> </a>
                                <a href="#" class="fs-4 text-decoration-none" style="color: #8e8e8e;"> <i class="bi social-hover bi-instagram"></i> </a>
                                <a href="#" class="fs-4 text-decoration-none" style="color: #8e8e8e;"> <i class="bi social-hover bi-pinterest"></i> </a>
                            </div>

                        </div>
                    </div>

                    <form action="property.php?id=<?php echo $property_id ?>" class="col" method="post">
                        <h3 class="fw-normal">Contact or Buy Property</h3>

                        <div class="d-flex flex-column mt-4">
                            <select style="appearance: none;" name="action" class="border border-secondary-subtle border-1 px-3 py-2" required>
                                <option value="Visit">Book a Visit</option>
                                <option value="Consultation">Request a Consultation</option>
                                <option value="Virtual Tour">Schedule a Virtual Tour</option>
                            </select>
                        </div>

                        <div class="d-flex flex-column mt-4">
                            <input placeholder="E-mail" type="email" name="email" class="border border-secondary-subtle border-1 px-3 py-2" required>
                        </div>

                        <div class="d-flex flex-column mt-4">
                            <input placeholder="Phone Number" type="number" name="phonenumber" class="border border-secondary-subtle border-1 px-3 py-2" required>
                        </div>

                        <div class="d-flex flex-column mt-4">
                            <textarea placeholder="Message" name="message" rows="4" class="border border-secondary-subtle border-1 px-3 py-2"></textarea>
                        </div>

                        <button type="submit" style="background-color: blue;" class="py-2 my-4 text-white px-4 border-0">
                            Submit
                        </button>

                    </form>
                </div>
            </div>

            <div class="col px-0">
                <div class="sticky-top" style="top: 1.4em; max-height: 500px; ">

                    <div class="d-flex flex-column align-items-center justify-content-center px-0 px-xl-4">
                        <img src="./uploads/adminassets/<?php echo htmlspecialchars($adminDetails['profile_image']) ?>" width="100px" height="100px" class="object-fit-cover rounded-circle" alt="">

                        <h3 class="fw-normal mt-3 fs-5 m-0"><?php echo htmlspecialchars($adminDetails['name']) ?></h3>
                        <p class="m-0 mt-1" style="font-size:.9em; color: #8e8e8e;"><?php echo htmlspecialchars($adminDetails['small_tagline']) ?></p>

                        <div class="d-flex w-75 flex-column gap-3">
                            <div class="d-flex justify-content-between mt-4">
                                <i class="bi bi-telephone" style="color:#0150b5;"></i>
                                <p class="m-0">+1 <?php echo htmlspecialchars($adminDetails['phone_number']) ?></p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <i class="bi bi-buildings" style="color:#0150b5;"></i>
                                <p class="m-0">+1 <?php echo htmlspecialchars($adminDetails['office_number']) ?></p>
                            </div>
                            <div class="d-flex justify-content-between     ">
                                <i class="bi bi-envelope" style="color:#0150b5;"></i>
                                <p class="m-0"><?php echo htmlspecialchars($adminDetails['email']) ?></p>
                            </div>
                        </div>

                        <div class="d-flex social gap-3 mt-4">
                            <a href="<?php echo htmlspecialchars($adminDetails['facebook']) ?>" class="fs-4 text-decoration-none" style="color: #8e8e8e;"> <i class="bi social-hover bi-facebook"></i> </a>
                            <a href="<?php echo htmlspecialchars($adminDetails['twitter_x']) ?>" class="fs-4 text-decoration-none" style="color: #8e8e8e;"> <i class="bi social-hover bi-twitter-x"></i> </a>
                            <a href="<?php echo htmlspecialchars($adminDetails['instagram']) ?>" class="fs-4 text-decoration-none" style="color: #8e8e8e;"> <i class="bi social-hover bi-instagram"></i> </a>
                            <a href="<?php echo htmlspecialchars($adminDetails['linkedin']) ?>" class="fs-4 text-decoration-none" style="color: #8e8e8e;"> <i class="bi social-hover bi-linkedin"></i> </a>
                        </div>
                        <div class="contact-or-buy-now-btns d-flex w-75 flex-column gap-3 mt-4">
                            <button class="py-2 px-4 border-1 bg-white text-primary">Contact Agent</button>
                            <button class="py-2 text-white px-4 border-0" style="background-color: #0150b5;">Buy Now</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<!-- Include Swiper CSS -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

<script>
    function calculateEMI() {
        let P = parseFloat(document.getElementById("loanAmount").value);
        let r = parseFloat(document.getElementById("interestRate").value) / 12 / 100;
        let n = parseInt(document.getElementById("duration").value);

        if (P && r && n) {
            let EMI = (P * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
            document.getElementById("emiResult").innerHTML = EMI.toFixed(2) + " $ /mo";
        } else {
            document.getElementById("emiResult").innerHTML = "Please enter all values.";
        }
    }

    // Initialize Swiper
    document.addEventListener('DOMContentLoaded', function() {
        var swiper = new Swiper('.swiper', {
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    });
 
</script>


<?php include "./components/footer.php" ?>