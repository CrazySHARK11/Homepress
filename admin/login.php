<?php 
    require_once "../config/db.php";

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $errors[] = "Email and Password are required.";
        } else {
            try {
                $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = :email");
                $stmt->execute(['email' => $email]);
                $admin = $stmt->fetch();

                if ($admin && password_verify($password, $admin['password'])) {
                    // Start session and set session variables
                    session_start();
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_email'] = $admin['email'];
                    $_SESSION['admin_login_success'] = true;
                    header("Location: ./");
                    exit;
                } else {
                    $errors[] = "Invalid email or password.";
                }
            } catch (PDOException $e) {
                $errors[] = "Database error: " . $e->getMessage();
            }
        }
    }
?>
   

<?php $adminpanelpath = './'; include "./components/header.php" ?>

<section style="height: 100vh; width: 100%;" class="admin-login bg-secondary-subtle d-flex align-items-center justify-content-center">
   <form action="login.php" method="post" class="w-25 h-75 d-flex flex-column align-items-center justify-content-center py-5">
        <h1 class="text-center text-black">Admin Login</h1>
        <div class="form-group mt-4 w-100">
            <input type="email" name="email" placeholder="email" class="border w-100 border-black border-1 fs-5 px-3 py-3" required>
        </div>
        <div class="form-group mt-4 w-100">
            <input type="password" name="password" placeholder="password" class="border w-100 border-black border-1 fs-5 px-3 py-3" required>
        </div>

        <?php if (!empty($errors)): ?>
          <div class="bg-trans parent rounded-pill alert px-3 p-2 d-flex align-items-center justify-content-between alert-danger mt-4 alert-dismissible fade show" style="width: 400px;" role="alert">
            <strong>
              <?php foreach ($errors as $error): ?>
                <p class="m-0"><?php echo $error; ?></p>
              <?php endforeach; ?>
            </strong>
            <i class="bi bi-x-circle cursor-pointer" data-bs-dismiss="alert" aria-label="Close"></i>
          </div>
        <?php endif; ?>

        <button  type="submit" style="background-color: blue;" class="py-3 my-4 text-white px-5 border-0">Login</button>
   </form>
</section>
 