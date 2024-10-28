<?php
 session_start();
include 'calendar.php';
?>

<?php
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>Aira Cater</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta content="" name="keywords" />
  <meta content="" name="description" />

  <!-- Google Web Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Playball&display=swap"
    rel="stylesheet" />

  <!-- Icon Font Stylesheet -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />

  <!-- Libraries Stylesheet -->
  <link href="lib/animate/animate.min.css" rel="stylesheet" />
  <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet" />
  <link href="lib/owlcarousel/owl.carousel.min.css" rel="stylesheet" />

  <!-- Customized Bootstrap Stylesheet -->
  <link href="css/bootstrap.min.css" rel="stylesheet" />

  <!-- Template Stylesheet -->
  <link href="css/style.css?v=1.0" rel="stylesheet" />
  <link rel="stylesheet" href="css/ionicons.min.css">

  <script src="
https://cdn.jsdelivr.net/npm/antd@5.20.6/dist/antd.min.js
"></script>
  <link href="
https://cdn.jsdelivr.net/npm/antd@5.20.6/dist/reset.min.css
" rel="stylesheet">
</head>

<body>
  <!-- Spinner Start 
        <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" role="status"></div>
        </div>
        Spinner End -->

  <!-- Navbar start -->

  <?php include 'header.php'; ?>
  <!-- Navbar End -->


  <!-- Modal -->
  <div class="modal fade" id="getStartedModal" tabindex="-1" aria-labelledby="getStartedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-square">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-center w-100" id="getStartedModalLabel">Get Started</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <p class="mb-3">Already Have an Account?</p>
          <div class="d-grid gap-2 d-md-flex justify-content-md-center">
            <button type="button" class="btn btn-primary me-md-2 mb-2 mb-md-0" id="loginButton">Yes - Login</button>
            <button type="button" class="btn btn-secondary" id="registerButton">No - Register</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('registerButton').addEventListener('click', function() {
      window.location.href = 'register.php';
    });

    document.getElementById('loginButton').addEventListener('click', function() {
      window.location.href = 'login.php';
    });
  </script>



  <!-- Hero Start -->
  <div class="container-fluid bg-light py-6 my-6 mt-0">
    <div class="container">
      <div class="row g-5 align-items-center">
        <div class="col-lg-7 col-md-12">
          <small
            class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-4 animated bounceInDown">Welcome
            to CaterHeart</small>
          <h1 class="display-1 mb-4 animated bounceInDown" style="font-size: 4rem">
            We make unforgettable memories with our great food and service.
          </h1>

          <button type="button" class="btn btn-primary rounded-pill py-3 px-4 px-md-5 animated bounceInUp"
            data-bs-toggle="modal" data-bs-target="#getStartedModal">
            Get Started
          </button>

        </div>
        <div class="col-lg-5 col-md-12">
          <img src="img/HeroImg.png" class="img-fluid rounded animated zoomIn" alt="" width="1000" height="800" />
        </div>
      </div>
    </div>
  </div>
  <!-- Hero End -->

  <!-- About Start -->
  <div class="container-fluid py-6" id="about">
    <div class="container">
      <div class="row g-5 align-items-center">
        <div class="col-lg-5 wow bounceInUp" data-wow-delay="0.1s">
          <img src="img/about.jpg" class="img-fluid rounded" alt="" />
        </div>
        <div class="col-lg-7 wow bounceInUp" data-wow-delay="0.3s">
          <small
            class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">About
            Us</small>
          <h1 class="display-5 mb-4">Trusted By 200 + satisfied clients</h1>
          <p class="mb-4">
            Established in 2018, Aira Catering Services, nestled in Sitio
            Bukid, Manggahan, Sta. Maria, Bulacan, is your premier destination
            for full-service catering. From meticulously planning your event
            to delivering delicious food and drinks, decorating the venue, and
            ensuring a seamless after-party cleanup, we cover it all with our
            signature motto, <span>"Serbisyong may Puso"</span> (Service with
            Heart). At Aira Catering Services, our mission is creating
            unforgettable memories through our food and services, Leaving a
            positive impact to our customer’s heart. While our vision is to
            provide excellent food and services with a heart to meet our
            customers satisfaction. Whether you're celebrating birthdays,
            fiestas, debuts, reunions, weddings, or any other special
            occasion, Aira Catering Services is here to make your event
            extraordinary. Join us in crafting memories that will be cherished
            for a lifetime.
          </p>
          <div class="row g-4 text-dark mb-5">
            <div class="col-sm-6">
              <i class="fas fa-share text-primary me-2"></i>Easy Customization
              Options
            </div>
            <div class="col-sm-6">
              <i class="fas fa-share text-primary me-2"></i>Tasty Offers for
              Tasty Dishes
            </div>
          </div>
        </div>
      </div>
      <!-- About End -->

      <!-- Events Start -->
      <div class="container-fluid event py-6">
        <div class="container">
          <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
            <small class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">Events</small>
            <h1 class="display-5 mb-5">Events Gallery</h1>
          </div>
          <div class="tab-class text-center">
            <div id="navCarousel" class="carousel slide" data-bs-ride="false">
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <ul class="nav nav-pills d-inline-flex justify-content-center mb-5">
                    <li class="nav-item p-2">
                      <a class="d-flex mx-2 py-2 border border-primary bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-all">
                        <span class="text-dark" style="width: 150px">All Events</span>
                      </a>
                    </li>
                    <?php
                    // Fetch distinct categories from the database
                    $sql = "SELECT DISTINCT category FROM events";
                    $result = $conn->query($sql);
                    $tabs = [];
                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        $tabs[] = $row['category'];
                      }
                    }

                    // Create nav pills
                    for ($i = 0; $i < count($tabs); $i++) {
                      if ($i > 0 && $i % 5 == 0) { // Every 5 items create a new carousel item
                        echo '</ul></div><div class="carousel-item"><ul class="nav nav-pills d-inline-flex justify-content-center mb-5">';
                      }
                      echo '
                                <li class="nav-item p-2">
                                    <a class="d-flex mx-2 py-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill" href="#tab-' . $tabs[$i] . '">
                                        <span class="text-dark" style="width: 150px">' . $tabs[$i] . '</span>
                                    </a>
                                </li>';
                    }
                    ?>
                  </ul>
                </div>
              </div>
              <button class="carousel-control-prev border border-primary bg-light rounded-pill mx-1" type="button" data-bs-target="#navCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next border border-primary bg-light rounded-pill mx-1" type="button" data-bs-target="#navCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>

            <div class="tab-content">
              <!-- All Events Tab -->
              <div id="tab-all" class="tab-pane fade show active p-0">
                <div class="row g-4">
                  <div class="col-lg-12">
                    <div class="row g-4">
                      <?php
                      // Fetch all events from the database
                      $sql = "SELECT event_name, category, image_path FROM events";
                      $result = $conn->query($sql);
                      if ($result->num_rows > 0) {
                        while ($event = $result->fetch_assoc()) {
                          echo '
                                        <div class="col-md-6 col-lg-3 wow bounceInUp" data-wow-delay="0.1s">
                                            <div class="event-img position-relative">
                                                <img class="img-fluid rounded w-100" src="' . $event['image_path'] . '" alt="" />
                                                <div class="event-overlay d-flex flex-column p-4">
                                                    <h4 class="me-auto">' . $event['event_name'] . '</h4>
                                                    <a href="' . $event['image_path'] . '" data-lightbox="all-events" class="my-auto">
                                                        <i class="fas fa-search-plus text-dark fa-2x"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>';
                        }
                      } else {
                        echo "<p>No events available.</p>";
                      }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Category-specific Tabs -->
              <?php
              foreach ($tabs as $tab) {
                echo '<div id="tab-' . $tab . '" class="tab-pane fade show p-0">';
                echo '<div class="row g-4">';
                echo '<div class="col-lg-12">';
                echo '<div class="row g-4">';

                // Fetch events specific to this category
                $sql = "SELECT event_name, image_path FROM events WHERE category = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $tab);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                  while ($event = $result->fetch_assoc()) {
                    echo '
                            <div class="col-md-6 col-lg-3 wow bounceInUp" data-wow-delay="0.1s">
                                <div class="event-img position-relative">
                                    <img class="img-fluid rounded w-100" src="' . $event['image_path'] . '" alt="" />
                                    <div class="event-overlay d-flex flex-column p-4">
                                        <h4 class="me-auto">' . $event['event_name'] . '</h4>
                                        <a href="' . $event['image_path'] . '" data-lightbox="event-' . $tab . '" class="my-auto">
                                            <i class="fas fa-search-plus text-dark fa-2x"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>';
                  }
                } else {
                  echo "<p>No events in this category.</p>";
                }

                echo '</div>'; // End row
                echo '</div>'; // End col-lg-12
                echo '</div>'; // End row g-4
                echo '</div>'; // End tab-pane
              }
              ?>
            </div>
          </div>
        </div>
      </div>
      <!-- Events End -->


      <!-- My Menu Start -->
      <div class="container-fluid blog py-6">
        <div class="container">
          <div class="text-center wow bounceInUp" data-wow-delay="0.1s">
            <small
              class="d-inline-block fw-bold text-dark text-uppercase bg-light border border-primary rounded-pill px-4 py-1 mb-3">MENU</small>
            <h1 class="display-5 mb-5">
              Discover a culinary wonderland, where every dish is a delightful
              adventure.
            </h1>
          </div>
          <div class="row gx-4 justify-content-center">
            <div class="col-md-6 col-lg-4 wow bounceInUp" style="max-width: 300px" data-wow-delay="0.1s">
              <div class="blog-item">
                <div class="overflow-hidden rounded">
                  <img src="img/MainDish.png" class="img-fluid w-100" alt="" />
                </div>
                <div class="blog-content mx-4 d-flex rounded bg-my-green justify-content-center">
                  <a href="#" class="my-h5 lh-base my-auto h-100 p-3">Main Dishes</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4 wow bounceInUp" style="max-width: 300px" data-wow-delay="0.3s">
              <div class="blog-item">
                <div class="overflow-hidden rounded">
                  <img src="img/Pasta.png" class="img-fluid w-100" alt="" />
                </div>
                <div class="blog-content mx-4 d-flex rounded bg-my-green justify-content-center">
                  <a href="#" class="my-h5 lh-base my-auto h-100 p-3">Pasta</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4 wow bounceInUp" style="max-width: 300px" data-wow-delay="0.5s">
              <div class="blog-item">
                <div class="overflow-hidden rounded">
                  <img src="img/Pulutan.png" class="img-fluid w-100" alt="" />
                </div>
                <div class="blog-content mx-4 d-flex rounded bg-my-green justify-content-center">
                  <a href="#" class="my-h5 lh-base my-auto h-100 p-3">Pulutan</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4 wow bounceInUp" style="max-width: 300px" data-wow-delay="0.3s">
              <div class="blog-item">
                <div class="overflow-hidden rounded">
                  <img src="img/Drinks.png" class="img-fluid w-100" alt="" />
                </div>
                <div class="blog-content mx-4 d-flex rounded bg-my-green justify-content-center">
                  <a href="#" class="my-h5 lh-base my-auto h-100 p-3">Drinks</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4 wow bounceInUp" style="max-width: 300px" data-wow-delay="0.5s">
              <div class="blog-item">
                <div class="overflow-hidden rounded">
                  <img src="img/Desserts.png" class="img-fluid w-100" alt="" />
                </div>
                <div class="blog-content mx-4 d-flex rounded bg-my-green justify-content-center">
                  <a href="#" class="my-h5 lh-base my-auto h-100 p-3">Desserts</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



  <!-- Footer Start -->

  <footer class="container-fluid footer-07">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-12 text-center">
          <h2 class="footer-heading">
            <a href="#" class="logo">AiraCatering</a>
          </h2>
          <p class="menu">
            <a href="index.php">Home</a>
            <a href="#">Catering Services</a>
            <a href="catering packages.php">Catering Packages</a>
            <a href="#about">About Us</a>
            <a href="#">Contact</a>
          </p>
          <ul class="ftco-footer-social p-0">
            <li class="ftco-animate">
              <a href="#" data-toggle="tooltip" data-placement="top" title="Facebook"><span
                  class="ion-logo-facebook"></span></a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <!-- Footer End -->

  <!-- Copyright Start -->
  <div class="container-fluid copyright bg-dark py-4">
    <div class="container">
      <div class="row">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
          <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>AiraCatering</a>, All
            right reserved.</span>
        </div>
      </div>
    </div>
  </div>
  <!-- Copyright End -->

  <!-- Floating Messenger Button -->
  <?php include 'messenger.php'; ?>

  <!-- Back to Top -->
  <!-- <a href="#" class="btn btn-md-square btn-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a> -->


  <!-- JavaScript Libraries -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/waypoints/waypoints.min.js"></script>
  <script src="lib/counterup/counterup.min.js"></script>
  <script src="lib/lightbox/js/lightbox.min.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>

  <!-- Template Javascript -->
  <script src="js/main.js"></script>
</body>

</html>