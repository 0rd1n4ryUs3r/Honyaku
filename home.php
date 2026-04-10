<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   // Get product stock
   $select_product = mysqli_query($conn, "SELECT stock FROM `products` WHERE name = '$product_name'") or die('query failed');
   if(mysqli_num_rows($select_product) > 0){
      $fetch_product = mysqli_fetch_assoc($select_product);
      $product_stock = $fetch_product['stock'];

      $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

      if(mysqli_num_rows($check_cart_numbers) > 0){
         $fetch_cart = mysqli_fetch_assoc($check_cart_numbers);
         $new_quantity = $fetch_cart['quantity'] + $product_quantity;
         if($new_quantity > $product_stock){
            $message[] = 'Cannot add to cart: total quantity would exceed available stock (' . $product_stock . ')!';
         }else{
            mysqli_query($conn, "UPDATE `cart` SET quantity = '$new_quantity' WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
            $message[] = 'product quantity updated in cart!';
         }
      }else{
         if($product_quantity > $product_stock){
            $message[] = 'Cannot add to cart: requested quantity exceeds available stock (' . $product_stock . ')!';
         }else{
            mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
            $message[] = 'product added to cart!';
         }
      }
   }else{
      $message[] = 'Product not found!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- font awesome cdn   -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!--  css link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="home">
   <div class="home-container">
      <div class="home-text">
         <span class="home-badge">Selamat Datang di HonyaKu </span>
         <h1>Explore the world <br> through <span class="highlight">books.</span></h1>
         <p>Dunia membaca dalam genggaman. Temukan ribuan koleksi terbaik dari penulis ternama dan cerita-cerita inspiratif.</p>
         <div class="home-buttons">
            <a href="shop.php" class="btn">Mulai Belanja →</a>
            <a href="about.php" class="option-btn">Pelajari Lebih</a>
         </div>
         <div class="home-stats">
            <div><span>500+</span><br>Buku</div>
            <div><span>200+</span><br>Penulis</div>
            <div><span>1k+</span><br>Pembaca Puas</div>
         </div>
      </div>
      <div class="home-image">
         <img src="images/hero-illustration.png" alt="Reading illustration">
         <div class="floating-card card-1">📖 Best Seller</div>
         <div class="floating-card card-2">⭐ 4.9 Rating</div>
      </div>
   </div>
</section>

<section class="products">

   <h1 class="title">Products</h1>

   <div class="box-container">

      <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
     <form action="" method="post" class="box">
      <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
      <div class="name"><?php echo $fetch_products['name']; ?></div>
      <div class="price">Rp<?php echo $fetch_products['price']; ?>/-</div>
      <div class="stock" style="font-size: 0.9rem; color: var(--text-muted); margin: 0.3rem 0;">Stock: <?php echo $fetch_products['stock']; ?></div>
      <input type="number" min="1" max="<?php echo $fetch_products['stock']; ?>" name="product_quantity" value="1" class="qty" <?php echo $fetch_products['stock'] == 0 ? 'disabled' : ''; ?>>
      <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
      <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
      <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
      <input type="submit" value="add to cart" name="add_to_cart" class="btn" <?php echo $fetch_products['stock'] == 0 ? 'disabled' : ''; ?>>
     </form>
      <?php
         }
      }else{
         echo '<p class="empty">belum ada produk yang ditambahkan</p>';
      }
      ?>
   </div>

   <div class="load-more" style="margin-top: 2rem; text-align:center">
      <a href="shop.php" class="option-btn">load more</a>
   </div>

</section>

<section class="about">

   <div class="flex">

      <div class="image">
         <img src="images/about.jpg" alt="">
      </div>

      <div class="content">
         <h3>about us</h3>
         <p>Kami adalah tim yang terdiri dari 7 orang yang berkolaborasi dalam mengembangkan sebuah aplikasi berbasis web dengan
             fokus pada kemudahan penggunaan, tampilan yang modern, dan fungsionalitas yang optimal. Project ini dibuat sebagai bentuk 
             penerapan pengetahuan dan keterampilan kami di bidang pengembangan web.</p>
         <a href="about.php" class="btn">read more</a>
      </div>

   </div>

</section>

<section class="home-contact">

   <div class="content">
      <h3>KRITIK DAN SARAN</h3>
      <p>Beri masukan untuk website ini guna perkembangan kedepannya</p>
      <a href="contact.php" class="white-btn">contact us</a>
   </div>

</section>





<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>