<?php

include 'config.php';
session_start();
error_reporting(0);

$email = "";
$name = "";
$errors = [];

$email = $_SESSION['email'];
if ($email == false) {
  header('Location: login.php');
}

if (isset($_POST['check-reset-otp'])) {
  $_SESSION['info'] = "";
  $otp_code = mysqli_real_escape_string($conn, $_POST['otp']);
  $check_code = "SELECT * FROM user_info WHERE code = $otp_code";
  $code_res = mysqli_query($conn, $check_code);
  if (mysqli_num_rows($code_res) > 0) {
    $fetch_data = mysqli_fetch_assoc($code_res);
    $email = $fetch_data['email'];
    $_SESSION['email'] = $email;
    header('location: new-password.php');
    exit();
  } else {
    $errors['otp-error'] = "You've entered incorrect code!";
  }
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
        <a href="index.php" class="transition ease-in-out duration-500 hover:font-bold hover:text-green-nav">
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
      <div id="mobile-menu" class="lg:hidden fixed inset-y-0 right-0 left-auto hidden bg-white z-40 w-1/2 -mx-8">
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
                <a href="index.php" class="transition ease-in-out duration-500 hover:font-bold hover:text-green-nav py-4">
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
  
  <main class="overflow-x-hidden">

    <div class="mt-5"> 
      <div class="w-screen h-[50vh]">

        <div class="w-3/4 flex flex-col items-center justify-center mx-auto my-auto">
          <div class="w-full flex justify-center content-center text-green-nav rounded-[20px]">
            <h3 class="bold-40 font-subtitle mb-2 pb-5">Code Verification</h3>
          </div>
          
          <form action="" method="post" class="mt-5">
            <?php if (isset($_SESSION['info'])) { ?>
            <div class="alert alert-success text-center" style="padding: 0.4rem 0.4rem">
                <?php echo $_SESSION['info']; ?>
            </div>
            <?php } ?>
            <?php if (count($errors) > 0) { ?>
                <div class="text-center">
                    <?php foreach ($errors as $showerror) {
                      echo $showerror;
                    } ?>
                </div>
            <?php } ?>
            
            <label for="otp" class="font-subtitle regular-18">Enter code:</label>
            <input type="text" name="otp" required placeholder="Enter code sent to your email" class="border-0 border-l-4 border-green-main py-2 mb-5 bg-gray-100 rounded-none">
            
            <input type="submit" name="check-reset-otp" class="btn_dark_green font-subtitle mt-5" value="Login">
          </form>
          
        </div>
      </div>
    </div>
  </main>

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
</body>
</html>
