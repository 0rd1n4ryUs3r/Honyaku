<?php

include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_POST['add_category'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);

   $select_category = mysqli_query($conn, "SELECT * FROM `categories` WHERE name = '$name'") or die('query failed');

   if(mysqli_num_rows($select_category) > 0){
      $message[] = 'category already exists';
   }else{
      mysqli_query($conn, "INSERT INTO `categories`(name) VALUES('$name')") or die('query failed');
      $_SESSION['message'][] = 'category added successfully!';
   }
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   
   // Check if there are any products in this category
   $check_products = mysqli_query($conn, "SELECT SUM(stock) as total_stock FROM `products` WHERE category_id = '$delete_id'") or die('query failed');
   $total_stock = mysqli_fetch_assoc($check_products)['total_stock'];
   
   if($total_stock > 0){
      $_SESSION['message'][] = 'Cannot delete category: there are ' . $total_stock . ' total stock in this category!';
   }else{
      mysqli_query($conn, "DELETE FROM `categories` WHERE id = '$delete_id'") or die('query failed');
      $_SESSION['message'][] = 'Category deleted successfully!';
   }
   
   header('location:admin_categories.php');
}

if(isset($_POST['update_category'])){

   $update_id = $_POST['update_id'];
   $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);

   $select_category = mysqli_query($conn, "SELECT * FROM `categories` WHERE name = '$update_name' AND id != '$update_id'") or die('query failed');
   if(mysqli_num_rows($select_category) > 0){
      $_SESSION['message'][] = 'another category with this name already exists';
   }else{
      mysqli_query($conn, "UPDATE `categories` SET name = '$update_name' WHERE id = '$update_id'") or die('query failed');
      header('location:admin_categories.php');
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>categories</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="add-products">

   <h1 class="title">book categories</h1>

   <form action="" method="post">
      <h3>add category</h3>
      <input type="text" name="name" class="box" placeholder="Enter category name" required>
      <input type="submit" value="add category" name="add_category" class="btn">   
   </form>

</section>

<section class="show-products">

   <div class="box-container">

      <?php
         $select_categories = mysqli_query($conn, "SELECT * FROM `categories`") or die('query failed');
         if(mysqli_num_rows($select_categories) > 0){
            while($fetch_categories = mysqli_fetch_assoc($select_categories)){
      ?>
      <div class="box" style="display: flex; flex-direction: column; gap: 0.8rem;">
         <div class="name"><?php echo $fetch_categories['name']; ?></div>
         <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="admin_categories.php?update=<?php echo $fetch_categories['id']; ?>" class="option-btn">update</a>
            <a href="admin_categories.php?delete=<?php echo $fetch_categories['id']; ?>" class="delete-btn" onclick="return confirm('delete this category?');">delete</a>
         </div>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">no categories added yet</p>';
         }
      ?>
   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `categories` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post">
      <h3>edit category</h3>
      <input type="hidden" name="update_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="Enter category name">
      <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.5rem;">
         <input type="submit" value="update" name="update_category" class="btn">
         <input type="reset" value="cancel" id="close-update" class="option-btn">
      </div>
   </form>
   <?php
            }
         }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

</section>

<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>