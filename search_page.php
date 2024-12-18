<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
}

include 'components/wishlist_cart.php';

// Initialize message array and get search query
$message = array();
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// Validate search query
if(!empty($search_query)) {
    if(!preg_match('/^[A-Za-z0-9\s\-_.\'",]+$/', $search_query)) {
        $message[] = 'Search contains invalid characters';
    } elseif(strlen($search_query) > 100) {
        $search_query = substr($search_query, 0, 100);
    }
}

// Process session messages
if(isset($_SESSION['error_msg'])) {
    $message[] = $_SESSION['error_msg'];
    unset($_SESSION['error_msg']);
}
if(isset($_SESSION['success_msg'])) {
    $message[] = $_SESSION['success_msg'];
    unset($_SESSION['success_msg']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search Results | WatchShop</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php
include 'components/user_header.php';

// Show any messages
if(!empty($message)){
   foreach($message as $msg){
      echo '<div class="message"><span>'.$msg.'</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
   }
}
?>

<section class="search-page">
   <form action="" method="GET" class="search-form">
      <input type="text" name="search" class="search-input" placeholder="search products..." value="<?= htmlspecialchars($search_query) ?>" required>
      <button type="submit" class="search-btn">Search</button>
   </form>

   <div class="box-container">
   <?php
   if(!empty($search_query)) {
      $search_pattern = '%' . trim($search_query) . '%';
      
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE 
         name LIKE ? OR category LIKE ? OR details LIKE ? 
         ORDER BY CASE 
            WHEN name LIKE ? THEN 1 
            WHEN category LIKE ? THEN 2 
            ELSE 3 
         END, name ASC");
         
      try {
         $select_products->execute([$search_pattern, $search_pattern, $search_pattern, $search_pattern, $search_pattern]);
         $total_results = $select_products->rowCount();

         if($total_results > 0) {
            echo '<div class="heading"><span>Found '.$total_results.' results for "'.htmlspecialchars($search_query).'"</span></div>';
            
            while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
            <form action="" method="post" class="box">
               <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
               <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
               <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
               <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
               <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
               <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
               <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
               <div class="name"><?= $fetch_product['name']; ?></div>
               <div class="flex">
                  <div class="price"><span>Nrs. </span><?= $fetch_product['price']; ?><span>/-</span></div>
                  <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
               </div>
               <input type="submit" value="add to cart" class="btn" name="add_to_cart">
            </form>
   <?php
            }
         } else {
   ?>
            <div class="empty">
               <p>no products found!</p>
               <a href="shop.php" class="btn">browse products</a>
            </div>
   <?php
         }
      } catch(PDOException $e) {
         $message[] = 'An error occurred while searching. Please try again.';
      }
   }
   ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>