<?php
require_once "../config/db.php";

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("location: ./login.php");
}

if (isset($_GET['id'])) {
    $enquiry_id = $_GET['id'];
} else {
    echo "No enquiry ID provided.";
    exit;
}

try {
    $sql = "SELECT * FROM contact_messages WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $enquiry_id]);
    $enquiry = $stmt->fetch();
} catch (PDOException $e) {
    echo "Something went wrong: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $responded = isset($_POST['responded']) ? 1 : 0;

    try {
        $sql = "UPDATE contact_messages SET responded = :responded WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':responded', $responded, PDO::PARAM_INT);
        $stmt->bindParam(':id', $enquiry_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: enquiries.php");
            exit;
        } else {
            $errors[] = "Failed to update the message status.";
        }
    } catch (PDOException $e) {
        $errors[] = "Something went wrong: " . $e->getMessage();
    }
}

?>

<?php
$adminpanelpath = './';
include "./components/header.php" ?>
<?php include "./components/navbar.php" ?>

<section class="dashboard">
    <div class="container " style="min-height: 800px;">

        <div class="heading d-flex align-items-center justify-content-between py-5 border-bottom">
            <div>
                <h1>User's Enquiry</h1>
                <p class="m-0">Respond to the query submitted by user </p>
            </div>

            <div class="d-flex gap-3">
                <a style="background-color: #212529;" class=" px-4 py-2 text-decoration-none fw-medium d-flex align-items-center justify-content-center gap-2 text-white" href="./">
                    <i class="bi bi-house-door fs-5"></i> Home
                </a>
                <a style="background-color: #001eff;" class=" px-4 py-2 text-decoration-none fw-medium d-flex align-items-center justify-content-center gap-2 text-white" href="./enquiries.php">
                    <i class="bi bi-ui-checks fs-5"></i> Enquiries
                </a>
            </div>
        </div>

        <section>
            <form method="post" action="update_enq.php?id=<?php echo htmlspecialchars($enquiry_id) ?>" class="row flex-column align-items-center gap-3 mt-5">

                <div class="d-flex flex-column w-75">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="border border-secondary border-1 px-3 py-3" id="name" value="<?php echo htmlspecialchars($enquiry['username']); ?>" readonly>
                </div>

                <div class="d-flex flex-column w-75">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="border border-secondary border-1 px-3 py-3" id="email" value="<?php echo htmlspecialchars($enquiry['email']); ?>" readonly>
                </div>

                <div class="d-flex flex-column w-75">
                    <label for="email" class="form-label">Phone Number</label>
                    <input type="email" class="border border-secondary border-1 px-3 py-3" id="email" value="<?php echo htmlspecialchars($enquiry['phone']); ?>" readonly>
                </div>

                <div class="d-flex flex-column w-75">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="border border-secondary border-1 px-3 py-3" id="message" rows="5" readonly><?php echo htmlspecialchars($enquiry['message']); ?></textarea>
                </div>

                <div class="d-flex align-items-center gap-2 w-75">
                    <label for="responded" class="m-0 form-label">Responded</label>
                    <input type="checkbox" class="border form-check-input border-secondary border-1 p-2 m-0" id="responded" name="responded" value="1" <?php echo $enquiry['responded'] ? 'checked' : ''; ?>>
                </div>

                <div class="d-flex flex-column w-75">
                    <button type="submit" style="background-color: blue;" class="py-3 w-25 my-1 text-white px-5 border-0">Submit</button>
                </div>

            </form>
        </section>

    </div>
</section>

<?php include "./components/footer.php" ?>