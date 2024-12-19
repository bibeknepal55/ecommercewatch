<?php

if(isset($_POST['add_to_wishlist'])){
   $redirect_url = isset($_POST['search_query']) ? 'search_page.php?search=' . urlencode($_POST['search_query']) : $_SERVER['PHP_SELF'];
   $return_url = isset($_POST['search_query']) ? 'search_page.php?search=' . urlencode($_POST['search_query']) : $_SERVER['PHP_SELF'];

   if($user_id == ''){
      header('location:user_login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$name, $user_id]);

      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $_SESSION['message'] = 'Product already exists in wishlist';
      header('location:'.$redirect_url);
      exit();
      }elseif($check_cart_numbers->rowCount() > 0){
         $message[] = 'already added to cart!';
      }else{
         $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
         $insert_wishlist->execute([$user_id, $pid, $name, $price, $image]);
         $_SESSION['message'] = 'Added to wishlist!';
      header('location:'.$redirect_url);
      exit();
      }

   }

}

if(isset($_POST['add_to_cart'])){
   $redirect_url = isset($_POST['search_query']) ? 'search_page.php?search=' . urlencode($_POST['search_query']) : $_SERVER['PHP_SELF'];
   $return_url = isset($_POST['search_query']) ? 'search_page.php?search=' . urlencode($_POST['search_query']) : $_SERVER['PHP_SELF'];

   if($user_id == ''){
      header('location:user_login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $qty = $_POST['qty'] ?? 1;
      $qty = filter_var($qty, FILTER_SANITIZE_STRING);

      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      if($check_cart_numbers->rowCount() > 0){
         $message[] = 'already added to cart!';
      }else{
         // Remove the wishlist check and delete - this was causing the issue
         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
         $_SESSION['message'] = 'Product added to cart successfully';
      header('location:'.$redirect_url);
      exit();
      }

   }

}

?>
