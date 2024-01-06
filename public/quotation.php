<?php

include 'config.php';
error_reporting(0);
session_start();

$user_id = $_SESSION['user_id'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendMail($email, $quotation_number)
{
  require "PHPMailer/PHPMailer.php";
  require "PHPMailer/SMTP.php";
  require "PHPMailer/Exception.php";

  $mail = new PHPMailer(true);

  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'jtongshs@gmail.com';
    $mail->Password = 'unomtomhhoiviogq';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('jtongshs@gmail.com', 'Curthings');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Quotation Request - Curthings';
    $mail->Body = "<h2 class='text-lg'>Thank you for requesting a quotation. Your quotation will be sent to you within 24 hours after you have submitted your request here in your email. <br><br> This is your quotation number, please remember this: Quotation No. #$quotation_number <br><br>For more information, please follow our social media accounts: <br><br>Facebook: <a href='https://www.facebook.com/curthings' class='underline text-green-nav bold-18'>Curthings</a><br>Instagram: <a href='https://www.instagram.com/curthings' class='underline text-green-nav bold-18'>Curthings</a><br><br>Thank you for choosing Curthings!<h2>";

    $mail->send();
    return true;
  } catch (Exception $e) {
    return false;
  }
}

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

if (isset($_POST['update'])) {
  foreach ($_POST['update'] as $cartId => $update) {
    $new_quantity = $_POST['quantity'][$cartId];
    $cart_id = $cartId;

    // Update the quantity in the cart table
    $update_query = "UPDATE cart SET quantity ='$new_quantity' WHERE cart_id = '$cart_id'";
    $update_result = mysqli_query($conn, $update_query);

    if (!$update_result) {
      // Handle the error if the update fails
      echo 'Error in executing update query: ' . mysqli_error($conn);
    }
  }

  // Redirect to the quotation page after successful update
  header('location:quotation.php');
  exit();
}

if (isset($_GET['remove'])) {
  $remove_id = $_GET['remove'];
  mysqli_query($conn, "DELETE FROM `cart` WHERE cart_id = '$remove_id'") or
    die('query failed');
  header('location:quotation.php');
}

if (isset($_SESSION['user_id'])) {
  if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $quotation_number = rand(10000, 99999);

    $query = "SELECT * FROM cart WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
      // Initialize an array to store all cart items
      $cartItems = [];

      while ($data = mysqli_fetch_assoc($result)) {
        $cartItems[] = $data;
      }

      $currentDate = date('d-m-y h:i:s');
      if ($name == "" || $email == "" || $contact_number == "") {
        echo "<script>alert('All fields are mandatory.');window.location.href='quotation.php';</script>";
        header('Location:quotation.php');
        exit(0);
      }

      $query = "INSERT INTO quotation(quotation_number, name, email, contact_number, quotation_status, date_created) VALUES ('$quotation_number','$name', '$email','$contact_number', '0', '$currentDate')";
      $insertQueryResult = mysqli_query($conn, $query);

      if ($insertQueryResult && sendMail($email, $quotation_number)) {
        $quotation_id = mysqli_insert_id($conn);

        foreach ($cartItems as $item) {
          $product_name = $item["product_name"];
          $product_color = $item["product_color"];
          $product_variation = $item["product_variation"];
          $quantity = $item["quantity"];
          $width = $item["width"];
          $height = $item["height"];

          $insertItemsQuery = "INSERT INTO quotation_items (quotation_id, product_name, product_color, product_variation, quantity, window_width, window_height) VALUES ('$quotation_id', '$product_name', '$product_color', '$product_variation', '$quantity', '$width', '$height')";

          $insertItemsQueryResult = mysqli_query($conn, $insertItemsQuery);

          if (!$insertItemsQueryResult) {
            echo 'Error in executing query: ' . mysqli_error($conn);
          }
        }

        // Clear the cart after successful insertion
        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or
          die('query failed');

        echo "<script>alert('Requested quotation successfully. Please check your email.');window.location.href='index.php';</script>";
        die();
      } else {
        echo 'Error in executing query: ' . mysqli_error($conn);
      }
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
  
    <script>
      function validateQuantity(input) {
        var minQuantity = 1;
        var enteredQuantity = parseInt(input.value, 10);

        // Check if the entered quantity is less than 1
        if (isNaN(enteredQuantity) || enteredQuantity < minQuantity) {
          // If so, set the quantity to the minimum allowed value (1)
          input.value = minQuantity;
        }
      }
    </script>

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
          <a href="quotation.php" class="relative inline-flex underline decoration-green-main font-bold text-green-main">
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
                  <a href="quotation.php" class="transition ease-in-out duration-500 text-white hover:font-bold hover:text-green-nav font-bold bg-green-main border-1 border-gray-800 py-4">
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
      <div class="max-container mt-5">

        <h3 class="font-subtitle bold-32 mb-2 mx-6 lg:mx-0">Quotation</h3>
        <div class="w-full h-1 bg-green-main rounded-full mb-5"></div>
      <!-- TOP -->
        <div class="flex justify-center mt-5 p-5">
        <h2 class="font-subtitle bold-32 text-center">Your Quotation - (<?php echo $idCount; ?>) Items</h2>
      </div>

      <!-- TABLE -->
      <div class="mt-8 max-container overflow-x-auto">
        <?php
        $query = "SELECT * FROM cart WHERE user_id = '$user_id'";
        $result = mysqli_query($conn, $query);
        ?>
        <form action="" method="POST">
          <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 font-subtitle mr-5 overflow-x-auto">
            <thead class="text-xs text-white uppercase bg-green-nav">
              <tr>
                <th scope="col" class="px-6 py-3">Item</th>
                <th scope="col" class="px-6 py-3">Color</th>
                <th scope="col" class="px-6 py-3">Variation</th>
                <th scope="col" class="px-6 py-3">Quantity</th>
                <th scope="col" class="px-6 py-3">Width</th>
                <th scope="col" class="px-6 py-3">Height</th>
                <th scope="col" class="px-6 py-3"></th>
              </tr>
            </thead>
            <?php if (mysqli_num_rows($result) > 0) {
              while ($data = mysqli_fetch_assoc($result)) { ?>
              
          <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
            
            <td scope="col" class="px-6 py-3 bold-18 flex flex-col lg:flex-row items-center">
              <?php
              $product_image = explode(',', $data['product_image']);
              foreach ($product_image as $product_images) {
                echo '<img class="mx-2 py-2" src="../public/uploaded_img/' .
                  $product_images .
                  '" alt="" height="60" width="60" />';
              }
              ?>
                
                <?php echo $data['product_name']; ?> 
              </td>
              <td scope="col" class="px-6 py-3"><?php echo $data[
                'product_color'
              ]; ?> </td>

              <td scope="col" class="px-6 py-3"><?php echo $data[
                'product_variation'
              ]; ?> </td>
              
              <!-- Change this part of your code -->

              <td scope="col" class="px-6 py-3">
                <input type="number" name="quantity[<?php echo $data[
                  'cart_id'
                ]; ?>]" value="<?php echo $data[
  'quantity'
]; ?>" class="w-fit py-2" oninput="validateQuantity(this)">
                <input type="hidden" name="cart_id" value="<?php echo $data[
                  'cart_id'
                ]; ?>">

                <button type="submit" name="update[<?php echo $data[
                  'cart_id'
                ]; ?>]" class="bg-yellow-600 font-subtitle px-5 py-2 rounded-lg text-white font-bold tracking-wider hover:shadow-lg hover:bg-yellow-700 hover:cursor-pointer">Update</button>
              </td>



              <td scope="col" class="px-6 py-3"><?php echo $data[
                'width'
              ]; ?> cm </td>

              <td scope="col" class="px-6 py-3"><?php echo $data[
                'height'
              ]; ?> cm </td>

              <<td scope="col" class="px-6 py-3 text-center gap-2">
                <a href="quotation.php?remove=<?php echo $data[
                  'cart_id'
                ]; ?>" name="remove" class="bg-red-600 font-subtitle px-5 py-2 rounded-lg text-white font-bold tracking-wider hover:shadow-lg hover:bg-red-700 hover:cursor-pointer" onclick="return confirm(`Are you sure you want to delete product '<?php echo $data[
  'product_name'
]; ?>'`);">Remove</a>
              </td>
              

                  <tr>
            <?php $sn++;}
            } else {
               ?>
                <tr>
                  <td colspan="8" class="text-center">No items added.</td>
                </tr>
                <tr>
                  <td><br /></td>
                </tr>
                <tr>
                    <td colspan="8" class="text-center"><a href="allProducts.php" class="btn_dark_green">Go to products</a></td>
                </tr>
            <?php
            } ?>
          </table>
        </form>
        
        <?php
        $query = "SELECT * FROM cart";
        $result = mysqli_query($conn, $query);
        ?>

        
        <div class="mt-5 mx-2">

        <?php if (mysqli_num_rows($result) > 0) { ?>
          <form action="" method="post">
            
            <label for="name" class="font-subtitle regular-18">Name:</label>
            <input type="text" name="name" required placeholder="Enter name" class="input">

            <label for="email" class="font-subtitle regular-18">Email:</label>
            <input type="email" name="email" required placeholder="Enter email" class="input">
            
            <label for="password" class="font-subtitle regular-18">Contact Number:</label>
            <input type="text" name="contact_number" required placeholder="Enter contact number" class="input">
            
            <div class="flex justify-end mt-5 mr-5 regular-18 font-subtitle">
              <button type="submit" name="submit" class="btn_dark_green">Send request</button>
            </div>
            
          </form>

          <?php } elseif (!isset($_SESSION['user_id'])) { ?>
            <form action="" method="">
          
              <label for="name" class="font-subtitle regular-18">Name:</label>
              <input type="text" name="name" required placeholder="Enter name" class="input" disabled>

              <label for="email" class="font-subtitle regular-18">Email:</label>
              <input type="email" name="email" required placeholder="Enter email" class="input" disabled>
              
              <label for="password" class="font-subtitle regular-18">Contact Number:</label>
              <input type="text" name="contact_number" required placeholder="Enter contact number" class="input" disabled>
              
              <div class="flex justify-center mt-5 mr-5 regular-18 font-subtitle">
                <button type="submit" name="submit" class="btn_dark_green">Sign in to request quotation</button>
              </div>
              
            </form>
          <?php } else { ?>
            <form action="" method="">
          
              <label for="name" class="font-subtitle regular-18">Name:</label>
              <input type="text" name="name" required placeholder="Enter name" class="input" disabled>

              <label for="email" class="font-subtitle regular-18">Email:</label>
              <input type="email" name="email" required placeholder="Enter email" class="input" disabled>
              
              <label for="password" class="font-subtitle regular-18">Contact Number:</label>
              <input type="text" name="contact_number" required placeholder="Enter contact number" class="input" disabled>
              
              <div class="flex justify-end mt-5 mr-5 regular-18 font-subtitle">
                <button type="submit" name="submit" class="bg-green-nav opacity-50 px-4 py-2 rounded-full font-subtitle" disabled>Send request</button>
              </div>
              
            </form>
          <?php } ?>
        </div>

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
