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
          <a href="index.php" class="inline-flex transition ease-in-out duration-500 hover:font-bold hover:text-green-nav">
            Home
          </a>
          <a href="allProducts.php" class="inline-flex transition ease-in-out duration-500 hover:font-bold hover:text-green-nav">
            Products
          </a>
          <a href="quotation.php" class="relative inline-flex transition ease-in-out duration-500 hover:font-bold hover:text-green-nav">
            Quotation
            <span class="absolute top-0 end-0 inline-flex items-center py-05 px-1.5 rounded-full text-xs font-medium transform -translate-y-1/2 translate-x-1/2 bg-red-500 text-white"><?php echo $idCount; ?></span>
          </a>
          <a href="booking.php" class="inline-flex transition ease-in-out duration-500 hover:font-bold hover:text-green-nav">
            Booking
          </a>
          <a href="about.php" class="underline decoration-green-main font-bold text-green-main">
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
                  <a href="about.php" class="transition ease-in-out duration-500 text-white hover:font-bold hover:text-green-nav font-bold bg-green-main border-1 border-gray-800 py-4">
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

    <main>
      <div class="max-container mt-5">
        <h3 class="font-subtitle bold-32 mb-2 mx-6 lg:mx-0">About Us</h3>
        <div class="w-full h-1 bg-green-main rounded-full mb-5"></div>
      </div>
      

      <div class="space-y-2">

      <!-- About -->
        <div class="max-container px-8">
          <h4 class="font-subtitle bold-26 text-green-main">About Curthings</h4>
          <p class="font-subtitle regular-20">At Curthings, we take pride in being the pioneers in Iligan City, dedicated to providing bespoke curtains and blinds that elevate the aesthetic of your home. Our commitment to excellence and passion for design sets us apart as the premier destination for those seeking to redefine their living spaces.</p>
        </div>

        <br />
        
        <!-- Our Vision -->
        <div class="max-container px-8">
          <h4 class="font-subtitle bold-26 text-green-main">Our Vision</h4>
          <p class="font-subtitle regular-20">Curthings is not just a brand; it's a vision. We envision homes adorned with customized curtains, blinds, and drapes that transcend the ordinary, adding an element of sophistication and charm. Our mission is to be the catalyst in transforming your living spaces into elegant havens.</p>
        </div>

        <br />

        <div class="max-container px-8">
          <h4 class="font-subtitle bold-26 text-green-main">Unparalleled Customization</h4>
          <p class="font-subtitle regular-20">Tailored to perfection. Our forte lies in crafting customized curtains, blinds, and drapes that align seamlessly with your unique style and preferences. Whether you prefer timeless classics or contemporary trends, our expert team ensures that every detail reflects your taste, making your home a true reflection of your personality.
          </p>
        </div>

        <br />

        <div class="max-container px-8">
          <h4 class="font-subtitle bold-26 text-green-main">Elegance Redefined</h4>
          <p class="font-subtitle regular-20">More than just window coverings. Curthings is a symbol of elegance and refinement. We believe that a well-dressed window is the key to enhancing the overall ambiance of any room. With an extensive range of fabrics, patterns, and styles, we empower you to create a home that exudes timeless elegance.</p>
        </div>

        <br />
        
        <div class="max-container px-8">
          <h4 class="font-subtitle bold-26 text-green-main">Craftsmanship and Quality</h4>
          <p class="font-subtitle regular-20">Excellence in every stitch. Our commitment to quality craftsmanship is unwavering. Each curtain, blind, or drape is meticulously crafted using premium materials to ensure longevity and enduring beauty. We stand behind the quality of our products, allowing you to enjoy a touch of luxury that lasts.</p>
        </div>

        <br />
        
        <div class="max-container px-8">
          <h4 class="font-subtitle bold-26 text-green-main">Personalized Service</h4>
          <p class="font-subtitle regular-20">Your vision, our priority. At Curthings, we understand that each home is unique. Our dedicated team is here to guide you through the customization process, offering personalized advice to help you make informed decisions. Your satisfaction is our ultimate goal.</p>
        </div>

        <br />

        <div class="max-container px-8">
          <h4 class="font-subtitle bold-26 text-green-main">Connect with Curthings</h4>
          <p class="font-subtitle regular-20">Experience the transformative power of customized window coverings with Curthings. Follow us on <span class="underline text-green-main"><a href="https://www.facebook.com/curthings" target="_blank">Facebook</a></span>, and <span class="underline text-green-main"><a href="https://www.instagram.com/curthings" target="_blank">Instagram</a></span> to stay inspired by the latest trends and exclusive offerings. We invite you to embark on a journey with us to redefine the elegance of your home.

          <br><br><span class="bold-18">Thank you for choosing Curthings—where elegance meets customization!</span></p>
        </div>

        <div class="flex justify-center">
          <img src="assets/logo.png" alt="logo" class="w-20 mt-7">
        </div>

      </div>
    </main>

    
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
