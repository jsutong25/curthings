<?php

include 'config.php';
error_reporting(0);
session_start();
$id = $_GET['view'];
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

$errors = [];

if (isset($_POST['change-password'])) {
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
  if ($password !== $cpassword) {
    $errors['password'] = "Confirm password not matched!";
  } else {
    $update_pass = "UPDATE user_info SET password = '$password', cpass = '$cpassword' WHERE user_id = '$user_id'";
    $run_query = mysqli_query($conn, $update_pass);
    if ($run_query) {
      echo "<script>alert('Password changed successfully.');window.location.href='profile.php';</script>";
    } else {
      echo "<script>alert('Error. Try again.');window.location.href='profile.php';</script>";
    }
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

        <main>
          <div class=" flex flex-row max-container h-[50vh]">
            
            <!-- LEFT SIDE -->
            <div class="flex flex-col w-fit">
                <ul class="p-5">
                    <a href="profile.php" class="font-subtitle regular-18"><li class="my-2">Profile</li></a>
                    <li class="my-2"><div class="bg-green-main w-full h-0.5"></div></li>
                    <a href="profile_bookingHistory.php" class="font-subtitle regular-18 underline bold-18"><li class="my-2">Booking History</li></a>
                </ul>
            </div>

            <div class="h-fit w-3 mr-5 ml-3 mt-8">
                <div class="h-full w-1/2 bg-green-main"></div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="mt-5 px-8 py-5 border-2 border-green-nav w-fit mx-auto">
                <div class="mb-5">
                <a href="profile_bookingHistory.php" class="text-green-nav flex flex-row font-subtitle bold-18 hover:text-gray-800 hover:underline">
                    <svg class="w-[1.75rem] h-[1.75rem]" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 512 512"><path d="M352 128.4 319.7 96 160 256l159.7 160 32.3-32.4L224.7 256z" fill="#3f532c" class="fill-000000"></path></svg>
                    Back
                </a>
                </div>
                <h3 class="bold-32 font-subtitle mb-5">Booking ID '<?php echo $id; ?>'</h3>
                <?php
                $query = "SELECT * FROM bookings WHERE booking_id = '$id'";
                $result = mysqli_query($conn, $query);
                ?>
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 font-subtitle mr-5">
                <thead class="text-xs text-white uppercase bg-green-nav">
                    <tr>
                    <th scope="col" class="px-6 py-3">Full Name</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Contact Number</th>
                    <th scope="col" class="px-6 py-3">Address</th>
                    <th scope="col" class="px-6 py-3">Quotation #</th>
                    <th scope="col" class="px-6 py-3">Downpayment</th>
                    </tr>
                </thead>
                <?php if (mysqli_num_rows($result) > 0) {
                  $sn = 1;
                  while ($data = mysqli_fetch_assoc($result)) { ?>
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td scope="col" class="px-6 py-3"><?php echo $data[
                          'full_name'
                        ]; ?> </td>
                        <td scope="col" class="px-6 py-3"><?php echo $data[
                          'email'
                        ]; ?> </td>
                        <td scope="col" class="px-6 py-3"><?php echo $data[
                          'contact_number'
                        ]; ?> </td>
                        <td scope="col" class="px-6 py-3"><?php echo $data[
                          'address'
                        ]; ?> </td>
                        <td scope="col" class="px-6 py-3"><?php echo $data[
                          'quotation_number'
                        ]; ?></td>
                        <td scope="col" class="px-6 py-3"><?php echo $data[
                          'downpayment_status'
                        ] .
                          '-' .
                          $data['downpayment_method']; ?> </td>
                    <tr>
                <?php $sn++;}
                } else {
                   ?>
                    <tr>
                        <td colspan="8">No data found</td>
                    </tr>
                <?php
                } ?>
                </table>
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
