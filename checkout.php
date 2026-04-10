<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['order_btn'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $number = $_POST['number'];
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $method = mysqli_real_escape_string($conn, $_POST['method']);
   $address = mysqli_real_escape_string($conn, 'No. '. $_POST['flat'].', '. $_POST['street'].', '. $_POST['city'].', '. $_POST['country'].' - '. $_POST['pin_code']);
   $placed_on = date('d-M-Y');
   
   $transfer_proof = '';
   if($method === 'Bank Transfer' && isset($_FILES['transfer_proof'])){
      $upload_dir = 'uploaded_img/transfer_proofs/';
      if(!is_dir($upload_dir)){
         mkdir($upload_dir, 0755, true);
      }
      $file_name = time() . '_' . basename($_FILES['transfer_proof']['name']);
      $file_path = $upload_dir . $file_name;
      if(move_uploaded_file($_FILES['transfer_proof']['tmp_name'], $file_path)){
         $transfer_proof = $file_name;
      }
   }

   $cart_total = 0;
   $cart_products[] = '';

   $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   if(mysqli_num_rows($cart_query) > 0){
      while($cart_item = mysqli_fetch_assoc($cart_query)){
         $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      }
   }

   $total_products = implode(', ',$cart_products);

   $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

   if($cart_total == 0){
      $message[] = 'your cart is empty';
   }else{
      // verify stock before placing order
      $stock_ok = true;
      mysqli_data_seek($cart_query, 0);
      while($cart_item = mysqli_fetch_assoc($cart_query)){
         $select_product = mysqli_query($conn, "SELECT stock FROM `products` WHERE name = '{$cart_item['name']}'") or die('query failed');
         if(mysqli_num_rows($select_product) > 0){
            $fetch_product = mysqli_fetch_assoc($select_product);
            if($cart_item['quantity'] > $fetch_product['stock']){
               $stock_ok = false;
               $message[] = 'Cannot place order: product "' . $cart_item['name'] . '" only has ' . $fetch_product['stock'] . ' left in stock.';
            }
         }else{
            $stock_ok = false;
            $message[] = 'Cannot place order: product "' . $cart_item['name'] . '" is not available.';
         }
      }

      if($stock_ok){
         if(mysqli_num_rows($order_query) > 0){
            $message[] = 'order already placed!'; 
         }else{
            mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on, transfer_proof) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on', '$transfer_proof')") or die('query failed');
            
            // decrement stock for each ordered product
            mysqli_data_seek($cart_query, 0);
            while($cart_item = mysqli_fetch_assoc($cart_query)){
               mysqli_query($conn, "UPDATE `products` SET stock = GREATEST(stock - {$cart_item['quantity']}, 0) WHERE name = '{$cart_item['name']}'") or die('query failed');
            }

            mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
            $message[] = 'order placed successfully!';
         }
      }
   }
   
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>checkout</h3>
   <p> <a href="home.php">home</a> / checkout </p>
</div>

<section class="display-order">

   <h1 class="title">Order Summary</h1>

   <?php  
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select_cart) > 0){
         while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
   ?>
   <p><?php echo $fetch_cart['name']; ?> <span>(<?php echo '$'.$fetch_cart['price'].'/-'.' x '. $fetch_cart['quantity']; ?>)</span></p>
   <?php
      }
   }else{
      echo '<p class="empty">your cart is empty</p>';
   }
   ?>
   <div class="grand-total">Grand Total: <span>$<?php echo $grand_total; ?>/-</span></div>

</section>

<section class="checkout">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Complete Your Order</h3>
      
      <div class="flex">
         <div class="inputBox">
            <span>Full Name:</span>
            <input type="text" name="name" required placeholder="Enter your full name">
         </div>
         <div class="inputBox">
            <span>Phone Number:</span>
            <input type="number" name="number" required placeholder="Enter your phone number">
         </div>
         <div class="inputBox">
            <span>Email Address:</span>
            <input type="email" name="email" required placeholder="Enter your email">
         </div>
         <div class="inputBox">
            <span>Payment Method:</span>
            <select name="method" id="paymentMethod" onchange="toggleTransferProof()">
            <option value="method">Pilih Metode</option>
            <option value="cod">Cash On Delivery</option>   
            <option value="Bank Transfer">Gopay</option>
               <option value="Bank Transfer">Dana</option>
               <option value="Bank Transfer">OVO</option>
            </select>
         </div>
         <div class="inputBox" id="bankTransferInfo" style="display:none; grid-column: 1 / -1;">
            <div style="background-color: var(--seashell); padding: 1.5rem; border-radius: 18px; border: 1px solid rgba(92, 75, 58, 0.18);">
               <p style="color: var(--coffee); font-size: 1.5rem; margin-bottom: 0.8rem;"><strong>Transfer Bank Account:</strong></p>
               <p style="color: var(--sage); font-size: 1.6rem; font-weight: 600;">Transfer ke: 085123303965</p>
            </div>
         </div>
         <div class="inputBox" id="transferProofBox" style="display:none; grid-column: 1 / -1;">
            <span>Upload Proof of Transfer:</span>
            <input type="file" name="transfer_proof" id="transferProof" accept="image/*" placeholder="Upload screenshot/photo of transfer">
         </div>
         <div class="inputBox">
            <span>House Number:</span>
            <input type="number" min="0" name="flat" required placeholder="Enter house number">
         </div>
         <div class="inputBox">
            <span>Street Address:</span>
            <input type="text" name="street" required placeholder="Enter street name">
         </div>
         <div class="inputBox">
            <span>City:</span>
            <input type="text" name="city" required placeholder="Enter city name">
         </div>
         <div class="inputBox">
            <span>State/Province:</span>
            <input type="text" name="state" required placeholder="Enter state or province">
         </div>
         <div class="inputBox">
            <span>Country:</span>
            <input type="text" name="country" required placeholder="Indonesia">
         </div>
         <div class="inputBox">
            <span>Postal Code:</span>
            <input type="number" min="0" name="pin_code" required placeholder="Enter postal code">
         </div>
      </div>
      <input type="submit" value="Place Order" class="btn" name="order_btn">
   </form>

</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>
function toggleTransferProof() {
   const method = document.getElementById('paymentMethod').value;
   const bankInfo = document.getElementById('bankTransferInfo');
   const transferProofBox = document.getElementById('transferProofBox');
   const transferProof = document.getElementById('transferProof');
   
   if(method === 'Bank Transfer') {
      bankInfo.style.display = 'block';
      transferProofBox.style.display = 'block';
      transferProof.required = true;
   } else {
      bankInfo.style.display = 'none';
      transferProofBox.style.display = 'none';
      transferProof.required = false;
   }
}
</script>

</body>
</html>









