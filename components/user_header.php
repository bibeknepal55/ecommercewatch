<?php
   // Start the session only if it hasn't been started already
   if (session_status() === PHP_SESSION_NONE) {
       session_start();
   }

   // Include the config file with the correct path
   include_once 'connect.php'; // Adjust the path to match your file structure

   // Initialize variables for logged-in and guest users
   $user_id = null;
   $total_wishlist_counts = 0;
   $total_cart_counts = 0;

   if (isset($_SESSION['user_id'])) {
       $user_id = $_SESSION['user_id'];

       // Fetch wishlist count
       $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
       $count_wishlist_items->execute([$user_id]);
       $total_wishlist_counts = $count_wishlist_items->rowCount();

       // Fetch cart count
       $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
       $count_cart_items->execute([$user_id]);
       $total_cart_counts = $count_cart_items->rowCount();
   }
?>


<header class="header">
    <!-- Main Header -->
    <div class="main-header">
        <div class="container">
            <div class="header-wrapper">
                <!-- Logo -->
                <a href="home.php" class="logo">
                    <h1>Watch <span>Online Shop</span></h1>
                </a>

                <!-- Search Bar -->
                <div class="search-bar">
                    <form action="search_page.php" method="GET" class="search-form" id="search-form">
                        <input type="text" name="search" class="search-input" 
                            placeholder="Search for products..." 
                            minlength="1" maxlength="100"
                            pattern="[A-Za-z0-9\s-]+" 
                            title="Only letters, numbers, spaces and hyphens allowed"
                            required>
                        <button type="submit" class="search-btn" aria-label="Search">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Wishlist -->
<a href="<?php echo $user_id ? 'wishlist.php' : 'user_login.php'; ?>" class="action-btn">
    <i class="fas fa-heart"></i>
    <span class="count" data-type="wishlist"><?= $total_wishlist_counts; ?></span>
</a>

<!-- Cart -->
<a href="<?php echo $user_id ? 'cart.php' : 'user_login.php'; ?>" class="action-btn">
    <i class="fas fa-shopping-cart"></i>
    <span class="count" data-type="cart"><?= $total_cart_counts; ?></span>
</a>
                    <!-- User Menu -->
                    <div class="user-menu">
                        <button class="user-menu-btn" onclick="toggleUserMenu(event)">
                            <i class="fas fa-user"></i>
                        </button>

                        <div class="user-dropdown">
                            <?php if ($user_id): ?>
                                <!-- Logged-in User Info -->
                                <?php
                                    $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                                    $select_profile->execute([$user_id]);
                                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <div class="user-info">
                                    <p><?= $fetch_profile["name"]; ?></p>
                                </div>
                                <ul class="user-links">
                                    <li><a href="orders.php"><i class="fas fa-shopping-bag"></i> My Orders</a></li>
                                    <li><a href="update_user.php"><i class="fas fa-cog"></i> Settings</a></li>
                                    <li>
                                        <a href="components/user_logout.php" class="logout-btn" 
                                           onclick="return confirm('Logout from the website?');">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            <?php else: ?>
                                <!-- Guest User Links -->
                                <div class="guest-links">
                                    <a href="user_register.php" class="btn-register">Register</a>
                                    <a href="user_login.php" class="btn-login">Login</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <!-- Navigation -->
            <nav class="main-nav">
                <ul class="nav-list">
                    <li><a href="home.php" class="nav-link">Home</a></li>
                    <li><a href="shop.php" class="nav-link">Shop</a></li>
                    <li><a href="orders.php" class="nav-link">Orders</a></li>
                    <li><a href="about.php" class="nav-link">About</a></li>
                    <li><a href="contact.php" class="nav-link">Contact</a></li>
                </ul>
                <button class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </nav>

            </div>
            
        </div>
    </div>
</header>

<script>
function toggleUserMenu(event) {
    event.stopPropagation(); // Prevent click from bubbling up
    const dropdown = document.querySelector('.user-dropdown');
    
    // Toggle current dropdown
    if (dropdown.style.display === 'block') {
        dropdown.style.display = 'none';
    } else {
        dropdown.style.display = 'block';
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.querySelector('.user-dropdown');
    const button = document.querySelector('.user-menu-btn');
    
    if (!button.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.style.display = 'none';
    }
});

// Close dropdown when pressing Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const dropdown = document.querySelector('.user-dropdown');
        dropdown.style.display = 'none';
    }
});
</script>
