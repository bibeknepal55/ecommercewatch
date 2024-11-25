<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<header class="header">
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-content">
                <div class="top-bar-left">
                    <span><i class="fas fa-phone"></i> +977 9847370247
                    <span><i class="fas fa-envelope"></i> info@thewatchbotique.com
                </div>
                <div class="top-bar-right">
                    <select class="currency-select">
                        <option value="NPR">NPR</option>
                        <!-- <option value="USD">USD</option> -->
                    </select>
                    <select class="language-select">
                        <option value="EN">English</option>
                        <!-- <option value="NE">Nepali</option> -->
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div class="main-header">
        <div class="container">
            <div class="header-wrapper">
                <!-- Logo -->
                <a href="home.php" class="logo">
                    <h1>The <span>Watch Botique</h1>
                </a>

                <!-- Search Bar -->
                <div class="search-bar">
                    <form action="search_page.php" method="GET">
                        <input type="text" name="search" placeholder="Search for products..." required>
                        <button type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Header Actions -->
                <div class="header-actions">
                    <?php
                        $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
                        $count_wishlist_items->execute([$user_id]);
                        $total_wishlist_counts = $count_wishlist_items->rowCount();

                        $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                        $count_cart_items->execute([$user_id]);
                        $total_cart_counts = $count_cart_items->rowCount();
                    ?>
                    <a href="wishlist.php" class="action-btn">
                        <i class="fas fa-heart"></i>
                        <span class="count"><?= $total_wishlist_counts; ?>
                    </a>
                    <a href="cart.php" class="action-btn">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="count"><?= $total_cart_counts; ?>
                    </a>
                    <div class="user-menu">
                        <button class="user-menu-btn">
                            <i class="fas fa-user"></i>
                        </button>
                        <div class="user-dropdown">
                            <?php
                                $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                                $select_profile->execute([$user_id]);
                                if($select_profile->rowCount() > 0){
                                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                            ?>
                                <div class="user-info">
                                    <img src="uploaded_img/<?= $fetch_profile['image'] ?? 'default-avatar.png'; ?>" alt="">
                                    <p><?= $fetch_profile["name"]; ?></p>
                                </div>
                                <ul class="user-links">
                                    <li><a href="profile.php"><i class="fas fa-user-circle"></i> My Profile</a></li>
                                    <li><a href="orders.php"><i class="fas fa-shopping-bag"></i> My Orders</a></li>
                                    <li><a href="update_user.php"><i class="fas fa-cog"></i> Settings</a></li>
                                    <li>
                                        <a href="components/user_logout.php" class="logout-btn" 
                                           onclick="return confirm('Logout from the website?');">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            <?php
                                }else{
                            ?>
                                <div class="guest-links">
                                    <a href="user_register.php" class="btn-register">Register</a>
                                    <a href="user_login.php" class="btn-login">Login</a>
                                </div>
                            <?php
                                }
                            ?>
                        </div>
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
</header>
