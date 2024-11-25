<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

if(isset($_GET['pid'])){
   $pid = $_GET['pid'];
   $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $select_product->execute([$pid]);
   if($select_product->rowCount() > 0){
      $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);
   }else{
      header('location:shop.php');
      exit();
   }
}else{
   header('location:shop.php');
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= $fetch_product['name']; ?> | WatchShop</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="quick-view-container">
    <div class="quick-view-wrapper">
        <div class="quick-view-content">
            <!-- Product Gallery -->
            <div class="quick-view-gallery">
                <div class="quick-view-main-image">
                    <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="" id="main-image">
                </div>
                <div class="quick-view-thumbnails">
                    <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="" 
                         onclick="changeImage(this)" class="active">
                    <?php if($fetch_product['image_02'] != ''): ?>
                    <img src="uploaded_img/<?= $fetch_product['image_02']; ?>" alt="" 
                         onclick="changeImage(this)">
                    <?php endif; ?>
                    <?php if($fetch_product['image_03'] != ''): ?>
                    <img src="uploaded_img/<?= $fetch_product['image_03']; ?>" alt="" 
                         onclick="changeImage(this)">
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="quick-view-info">
                <div class="quick-view-header">
                    <h1 class="quick-view-title"><?= $fetch_product['name']; ?></h1>
                    <div class="quick-view-price">
                        <span class="quick-view-current-price">Nrs. <?= number_format($fetch_product['price']); ?>/-
                        
                    </div>
                </div>

                <div class="quick-view-description">
                    <h3>Description</h3>
                    <p><?= $fetch_product['details']; ?></p>
                </div>


                <form action="" method="post">
                    <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                    <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
                    <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
                    <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
                    
                    <div class="quick-view-quantity">
                        <label>Quantity:</label>
                        <div class="quick-view-quantity-controls">
                            <button type="button" class="quick-view-qty-btn minus">-</button>
                            <input type="number" name="qty" class="quick-view-qty-input" min="1" max="99" value="1">
                            <button type="button" class="quick-view-qty-btn plus">+</button>
                        </div>
                    </div>

                    <div class="quick-view-actions">
                        <button type="submit" name="add_to_cart" class="quick-view-btn quick-view-btn-primary">
                            <i class="fas fa-shopping-cart"></i>
                            Add to Cart
                        </button>
                        <button type="submit" name="add_to_wishlist" class="quick-view-btn quick-view-btn-secondary">
                            <i class="fas fa-heart"></i>
                            Add to Wishlist
                        </button>
                    </div>
                </form>

                <div class="quick-view-additional">
                    <div class="quick-view-info-item">
                        <i class="fas fa-truck"></i>
                        <span>Free Delivery
                    </div>
                    <div class="quick-view-info-item">
                        <i class="fas fa-undo"></i>
                        <span>30 Days Return
                    </div>
                    <div class="quick-view-info-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Secure Payment
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php include 'components/footer.php'; ?>

<script>
function changeImage(element) {
    document.getElementById('main-image').src = element.src;
    document.querySelectorAll('.quick-view-thumbnails img').forEach(img => {
        img.classList.remove('active');
    });
    element.classList.add('active');
}

// Quantity controls
document.addEventListener('DOMContentLoaded', function() {
    const minusBtn = document.querySelector('.quick-view-qty-btn.minus');
    const plusBtn = document.querySelector('.quick-view-qty-btn.plus');
    const qtyInput = document.querySelector('.quick-view-qty-input');

    plusBtn.addEventListener('click', function() {
        let currentValue = parseInt(qtyInput.value);
        if (currentValue < 99) {
            qtyInput.value = currentValue + 1;
        }
    });

    minusBtn.addEventListener('click', function() {
        let currentValue = parseInt(qtyInput.value);
        if (currentValue > 1) {
            qtyInput.value = currentValue - 1;
        }
    });

    // Prevent manual input of invalid values
    qtyInput.addEventListener('change', function() {
        let value = parseInt(this.value);
        if (isNaN(value) || value < 1) {
            this.value = 1;
        } else if (value > 99) {
            this.value = 99;
        }
    });
});
</script>


</body>
</html>
