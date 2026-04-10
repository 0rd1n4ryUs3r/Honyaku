<?php

$conn = mysqli_connect('localhost','root','','shop_db') or die('connection failed');

// Pastikan tabel kategori tersedia dan produk memiliki kolom category_id
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4") or die('query failed');

$check_category_column = mysqli_query($conn, "SHOW COLUMNS FROM `products` LIKE 'category_id'") or die('query failed');
if(mysqli_num_rows($check_category_column) == 0){
   mysqli_query($conn, "ALTER TABLE `products` ADD COLUMN `category_id` int NOT NULL DEFAULT 0") or die('query failed');
}

// Fungsi untuk prepared statements (anti SQL injection)
function query($conn, $sql, $params = []) {
    $stmt = mysqli_prepare($conn, $sql);
    if($params) {
        $types = str_repeat('s', count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

?>