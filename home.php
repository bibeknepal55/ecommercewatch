<?php
include 'components/connect.php';
session_start();
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};
include 'components/wishlist_cart.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>The Watch Botique</title>
   
   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- Google Fonts -->
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>The Watch Botique</h1>
        <p>Class in Every Tick, Style in Every Click.</p>
        <a href="shop.php" class="cta-button">Shop Now</a>
    </div>
    <div class="hero-features">
        <div class="feature">
            <i class="fas fa-shipping-fast"></i>
            <span><font color="black">Free Shipping</font>
        </div>
        <div class="feature">
            <i class="fas fa-shield-alt"></i>
            <span><font color="black">Secure Payment</font>
        </div>
        <div class="feature">
            <i class="fas fa-undo"></i>
            <span><font color="black">Easy Returns</font>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories">
    <div class="section-header">
        <h2>Shop by Category</h2>
        <p>Browse our wide selection of products</p>
    </div>
    <div class="category-grid">
        <a href="category.php?category=watch" class="category-card">
            <div class="category-icon">
                <img src="images/icon-2.png" alt="Watch">
            </div>
            <h3>Watches</h3>
            <span class="category-arrow">→
        </a>
        <!-- <a href="category.php?category=laptop" class="category-card">
            <div class="category-icon">
                <img src="images/icon-1.png" alt="Laptop">
            </div>
            <h3>Laptops</h3>
            <span class="category-arrow">→
        </a> -->
        <!-- Add other categories similarly -->
    </div>
</section>

<!-- Featured Products -->
<section class="featured-products">
    <div class="section-header">
        <h2>Featured Products</h2>
        <p>Our most popular selections</p>
    </div>
    <div class="products-grid">
        <?php
        $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 8"); 
        $select_products->execute();
        if($select_products->rowCount() > 0){
            while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
        ?>
        <div class="product-card">
            
            <form action="" method="post" class="product-form">
                <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
                <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
                <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
                
                <div class="product-image">
                    <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="<?= $fetch_product['name']; ?>">
                    <div class="product-actions">
                        <button type="submit" name="add_to_wishlist" class="action-btn">
                            <i class="fas fa-heart"></i>
                        </button>
                        <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="action-btn">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
                
                <div class="product-info">
                    <h3 class="product-name"><?= $fetch_product['name']; ?></h3>
                    <div class="product-price">
                        <span class="price">Nrs. <?= number_format($fetch_product['price']); ?>/-
                    </div>
                    <div class="product-footer">
                        <div class="quantity">
                            <input type="number" name="qty" class="qty-input" min="1" max="99" value="1">
                        </div>
                        <button type="submit" name="add_to_cart" class="add-to-cart">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">No products added yet!</p>';
        }
        ?>
    </div>
</section>

<!-- Special Offers Section -->
<section class="special-offers">
    <div class="offer-grid">
        <div class="offer-card">
            <div class="offer-content">
                <h3>Premium Watches</h3>
                <p>Up to 50% off on selected items</p>
                <a href="shop.php?category=luxury" class="offer-btn">Shop Now</a>
            </div>
            <img src="images/home-img-1.png" alt="Premium Watches">
        </div>
        <div class="offer-card">
            <div class="offer-content">
                <h3>Latest Watches</h3>
                <p>New arrivals with special launch prices</p>
                <a href="shop.php?category=all" class="offer-btn">Shop Now</a>
            </div>
            <img src="images/home-img-2.png" alt="Smartphones">
        </div>
    </div>
</section>

<!-- Newsletter Section
<section class="newsletter">
    <div class="newsletter-content">
        <h3>Subscribe to Our Newsletter</h3>
        <p>Get updates about new products and special offers</p>
        <form class="newsletter-form">
            <input type="email" placeholder="Enter your email address">
            <button type="submit">Subscribe</button>
        </form>
    </div>
</section> -->

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
