<?php
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

// Handle AJAX requests
if(isset($_POST['action']) && !empty($user_id)) {
   $pid = $_POST['pid'];
   $name = $_POST['name'];
   $price = $_POST['price'];
   $image = $_POST['image'];
   $qty = $_POST['qty'];
    
   if($_POST['action'] == 'add_to_cart') {
      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);
  
      $check_stock = $conn->prepare("SELECT stock FROM `products` WHERE id = ?");
      $check_stock->execute([$pid]);
      $product_stock = $check_stock->fetch(PDO::FETCH_ASSOC)['stock'];
  
      if($product_stock < $qty) {
          echo 'not enough stock!';
      } elseif($check_cart_numbers->rowCount() > 0) {
          echo 'already added to cart!';
      } else {
          $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
          $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
          echo 'added to cart!';
      }
   }
    
   if($_POST['action'] == 'add_to_wishlist') {
      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         echo 'already added to wishlist!';}
      else{
         $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
         $insert_wishlist->execute([$user_id, $pid, $name, $price, $image]);
         echo 'added to wishlist!';
        }
      }
    exit;
}

include 'components/wishlist_cart.php';

// Get price range filter
$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 1000000;

// Get sorting option
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'latest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shop | WatchShop</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="shop">
   <div class="container">
      <div class="page-header">
         <div class="header-content">
            <h1>Our Collection of Watches</h1>
         </div>
         
         <div class="filter-sort-container">
            <!-- Price Filter (Left) -->
            <div class="price-filter">
               <form class="price-range" method="GET">
                  <div class="range-inputs">
                     <input type="number" name="min_price" placeholder="Min Price" 
                           value="<?= $min_price ?>" min="0">
                     <span>-</span>
                     <input type="number" name="max_price" placeholder="Max Price" 
                           value="<?= $max_price ?>" min="0">
                     <button type="submit" class="apply-filter">Apply</button>
                  </div>
               </form>
            </div>

            <!-- Filter Title (Center) -->
            <h2 class="filter-title">Filter Products</h2>

            <!-- Sort Options (Right) -->
            <div class="sort-options">
               <select name="sort" onchange="location = this.value;">
                  <option value="?sort=latest" <?= $sort === 'latest' ? 'selected' : '' ?>>Latest</option>
                  <option value="?sort=price-low" <?= $sort === 'price-low' ? 'selected' : '' ?>>Price: Low to High</option>
                  <option value="?sort=price-high" <?= $sort === 'price-high' ? 'selected' : '' ?>>Price: High to Low</option>
                  <option value="?sort=name-asc" <?= $sort === 'name-asc' ? 'selected' : '' ?>>Name: A to Z</option>
               </select>
            </div>
         </div>
      </div>

      <div class="shop-container">
         <!-- Products Section -->
         <div class="products-section">
            <div class="results-count">
               <?php
               $count_query = $conn->prepare("SELECT COUNT(*) as total FROM `products`");
               $count_query->execute();
               $total = $count_query->fetch(PDO::FETCH_ASSOC)['total'];
               echo "<span>{$total} Products Found</span>";
               ?>
            </div>

            <div class="products-grid">
               <?php
                  $query = "SELECT * FROM `products` WHERE 1";
                  
                  // Apply price filter
                  $query .= " AND price BETWEEN :min_price AND :max_price";
                  
                  // Apply sorting
                  switch($sort) {
                     case 'price-low':
                        $query .= " ORDER BY price ASC";
                        break;
                     case 'price-high':
                        $query .= " ORDER BY price DESC";
                        break;
                     case 'name-asc':
                        $query .= " ORDER BY name ASC";
                        break;
                     default:
                        $query .= " ORDER BY id DESC";
                  }

                  $select_products = $conn->prepare($query);
                  
                  // Bind parameters
                  $select_products->bindParam(':min_price', $min_price);
                  $select_products->bindParam(':max_price', $max_price);
                  
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
                        <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
                        <div class="product-actions">
                           <button type="button" class="action-btn wishlist-btn">
                              <i class="fas fa-heart"></i>
                           </button>
                           <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="action-btn">
                              <i class="fas fa-eye"></i>
                           </a>
                        </div>
                     </div>

                     <div class="product-content">
                        <h3 class="product-name"><?= htmlspecialchars($fetch_product['name']); ?></h3>
                        <div class="product-price">
                           <span class="price">Nrs. <?= number_format($fetch_product['price']); ?>/-</span>
                        </div>
                        <div class="product-stock">
                           <?php if ($fetch_product['stock'] == 0): ?>
                                 <span class="stock out-of-stock" style="color: red;">Out of Stock</span>
                           <?php elseif ($fetch_product['stock'] < 5): ?>
                                 <span class="stock low-stock" style="color: #f67800;">Low Stock: <?= $fetch_product['stock']; ?></span>
                           
                           <?php endif; ?>
                        </div>
                        <div class="product-details">
                           <div class="quantity">
                                 <input type="number" name="qty" class="qty-input" min="1" max="<?= $fetch_product['stock']; ?>" value="1" <?= $fetch_product['stock'] == 0 ? 'disabled' : ''; ?>>
                           </div>
                           <button type="button" class="add-to-cart" <?= $fetch_product['stock'] == 0 ? 'disabled' : ''; ?>>
                                 <i class="fas fa-shopping-cart"></i>
                                 <span>Add to Cart</span>
                           </button>
                        </div>
                     </div>
                  </form>
               </div>
               <?php
                     }
                  }else{
                     echo '<p class="empty">No products found!</p>';
                  }
               ?>
            </div>
         </div>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.product-form');
    
    forms.forEach(form => {
        // Wishlist button handler
        const wishlistBtn = form.querySelector('.wishlist-btn');
        if(wishlistBtn) {
            wishlistBtn.onclick = function(e) {
                e.preventDefault();
                handleAction(form, 'add_to_wishlist');
            };
        }

        // Cart button handler
        const cartBtn = form.querySelector('.add-to-cart');
        if(cartBtn) {
            cartBtn.onclick = function(e) {
                e.preventDefault();
                handleAction(form, 'add_to_cart');
            };
        }

        // Quantity input handler
        const qtyInput = form.querySelector('.qty-input');
        if(qtyInput) {
            qtyInput.oninput = function() {
                const maxQty = parseInt(qtyInput.max);
                const currentQty = parseInt(qtyInput.value);
                const stockSpan = form.querySelector('.product-stock span');

                if (currentQty >= maxQty) {
                    stockSpan.style.color = 'red';
                    stockSpan.textContent = `In Stock: ${maxQty}`;
                } else if (maxQty < 5) {
                    stockSpan.style.color = '#f67800';
                    stockSpan.textContent = `Low Stock: ${maxQty}`;
                } else {
                    stockSpan.style.color = 'green';
                    stockSpan.textContent = `In Stock: ${maxQty}`;
                }
            };
        }
    });

    function updateHeaderCounts(type) {
        if(type === 'cart') {
            const cartCounts = document.querySelectorAll('.cart-count, .count[data-type="cart"]');
            cartCounts.forEach(count => {
                let currentCount = parseInt(count.textContent) || 0;
                count.textContent = currentCount + 1;
            });
        } else if(type === 'wishlist') {
            const wishlistCounts = document.querySelectorAll('.wishlist-count, .count[data-type="wishlist"]');
            wishlistCounts.forEach(count => {
                let currentCount = parseInt(count.textContent) || 0;
                count.textContent = currentCount + 1;
            });
        }
    }

    function handleAction(form, action) {
        if('<?= $user_id ?>' === '') {
            showMessage('please login first!', true);
            return;
        }

        const formData = new FormData(form);
        formData.append('action', action);

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            showMessage(data);
            // Update counts only if item was successfully added (not "already added" or "not enough stock")
            if (!data.includes('already') && !data.includes('not enough stock')) {
                if (action === 'add_to_cart') {
                    updateHeaderCounts('cart');
                } else if (action === 'add_to_wishlist') {
                    updateHeaderCounts('wishlist');
                }
            }
        })
        .catch(error => {
            showMessage('something went wrong!', true);
            console.error('Error:', error);
        });
    }

    function showMessage(msg) {
        const message = document.createElement('div');
        message.className = 'message';
        message.innerHTML = `
            <span>${msg}</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        `;
        document.body.appendChild(message);
        setTimeout(() => message.remove(), 3000);
    }
});
</script>

</body>
</html>
