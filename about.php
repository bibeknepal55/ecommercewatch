<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
}

include 'components/wishlist_cart.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About Us | WatchShop</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="about">
   <div class="container">
      <!-- Hero Section -->
      <div class="about-hero">
         <div class="hero-content">
            <h1>About <i>Watch Online Shop</i></h1>
            <p>Class in Every Tick, Style in Every Click.</p>
         </div>
      </div>

      <!-- Heritage Section -->
      <div class="heritage-section">
         <div class="heritage-content">
            <h2>Our Heritage</h2>
            <p>Founded in 2023, WatchShop has established itself as Nepal's premier destination for luxury timepieces. We take pride in offering an expertly curated collection of watches that combines traditional craftsmanship with contemporary design.</p>
         </div>
         <div class="heritage-image">
            <img src="images/store-front.jpg" alt="Our Store">
         </div>
      </div>

      <!-- Why Choose Us Section -->
      <div class="features-section">
         <h2>Why Choose WatchShop</h2>
         <div class="features-grid">
            <div class="feature-card">
               <i class="fas fa-certificate"></i>
               <h3>Authentic Products</h3>
               <p>100% genuine products with manufacturer warranty and certification</p>
            </div>
            <div class="feature-card">
               <i class="fas fa-shipping-fast"></i>
               <h3>Express Delivery</h3>
               <p>Swift and secure delivery across Nepal with package tracking</p>
            </div>
            <div class="feature-card">
               <i class="fas fa-tools"></i>
               <h3>Expert Service</h3>
               <p>Professional after-sales service and maintenance support</p>
            </div>
            <div class="feature-card">
               <i class="fas fa-hand-holding-heart"></i>
               <h3>Customer First</h3>
               <p>Dedicated support team for personalized shopping experience</p>
            </div>
         </div>
      </div>

      <!-- Brands Section -->
      <div class="brands-section">
         <h2>Our Premium Brands</h2>
         <div class="brands-grid">
            <div class="brand-logo">
               <img src="images/brands/rolex.png" alt="Rolex">
            </div>
            <div class="brand-logo">
               <img src="images/brands/omega.png" alt="Omega">
            </div>
            <div class="brand-logo">
               <img src="images/brands/tag-heuer.png" alt="TAG Heuer">
            </div>
            <!-- Add more brand logos as needed -->
         </div>
      </div>

      <!-- Expertise Section -->
      <div class="expertise-section">
         <h2>Our Expertise</h2>
         <div class="expertise-content">
            <div class="expertise-text">
               <p>With over three years of experience in luxury watches, our team of certified horologists and watch experts ensures that every timepiece meets the highest standards of quality and authenticity. We offer:</p>
               <ul>
                  <li>Expert watch authentication and verification</li>
                  <li>Professional watch servicing and maintenance</li>
                  <li>Personalized watch recommendations</li>
                  <li>Watch investment consultation</li>
               </ul>
            </div>
            <div class="expertise-image">
               <img src="images/watchmaker.jpg" alt="Watch Expert">
            </div>
         </div>
      </div>

      <!-- Mission Section -->
      <div class="mission-section">
         <div class="mission-content">
            <h2>Our Mission</h2>
            <p>To provide watch enthusiasts with exceptional timepieces while delivering unparalleled customer service and expertise in the world of horology.</p>
         </div>
      </div>

      <!-- CTA Section -->
      <div class="cta-section">
         <h2>Discover Your Perfect Timepiece</h2>
         <p>Browse our collection of premium watches from world-renowned brands.</p>
         <div class="cta-buttons">
            <a href="shop.php" class="btn-primary">Explore Collection</a>
            <a href="contact.php" class="btn-secondary">Get in Touch</a>
         </div>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>

</body>
</html>
