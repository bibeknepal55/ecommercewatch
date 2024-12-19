<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
   $delete_admins->execute([$delete_id]);
   header('location:admin_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Accounts</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="accounts">

   <h1 class="heading">Admin Accounts</h1>

   <div class="box-container">

   <div class="box">
      <a href="register_admin.php" class="option-btn">Register Admin</a>
   </div>

   <?php
      $select_accounts = $conn->prepare("SELECT * FROM `admins`");
      $select_accounts->execute();
      if($select_accounts->rowCount() > 0){
   ?>
   <div class="table-container">
      <table>
         <thead>
            <tr>
               <th>Admin ID</th>
               <th>Admin Name</th>
               <th>Actions</th>
            </tr>
         </thead>
         <tbody>
         <?php while($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)){ ?>
            <tr>
               <td><?= $fetch_accounts['id']; ?></td>
               <td><?= $fetch_accounts['name']; ?></td>
               <td>
                  <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('delete this account?');">Delete</a>
                  <?php if($fetch_accounts['id'] == $admin_id): ?>
                     <a href="update_profile.php" class="option-btn">Update</a>
                  <?php endif; ?>
               </td>
            </tr>
         <?php } ?>
         </tbody>
      </table>
   </div>
   <?php
      }else{
         echo '<p class="empty">no accounts available!</p>';
      }
   ?>

   </div>

</section>












<script src="../js/admin_script.js"></script>
   
</body>
</html>