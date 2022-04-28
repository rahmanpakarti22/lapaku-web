<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);

   $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist_numbers->execute([$p_name, $user_id]);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_wishlist_numbers->rowCount() > 0){
      $message[] = 'already added to wishlist!';
   }elseif($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'added to wishlist!';
   }

}

if(isset($_POST['add_to_cart'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$p_name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         $delete_wishlist->execute([$p_name, $user_id]);
      }

      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'added to cart!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="frontend/style.css">

</head>
<body>
   
    <section id="header">
        <a href="#"><img src="frontend/img/LAPAKU.png" class="logo" alt=""></a>
        <div>
            <ul id="navbar">
                <li><a class="active" href="home_user.php">Home</a></li>
                <li><a href="orders.php">Order</a></li>
                <li><a href="wishlist.php">Wishlist</a></li>
                <li><a href="cart.php">Basket</a></li>
                <li><a href="user_profile_update.php">Profile</a></li>
            </ul>
        </div>
    </section>

    <section id="hero">
        <h4>BUDAYAKAN MEMBELI</h4>
        <h2>PRODUK-PRODUK</h2>
        <h1>LOKAL BERKUALITAS</h1>
        <p>DAPATKAN DENGAN HARGA MENARIK</p>
    </section>

    <section id="feature" class="section-p1">
        <dir class="fe-box">
            <img src="frontend/img/delivery-truck.png" alt="">
            <h5>Bebas Ongkir</h5>
        </dir>
        <dir class="fe-box">
            <img src="frontend/img/call-center.png" alt="">
            <h5>Layanan 24 jam</h5>
        </dir>
        <dir class="fe-box">
            <img src="frontend/img/cash-on-delivery.png" alt="">
            <h5>Pembayaran aman</h5>
        </dir>
        <dir class="fe-box">
            <img src="frontend/img/chat.png" alt="">
            <h5>Pelayanan ramah</h5>
        </dir>
    </section>

    <section id="banner" class="section-m1">
        <h4>Khusus Pengguna Baru</h4>
        <h2>Dapatkan Diskon <span>Up To 70%</span> - Untuk Semua Kategori</h2>
    </section>

    <section id="sm-banner" class="section-p1">
        <div class="banner-box">
            <h4>Promo Gokil</h4>
            <h2>Jangan Lewatkan</h2>
        </div>
        <div class="banner-box banner-box2">
            <h4>Ajak Orang Terdekat</h4>
            <h2>Untuk Membeli Produk Lokal Berkualitas</h2>
        </div>
    </section>

    <section class="products">

        <div class="box-container">

            <?php
                $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
                $select_products->execute();
                if($select_products->rowCount() > 0){
                    while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
            ?>
            <form action="" class="box" method="POST">
                <div class="price">$<span><?= $fetch_products['price']; ?></span>/-</div>
                <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
                <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
                <div class="name"><?= $fetch_products['name']; ?></div>
                <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
                <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
                <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
                <input type="number" min="1" value="1" name="p_qty" class="qty">
                <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist">
                <input type="submit" value="add to cart" class="btn" name="add_to_cart">
            </form>
            <?php
                }
            }else{
                echo '<p class="empty">Belum ada produk!</p>';
            }
            ?>

        </div>

    </section>

<script src="js/script.js"></script>

</body>
</html>