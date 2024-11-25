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

if(isset($_POST['order'])){
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);
   $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
   $total_products = filter_var($_POST['total_products'], FILTER_SANITIZE_STRING);
   $total_price = filter_var($_POST['total_price'], FILTER_SANITIZE_STRING);

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'Order placed successfully!';
      header('location:orders.php');
      exit();
   }else{
      $message[] = 'Your cart is empty!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout | WatchShop</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="checkout">
   <div class="container">
      <div class="page-header">
         <h1>Checkout</h1>
         <div class="breadcrumb">
            <a href="home.php">Home</a>
            <i class="fas fa-angle-right"></i>
            <a href="cart.php">Cart</a>
            <i class="fas fa-angle-right"></i>
            <span>Checkout
         </div>
      </div>

      <div class="checkout-container">
         <!-- Order Summary -->
         <div class="order-summary">
            <h2>Order Summary</h2>
            <?php
               $grand_total = 0;
               $cart_items = [];
               $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
               $select_cart->execute([$user_id]);
               if($select_cart->rowCount() > 0){
                  while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                     $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') ';
                     $total_products = implode($cart_items);
                     $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
            ?>
            <div class="cart-item">
               <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
               <div class="item-details">
                  <h3><?= $fetch_cart['name']; ?></h3>
                  <p>Nrs. <?= number_format($fetch_cart['price']); ?> x <?= $fetch_cart['quantity']; ?></p>
               </div>
               <div class="item-total">
                  Nrs. <?= number_format($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-
               </div>
            </div>
            <?php
                  }
               }else{
                  echo '<p class="empty">Your cart is empty!</p>';
               }
            ?>
            <div class="summary-totals">
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
         </div>

         <!-- Checkout Form -->
         <div class="checkout-form">
            <h2>Shipping Details</h2>
            <form action="" method="POST">
               <input type="hidden" name="total_products" value="<?= $total_products; ?>">
               <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
               
               <div class="form-group">
                  <label for="name">Full Name</label>
                  <input type="text" name="name" id="name" required placeholder="Enter your name" 
                         class="form-control">
               </div>

               <div class="form-group">
                  <label for="number">Phone Number</label>
                  <input type="number" name="number" id="number" required placeholder="Enter your number" 
                         class="form-control" min="0" max="9999999999" 
                         onkeypress="if(this.value.length == 10) return false;">
               </div>

               <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" name="email" id="email" required placeholder="Enter your email" 
                         class="form-control">
               </div>

               <div class="form-group">
                  <label for="method">Payment Method</label>
                  <select name="method" id="method" class="form-control" required>
                     <option value="" disabled selected>Select Payment Method</option>
                     <option value="cash on delivery">Cash on Delivery</option>
                     <option value="credit card">Credit Card</option>
                     <option value="esewa">eSewa</option>
                     <option value="khalti">Khalti</option>
                  </select>
               </div>

               <div class="form-group">
                  <label for="address">Delivery Address</label>
                  <textarea name="address" id="address" class="form-control" required 
                            placeholder="Enter your address" rows="4"></textarea>
               </div>

               <button type="submit" name="order" class="place-order-btn" 
                       <?= ($grand_total > 1)?'':'disabled' ?>>
                  Place Order <i class="fas fa-arrow-right"></i>
               </button>
            </form>
         </div>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
