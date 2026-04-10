<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

$categories = [];
$select_categories = mysqli_query($conn, "SELECT * FROM `categories`") or die('query failed');
if(mysqli_num_rows($select_categories) > 0){
   while($fetch_category = mysqli_fetch_assoc($select_categories)){
      $categories[] = $fetch_category;
   }
}

if(isset($_POST['add_product'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = $_POST['price'];
   $stock = (int) $_POST['stock'];
   $category_id = isset($_POST['category_id']) ? (int) $_POST['category_id'] : 0;
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('query failed');

   if(mysqli_num_rows($select_product_name) > 0){
      $message[] = 'product name already added';
   }else{
      $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, price, stock, image, category_id) VALUES('$name', '$price', '$stock', '$image', '$category_id')") or die('query failed');

      if($add_product_query){
         if($image_size > 2000000){
            $message[] = 'image size is too large';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $_SESSION['message'][] = 'product added successfully!';
         }
      }else{
         $message[] = 'product could not be added!';
      }
   }
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $_SESSION['message'][] = 'Product deleted successfully!';
   header('location:admin_products.php');
}

if(isset($_POST['update_product'])){

   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];
   $update_stock = (int) $_POST['update_stock'];
   $update_category = isset($_POST['update_category']) ? (int) $_POST['update_category'] : 0;

   mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price', stock = '$update_stock', category_id = '$update_category' WHERE id = '$update_p_id'") or die('query failed');

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $_SESSION['message'][] = 'image file size is too large';
      }else{
         mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/'.$update_old_image);
      }
   }

   $_SESSION['message'][] = 'Product updated successfully!';
   header('location:admin_products.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- product CRUD section starts  -->

<section class="add-products">

   <h1 class="title">shop products</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>add product</h3>
      <input type="text" name="name" class="box" placeholder="Masukan judul buku" required>
      <input type="number" min="0" name="price" class="box" placeholder="Masukan harga" required>
      <input type="number" min="0" name="stock" class="box" placeholder="Masukan stok buku" required>
      <select name="category_id" class="box">
         <option value="0">Uncategorized</option>
         <?php foreach($categories as $category){ ?>
            <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
         <?php } ?>
      </select>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input type="submit" value="add product" name="add_product" class="btn">   
   </form>

</section>

<!-- product CRUD section ends -->

<!-- show products  -->

<section class="show-products">

   <div class="box-container">

      <?php
         $select_products = mysqli_query($conn, "SELECT p.*, c.name AS category_name FROM `products` p LEFT JOIN `categories` c ON p.category_id = c.id") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <div class="box">
         <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <div class="category" style="font-size: 0.82rem; color: var(--admin-text-light); margin: 0.3rem 0;">
            Category: <?php echo !empty($fetch_products['category_name']) ? $fetch_products['category_name'] : 'Uncategorized'; ?>
         </div>
         <div class="price" style="font-size: 1rem; font-weight: 700; color: var(--admin-accent); margin: 0.4rem 0 0.2rem;">
            Rp<?php echo $fetch_products['price']; ?>/-
         </div>
         <div class="stock" style="font-size: 0.9rem; color: var(--admin-secondary); margin: 0.2rem 0 0.8rem;">
            Stock: <?php echo $fetch_products['stock']; ?>
         </div>
         <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">update</a>
            <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
         </div>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">belum ada produk yang ditambahkan</p>';
      }
      ?>
   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <h3>edit product</h3>
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
      <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="" style="width:100%; height:200px; object-fit:cover; border-radius:16px; margin-bottom:1rem;">
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required placeholder="enter product name">
      <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box" required placeholder="enter product price">
      <input type="number" name="update_stock" value="<?php echo $fetch_update['stock']; ?>" min="0" class="box" required placeholder="enter product stock">
      <select name="update_category" class="box">
         <option value="0" <?php echo ($fetch_update['category_id'] == 0 ? 'selected' : ''); ?>>Uncategorized</option>
         <?php foreach($categories as $category){ ?>
            <option value="<?php echo $category['id']; ?>" <?php if($category['id'] == $fetch_update['category_id']) echo 'selected'; ?>><?php echo $category['name']; ?></option>
         <?php } ?>
      </select>
      <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
      <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.5rem;">
         <input type="submit" value="update" name="update_product" class="btn">
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