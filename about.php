<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      /* ── About Hero ── */
      .about-hero {
         max-width: 1200px;
         margin: 0 auto 5rem;
         padding: 4rem 2rem 0;
         display: grid;
         grid-template-columns: 1fr 1fr;
         gap: 4rem;
         align-items: center;
      }

      .about-hero-text .eyebrow {
         display: inline-block;
         background: rgba(54, 91, 91, 0.1);
         color: var(--primary);
         font-size: 0.8rem;
         font-weight: 700;
         letter-spacing: 0.12em;
         text-transform: uppercase;
         padding: 0.4rem 1rem;
         border-radius: 999px;
         margin-bottom: 1.5rem;
      }

      .about-hero-text h1 {
         font-family: 'Playfair Display', serif;
         font-size: clamp(2.4rem, 4vw, 3.6rem);
         color: var(--text);
         line-height: 1.15;
         margin-bottom: 1.5rem;
      }

      .about-hero-text h1 em {
         font-style: italic;
         color: var(--accent);
      }

      .about-hero-text p {
         font-size: 1.05rem;
         color: var(--text-muted);
         line-height: 1.8;
         margin-bottom: 2rem;
         max-width: 480px;
      }

      .about-hero-visual {
         position: relative;
      }

      .about-hero-visual .main-img {
         width: 100%;
         height: 420px;
         object-fit: cover;
         border-radius: 32px;
         box-shadow: var(--shadow-lg);
         display: block;
      }

      .about-hero-visual .badge {
         position: absolute;
         background: white;
         border-radius: 20px;
         padding: 0.85rem 1.2rem;
         box-shadow: var(--shadow-md);
         display: flex;
         align-items: center;
         gap: 0.7rem;
         font-weight: 600;
         font-size: 0.85rem;
         color: var(--text);
      }

      .about-hero-visual .badge i {
         font-size: 1.2rem;
         color: var(--accent);
      }

      .badge-1 {
         bottom: 2rem;
         left: -1.5rem;
         animation: float 4s ease-in-out infinite;
      }

      .badge-2 {
         top: 2rem;
         right: -1.5rem;
         animation: float 4s ease-in-out infinite;
         animation-delay: 1.2s;
      }

      /* ── Values strip ── */
      .values-strip {
         background: var(--primary);
         padding: 3rem 2rem;
         margin-bottom: 5rem;
      }

      .values-strip-inner {
         max-width: 1100px;
         margin: 0 auto;
         display: grid;
         grid-template-columns: repeat(3, 1fr);
         gap: 2.5rem;
         text-align: center;
      }

      .value-item i {
         font-size: 1.8rem;
         color: var(--accent);
         margin-bottom: 0.8rem;
         display: block;
      }

      .value-item h4 {
         font-family: 'Playfair Display', serif;
         font-size: 1.15rem;
         color: white;
         margin-bottom: 0.5rem;
      }

      .value-item p {
         font-size: 0.88rem;
         color: rgba(255,255,255,0.65);
         line-height: 1.6;
      }

      /* ── Reviews ── */
      .reviews {
         max-width: 1200px;
         margin: 0 auto 5rem;
         padding: 0 1.5rem;
      }

      .reviews .box-container {
         display: grid;
         grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
         gap: 1.5rem;
      }

      .reviews .box {
         background: var(--surface);
         border-radius: 24px;
         padding: 1.8rem;
         box-shadow: var(--shadow-sm);
         border: 1px solid rgba(209,166,90,0.12);
         transition: transform 0.25s ease, box-shadow 0.25s ease;
         display: flex;
         flex-direction: column;
         gap: 1rem;
      }

      .reviews .box:hover {
         transform: translateY(-5px);
         box-shadow: var(--shadow-md);
      }

      .reviewer-info {
         display: flex;
         align-items: center;
         gap: 1rem;
      }

      .reviewer-info img {
         width: 52px;
         height: 52px;
         border-radius: 50%;
         object-fit: cover;
         border: 2px solid var(--accent);
      }

      .reviewer-info h3 {
         font-size: 1rem;
         color: var(--text);
         margin-bottom: 0.2rem;
      }

      .stars {
         color: var(--accent);
         font-size: 0.82rem;
         display: flex;
         gap: 2px;
      }

      .reviews .box p {
         font-size: 0.95rem;
         color: var(--text-muted);
         line-height: 1.65;
         padding-top: 0.5rem;
         border-top: 1px solid rgba(0,0,0,0.05);
      }

      /* ── Authors/Helper ── */
      .authors {
         background: var(--surface-soft);
         padding: 4rem 2rem;
         margin-bottom: 0;
      }

      .authors-inner {
         max-width: 900px;
         margin: 0 auto;
      }

      .authors .box-container {
         display: flex;
         justify-content: center;
         gap: 2.5rem;
         flex-wrap: wrap;
      }

      .authors .box {
         background: white;
         border-radius: 28px;
         padding: 2rem 1.5rem;
         text-align: center;
         width: 220px;
         box-shadow: var(--shadow-sm);
         transition: transform 0.25s, box-shadow 0.25s;
      }

      .authors .box:hover {
         transform: translateY(-6px);
         box-shadow: var(--shadow-md);
      }

      .authors .box img {
         width: 72px;
         height: 72px;
         object-fit: contain;
         border-radius: 18px;
         margin: 0 auto 1rem;
         display: block;
         background: var(--bg);
         padding: 0.5rem;
      }

      .authors .box h3 {
         font-size: 1rem;
         color: var(--text);
         margin-bottom: 0.8rem;
      }

      .authors .box .share {
         display: flex;
         justify-content: center;
         gap: 0.6rem;
         margin-bottom: 0.8rem;
      }

      .authors .box .share a {
         width: 32px;
         height: 32px;
         border-radius: 50%;
         background: var(--bg);
         color: var(--primary);
         display: flex;
         align-items: center;
         justify-content: center;
         font-size: 0.8rem;
         text-decoration: none;
         transition: background 0.2s, color 0.2s;
      }

      .authors .box .share a:hover {
         background: var(--primary);
         color: white;
      }

      /* ── Responsive ── */
      @media (max-width: 768px) {
         .about-hero {
            grid-template-columns: 1fr;
            gap: 2rem;
            padding: 2rem 1.5rem 0;
         }
         .about-hero-visual .badge { display: none; }
         .values-strip-inner {
            grid-template-columns: 1fr;
            gap: 1.5rem;
         }
      }
   </style>

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>about us</h3>
   <p><a href="home.php">home</a> / about</p>
</div>

<!-- ── About Hero ── -->
<section class="about-hero">

   <div class="about-hero-text">
      <span class="eyebrow"><i class="fas fa-book-open" style="margin-right:6px;"></i>Our Story</span>
      <h1>Kenapa memilih <em>e-commerce buku</em>?</h1>
      <p>
         Selain tutorialnya cukup banyak di YouTube, kami memilih projek ini karena tidak terlalu sulit untuk dibuat
         dan juga sudah pernah membuatnya di tugas sebelumnya — walau tidak sekompleks yang sekarang.
         Kami memilih projek yang mudah juga agar bisa membagi waktu untuk belajar. Dan jangan lupa beri masukan
      </p>
      <a href="contact.php" class="btn"><i class="fas fa-envelope"></i> Hubungi Kami</a>
   </div>

   <div class="about-hero-visual">
      <img src="images/booksmage.jpg" alt="Books" class="main-img">
      <div class="badge badge-1">
         <i class="fas fa-star"></i>
         <span>Proyek Aseli</span>
      </div>
      <div class="badge badge-2">
         <i class="fas fa-code"></i>
         <span>Built with PHP</span>
      </div>
   </div>

</section>

<!-- ── Values Strip ── -->
<div class="values-strip">
   <div class="values-strip-inner">
      <div class="value-item">
         <i class="fas fa-book"></i>
         <h4>Koleksi Lengkap</h4>
         <p>Dari fiksi hingga non-fiksi, semua tersedia di satu tempat.</p>
      </div>
      <div class="value-item">
         <i class="fas fa-shield-alt"></i>
         <h4>Transaksi Aman</h4>
         <p>Sistem pembayaran yang sederhana dan terpercaya.</p>
      </div>
      <div class="value-item">
         <i class="fas fa-heart"></i>
         <h4>Dibuat dengan laptop</h4>
         <p>Projek orang yang penuh semangat kodim.</p>
      </div>
   </div>
</div>

<!-- ── Reviews ── -->
<section class="reviews">

   <h1 class="title">Apa Kata Mereka</h1>

   <div class="box-container">

      <div class="box">
         <div class="reviewer-info">
            <img src="images/nima.jpg" alt="">
            <div>
               <h3>Numar</h3>
               <div class="stars">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                  <i class="fas fa-star"></i><i class="fas fa-star"></i>
               </div>
            </div>
         </div>
         <p>dung cak cak cak dung cak cak</p>
      </div>

      <div class="box">
         <div class="reviewer-info">
            <img src="images/dodo.jpeg" alt="">
            <div>
               <h3>Dodo</h3>
               <div class="stars">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                  <i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
         <p>SIUUUUUUUUUUU!</p>
      </div>

      <div class="box">
         <div class="reviewer-info">
            <img src="images/spiyt.png" alt="">
            <div>
               <h3>ElKecepatan</h3>
               <div class="stars">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                  <i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
         <p>Ayways! Aways!</p>
      </div>

      <div class="box">
         <div class="reviewer-info">
            <img src="images/fatrik.jpg" alt="">
            <div>
               <h3>Fatriko</h3>
               <div class="stars">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                  <i class="fas fa-star"></i><i class="fas fa-star"></i>
               </div>
            </div>
         </div>
         <p>uhhhhh...</p>
      </div>

      <div class="box">
         <div class="reviewer-info">
            <img src="images/i-can.png" alt="">
            <div>
               <h3>Fih</h3>
               <div class="stars">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                  <i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
         <p>blup blup..</p>
      </div>

      <div class="box">
         <div class="reviewer-info">
            <img src="images/dexter meme.jpeg" alt="">
            <div>
               <h3>D</h3>
               <div class="stars">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                  <i class="fas fa-star"></i><i class="fas fa-star"></i>
               </div>
            </div>
         </div>
         <p>This is the best web in world but i cant prove it.</p>
      </div>

   </div>

</section>

<!-- ── Helper/Authors ── -->
<section class="authors">
   <div class="authors-inner">

      <h1 class="title">Dibantu Oleh</h1>

      <div class="box-container">

         <div class="box">
            <img src="images/tang.png" alt="Claude">
            <h3>Tangan</h3>
            <div class="share">
               <a href="#" class="fab fa-facebook-f"></a>
               <a href="#" class="fa-brands fa-x-twitter"></a>
               <a href="#" class="fab fa-instagram"></a>
               <a href="#" class="fab fa-linkedin"></a>
            </div>
         </div>

         <div class="box">
            <img src="images/otaq.jpg" alt="GPT">
            <h3>Otaku</h3>
            <div class="share">
               <a href="#" class="fab fa-facebook-f"></a>
               <a href="#" class="fa-brands fa-x-twitter"></a>
               <a href="#" class="fab fa-instagram"></a>
               <a href="#" class="fab fa-linkedin"></a>
            </div>
         </div>

         <div class="box">
            <img src="images/Youtube logo 2.png" alt="Youtube">
            <h3>YouTube</h3>
            <div class="share">
               <a href="#" class="fab fa-facebook-f"></a>
               <a href="#" class="fa-brands fa-x-twitter"></a>
               <a href="#" class="fab fa-instagram"></a>
               <a href="#" class="fab fa-linkedin"></a>
            </div>
         </div>

      </div>

   </div>
</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>