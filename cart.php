<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
   exit();
}

// Delete item from cart
if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
   $message[] = 'Item removed from cart!';
}

// Delete all items from cart
if(isset($_GET['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
   exit();
}

// Update quantity
if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   $message[] = 'Cart quantity updated!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart | WatchShop</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="shopping-cart">
   <div class="container">
      <div class="page-title">
         <h1>Shopping Cart</h1>
         <p>Review and modify your selected items</p>
      </div>

      <div class="cart-content">
         <?php
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
         ?>
         
         <div class="cart-items">
            <?php
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']);
               $grand_total += $sub_total;
            ?>
            <div class="cart-item">
               <form action="" method="post" class="cart-form">
                  <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                  
                  <div class="item-image">
                     <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="<?= $fetch_cart['name']; ?>">
                     <button type="submit" name="delete" class="delete-btn" 
                             onclick="return confirm('Delete this item?');">
                        <i class="fas fa-trash"></i>
                     </button>
                  </div>

                  <div class="item-details">
                     <div class="item-name">
                        <h3><?= $fetch_cart['name']; ?></h3>
                        <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="view-product">
                           <i class="fas fa-eye"></i> View Details
                        </a>
                     </div>

                     <div class="item-price">
                        <span class="price">Nrs. <?= number_format($fetch_cart['price']); ?>/-
                     </div>

                     <div class="item-quantity">
                        <div class="quantity-control">
                           <button type="button" class="qty-btn minus">-</button>
                           <input type="number" name="qty" class="qty-input" min="1" max="99" 
                                  value="<?= $fetch_cart['quantity']; ?>"
                                  onkeypress="if(this.value.length == 2) return false;">
                           <button type="button" class="qty-btn plus">+</button>
                        </div>
                        <button type="submit" name="update_qty" class="update-btn">
                           <i class="fas fa-sync-alt"></i> Update
                        </button>
                     </div>

                     <div class="item-subtotal">
                        <span>Subtotal:
                        <span class="amount">Nrs. <?= number_format($sub_total); ?>/-
                     </div>
                  </div>
               </form>
            </div>
            <?php
            }
            ?>
         </div>

         <div class="cart-summary">
            <div class="summary-details">
               <h3>Order Summary</h3>
               
               <div class="summary-item">
                  <span>Subtotal
                  <span>Nrs. <?= number_format($grand_total); ?>/-
               </div>
               
               <div class="summary-item">
                  <span>Delivery
                  <span>Free
               </div>
               
               <div class="summary-total">
                  <span>Total
                  <span>Nrs. <?= number_format($grand_total); ?>/-
               </div>
            </div>

            <div class="cart-actions">
               <a href="shop.php" class="continue-btn">
                  <i class="fas fa-arrow-left"></i> Continue Shopping
               </a>
               <a href="cart.php?delete_all" class="clear-btn <?= ($grand_total > 1)?'':'disabled'; ?>"
                  onclick="return confirm('Delete all items from cart?');">
                  <i class="fas fa-trash"></i> Clear Cart
               </a>
               <a href="checkout.php" class="checkout-btn <?= ($grand_total > 1)?'':'disabled'; ?>">
                  Proceed to Checkout <i class="fas fa-arrow-right"></i>
               </a>
            </div>
         </div>

         <?php
         }else{
            echo '<div class="empty-cart">
                     <img src="images/empty-cart.png" alt="Empty Cart">
                     <h3>Your cart is empty!</h3>
                     <p>Add items to your cart to proceed with checkout</p>
                     <a href="shop.php" class="shop-btn">
                        <i class="fas fa-shopping-bag"></i> Start Shopping
                     </a>
                  </div>';
         }
         ?>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script>
document.querySelectorAll('.qty-btn').forEach(button => {
    button.addEventListener('click', function() {
        const input = this.parentElement.querySelector('.qty-input');
        let value = parseInt(input.value);
        
        if(this.classList.contains('plus') && value < 99) {
            input.value = value + 1;
        }
        else if(this.classList.contains('minus') && value > 1) {
            input.value = value - 1;
        }
    });
});
</script>

</body>
</html>
