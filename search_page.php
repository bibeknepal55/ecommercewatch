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
   <title>Search Results | WatchShop</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="search-page">
   <div class="container">
      <!-- Search Form -->
      <div class="search-header">
         <form action="" method="POST" class="search-form">
            <input type="text" name="search_box" placeholder="Search for products..." 
                   value="<?= isset($_POST['search_box']) ? $_POST['search_box'] : ''; ?>"
                   class="search-input">
            <button type="submit" name="search_btn" class="search-btn">
               <i class="fas fa-search"></i>
            </button>
         </form>
      </div>

      <!-- Search Results -->
      <div class="search-results">
         <?php
         if(isset($_POST['search_box']) || isset($_POST['search_btn'])){
            $search_box = $_POST['search_box'];
            $search_box = filter_var($search_box, FILTER_SANITIZE_STRING);
            
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE ? OR category LIKE ? OR details LIKE ?");
            $select_products->execute(["%{$search_box}%", "%{$search_box}%", "%{$search_box}%"]);
            
            if($select_products->rowCount() > 0){
         ?>
            <div class="results-header">
               <h2>Search Results for "<?= $search_box ?>"</h2>
               <p><?= $select_products->rowCount() ?> products found</p>
            </div>

            <div class="products-grid">
               <?php
                  while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
               ?>
               <div class="product-card">
                  <form action="" method="post" class="product-form">
                     <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
                     <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
                     <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
                     <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
                     
                     <div class="product-image">
                        <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                        <div class="product-actions">
                           <button type="submit" name="add_to_wishlist" class="action-btn">
                              <i class="fas fa-heart"></i>
                           </button>
                           <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="action-btn">
                              <i class="fas fa-eye"></i>
                           </a>
                        </div>
                     </div>

                     <div class="product-content">
                        <div class="product-category"><?= $fetch_product['category']; ?></div>
                        <h3 class="product-name"><?= $fetch_product['name']; ?></h3>
                        <div class="product-price">
                           <span class="price">Nrs. <?= number_format($fetch_product['price']); ?>/-
                        </div>
                        <div class="product-details">
                           <div class="quantity">
                              <input type="number" name="qty" class="qty" min="1" max="99" value="1" 
                                     onkeypress="if(this.value.length == 2) return false;">
                           </div>
                           <button type="submit" name="add_to_cart" class="add-to-cart">
                              <i class="fas fa-shopping-cart"></i>
                              <span>Add to Cart
                           </button>
                        </div>
                     </div>
                  </form>
               </div>
               <?php
                  }
               ?>
            </div>
         <?php
            }else{
         ?>
            <div class="no-results">
               <img src="images/no-results.svg" alt="No Results">
               <h3>No Results Found</h3>
               <p>We couldn't find any products matching your search.</p>
               <div class="suggestions">
                  <h4>Suggestions:</h4>
                  <ul>
                     <li>Check your spelling</li>
                     <li>Try more general keywords</li>
                     <li>Try different keywords</li>
                  </ul>
               </div>
               <a href="shop.php" class="browse-btn">Browse All Products</a>
            </div>
         <?php
            }
         }
         ?>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
