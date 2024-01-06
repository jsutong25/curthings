<?php
// header('Content-Type: application/json');
include 'config.php';

error_reporting(0);
session_start();
$user_id = $_SESSION['user_id'];
if (isset($_GET['product'])) {
  $id = $_GET['product'];
} else {
  echo "Product ID is not set.";
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

if (isset($_POST['add_to_quote'])) {
  $product_id = $id;
  $product_name = $_POST['product_name'];

  $stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?"
  );

  mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  mysqli_stmt_close($stmt);

  if (mysqli_num_rows($result) == 0) {
    $select_product = mysqli_query(
      $conn,
      "SELECT * FROM `products` WHERE product_id = '$id'"
    );

    if ($fetch_product = mysqli_fetch_assoc($select_product)) {
      $product_images = explode(',', $fetch_product['product_image']);
      $main_image = reset($product_images);

      $product_image = $main_image;
      $product_color = $_POST['product_color'];
      $product_variation = $_POST['product_variation'];
      $quantity = $_POST['quantity'];
      $width = $_POST['width'];
      $height = $_POST['height'];

      $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO `cart` (user_id, product_id, product_name, product_image, product_color, product_variation, quantity, width, height) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
      );

      mysqli_stmt_bind_param(
        $stmt,
        "iissssiss",
        $user_id,
        $product_id,
        $product_name,
        $product_image,
        $product_color,
        $product_variation,
        $quantity,
        $width,
        $height
      );

      if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Added to quote. Check quotation page for your items.');window.location.href='#';</script>";
      } else {
        echo 'Error in execution: ' . mysqli_stmt_error($stmt);
      }

      mysqli_stmt_close($stmt);
    }
  }
}

$select_product = mysqli_query(
  $conn,
  "SELECT * FROM `products` WHERE product_id = '$id'"
);
if (!$select_product) {
  die('Query failed: ' . mysqli_error($conn));
}

if (isset($_GET['logout'])) {
  unset($user_id);
  session_destroy();
  header('location:index.php');
}

if (
  !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
) {
  // It's an AJAX request, return JSON response
  $response = ['idCount' => $idCount];
  echo json_encode($response);
  exit(); // Make sure to exit to prevent further HTML output
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
          <a href="allProducts.php" class="underline decoration-green-main font-bold text-green-main">
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
                  <a href="allProducts.php" class="transition ease-in-out duration-500 text-white hover:font-bold hover:text-green-nav font-bold bg-green-main border-1 border-gray-800 py-4">
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
    
    <main class="pb-8">
      <div class="max-container mt-5">
        <div class="mb-4">
          <a href="allProducts.php" class="text-green-nav flex flex-row font-subtitle bold-18 hover:text-gray-800 hover:underline">
            <svg class="w-[1.75rem] h-[1.75rem]" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 512 512"><path d="M352 128.4 319.7 96 160 256l159.7 160 32.3-32.4L224.7 256z" fill="#3f532c" class="fill-000000"></path></svg>
            Back
          </a>
        </div>
          <div class="flex flex-col md:flex-row justify-center gap-5">

          <!-- LEFT SIDE -->

            <div class="my-4 mx-1 flex flex-col justify-center items-center md:items-start md:justify-start">
              
              <?php
              ($select_product = mysqli_query(
                $conn,
                "SELECT * FROM `products` WHERE product_id = '$id'"
              )) or die('query failed');
              if (mysqli_num_rows($select_product) > 0) {
                while (
                  $fetch_product = mysqli_fetch_assoc($select_product)
                ) { ?>
                  <?php
                  $product_images = explode(
                    ',',
                    $fetch_product['product_image']
                  );
                  $main_image = reset($product_images);
                  ?>
                  <img id="mainImage" class="w-96 h-72 md:w-[400px] md:h-[400px] lg:w-[450px] lg:h-[400px] xl:w-[550px] xl:h-[500px] rounded-lg" src="uploaded_img/<?php echo $main_image; ?>" alt="" />
                  <div class="mt-2">
                    <button class="flex flex-row py-2 bg-white shadow-sm rounded-xl overflow-hidden hover:shadow-lg transition">
                        <?php foreach ($product_images as $product_image) {
                          echo '<img class="thumbnail mx-2 border-2 border-gray-600 rounded-lg transition ease-in-out duration-500" src="uploaded_img/' .
                            $product_image .
                            '" alt="" style="width:60px;height:75px;" onmouseover="hoverImage(this)" onmouseout="unhoverImage(this)" />';
                        } ?>
                    </button>
                  </div>
                  <?php }
              }
              ?>

                  <script>
                    function hoverImage(img) {
                      img.style.transform = 'scale(1.05)';
                    }

                    function unhoverImage(img) {
                      img.style.transform = 'scale(1)';
                    }
                    // JavaScript code to handle image switching
                    document.addEventListener('DOMContentLoaded', function () {
                      const mainImage = document.getElementById('mainImage');
                      const thumbnails = document.querySelectorAll('.thumbnail');

                      thumbnails.forEach(function (thumbnail) {
                        thumbnail.addEventListener('click', function () {
                          // Update the source of the main image with the clicked thumbnail's source
                          mainImage.src = thumbnail.src;
                        });
                      });
                    });
                  </script>
              
            </div>


            <!-- RIGHT SIDE -->
            <div class="mx-4 mt-5 md:mx-1">
              <?php
              ($select_product = mysqli_query(
                $conn,
                "SELECT * FROM `products` WHERE product_id = '$id'"
              )) or die('query failed');
              if (mysqli_num_rows($select_product) > 0) {
                while (
                  $fetch_product = mysqli_fetch_assoc($select_product)
                ) { ?>
                  <form action="" method="POST">
                      <h1 class="font-subtitle bold-32"><?php echo $fetch_product[
                        'product_name'
                      ]; ?></h1>
                      <input type="hidden" name="product_name" value='<?php echo $fetch_product[
                        'product_name'
                      ]; ?>'>
                      <div class="h-1 w-[3rem] bg-green-main rounded-full my-2 text-xl"></div>
                      <p><?php echo $fetch_product[
                        'product_description'
                      ]; ?></p>

                      <input type="hidden" name="id" value="<?php echo $id; ?>">

                      <?php $color = explode(',', $fetch_product['color']); ?>
                      
                        <div class="mt-5">
                          <label for="color" class="font-subtitle">Color:</label>
                          <select name="product_color" class="h-8">
                            <option value="">-----------------</option>
                            <?php
                            $color = explode(',', $fetch_product['color']);
                            foreach ($color as $colour) {
                              $colour = ucwords($colour);
                              echo "<option class='font-subtitle' value='$colour'>$colour</option>";
                            }
                            ?>
                          </select>

                          <?php $color = explode(
                            ',',
                            $fetch_product['variation']
                          ); ?>

                          <label for="variation" class="font-subtitle">Variation:</label>
                          <select name="product_variation" class="h-8">
                            <option value="">-----------------</option>
                            <?php
                            $variation = explode(
                              ',',
                              $fetch_product['variation']
                            );
                            foreach ($variation as $variations) {
                              $variations = ucwords($variations);
                              echo "<option class='font-subtitle' value='$variations'>$variations</option>";
                            }
                            ?>
                          </select>
                        <?php }
              }
              ?>
                  
                  <div class="">
                      <label for="width" class="font-subtitle">Width: (of your window in 'cm')</label>
                      <div class="flex flex-row gap-2">
                        <input type="text" name="width" autocomplete="off" placeholder="Enter your window width size in cm" class="w-20 h-8" required><span class="tracking-wide">cm</span>
                      </div>
                    
                    <label for="height" class="font-subtitle">Height: (of your window in 'cm')</label>
                    <div class="flex flex-row gap-2">
                      <input type="text" name="height" autocomplete="off" placeholder="Enter your window width size in cm" class="w-20 h-8" required><span class="tracking-wide">cm</span>
                    </div>

                    <label for="quantity" class="font-subtitle">Quantity:</label>
                    <input type="number" min="1" autocomplete="off" name="quantity" value="1">
                  </div>

                  <div class="mx-auto pt-8 mt-8">
                  <?php if (
                    isset($_SESSION['user_id']) &&
                    !empty($_SESSION['user_id'])
                  ) { ?>
                        <button type="submit" name="add_to_quote" class="btn_dark_green w-full font-subtitle">Add to quotation</button>
                  <?php } else { ?>
                    <div class="w-full text-center">
                      <button class="bg-green-nav opacity-50 px-4 py-2 rounded-full w-full font-subtitle" disabled>Add to quotation</button>
                      <a href="login.php" class="bold-18 underline text-green-main hover:text-gray-800 mt-4">
                        Login to add to quote
                      </a>
                    </div>
                        
                  <?php } ?>
                  </div>
              </div>
            </form>


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

    <script>
      function changeImage() {
      var img = document.getElementById('image');
      img.src = "/uploaded_img/";
      return false
      }

      function updateCartCount() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            document.getElementById('cartCount').innerHTML = response.idCount;
          }
        };
        xhttp.open('GET', window.location.pathname, true);
        xhttp.send();
      }
    </script>

    <script src="../node_modules/preline/dist/preline.js"></script>
</body>
</html>
