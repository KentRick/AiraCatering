<?php
// Include database connection
include 'db_connect.php';

// Fetch all categories
$sql_categories = "SELECT * FROM event_categories";
$result_categories = $conn->query($sql_categories);
$categories = $result_categories->fetch_all(MYSQLI_ASSOC);

// Initialize slides array
$slides = [];

// Fetch packages related to each category
foreach ($categories as $category) {
    $category_id = $category['id'];
    $sql_packages = "SELECT * FROM event_packages WHERE category_id = $category_id";
    $result_packages = $conn->query($sql_packages);
    $slides[$category_id] = $result_packages->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catering Packages</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        h1 {
            text-align: center;
            font-size: 2.5rem;
            margin: 1.5rem 0;
        }

        h2 {
            padding-top: 10px;
            margin-bottom: 10px;
        }

        .collection {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .swiper {
            height: fit-content;
        }

        .collection .content {
            position: relative; /* Set to relative for positioning the button */
            height: 100%;
            width: 25rem;
            background-color: transparent;
            border: .2rem solid rgba(255, 255, 255, .1);
            border-radius: .7rem;
            overflow: hidden;
        }

        .content img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            border-bottom-left-radius: .6rem;
            border-bottom-right-radius: .6rem;
            cursor: pointer;
        }

        /* Button styles */
        .view-btn {
            position: absolute;
            bottom: 20px; /* Position it above the bottom of the card */
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(255, 255, 255, 0.8);
            border: none;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            color: #275437;
            border-radius: 0.5rem;
            opacity: 0; /* Hidden by default */
            transition: opacity 0.3s ease;
        }

        /* Show button on hover */
        .content:hover .view-btn {
            opacity: 1; /* Show on hover */
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fffcf8;
            padding: 2rem;
            border-radius: .5rem;
            text-align: center;
            width: 80%;
            max-width: 600px;
        }

        .modal-content img {
            width: 100%;
            border-radius: .5rem;
        }

        .modal h3 {
            margin-top: 1rem;
            font-size: 1.5rem;
        }

        .modal p {
            font-size: 1rem;
            margin: 1rem 0;
        }

        .close-btn-packages {
            background-color: #275437;
            padding: .5rem 1rem;
            font-size: 1rem;
            border: none;
            color: #fff;
            cursor: pointer;
            border-radius: .5rem;
        }

    </style>
</head>

<body>
    <div>
        <h1 class="text-primary fw-bold mb-5 text-center" style="text-decoration: underline; text-decoration-color:black;"  >Catering <span class="text-dark">Packages</span></h1>

        <?php foreach ($categories as $category): ?>
            <section class="collection">
                <div class="swiper mySwiper">
                    <h2 class="text-primary fw-bold mb-5 text-center" style="margin-bottom: 5px;">
                        <?php echo $category['category_name']; ?> <span class="text-dark">Packages</span>
                    </h2>
                    <div class="swiper-wrapper">
                        <?php foreach ($slides[$category['id']] as $index => $slide): ?>
                            <div class="content swiper-slide" data-index="<?php echo $index; ?>">
                                <img src="<?php echo 'uploads/' . $slide['image']; ?>" alt="" onclick="openModal(<?php echo $index; ?>, <?php echo $category['id']; ?>)">
                                <button class="view-btn" onclick="openModal(<?php echo $index; ?>, <?php echo $category['id']; ?>)">View</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endforeach; ?>
    </div>

    <!-- Modal HTML -->
    <div id="imageModal" class="modal">
        <div class="modal-content">
            <img id="modalImage" src="" alt="">
            <h3 id="modalTitle"></h3>
            <p id="modalDescription"></p>
            <div style="display: flex; justify-content: space-between; margin-top: 1.5rem;">
                <button class="close-btn-packages" onclick="closeModal()">Close</button>
                <button class="close-btn-packages" onclick="reserveNow()">Reserve Now</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            loop: true,
            slidesPerView: "auto",
            coverflowEffect: {
                rotate: 0,
                stretch: 0,
                depth: 150,
                modifier: 2.5,
                slideShadows: true,
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            }
        });

        var slidesData = <?php echo json_encode($slides); ?>;
        var modal = document.getElementById("imageModal");
        var modalImage = document.getElementById("modalImage");
        var modalTitle = document.getElementById("modalTitle");
        var modalDescription = document.getElementById("modalDescription");

        function openModal(index, categoryId) {
            var slide = slidesData[categoryId][index];
            modalImage.src = 'uploads/' + slide.image;
            modalTitle.innerHTML = slide.title;
            modalDescription.innerHTML = slide.description;
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        };

        function reserveNow() {
            var slide = slidesData[modal.getAttribute('data-index')];
            alert("Reserve Now clicked for: " + slide.title);
        }
    </script>
</body>
</html>

