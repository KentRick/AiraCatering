<!-- Navbar start -->
<div class="container-fluid nav-bar">
    <div class="container">
        <nav class="navbar navbar-light navbar-expand-lg py-4">
            <a href="index.php" class="navbar-brand">
                <h1 class="text-primary fw-bold mb-0">
                    Aira<span class="text-dark">Catering</span>
                </h1>
            </a>
            <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars text-primary"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    <a href="index.php" class="nav-item nav-link active">Home</a>
                    <a href="catering services.php" class="nav-item nav-link">Catering Services</a>
                    <a href="catering packages.php" class="nav-item nav-link">Catering Packages</a>
                    <a href="index.php#about" class="nav-item nav-link">About Us</a>
                    <!-- Updated Button to Open Modal -->
                    <a href="#" class="btn btn-primary py-2 px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#exampleModal">Reservation Calendar</a>
                </div>


                <?php
                // Start session if not already started
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // Check if the user is logged in
                $isLoggedIn = isset($_SESSION['users']); // Check if user session exists


                // Set user name or guest
                $user = $isLoggedIn ? ($_SESSION['users']['first_name'] . ' ' . $_SESSION['users']['last_name']) : 'Guest';
                ?>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 profile-menu">
                    <?php if ($isLoggedIn): // Check if user is logged in and verified 
                    ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="profile-pic">
                                    <img src="https://placehold.co/35" alt="Profile Picture">
                                </div>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" id="username">
                                        <i class="fas fa-user fa-fw"></i>
                                        <?php echo htmlspecialchars($user); ?>
                                    </a>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-sliders-h fa-fw"></i> Account</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog fa-fw"></i> Settings</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt fa-fw"></i> Log Out</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt fa-fw"></i> Log In</a>
                        </li>
                    <?php endif; ?>
                </ul>


            </div>
        </nav>
    </div>
</div>

<!-- Include the Modal HTML Here -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reservation Calendar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="month" id="month-picker" class="form-control" value="2024-09" min="" />
                </div>
                <div id="calendar"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Navbar End -->