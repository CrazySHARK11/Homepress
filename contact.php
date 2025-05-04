<?php

require_once "./config/db.php";
$errors = [];

$username = $email = $phone = $message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $message = trim($_POST['message']);

    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }
    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    }
    if (empty($message)) {
        $errors[] = "Message cannot be empty.";
    }

    if (empty($errors)) {
        try {
            $sql = "INSERT INTO contact_messages (username, email, phone, message) VALUES (:username, :email, :phone, :message)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':phone' => $phone,
                ':message' => $message
            ]);

            $_SESSION['success'] = "Message sent successfully.";
            header('Location: contact.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = "Something went wrong: " . $e->getMessage();
        }
    }
}

?>

<?php $basepath = "./";
include "./components/header.php" ?>
<?php $navtextcolor = "white";
$mega_nav_position = "absolute";
include "./components/navbar.php" ?>


<section class="w-100 " style=" background: linear-gradient(90deg, rgba(0,0,0,0.59352240896358546) 0%, rgba(0,0,0,0.49) 100%), url('./assets/img/real_agent.jpg') ; background-size: cover;">
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 450px;">
        <h2 class="text-white fs-1 mt-5 pt-5 mt-sm-0 pt-sm-0 fw-bold">Contact Us <span style="color: #001eff;">Today</span> </h2>
    </div>
</section>

<section class="w-100">
    <div class="container pb-5" style="min-height: 600px;">
        <div class="row justify-content-evenly gap-4">
            <h3 class="fw-semibold fs-1 text-center mt-5">Contact Us</h3>

            <div class="col-12 col-md d-flex flex-column gap-3 ">

                <div class="phonenumber d-flex gap-2 align-items-center">
                    <i class="bi bi-geo-alt" style="color:#001eff;"></i>
                    <p class="text-secondary m-0"><?php echo htmlspecialchars($adminDetails['location']) ?></p>
                </div>
                <div class="phonenumber d-flex gap-2 align-items-center">
                    <i class="bi bi-envelope" style="color:#001eff;"></i>
                    <p class="text-secondary m-0"><?php echo htmlspecialchars($adminDetails['email']) ?></p>
                </div>
                <div class="phonenumber d-flex gap-1 align-items-center">
                    <i class="bi bi-telephone" style="color:#001eff;"></i>
                    <p class="text-secondary m-0">
                        <?php echo htmlspecialchars("+1 " . $adminDetails['phone_number']) ?>
                    </p>
                </div>


                <iframe class="flex-grow-1 rounded shadow-sm mt-3" src="<?php echo htmlspecialchars($adminDetails['google_map_src']) ?>" width="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

            <form action="contact.php" class="col-12 col-md d-flex flex-column align-items-center" method="post">

                <div class="d-flex flex-column mt-4 w-100 ">
                    <input placeholder="Username" type="username" name="username" class="border border-secondary-subtle border-1 px-3 py-3" required>
                </div>

                <div class="d-flex flex-column mt-4 w-100 ">
                    <input placeholder="E-mail" type="email" name="email" class="border border-secondary-subtle border-1 px-3 py-3" required>
                </div>

                <div class="d-flex flex-column mt-4 w-100 ">
                    <input placeholder="Phone Number" type="number" name="phone" class="border border-secondary-subtle border-1 px-3 py-3" required>
                </div>

                <div class="d-flex flex-column mt-4 w-100 ">
                    <textarea placeholder="Message" type="email" name="message" rows="6" class="border border-secondary-subtle border-1 px-3 py-2 " required></textarea>
                </div>

                <button type="submit" style="background-color: blue;" class="py-3 mt-4 text-white px-5 border-0">
                    Submit
                </button>

            </form>
        </div>
    </div>
</section>

<?php include "./components/footer.php" ?>