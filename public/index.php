<?php

include 'config.php';
error_reporting(0);
session_start();
$user_id = $_SESSION['user_id'];

$sql = 'SELECT COUNT(cart_id) AS idCount FROM cart';
$result = $conn->query($sql);

if (isset($_SESSION['user_id'])) {
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $idCount = $row['idCount'];
  } else {
    $idCount = 0;
  }
}

if (isset($_GET['logout'])) {
  unset($user_id);
  session_destroy();
  header('location:index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curthings</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>

  <!-- Nav -->
  <header class="w-full bg-bgMain-main">
    <nav class="bg-bgMain-main flexBetween relative z-30 py-5">
      <a href="index.php" class="flex">
        <img class="sm:hidden mx-10" src="assets/logo.png" alt="logo" height="40" width="40" />
        <img class="hidden sm:flex mx-10" src="assets/logo-text.png" alt="logo w/text" height="40" width="150" />
      </a>

       <!-- Mobile Menu Button -->
       <button id="mobile-menu-btn" class="lg:hidden mx-8">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
          </svg>
      </button>

      <ul class="flex-row h-full gap-12 hidden lg:flex">
        <a href="index.php" class="underline decoration-green-main font-bold text-green-main">
          Home
        </a>
        <a href="allProducts.php" class="transition ease-in-out duration-500 hover:font-bold hover:text-green-nav">
          Products
        </a>
        <a href="quotation.php" class="relative inline-flex transition ease-in-out duration-500 hover:font-bold hover:text-green-nav">
          Quotation
          <span class="absolute top-0 end-0 inline-flex items-center py-05 px-1.5 rounded-full text-xs font-medium transform -translate-y-1/2 translate-x-1/2 bg-red-500 text-white"><?php echo $idCount; ?></span>
        </a>
        <a href="booking.php" class="transition ease-in-out duration-500 hover:font-bold hover:text-green-nav">
          Booking
        </a>
        <a href="about.php" class="transition ease-in-out duration-500 hover:font-bold hover:text-green-nav">
          About
        </a>
      </ul>

      <!-- Mobile Menu Container -->
      <div id="mobile-menu" class="lg:hidden fixed inset-y-0 right-0 hidden bg-white z-40 w-1/2 -mx-8">
            <ul class="flex flex-col items-center h-full gap-12">

              <div class="mt-5 flex items-center justify-between">
                <a href="index.php" class="flex items-start">
                  <img class="" src="assets/logo-text.png" alt="logo w/text" height="40" width="150" />
                  <?php if (
                    isset($_SESSION['user_id']) &&
                    !empty($_SESSION['user_id'])
                  ) { ?>
                  <a href="profile.php" class="mx-8">
                    <img src="assets/user.png" alt="" width=20 height=20>
                  </a>
                  <?php } ?>
                </a>
              </div>

              <div class="flex flex-col w-full text-center">
                <a href="index.php" class="transition ease-in-out duration-500 text-white hover:font-bold hover:text-green-nav font-bold bg-green-main border-1 border-gray-800 py-4">
                  Home
                </a>
                <a href="allProducts.php" class="transition ease-in-out duration-500 hover:font-bold hover:text-green-nav py-4">
                  Products
                </a>
                <a href="quotation.php" class="transition ease-in-out duration-500 hover:font-bold hover:text-green-nav py-4">
                  Quotation
                </a>
                <a href="booking.php" class="transition ease-in-out duration-500 hover:font-bold hover:text-green-nav py-4">
                  Booking
                </a>
                <a href="about.php" class="transition ease-in-out duration-500 hover:font-bold hover:text-green-nav py-4">
                  About
                </a>
              </div>

              <a href="index.php?logout=<?php echo $user_id; ?>" name="logout" onclick="return confirm('Are your sure you want to logout?');">
                <div class="w-full text-center">
                <?php if (
                  isset($_SESSION['user_id']) &&
                  !empty($_SESSION['user_id'])
                ) { ?>
                  
                    <div class="transition ease-in-out duration-500 bg-gray-800 py-4 text-white hover:cursor-pointer hover:bg-gray-700">
                      Logout
                    </div>
                  </a>
                <?php } else { ?>
                    <a href="login.php">
                      <div class="transition ease-in-out duration-500 bg-gray-800 py-4 text-white hover:cursor-pointer hover:bg-gray-700">Login</div>
                    </a>
                <?php } ?>
              </div>
            </ul>
        </div>

      <?php if (
        isset($_SESSION['user_id']) &&
        !empty($_SESSION['user_id'])
      ) { ?>
            <div class="lg:flex flex-row items-center hidden mx-10">
              <a href="profile.php" class="mx-8">
                <img src="assets/user.png" alt="" width=20 height=20>
              </a>
              <a href="index.php?logout=<?php echo $user_id; ?>" name="logout" onclick="return confirm('Are your sure you want to logout?');">
                <div class="bg-green-nav w-full py-2 px-5 text-white rounded-full hover:bg-gray-800">
                  Logout
                </div>
              </a>
            </div>
      <?php } else { ?>
          <a href="login.php" class="hidden lg:flex mx-10">
            <div class="bg-green-nav w-full py-2 px-5 text-white rounded-full hover:bg-gray-800">Login</div>
          </a>
      <?php } ?>
    </nav>

    <script>
        var mobileMenuBtn = document.getElementById('mobile-menu-btn');
        var mobileMenu = document.getElementById('mobile-menu');

        // Open or close mobile menu when the button is clicked
        mobileMenuBtn.addEventListener('click', function (event) {
            mobileMenu.classList.toggle('hidden');
            event.stopPropagation(); // Stop the click event from propagating to the document
        });

        // Add event listener to close mobile menu when clicking outside of it
        document.addEventListener('click', function (event) {
            if (mobileMenu && !mobileMenu.contains(event.target) && event.target !== mobileMenuBtn) {
                mobileMenu.classList.add('hidden');
            }
        });
    </script>

  </header>


    <!-- Hero Section -->
    <section>
      <!-- Mobile -->
      <div class="lg:hidden max-container flex flex-col gap-20 md:gap-28 xl:flex-row h-screen w-screen">
        <img class="absolute bg-cover bg-center w-full h-full" src="assets/hero-bg.jpg" alt="">
        <div class="relative z-20 flex flex-1 flex-col justify-center text-center">
          <h1 class="font-title text-white bold-52 md:bold-64">
            Making your
            <br />
            House a Home
          </h1>
          <p class="font-subtitle text-[1.3rem] mt-6 mb-12 text-white">
            First in Iligan to cater Customized <br class="md:hidden" />
            Curtain & Blinds
          </p>
          <div class="w-96 mx-auto">
            <a href="quotation.php">
              <button class="btn_dark_green w-full">Get Quotation</button>
            </a>
          </div>
        </div>
      </div>

      <div class="hidden w-full lg:flex flex-row h-screen">
        <!-- Left Side -->
        <div class="flex flex-col justify-center text-center place-items-center w-full bg-bgMain-main">
          <h1 class="font-title text-green-main bold-64 w-[500px]">
            Making your House a Home
          </h1>
          <p class="font-subtitle text-[1.3rem] mt-6 mb-12 text-black-sub">
            First in Iligan to cater Customized <br />
            Curtain & Blinds
          </p>
          <div class="w-96 mx-auto">
            <a href="quotation.php">
              <button class="btn_dark_green w-full">Get Quotation</button>
            </a>
          </div>
        </div>

        <!-- Right Side -->
        <div class="w-full h-full">
          <img class="bg-cover bg-center w-full h-full" src="assets/hero-bg.jpg" alt="">
        </div>
      </div>
    </section>

    <!-- Overview Section -->
    <section class="bg-bgMain-main py-8">
      <div class="flex flex-col md:flex-row w-full">
        <div class="max-container lg:w-full px-6 text-center lg:text-start">
          <h2 class="font-title bold-52 text-green-main">
            Different Options
          </h2>
          <p class="font-subtitle text-green-nav lg:hidden inline-block">
            From different colors, to materials, rail or rod - explore what
            suits your taste.
          </p>

          <div class="flex flex-col lg:flex-row place-items-center gap-8">
            <div class=" w-52 h-[300px] lg:flex hidden mt-8">
              <p class="font-subtitle text-green-nav">
                From different colors, to materials, rail or rod - explore what
                suits your taste.
              </p>
            </div>
            <div class="h-[300px] bg-center overflow-hidden mt-8">
              <img
                src="assets/bg-color-curtain-1.jpg"
                alt="pink-curtain"
                height=200
                width=420
              />
            </div>
            <div class="h-[300px] bg-center overflow-hidden mt-8">
              <img
                src="assets/bg-material-curtain-1.jpg"
                alt="pink-curtain"
                height=200
                width=420
              />
            </div>
            <div class="h-[300px] bg-center overflow-hidden mt-8">
              <img
                src="assets/bg-box-curtain-1.jpg"
                alt="pink-curtain"
                height=200
                width=420
              />
            </div>
          </div>
        </div>
      </div>

      <!-- CARDS -->
      <div class="w-full px-5 py-12">
        <div class="flex flex-col lg:flex-row lg:gap-8 lg:max-container px-6">
          <h3 class="text-green-main bold-40 font-title ">
            We help you achieve elegance in your home.
          </h3>

          <!-- SUBCARD -->
          <div class="mt-8">
            <h4 class="font-subtitle text-brown-main regular-24">
              Conception Service
            </h4>
            <p class="font-subtitle text-black-sub">
              Personalized service with an expert advisor for your interior and
              exterior decoration or layout projects.
            </p>
          </div>

          <div class="mt-8">
            <h4 class="font-subtitle text-brown-main regular-24">
              Installation Service
            </h4>
            <p class="font-subtitle text-black-sub">
              Our furniture packaged in flat packs are designed for easy
              assembly. But you can call on our partner.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Recent Projects Section -->
    <section class="bg-white py-8">
        <div class="max-container px-5 py-12">
            <div class=" flex flex-col px-6">
            <h2 class="text-green-main font-title bold-40">
                Recent Projects
            </h2>
            <p class="font-subtitle text-black-sub regular-18 mt-2">
                Browse through our recent projects
            </p>
            </div>
        </div>  

        <!-- Slider -->
        <?php
        $query =
          "SELECT recent_projects_id, recent_proj_img FROM recent_projects";
        $result = mysqli_query($conn, $query);
        ?>
        <div data-hs-carousel='{
            "loadingClasses": "opacity-0",
            "isAutoPlay": false
          }' class="relative max-container">
          <div class="hs-carousel relative overflow-hidden min-h-[350px] h-max md:h-[80vh] lg:h-[80vh] bg-white rounded-lg">
            <div class="hs-carousel-body absolute top-0 bottom-0 start-0 flex flex-nowrap transition-transform duration-700 opacity-0">
              <?php if (mysqli_num_rows($result) > 0) {
                while ($data = mysqli_fetch_assoc($result)) { ?>
                  <div class="hs-carousel-slide">
                    <div class="flex justify-center h-full bg-gray-100 p-6 ">
                      <span class="self-center transition duration-700">
                        <img src="recent_projects_img/<?php echo $data[
                          'recent_proj_img'
                        ]; ?>" alt="">
                      </span>
                    </div>
                  </div>
              <?php }
              } ?>
            </div>
          </div>

          <button type="button" class="hs-carousel-prev hs-carousel:disabled:opacity-50 disabled:pointer-events-none absolute inset-y-0 start-0 inline-flex justify-center items-center w-[46px] h-full text-gray-800 hover:bg-gray-800/[.1]">
            <span class="text-2xl" aria-hidden="true">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
              </svg>
            </span>
            <span class="sr-only">Previous</span>
          </button>
          <button type="button" class="hs-carousel-next hs-carousel:disabled:opacity-50 disabled:pointer-events-none absolute inset-y-0 end-0 inline-flex justify-center items-center w-[46px] h-full text-gray-800 hover:bg-gray-800/[.1]">
            <span class="sr-only">Next</span>
            <span class="text-2xl" aria-hidden="true">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
              </svg>
            </span>
          </button>
            
          <!-- PAGINATION -->
          <!-- <?php
          $query = "SELECT recent_projects_id FROM recent_projects";
          $result = mysqli_query($conn, $query);
          ?>
          <?php if (mysqli_num_rows($result) > 0) {
            while ($data = mysqli_fetch_assoc($result)) { ?>
              <div class="hs-carousel-pagination flex justify-center absolute bottom-3 start-0 end-0 space-x-2">
                <span class="hs-carousel-active:bg-green-main hs-carousel-active:border-green-main w-3 h-3 border border-gray-400 rounded-full cursor-pointer"></span>
              </div>
              <?php }
          } ?> -->
          
        </div>
        <!-- End Slider -->
    </section>
    
    <!-- Footer -->
    <footer class="bg-bgMain-main">
      <div class="w-full p-8 mt-7">
        <div class="max-container flex flex-col md:flex-row md:justify-evenly">
          <!-- /* LOGO */ -->
          <div class="flex justify-center">
            <a href="/">
              <h4 class="font-title bold-32 text-[#534B42]">Curthings</h4>
            </a>
          </div>
          <!-- /* Products */ -->
          <div class="my-5">
            <h4 class="font-subtitle bold-18 ">Products</h4>
            <ul class="font-subtitle leading-8 text-brown-link">
              <a href="allProducts.php"><li>Curtains</li></a>
              <a href="allProducts.php"><li>Blinds</li></a>
              <a href="allProducts.php"><li>Drapes</li></a>
            </ul>
          </div>
          <!-- /* Services */ -->
          <div class="my-5">
            <h4 class="font-subtitle bold-18 ">Quotation</h4>
            <ul class="font-subtitle leading-8 text-brown-link">
              <a href="quotation.php">
                <li>Request Quotation</li>
              </a>
            </ul>
          </div>
          <!-- /* Booking */ -->
          <div class="my-5">
            <h4 class="font-subtitle bold-18 ">Booking</h4>
            <ul class="font-subtitle leading-8 text-brown-link">
              <a href="quotation.php">
                <li>Book now</li>
              </a>
            </ul>
          </div>
          <!-- /* About */ -->
          <div class="my-5">
            <h4 class="font-subtitle bold-18 ">About</h4>
            <ul class="font-subtitle leading-8 text-brown-link">
              <a href="about.php">
                <li>About Curthings</li>
              </a>
            </ul>
          </div>
        </div>

        <!-- /* Socials */ -->
        <div class="flex flex-row justify-center gap-5 my-5">
          <a href="https://www.facebook.com/curthings" target="_blank">
            <Image src="assets/facebook-logo.svg" alt="fb" width=40 height=40 />
        </a>
          <a href="https://www.instagram.com/curthings" target="_blank">
            <Image
              src="assets/instagram-logo.svg"
              alt="insta"
              width=40
              height=40
            />
        </a>
        </div>
        <!-- /* COPYRIGHT */ -->
        <div class="flex justify-center mt-12 pb-5">
          <span class="font-subtitle text-brown-link opacity-50">
            &copy; 2020 Curthings
          </span>
        </div>
      </div>
    </footer>

    <script src="../node_modules/preline/dist/preline.js"></script>
</body>
</html>
