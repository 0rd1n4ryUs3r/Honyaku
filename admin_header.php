<?php
$messages = [];
if(isset($message) && is_array($message)){
   $messages = array_merge($messages, $message);
}
if(isset($_SESSION['message']) && is_array($_SESSION['message'])){
   $messages = array_merge($messages, $_SESSION['message']);
   unset($_SESSION['message']);
}
foreach($messages as $msg){
   echo '
   <div class="message">
      <span>'.$msg.'</span>
      <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
   </div>
   ';
}
?>

<header class="header">

   <div class="flex">

      <a href="admin_page.php" class="logo">Honyaku<span>Panel</span></a>

      <nav class="navbar">
         <a href="admin_page.php">HOME</a>
         <a href="admin_products.php">PRODUCTS</a>
         <a href="admin_categories.php">CATEGORIES</a>
         <a href="admin_orders.php">ORDERS</a>
         <a href="admin_users.php">USERS</a>
         <a href="admin_contacts.php">MESSAGES</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars" style="display:none; margin-right: 1rem;"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="account-box">
         <p><i class="fas fa-user-circle"></i> <span><?php echo $_SESSION['admin_name']; ?></span></p>
         <p><i class="fas fa-envelope"></i> <span><?php echo $_SESSION['admin_email']; ?></span></p>
         <div class="divider"></div>
         <a href="logout.php" class="delete-btn">logout</a>
      </div>

   </div>

</header>