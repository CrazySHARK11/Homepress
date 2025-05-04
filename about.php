<?php
require_once 'config/db.php'; ?>

<?php $basepath = "./"; include "./components/header.php" ?>
<?php $navtextcolor = "black"; $mega_nav_position = "static"; include "./components/navbar.php" ?>

<!-- About me -->
<section class="aboutme bg-white py-md-2 py-0 " style=" min-height: 430px;">
  <div class="container">
    <div class="row py-5 gap-3 justify-content-center align-items-center">

    <div class="col-12 d-flex justify-content-center">
      <img src="./uploads/adminassets/<?php echo htmlspecialchars($adminDetails['profile_image']) ?>" class=" rounded-circle object-fit-cover p-2" width="100%" style="max-width: 400px; height: 400px; background-color: #e1e1e1 ;" alt="">
    </div>

      <div class="col-12 flex-grow-1 d-flex flex-column align-items-center align-items-xl-center gap-3">
        <h2 class="fs-1 fw-semibold " style="color: #18162d;">
          <?php echo htmlspecialchars($adminDetails['name']) ?>
        </h2>

        <p class="d-flex align-items-center gap-sm-3 flex-wrap justify-content-center gap-1">
          <span class="d-flex align-items-center gap-2"> <i class="bi fs-4 bi-book-half" style="color: #001eff;"></i> <?php echo htmlspecialchars($adminDetails['university']) ?> </span> <span class="d-none d-sm-block text-secondary">|</span>
          <span class="d-flex align-items-center gap-2"> <i class="bi fs-4 bi-shield-shaded" style="color: #001eff;"></i> Gov. Certified Agent </span> <span class="d-none d-sm-block text-secondary">|</span>
          <span class="d-flex align-items-center gap-2"> <i class="bi fs-4 bi-house-check" style="color: #001eff;"></i> <?php echo htmlspecialchars($adminDetails['properties_sold']) ?> + Properties Sold </span>
        </p>

        <p class="lh-lg fw-normal text-center w-100" style="word-spacing: .5em; color:rgb(102, 102, 102);">
          <?php echo htmlspecialchars($adminDetails['description']) ?>
        </p>

        <div class="links d-flex gap-4">
          <a href="./properties.php" class="fs-6 fw-semibold">Properties</a>
          <a href="./properties.php" class="fs-6 fw-semibold">Consult</a>
          <a href="./properties.php" class="fs-6 fw-semibold">Services</a>
        </div>

        <div class="socials mt-3">
          <p class="fw-bold d-flex align-items-center gap-3">
            Follow :
            <a href="" style="color: #8e8e8e;">
              <i class="bi fs-4 bi-instagram"></i>
            </a>
            <a href="" style="color: #8e8e8e;">
              <i class="bi fs-4 bi-facebook"></i>
            </a>
            <a href="" style="color: #8e8e8e;">
              <i class="bi fs-4 bi-twitter-x"></i>
            </a>
            <a href="" style="color: #8e8e8e;">
              <i class="bi fs-4 bi-linkedin"></i>
            </a>
          </p>

        </div>

      </div>

    </div>
  </div>
</section>
<!-- About me -->

<section class="w-100 " style=" background: linear-gradient(90deg, hsla(0, 0.00%, 0.00%, 0.39) 0%, rgba(0,0,0,0.39) 100%), url('./assets/img/aboutbanner.png') ; background-position: center; background-size: cover;">
  <div class="container d-flex align-items-center flex-column justify-content-center" style="min-height: 450px;">
    <h2 class="text-white text-center fw-bold" style="font-size: min(10vw, 3em);">
      Your Trusted Real Estate Partner
    </h2>
    <p class="mt-3 text-white text-center" style="width: min(700px, 90%) ;">
      We are dedicated to providing exceptional service and expertise to help you navigate the real estate market with confidence, ensuring that you find the perfect home that meets all your needs and exceeds your expectations.
    </p>
  </div>
</section>


<section id="testimonials">
  <div class="container py-5" style="min-height: 500px;">
    <h2 class="fs-1 text-center fw-semibold" style="color: #18162d;">
      Testimonials
    </h2>

    <div class="swiper mySwiper">
      <div class="swiper-button-next d-none d-lg-block"></div>
      <div class="swiper-button-prev d-none d-lg-block"></div>

      <div class="swiper-wrapper mt-5">
        <div class="col-12 swiper-slide d-flex flex-column align-items-center justiy-content-center gap-3">
          <img src="./assets/img/John.png" class="rounded-circle p-1 shadow-md" width="140" alt="">

          <p class="m-0 fs-2 fw-semibold">John Doe</p>

          <p class="d-flex align-items-center gap-3">
            <span class="d-flex align-items-center text-secondary gap-2 d-none d-md-block"> <i class="bi fs-5 bi-info-circle" style="color: #001eff;"></i> Citizen, Cleaveland, Ohio </span> <span class="text-secondary d-none d-md-block">|</span>
            <span class="d-flex align-items-center text-secondary gap-2"> <i class="bi fs-5 bi-house-check" style="color: #001eff;"></i> Purchased a Beach side condo </span>
          </p>

          <i class="m-0 w-100 text-center lh-lg" style="max-width: 800px;">
            I had a great experience working with Rebecca. She was very professional and knowledgeable about the market. She was always available to answer any questions I had and made the process of buying a home much easier. I would highly recommend her to anyone looking to buy or sell a home.
          </i>

          <div class="socials d-flex gap-4">
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-facebook"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-instagram"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-linkedin"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-google"></i> </a>
          </div>

        </div>
        <div class="col-12 swiper-slide d-flex flex-column align-items-center justiy-content-center gap-3">
          <img src="./assets/img/John.png" class="rounded-circle p-1 shadow-md" width="140" alt="">

          <p class="m-0 fs-2 fw-semibold">John Doe</p>

          <p class="d-flex align-items-center gap-3">
            <span class="d-flex align-items-center text-secondary gap-2 d-none d-md-block"> <i class="bi fs-5 bi-info-circle" style="color: #001eff;"></i> Citizen, Cleaveland, Ohio </span> <span class="text-secondary d-none d-md-block">|</span>
            <span class="d-flex align-items-center text-secondary gap-2"> <i class="bi fs-5 bi-house-check" style="color: #001eff;"></i> Purchased a Beach side condo </span>
          </p>

          <i class="m-0 w-100 text-center lh-lg" style="max-width: 800px;">
            I had a great experience working with Rebecca. She was very professional and knowledgeable about the market. She was always available to answer any questions I had and made the process of buying a home much easier. I would highly recommend her to anyone looking to buy or sell a home.
          </i>

          <div class="socials d-flex gap-4">
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-facebook"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-instagram"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-linkedin"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-google"></i> </a>
          </div>

        </div>
        <div class="col-12 swiper-slide d-flex flex-column align-items-center justiy-content-center gap-3">
          <img src="./assets/img/John.png" class="rounded-circle p-1 shadow-md" width="140" alt="">

          <p class="m-0 fs-2 fw-semibold">John Doe</p>

          <p class="d-flex align-items-center gap-3">
            <span class="d-flex align-items-center text-secondary gap-2 d-none d-md-block"> <i class="bi fs-5 bi-info-circle" style="color: #001eff;"></i> Citizen, Cleaveland, Ohio </span> <span class="text-secondary d-none d-md-block">|</span>
            <span class="d-flex align-items-center text-secondary gap-2"> <i class="bi fs-5 bi-house-check" style="color: #001eff;"></i> Purchased a Beach side condo </span>
          </p>

          <i class="m-0 w-100 text-center lh-lg" style="max-width: 800px;">
            I had a great experience working with Rebecca. She was very professional and knowledgeable about the market. She was always available to answer any questions I had and made the process of buying a home much easier. I would highly recommend her to anyone looking to buy or sell a home.
          </i>

          <div class="socials d-flex gap-4">
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-facebook"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-instagram"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-linkedin"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-google"></i> </a>
          </div>

        </div>
        <div class="col-12 swiper-slide d-flex flex-column align-items-center justiy-content-center gap-3">
          <img src="./assets/img/John.png" class="rounded-circle p-1 shadow-md" width="140" alt="">

          <p class="m-0 fs-2 fw-semibold">John Doe</p>

          <p class="d-flex align-items-center gap-3">
            <span class="d-flex align-items-center text-secondary gap-2 d-none d-md-block"> <i class="bi fs-5 bi-info-circle" style="color: #001eff;"></i> Citizen, Cleaveland, Ohio </span> <span class="text-secondary d-none d-md-block">|</span>
            <span class="d-flex align-items-center text-secondary gap-2"> <i class="bi fs-5 bi-house-check" style="color: #001eff;"></i> Purchased a Beach side condo </span>
          </p>

          <i class="m-0 w-100 text-center lh-lg" style="max-width: 800px;">
            I had a great experience working with Rebecca. She was very professional and knowledgeable about the market. She was always available to answer any questions I had and made the process of buying a home much easier. I would highly recommend her to anyone looking to buy or sell a home.
          </i>

          <div class="socials d-flex gap-4">
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-facebook"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-instagram"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-linkedin"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-google"></i> </a>
          </div>

        </div>
        <div class="col-12 swiper-slide d-flex flex-column align-items-center justiy-content-center gap-3">
          <img src="./assets/img/John.png" class="rounded-circle p-1 shadow-md" width="140" alt="">

          <p class="m-0 fs-2 fw-semibold">John Doe</p>

          <p class="d-flex align-items-center gap-3">
            <span class="d-flex align-items-center text-secondary gap-2 d-none d-md-block"> <i class="bi fs-5 bi-info-circle" style="color: #001eff;"></i> Citizen, Cleaveland, Ohio </span> <span class="text-secondary d-none d-md-block">|</span>
            <span class="d-flex align-items-center text-secondary gap-2"> <i class="bi fs-5 bi-house-check" style="color: #001eff;"></i> Purchased a Beach side condo </span>
          </p>

          <i class="m-0 w-100 text-center lh-lg" style="max-width: 800px;">
            I had a great experience working with Rebecca. She was very professional and knowledgeable about the market. She was always available to answer any questions I had and made the process of buying a home much easier. I would highly recommend her to anyone looking to buy or sell a home.
          </i>

          <div class="socials d-flex gap-4">
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-facebook"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-instagram"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-linkedin"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-google"></i> </a>
          </div>

        </div>
        <div class="col-12 swiper-slide d-flex flex-column align-items-center justiy-content-center gap-3">
          <img src="./assets/img/John.png" class="rounded-circle p-1 shadow-md" width="140" alt="">

          <p class="m-0 fs-2 fw-semibold">John Doe</p>

          <p class="d-flex align-items-center gap-3">
            <span class="d-flex align-items-center text-secondary gap-2 d-none d-md-block"> <i class="bi fs-5 bi-info-circle" style="color: #001eff;"></i> Citizen, Cleaveland, Ohio </span> <span class="text-secondary d-none d-md-block">|</span>
            <span class="d-flex align-items-center text-secondary gap-2"> <i class="bi fs-5 bi-house-check" style="color: #001eff;"></i> Purchased a Beach side condo </span>
          </p>

          <i class="m-0 w-100 text-center lh-lg" style="max-width: 800px;">
            I had a great experience working with Rebecca. She was very professional and knowledgeable about the market. She was always available to answer any questions I had and made the process of buying a home much easier. I would highly recommend her to anyone looking to buy or sell a home.
          </i>

          <div class="socials d-flex gap-4">
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-facebook"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-instagram"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-linkedin"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-google"></i> </a>
          </div>

        </div>
        <div class="col-12 swiper-slide d-flex flex-column align-items-center justiy-content-center gap-3">
          <img src="./assets/img/John.png" class="rounded-circle p-1 shadow-md" width="140" alt="">

          <p class="m-0 fs-2 fw-semibold">John Doe</p>

          <p class="d-flex align-items-center gap-3">
            <span class="d-flex align-items-center text-secondary gap-2 d-none d-md-block"> <i class="bi fs-5 bi-info-circle" style="color: #001eff;"></i> Citizen, Cleaveland, Ohio </span> <span class="text-secondary d-none d-md-block">|</span>
            <span class="d-flex align-items-center text-secondary gap-2"> <i class="bi fs-5 bi-house-check" style="color: #001eff;"></i> Purchased a Beach side condo </span>
          </p>

          <i class="m-0 w-100 text-center lh-lg" style="max-width: 800px;">
            I had a great experience working with Rebecca. She was very professional and knowledgeable about the market. She was always available to answer any questions I had and made the process of buying a home much easier. I would highly recommend her to anyone looking to buy or sell a home.
          </i>

          <div class="socials d-flex gap-4">
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-facebook"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-instagram"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-linkedin"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-google"></i> </a>
          </div>

        </div>
        <div class="col-12 swiper-slide d-flex flex-column align-items-center justiy-content-center gap-3">
          <img src="./assets/img/John.png" class="rounded-circle p-1 shadow-md" width="140" alt="">

          <p class="m-0 fs-2 fw-semibold">John Doe</p>

          <p class="d-flex align-items-center gap-3">
            <span class="d-flex align-items-center text-secondary gap-2 d-none d-md-block"> <i class="bi fs-5 bi-info-circle" style="color: #001eff;"></i> Citizen, Cleaveland, Ohio </span> <span class="text-secondary d-none d-md-block">|</span>
            <span class="d-flex align-items-center text-secondary gap-2"> <i class="bi fs-5 bi-house-check" style="color: #001eff;"></i> Purchased a Beach side condo </span>
          </p>

          <i class="m-0 w-100 text-center lh-lg" style="max-width: 800px;">
            I had a great experience working with Rebecca. She was very professional and knowledgeable about the market. She was always available to answer any questions I had and made the process of buying a home much easier. I would highly recommend her to anyone looking to buy or sell a home.
          </i>

          <div class="socials d-flex gap-4">
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-facebook"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-instagram"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-linkedin"></i> </a>
            <a href="#" class="text-decoration-none text-secondary fs-3"> <i class="bi bi-google"></i> </a>
          </div>

        </div>
     
      </div>
    </div>

  </div>
</section>

<!-- Initialize Swiper -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var swiper = new Swiper('.swiper', {
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
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