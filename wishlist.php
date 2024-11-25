<?php
include 'components/connect.php';
session_start();
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};

include 'components/wishlist_cart.php';

// Delete item from wishlist
if(isset($_POST['delete'])){
   $wishlist_id = $_POST['wishlist_id'];
   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE id = ?");
   $delete_wishlist_item->execute([$wishlist_id]);
   $message[] = 'Wishlist item deleted successfully';
}

// Add to cart from wishlist
if(isset($_POST['add_to_cart'])){
   $pid = $_POST['pid'];
   $name = $_POST['name'];
   $price = $_POST['price'];
   $image = $_POST['image'];
   $qty = $_POST['qty'] ?? 1; // Default quantity is 1

   // Check if product already exists in cart
   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE pid = ? AND user_id = ?");
   $check_cart_numbers->execute([$pid, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'Already added to cart!';
   }else{
      try {
         // Insert into cart
         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
         $message[] = 'Added to cart successfully!';
      } catch(PDOException $e) {
         $message[] = 'Error adding to cart: ' . $e->getMessage();
      }
   }
}

// Delete all items from wishlist
if(isset($_GET['delete_all'])){
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist->execute([$user_id]);
   header('location:wishlist.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Wishlist</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="wishlist">
   <div class="container">
      <div class="page-title">
         <h1>My Wishlist</h1>
         <p>Products you have saved for later</p>
      </div>

      <div class="wishlist-content">
         <?php
         $grand_total = 0;
         $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
         $select_wishlist->execute([$user_id]);
         if($select_wishlist->rowCount() > 0){
         ?>
         <div class="wishlist-grid">
            <?php
            while($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)){
               $grand_total += $fetch_wishlist['price'];
            ?>
            <div class="wishlist-card">
               <!-- Separate forms for delete and add to cart actions -->
               <div class="wishlist-image">
                  <img src="uploaded_img/<?= $fetch_wishlist['image']; ?>" alt="<?= $fetch_wishlist['name']; ?>">
                  <form action="" method="post" class="delete-form">
                     <input type="hidden" name="wishlist_id" value="<?= $fetch_wishlist['id']; ?>">
                     <button type="submit" name="delete" class="delete-btn" onclick="return confirm('Delete this item?');">
                        <i class="fas fa-times"></i>
                     </button>
                  </form>
               </div>

               <div class="wishlist-info">
                  <h3 class="product-name"><?= $fetch_wishlist['name']; ?></h3>
                  <div class="price">
                     <span class="currency">Nrs.</span>
                     <span class="amount"><?= number_format($fetch_wishlist['price']); ?></span>
                  </div>
                  
                  <div class="wishlist-actions">
                     <a href="quick_view.php?pid=<?= $fetch_wishlist['pid']; ?>" class="view-btn">
                        <i class="fas fa-eye"></i>
                        <span>View Details</span>
                     </a>
                     
                     <form action="" method="post" class="cart-form">
                        <input type="hidden" name="pid" value="<?= $fetch_wishlist['pid']; ?>">
                        <input type="hidden" name="name" value="<?= $fetch_wishlist['name']; ?>">
                        <input type="hidden" name="price" value="<?= $fetch_wishlist['price']; ?>">
                        <input type="hidden" name="image" value="<?= $fetch_wishlist['image']; ?>">
                        <button type="submit" name="add_to_cart" class="cart-btn">
                           <i class="fas fa-shopping-cart"></i>
                           <span>Add to Cart</span>
                        </button>
                     </form>
                  </div>
               </div>
            </div>
            <?php
            }
            ?>
         </div>

         <div class="wishlist-summary">
            <p class="grand-total">
               Total Amount: <span>Nrs. <?= number_format($grand_total); ?>/-</span>
            </p>
            <div class="wishlist-buttons">
               <a href="shop.php" class="continue-btn">Continue Shopping</a>
               <a href="wishlist.php?delete_all" class="delete-all-btn" onclick="return confirm('Delete all from wishlist?');">
                  Delete All Items
               </a>
            </div>
         </div>
         <?php
         }else{
            echo '<div class="empty-wishlist">
                     <img src="images/empty-wishlist.png" alt="Empty Wishlist">
                     <h3>Your wishlist is empty!</h3>
                     <p>Add items that you want to buy later</p>
                     <a href="shop.php" class="shop-btn">Start Shopping</a>
                  </div>';
         }
         ?>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>

</body>
</html>
