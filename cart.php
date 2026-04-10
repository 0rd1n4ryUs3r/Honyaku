<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['update_cart'])){
   $cart_id = $_POST['cart_id'];
   $cart_quantity = $_POST['cart_quantity'];

   // Get cart item to find product name
   $select_cart_item = mysqli_query($conn, "SELECT name FROM `cart` WHERE id = '$cart_id'") or die('query failed');
   if(mysqli_num_rows($select_cart_item) > 0){
      $fetch_cart_item = mysqli_fetch_assoc($select_cart_item);
      $product_name = $fetch_cart_item['name'];

      // Get product stock
      $select_product = mysqli_query($conn, "SELECT stock FROM `products` WHERE name = '$product_name'") or die('query failed');
      if(mysqli_num_rows($select_product) > 0){
         $fetch_product = mysqli_fetch_assoc($select_product);
         $product_stock = $fetch_product['stock'];

         if($cart_quantity > $product_stock){
            $message[] = 'Cannot update: quantity exceeds available stock (' . $product_stock . ')!';
         }else{
            mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
            $message[] = 'cart quantity updated!';
         }
      }else{
         $message[] = 'Product not found!';
      }
   }else{
      $message[] = 'Cart item not found!';
   }
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
   header('location:cart.php');
}

if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>shopping cart</h3>
   <p> <a href="home.php">home</a> / cart </p>
</div>

<section class="shopping-cart">

   <h1 class="title">products added</h1>

   <div class="box-container">
      <?php
         $grand_total = 0;
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){   
      ?>
      <div class="box">
         <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
         <div class="image-wrapper">
            <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
         </div>
         <div class="product-info">
            <div class="name"><?php echo $fetch_cart['name']; ?></div>
            <div class="price">Rp<?php echo $fetch_cart['price']; ?>/-</div>
            <form action="" method="post">
               <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
               <div class="quantity-controls">
                  <label for="qty-<?php echo $fetch_cart['id']; ?>">Quantity:</label>
                  <input type="number" id="qty-<?php echo $fetch_cart['id']; ?>" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>" class="qty-input">
                  <input type="submit" name="update_cart" value="Update" class="update-btn">
               </div>
            </form>
            <div class="sub-total">
               Sub total: <span>Rp<?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?>/-</span>
            </div>
         </div>
      </div>
      <?php
      $grand_total += $sub_total;
         }
      }else{
         echo '<p class="empty">your cart is empty</p>';
      }
      ?>
   </div>

   <?php if($grand_total > 0): ?>
   <div style="margin-top: 2rem; text-align:center;">
      <a href="cart.php?delete_all" class="delete-btn" onclick="return confirm('delete all from cart?');">delete all</a>
   </div>

   <div class="cart-total">
      <p>Grand Total: <span>Rp<?php echo $grand_total; ?>/-</span></p>
      <div class="flex">
         <a href="shop.php" class="option-btn">Continue Shopping</a>
         <a href="checkout.php" class="btn">Proceed to Checkout</a>
      </div>
   </div>
   <?php endif; ?>

</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>