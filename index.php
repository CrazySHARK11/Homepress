<?php
require_once 'config/db.php';

$errors = [];

try {
  $sql = "SELECT p.*, MIN(pi.image_name) AS image_name 
            FROM properties p
            LEFT JOIN property_images pi ON p.id = pi.property_id
            WHERE p.featured = 1 
            GROUP BY p.id 
            LIMIT 4";

  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $featuredProperties = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $sqlForSale = "SELECT p.*, MIN(pi.image_name) AS image_name 
             FROM properties p
             LEFT JOIN property_images pi ON p.id = pi.property_id
             WHERE p.selling_method = 'for_sale'
             GROUP BY p.id 
             LIMIT 4";

  $stmtForSale = $pdo->prepare($sqlForSale);
  $stmtForSale->execute();
  $forSaleProperties = $stmtForSale->fetchAll(PDO::FETCH_ASSOC);

  $sqlPopular = "SELECT p.*, MIN(pi.image_name) AS image_name 
             FROM properties p
             LEFT JOIN property_images pi ON p.id = pi.property_id
             WHERE p.popular = 1 
             GROUP BY p.id 
             LIMIT 5";

  $stmtPopular = $pdo->prepare($sqlPopular);
  $stmtPopular->execute();
  $popularProperties = $stmtPopular->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $errors[] = "error";
}

?>

<?php $basepath = "./";
include "./components/header.php" ?>
<?php $navtextcolor = "white";
$mega_nav_position = "absolute";
include "./components/navbar.php" ?>

<!-- Main section  -->
<main style="background:linear-gradient(90deg, rgba(0,0,0,0.49352240896358546) 0%, rgba(0,0,0,0.49) 100%), url('./assets/img/house.png');  background-size: cover; " class="background-cover">
  <div class="container">
    <div class="content d-flex align-items-center justify-content-center" style="min-height: 730px;">
      <div class="searchbarandcontent mt-5 mt-md-0">
        <h1 style="font-size: min(4em, 10vw);" class="text-white text-center fw-bold ">Find Your Dream Property</h1>
        <p class="text-white text-center">Discover Your Perfect Property with Ease - Turning Your Real Estate Dreams into Reality, <br>
          One Home at a Time</p>

        <form action="properties.php" method="get" class="searchbar d-flex flex-column gap-2">

          <div class="d-flex">
            <input type="text" name="sort_title" placeholder="Enter the Location" class="px-3 py-3 border-0 w-100" style="background-color: #e1e1e1; outline: none;">
            <input type="submit" style="background-color: blue;" class=" text-white px-5 border-0" value="Search">
          </div>

          <div class="d-flex gap-4">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="all" checked>
              <label class="form-check-label text-white fw-medium" for="exampleRadios1">
                All
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option2" checked>
              <label class="form-check-label text-white fw-medium" for="exampleRadios1">
                Buy
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="option3">
              <label class="form-check-label text-white fw-medium" for="exampleRadios2">
                Rent
              </label>
            </div>
          </div>
        </form>


      </div>
    </div>
  </div>
</main>
<!-- Main section  -->

<!-- Featured Properties -->
<section class="py-5" style=" background-color: #f2f2f2;">
  <div class="container">

    <div class="heading d-flex justify-content-between mb-5 align-items-center">
      <h2 class="fs-1 fw-semibold" style="color: #18162d;">Featured Properties</h2>
      <p class="fw-bold m-0 d-none d-md-flex align-items-center gap-2">
        <img src="./assets/img/Home.png" width="25" alt="">
        <span style="color: #0150B5;">120 +</span>
        Properties
      </p>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row px-md-5 px-3 gap-4 justify-content-center">

      <?php foreach ($featuredProperties as $featuredProperty): ?>
        <div class="col-12 col-md-5 col-lg overflow-hidden px-0 bg-white rounded shadow-sm" style="min-height: 300px;">
          <img src="./uploads/<?php echo htmlspecialchars($featuredProperty['image_name']) ?>" class="object-fit-cover" height="250" width="100%" alt="">

          <div class="details p-3 d-flex flex-column gap-3">
            <a href="./property.php?id=<?php echo $featuredProperty['id'] ?>" class="text-decoration-none text-black">
              <h3 class="fs-4 line-clamp-2 fw-normal"><?php echo htmlspecialchars($featuredProperty['title']) ?></h3>
            </a>

            <div class="features d-flex flex-column gap-3 ">
              <div class="d-flex justify-content-between">
                <div class="room d-flex align-items-center gap-1">
                  <img src="./assets/img/opendoor.png" width="28px" alt="">
                  <span style="color: #8e8e8e;"> <?php echo htmlspecialchars($featuredProperty['bedrooms']) ?> Rooms</span>
                </div>
                <div class="room d-flex align-items-center gap-1">
                  <img src="./assets/img/bathtub.png" width="28px" alt="">
                  <span style="color: #8e8e8e;"> <?php echo htmlspecialchars($featuredProperty['bathrooms']) ?> Baths</span>
                </div>
              </div>
              <div class="d-flex justify-content-between">
                <div class="room d-flex align-items-center gap-1">
                  <img src="./assets/img/car.png" width="28px" alt="">
                  <span style="color: #8e8e8e;"> <?php echo htmlspecialchars($featuredProperty['garage']) ?> Garage</span>
                </div>
                <div class="room d-flex align-items-center gap-1">
                  <img src="./assets/img/ruler.png" width="28px" alt="">
                  <span style="color: #8e8e8e;"> <?php echo htmlspecialchars($featuredProperty['square_ft']) ?> Sq ft</span>
                </div>
              </div>
            </div>

            <hr class="border-0 m-0 bg-secondary" style="height: 2px;">

            <div class="price d-flex align-items-center gap-2">
              <del class="fs-5" style="color: #8e8e8e;">$<?php echo number_format($featuredProperty['deleted_price']) ?>
                <?php if ($featuredProperty['selling_method'] == 'for_rent'): ?>
                  /mo
                <?php endif; ?>
              </del>
              <span class="fs-4">
                $<?php echo number_format($featuredProperty['current_price']) ?>

                <?php if ($featuredProperty['selling_method'] == 'for_rent'): ?>
                  /mo
                <?php endif; ?>
              </span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

    </div>
  </div>

  <div class="linktoproperty text-center mt-5">
    <a href="./properties.php" class="fs-5 fw-semibold">Explore</a>
  </div>
</section>
<!-- Featured Properties -->

<!-- About me -->
<section class="aboutme bg-white py-md-5 py-0 " style="min-height: 430px;">
  <div class="container">
    <div class="row py-5 gap-5 justify-content-center align-items-center">

      <img src="./uploads/adminassets/<?php echo htmlspecialchars($adminDetails['profile_image']) ?>" class="col-12 object-fit-cover col-md-8 col-xl-5" height="600px" alt="">

      <div class="col-12 col-md-8 col-xl-5 d-flex flex-column align-items-center align-items-xl-start gap-3">
        <h2 class="fs-1 fw-semibold" style="color: #18162d;">
          Who is <?php echo htmlspecialchars($adminDetails['name']) ?> ?
        </h2>

        <p class="lh-base fw-normal text-center text-xl-start" style="word-spacing: .5em;">
          At Property Real Estate Company, we believe that when it comes to finding a home what’s outside the front door is just as important as what’s behind it.
        </p>

        <p class="lh-lg text-center text-xl-start" style="color: #8e8e8e; -webkit-line-clamp: 10 !important; overflow: hidden; text-overflow: ellipsis; max-height: 20em; line-height: 2em; white-space: normal;">
          <?php echo htmlspecialchars($adminDetails['description']) ?>
        </p>

        <div class="links d-flex gap-4">
          <a href="./properties.php" class="fs-6 fw-semibold">Properties</a>
          <a href="./contact.php" class="fs-6 fw-semibold">Consult</a>
          <a href="./about.php" class="fs-6 fw-semibold">Services</a>
        </div>

        <div class="socials mt-3">
          <p class="fw-bold d-flex align-items-center gap-3">
            Follow :
            <a href="<?php echo htmlspecialchars($adminDetails['instagram']) ?>" style="color: #8e8e8e;">
              <i class="bi fs-4 bi-instagram"></i>
            </a>
            <a href="<?php echo htmlspecialchars($adminDetails['facebook']) ?>" style="color: #8e8e8e;">
              <i class="bi fs-4 bi-facebook"></i>
            </a>
            <a href="<?php echo htmlspecialchars($adminDetails['twitter_x']) ?>" style="color: #8e8e8e;">
              <i class="bi fs-4 bi-twitter-x"></i>
            </a>
            <a href="<?php echo htmlspecialchars($adminDetails['linkedin']) ?>" style="color: #8e8e8e;">
              <i class="bi fs-4 bi-linkedin"></i>
            </a>
          </p>

        </div>

      </div>

    </div>
  </div>
</section>
<!-- About me -->

<!-- Recent properties for sale -->
<section class="propertiesforsale" style="background-color: #f2f2f2; min-height: 430px;">
  <div class="container py-5">

    <div class="heading d-flex justify-content-between mb-5 align-items-center">
      <h2 class="fs-1 fw-semibold" style="color: #18162d;">Recent Properties For Sale</h2>
      <p class="fw-bold m-0 d-none d-md-flex align-items-center gap-2">
        <img src="./assets/img/Mortgage.png" width="25" alt="">
        <span style="color: #0150B5;">120 +</span>
        Properties
      </p>
    </div>


    <div class="row gap-3 px-3 px-sm-0 justify-content-center">
      <?php foreach ($forSaleProperties as $property_forsale): ?>
        <div class="col-12 col-md-5 col-lg rounded d-flex flex-column shadow-sm overflow-hidden bg-white px-0" style="min-height:461px;">
          <img src="./uploads/<?php echo htmlspecialchars($property_forsale['image_name']) ?>" class="object-fit-cover" width="100%" height="50%" alt="">
          <div class="p-4 d-flex gap-3 flex-column">
            <a href="./property.php?id=<?php echo htmlspecialchars($property_forsale['id']) ?>" class="fs-5 text-decoration-none text-black m-0 fw-normal line-clamp-2">
              <?php echo htmlspecialchars($property_forsale['title']) ?>
            </a>

            <div class="priceandinteraction d-flex justify-content-between align-items-center">
              <p class="m-0 fs-5 fw-medium" style="color: #292929;">$<?php echo number_format($property_forsale['current_price'], 2) ?></p>

              <i class="bi fs-4 bi-eye" style="color: #8e8e8e;"></i>
            </div>

            <hr class="m-0">

            <div class="details d-flex align-items-center justify-content-between">
              <p style="color: #8e8e8e;" class="m-0 d-flex align-items-center gap-2"><img src="./assets/img/opendoor.png" width="30" alt=""> 3</p>
              <p style="color: #8e8e8e;" class="m-0 d-flex align-items-center gap-2"><img src="./assets/img/bathtub.png" width="30" alt="">2</p>
              <p style="color: #8e8e8e;" class="m-0 d-flex align-items-center gap-2"><img src="./assets/img/car.png" width="30" alt="">1</p>
              <p style="color: #8e8e8e;" class="m-0 d-flex align-items-center gap-2"><img src="./assets/img/Construction Worker.png" width="30" alt="">2014</p>
            </div>


          </div>
        </div>
      <?php endforeach; ?>

    </div>

  </div>
</section>
<!-- Recent properties for sale -->

<!-- our partners -->
<section class="ourpartners py-5">
  <div class="container">

    <h2 class="fs-1 fw-semibold text-center" style="color:#18162d;">
      Our Partners
    </h2>

    <div class="row py-5 gap-5 px-3 px-md-0 justify-content-around">
      <img class="col-12 col-md-5 col-lg-3 col-xl-2" src="./assets/img/highrise.png" alt="">
      <img class="col-12 col-md-5 col-lg-3 col-xl-2" src="./assets/img/marknco.png" alt="">
      <img class="col-12 col-md-5 col-lg-3 col-xl-2" src="./assets/img/reexperts.png" alt="">
      <img class="col-12 col-md-5 col-lg-3 col-xl-2" src="./assets/img/homedesign.png" alt="">
    </div>

  </div>
</section>
<!-- our partners -->


<!-- Why chose us -->
<section class="whychooseus py-5" style="background-size: cover; background:linear-gradient(90deg,rgba(17, 17, 54, 0.83) 100%,rgba(17, 17, 54, 0.83) 100%), url('./assets/img/house.png');">
  <div class="container py-5" style="min-height: 500px;">

    <h2 class="fs-1 fw-semibold text-center text-white" style="color:#18162d;">
      Our Partners
    </h2>

    <div class="row gap-3 mt-5 justify-content-evenly">

      <div class="col-12 col-md-5 col-lg-3 d-flex align-items-center flex-column">
        <img src="./assets/img/apartment.png" width="80px" alt="">
        <h2 style="word-spacing: 3px;" class="text-uppercase text-center lh-base text-white fs-5 fw-semibold">
          wide range of <br> properties
        </h2>
        <p style="color: #b9b4ba;" class="lh-base text-center">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolores inventore, expedita explicabo sunt ut deleniti minima cupiditate iure vel enim, </p>
      </div>
      <div class="col-12 col-md-5 col-lg-3 d-flex align-items-center flex-column">
        <img src="./assets/img/apartment.png" width="80px" alt="">
        <h2 style="word-spacing: 3px;" class="text-uppercase text-center lh-base text-white fs-5 fw-semibold">
          wide range of <br> properties
        </h2>
        <p style="color: #b9b4ba;" class="lh-base text-center">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolores inventore, expedita explicabo sunt ut deleniti minima cupiditate iure vel enim, </p>
      </div>
      <div class="col-12 col-md-5 col-lg-3 d-flex align-items-center flex-column">
        <img src="./assets/img/apartment.png" width="80px" alt="">
        <h2 style="word-spacing: 3px;" class="text-uppercase text-center lh-base text-white fs-5 fw-semibold">
          wide range of <br> properties
        </h2>
        <p style="color: #b9b4ba;" class="lh-base text-center">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolores inventore, expedita explicabo sunt ut deleniti minima cupiditate iure vel enim, </p>
      </div>
      <div class="col-12 col-md-5 col-lg-3 d-flex align-items-center flex-column">
        <img src="./assets/img/apartment.png" width="80px" alt="">
        <h2 style="word-spacing: 3px;" class="text-uppercase text-center lh-base text-white fs-5 fw-semibold">
          wide range of <br> properties
        </h2>
        <p style="color: #b9b4ba;" class="lh-base text-center">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolores inventore, expedita explicabo sunt ut deleniti minima cupiditate iure vel enim, </p>
      </div>
      <div class="col-12 col-md-5 col-lg-3 d-flex align-items-center flex-column">
        <img src="./assets/img/apartment.png" width="80px" alt="">
        <h2 style="word-spacing: 3px;" class="text-uppercase text-center lh-base text-white fs-5 fw-semibold">
          wide range of <br> properties
        </h2>
        <p style="color: #b9b4ba;" class="lh-base text-center">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolores inventore, expedita explicabo sunt ut deleniti minima cupiditate iure vel enim, </p>
      </div>
      <div class="col-12 col-md-5 col-lg-3 d-flex align-items-center flex-column">
        <img src="./assets/img/apartment.png" width="80px" alt="">
        <h2 style="word-spacing: 3px;" class="text-uppercase text-center lh-base text-white fs-5 fw-semibold">
          wide range of <br> properties
        </h2>
        <p style="color: #b9b4ba;" class="lh-base text-center">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolores inventore, expedita explicabo sunt ut deleniti minima cupiditate iure vel enim, </p>
      </div>


    </div>


  </div>
</section>
<!-- Why chose us -->

<!-- Popular properties -->
<section class="pop-properties">
  <div class="container py-5">

    <div class="heading d-flex justify-content-between mb-5 align-items-center">
      <h2 class="fs-1 fw-semibold" style="color: #18162d;">Popular Properties</h2>
      <p class="fw-bold m-0 d-none d-md-flex align-items-center gap-2">
        <img src="./assets/img/Home.png" width="25" alt="">
        <span style="color: #0150B5;">120 +</span>
        Properties
      </p>
    </div>


    <div class="row gap-4 mt-4 justify-content-evenly">
      <?php foreach ($popularProperties as $pop_prop): ?>
        <div class="col-3 flex-grow-1 d-flex flex-column align-items-start justify-content-between p-4 bg-secondary" style="min-height: 400px; background:linear-gradient(178.74deg, rgba(0, 0, 0, 0) 3.04%, rgba(0, 0, 0, 0.73) 66.27%), url('./uploads/<?php echo htmlspecialchars($pop_prop['image_name']) ?>'); background-size: cover; ">
          <div class="shadow rounded fw-semibold text-white fs-6 p-1 px-4" style="background-color: red;">
            <?php if ($pop_prop['selling_method'] == 'for_sale'): ?>
              Sale
            <?php elseif ($pop_prop['selling_method'] == 'for_rent'): ?>
              Rent
            <?php elseif ($pop_prop['selling_method'] == 'for_mortage'): ?>
              Mortgage
            <?php elseif ($pop_prop['selling_method'] == 'for_auction'): ?>
              Auction
            <?php endif; ?>
          </div>

          <div class="details w-100">
            <a href="property.php?id=<?php echo htmlspecialchars($pop_prop['id']) ?>" style="word-spacing: 5px;" class="title text-decoration-none d-block text-white text-truncate fs-3 mb-2 fw-medium">
              <?php echo htmlspecialchars($pop_prop['title']) ?>
            </a>
            <div class="nuanced-details gap-2 d-flex flex-column align-items-start justify-content-between">

              <div class="price">
                <del class="fs-5" style=" word-spacing: 6px; color: #8e8e8e;">
                  $<?php echo number_format($pop_prop['deleted_price'], 2) ?><?php if ($pop_prop['selling_method'] == 'for_rent'): ?> /mo<?php endif; ?>
                </del>
                <span class="fs-4 text-white word-spacing: 6px; ">
                  $<?php echo number_format($pop_prop['current_price'], 2) ?><?php if ($pop_prop['selling_method'] == 'for_rent'): ?> /mo<?php endif; ?>
                </span>
              </div>

              <div class="features d-flex gap-3">
                <p class="m-0 text-white"> <img width="25" src="./assets/img/opendoorwhite.png"> <?php echo htmlspecialchars($pop_prop['bedrooms']) ?> Rooms </p>
                <p class="m-0 text-white"> <img width="25" src="./assets/img/whitebathtub.png"> <?php echo htmlspecialchars($pop_prop['bathrooms']) ?> Baths </p>
                <p class="m-0 text-white"> <img width="25" src="./assets/img/angletoolwhite.png"> <?php echo htmlspecialchars($pop_prop['square_ft']) ?> sq </p>
              </div>

            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>


    <div class="linktoproperty text-center mt-5">
      <a href="./properties.php" class="fs-5 fw-semibold">Explore</a>
    </div>

  </div>
</section>
<!-- Popular properties -->

<!-- Experience -->
<section class="experience" style=" background: linear-gradient(90deg,rgba(0, 0, 0, 0.73) 100%,rgba(0, 0, 0, 0.73) 100%), url('./assets/img/miami.png'); background-position: center; background-size: cover;">
  <div class="container" style="min-height: 200px;">
    <div class="row align-items-center justify-content-evenly" style="padding: 6em 0;">

      <div class="col-md-4 col">
        <h2 class="fw-medium text-white" style="font-size: 3em; letter-spacing:3px ;">
          More Than 10 <br>Years of <br>Experience
        </h2>
        <hr class=" opacity-100 border-0 w-50" style="height: 2px; background-color: #0150B5;">
        <p class="text-white fs-6">
          Our agents are local market experts with access to more information than other <br> agents nationwide.
        </p>
      </div>

      <div class="col-md-5 col">
        <div class="d-flex flex-column gap-5">

          <div class="d-flex justify-content-between">
            <div class="d-flex gap-2 align-items-center">
              <img width="80px" src="./assets/img/smarthoem.png" alt="">
              <div class="cred-details d-flex flex-column">
                <p class="m-0 fs-1 text-white">200 +</p>
                <p class="m-0 text-white">Properties Sold</p>
              </div>
            </div>

            <div class="d-flex gap-2 align-items-center">
              <img width="80px" src="./assets/img/like.png" alt="">
              <div class="cred-details d-flex flex-column">
                <p class="m-0 fs-1 text-white">200 +</p>
                <p class="m-0 text-white">Properties Sold</p>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-between">
            <div class="d-flex gap-2 align-items-center">
              <img width="80px" src="./assets/img/helpinghand.png" alt="">
              <div class="cred-details d-flex flex-column">
                <p class="m-0 fs-1 text-white">200 +</p>
                <p class="m-0 text-white">Properties Sold</p>
              </div>
            </div>

            <div class="d-flex gap-2 align-items-center">
              <img width="80px" src="./assets/img/happycustomer.png" alt="">
              <div class="cred-details d-flex flex-column">
                <p class="m-0 fs-1 text-white">200 +</p>
                <p class="m-0 text-white">Properties Sold</p>
              </div>
            </div>
          </div>




        </div>
      </div>

    </div>
  </div>
</section>
<!-- Experience -->

<!-- Testimonials -->
<section class="testimonials py-5">
  <div class="container py-5" style="min-height: 500px;">

    <h2 class="fs-1 fw-semibold text-center" style="color:#18162D;">
      Testimonials
    </h2>

    <div class="row mt-5 gap-5 justify-content-evenly">

      <div class="col d-flex flex-column align-items-center gap-2">
        <img src="./assets/img/Jane.png" width="100" style="background-color: #0150B5;" class="p-1 rounded-circle" alt="">
        <p class="m-0 fs-2 fw-semibold">Jane Smith</p>
        <p class="m-0 text-secondary">Citizen, Ohio</p>
        <i class="m-0 mt-4 text-center lh-lg"> Lore m ipsu dolor sit ame m ipsu dolor sit amet c distinctio molestias, placeat rem vel aliquid tenetur iure dignissimos m ipsu dolor sit ame natus ut blanditiis ducimus offic m do ficia. </i>
      </div>
      <div class="col d-flex flex-column align-items-center gap-2">
        <img src="./assets/img/Dr-C.png" width="100" style="background-color: #0150B5;" class="p-1 rounded-circle" alt="">
        <p class="m-0 fs-2 fw-semibold">Naz Nima</p>
        <p class="m-0 text-secondary">Doctor, Texas</p>
        <i class="m-0 mt-4 text-center lh-lg"> Lore m ipsu dolor sit ame m ipsu dolor sit amet c distinctio molestias, placeat rem vel aliquid tenetur iure dignissimos m ipsu dolor sit ame natus ut blanditiis ducimus offic m do ficia. </i>
      </div>
      <div class="col d-flex flex-column align-items-center gap-2">
        <img src="./assets/img/Jai.png" width="100" style="background-color: #0150B5;" class="p-1 rounded-circle" alt="">
        <p class="m-0 fs-2 fw-semibold">Alexander</p>
        <p class="m-0 text-secondary">Software Developer, Texas</p>
        <i class="m-0 mt-4 text-center lh-lg"> Lore m ipsu dolor sit ame m ipsu dolor sit amet c distinctio molestias, placeat rem vel aliquid tenetur iure dignissimos m ipsu dolor sit ame natus ut blanditiis ducimus offic m do ficia. </i>
      </div>


    </div>

  </div>
</section>
<!-- Testimonials -->


<?php include "./components/footer.php" ?>