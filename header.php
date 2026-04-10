<?php
if(!isset($user_id)) {
   $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
}

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">
   <div class="header-1">
      <div class="flex">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <?php if(isset($_SESSION['user_id'])): ?>
         <p>Welcome, <?php echo $_SESSION['user_name']; ?> | <a href="logout.php">logout</a></p>
         <?php else: ?>
         <p> new <a href="login.php">login</a> | <a href="register.php">register</a> </p>
         <?php endif; ?>
      </div>
   </div>

   <div class="header-2">
      <div class="flex">
         <a href="home.php" class="logo">Honya<span>Ku.</span></a>

         <nav class="navbar">
            <a href="home.php">HOME</a>
            <a href="about.php">ABOUT</a>
            <a href="shop.php">SHOP</a>
            <a href="contact.php">CONTACT</a>
            <a href="orders.php">ORDERS</a>
         </nav>

         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
            <?php
               $cart_rows_number = 0;
               if($user_id > 0) {
                  $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'");
                  $cart_rows_number = mysqli_num_rows($select_cart_number);
               }
            ?>
            <a href="cart.php"> <i class="fas fa-shopping-cart"></i> <span>(<?php echo $cart_rows_number; ?>)</span> </a>
         </div>

         <div class="user-box">
            <p>username : <span><?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest'; ?></span></p>
            <p>email : <span><?php echo isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '-'; ?></span></p>
            <a href="logout.php" class="delete-btn">logout</a>
         </div>
      </div>
   </div>
</header>