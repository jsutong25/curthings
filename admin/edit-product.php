<?php

include "../public/config.php";
session_start();
error_reporting(0);
$user_id = $_SESSION['user_id'];
$id = $_GET['edit'];

$db = $conn;
$tableName = "products";
$columns = [
  'product_id',
  'product_name',
  'slug',
  'product_description',
  'product_image',
  'color',
  'variation',
];

if (isset($_POST['submit'])) {
  $product_name = $_POST['product_name'];
  $product_description = $_POST['product_description'];
  $product_image = $_FILES['product_image']['name'];
  $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
  $product_image_folder = '../public/uploaded_img/' . $product_image;
  $color = $_POST['color'];
  $variation = $_POST['variation'];

  if (empty($product_name) || empty($product_description)) {
    echo "<script>alert('Please fill out all blanks.')</script>";
  } elseif (empty($_FILES['product_image']['name'])) {
    $insert_query = mysqli_query(
      $conn,
      "update products set product_name ='$product_name', product_description = '$product_description', color = '$color', variation = '$variation' where product_id = '$id'"
    );
    if ($insert_query > 0) {
      echo "<script>alert('Product updated.');window.location.href='adminProducts.php';</script>";
    } else {
      echo "<script>alert('Error 3.');window.location.href='adminProducts.php';</script>";
    }
  } else {
    $insert_query = mysqli_query(
      $conn,
      "update products set product_name ='$product_name', product_description = '$product_description', product_image = '$product_image', color = '$color', variation = '$variation' where product_id = '$id'"
    );
    if ($insert_query > 0) {
      move_uploaded_file($product_image_tmp_name, $product_image_folder);
      echo "<script>alert('Product updated.');window.location.href='adminProducts.php';</script>";
    } else {
      echo "<script>alert('Error 3.');window.location.href='adminProducts.php';</script>";
    }
  }
}

if (isset($_GET['logout'])) {
  unset($user_id);
  session_destroy();
  header('location:../public/login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products - Curthings</title>
   <link rel="stylesheet" href="../styles.css">

</head>
   <body class="">

      <!-- SIDEBAR -->
      <aside id="default-sidebar" class="hidden sm:inline fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-green-main dark:bg-gray-800">
            <a href="#" class="flex flex-row justify-center items-center">
                <img src="../public/assets/logo.png" alt="logo" class="h-[50px] w-[55px]">
                <span class="content-center text-white font-title bold-32">Curthings</span>
            </a>
            <ul class="text-center space-y-2 mt-5">
                <li>
                    <a href="adminIndex.php" class="sidebar-a group">
                    <svg class="sidebar-svg" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0,0,256,256">
                    <g fill="#f3f4f6" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(10.66667,10.66667)"><path d="M12,2.09961l-11,9.90039h3v9h7v-6h2v6h7v-9h3zM12,4.79102l6,5.40039v0.80859v8h-3v-6h-6v6h-3v-8.80859z"></path></g></g>
                    </svg>
                    <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="adminUsers.php" class="sidebar-a group">
                    <svg class="sidebar-svg" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0,0,256,256">
                    <g fill="#f3f4f6" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(10.66667,10.66667)"><path d="M12,2c-4,0 -10,3 -10,18h4.72852c1.41006,1.24081 3.25301,2 5.27148,2c2.01847,0 3.86142,-0.75919 5.27148,-2h4.72852c0,-15 -6,-18 -10,-18zM12,4c1.669,0 4.87173,0.75025 6.67773,6.03125c-0.71,-0.641 -1.64873,-1.03125 -2.67773,-1.03125h-8c-1.029,0 -1.96773,0.39025 -2.67773,1.03125c1.806,-5.281 5.00873,-6.03125 6.67773,-6.03125zM8,11h8c1.105,0 2,0.895 2,2v1c0,3.32556 -2.67444,6 -6,6c-3.32556,0 -6,-2.67444 -6,-6v-1c0,-1.105 0.895,-2 2,-2z"></path></g></g>
                    </svg>
                    <span class="ms-3">Users</span>
                    </a>
                </li>

                <li>
                    <a href="adminProducts.php" class="sidebar-a group bg-gray-800">
                    <svg class="sidebar-svg" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0,0,256,256">
                    <g fill="#f3f4f6" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(10.66667,10.66667)"><path d="M5.75,3c-0.35548,0.00019 -0.68414,0.18906 -0.86328,0.49609l-1.75,3c-0.08939,0.1529 -0.13657,0.32679 -0.13672,0.50391v12c0,1.09306 0.90694,2 2,2h14c1.09306,0 2,-0.90694 2,-2v-12c-0.00015,-0.17711 -0.04733,-0.35101 -0.13672,-0.50391l-1.75,-3c-0.17914,-0.30704 -0.5078,-0.4959 -0.86328,-0.49609zM6.32422,5h11.35156l1.16602,2h-13.68359zM5,9h14v10h-14zM9,11v2h6v-2z"></path></g></g>
                    </svg>
                    <span class="ms-3">Products</span>
                    </a>
                </li>

                <li>
                    <a href="adminQuotation.php" class="sidebar-a group">
                    <svg class="sidebar-svg" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0,0,256,256">
                    <g fill="#f3f4f6" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(10.66667,10.66667)"><path d="M6,2c-1.09425,0 -2,0.90575 -2,2v16c0,1.09426 0.90575,2 2,2h12c1.09426,0 2,-0.90574 2,-2v-12l-6,-6zM6,4h7v5h5v11h-12z"></path></g></g>
                    </svg>
                    <span class="ms-3">Quotation</span>
                    </a>
                </li>

                <li>
                    <a href="adminBooking.php" class="sidebar-a group">
                    <svg class="sidebar-svg" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0,0,256,256">
                    <g fill="#f3f4f6" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(10.66667,10.66667)"><path d="M5,3c-1.103,0 -2,0.897 -2,2v14c0,1.103 0.897,2 2,2h14c1.103,0 2,-0.897 2,-2v-9.75781l-2,2l0.00195,7.75781h-14.00195v-14h11.75781l2,-2zM21.29297,3.29297l-10.29297,10.29297l-3.29297,-3.29297l-1.41406,1.41406l4.70703,4.70703l11.70703,-11.70703z"></path></g></g>
                    </svg>
                    <span class="ms-3">Booking</span>
                    </a>
                </li>

                <li>
                    <div class="w-full h-1 bg-gray-50 opacity-70 rounded-full my-5" />
                </li>

                <li class="">
                    <a href="../public/index.php?logout=<?php echo $user_id; ?>" name="logout" onclick="return confirm('Are your sure you want to logout?');" class="sidebar-a group">
                    <svg class="sidebar-svg" aria-hidden="true" fill="none" viewBox="0 0 22 21" xmlns="http://www.w3.org/2000/svg"><path d="m17 16 4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1" stroke="#f3f4f6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="stroke-374151"></path></svg>
                    <span class="ms-3">Logout</span>
                    </a>\
                </li>
            </ul>
        </div>
      </aside>

      <!-- MOBILE SIDEBAR -->
      <!-- Navigation Toggle -->
      <div class="flex sm:hidden bg-green-main justify-between p-2">
          <img src="../public/assets/logo.png" alt="" width=40 height=20 class="bg-gray-800 rounded-full p-1.5">
            <button type="button" class="p-2 inline-flex justify-center items-center gap-x-2 rounded-lg border border-gray-200 bg-gray-100 text-gray-800 shadow-sm hover:bg-gray-300 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#sidebar-mini" aria-controls="sidebar-mini" aria-label="Toggle navigation">
                <span class="sr-only">Toggle Navigation</span>
                <svg class="flex-shrink-0 w-4 h-4" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                </svg>
            </button>
            </div>
        <!-- End Navigation Toggle -->

        <!-- Sidebar -->
        <aside class="sm:disabled sm:hidden">
            <div id="sidebar-mini" class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform hidden fixed top-0 start-0 bottom-0 z-[60] w-20 bg-green-main border-e border-gray-200 lg:block lg:translate-x-0 lg:end-auto lg:bottom-0 [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-slate-700 dark:[&::-webkit-scrollbar-thumb]:bg-slate-500 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex flex-col justify-center items-center gap-y-2 py-4">
                <div class="mb-4">
                <a class="flex-none" href="#">
                    <img src="../public/assets/logo.png" alt="logo" width="35" height="35">
                </a>
                </div>
                <div class="hs-tooltip inline-block [--placement:right]">
                <button type="button" class="hs-tooltip-toggle w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-500 hover:bg-gray-800">
                    <a href="adminIndex.php">
                        <svg class="sidebar-svg" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0,0,256,256">
                                <g fill="#f3f4f6" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(10.66667,10.66667)"><path d="M12,2.09961l-11,9.90039h3v9h7v-6h2v6h7v-9h3zM12,4.79102l6,5.40039v0.80859v8h-3v-6h-6v6h-3v-8.80859z"></path></g></g>
                                </svg>
                        <span class="font-subtitle hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 inline-block absolute invisible z-20 py-1.5 px-2.5 bg-gray-900 text-xs text-white rounded-lg whitespace-nowrap dark:bg-neutral-700" role="tooltip">
                        Dashboard
                        </span>
                    </a>
                </button>
                </div>
                <div class="hs-tooltip inline-block [--placement:right]">
                <button type="button" class="hs-tooltip-toggle w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-500 hover:bg-gray-800">
                    <a href="adminUsers.php">
                        <svg class="sidebar-svg" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0,0,256,256">
                            <g fill="#f3f4f6" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(10.66667,10.66667)"><path d="M12,2c-4,0 -10,3 -10,18h4.72852c1.41006,1.24081 3.25301,2 5.27148,2c2.01847,0 3.86142,-0.75919 5.27148,-2h4.72852c0,-15 -6,-18 -10,-18zM12,4c1.669,0 4.87173,0.75025 6.67773,6.03125c-0.71,-0.641 -1.64873,-1.03125 -2.67773,-1.03125h-8c-1.029,0 -1.96773,0.39025 -2.67773,1.03125c1.806,-5.281 5.00873,-6.03125 6.67773,-6.03125zM8,11h8c1.105,0 2,0.895 2,2v1c0,3.32556 -2.67444,6 -6,6c-3.32556,0 -6,-2.67444 -6,-6v-1c0,-1.105 0.895,-2 2,-2z"></path></g></g>
                            </svg>
                        <span class="font-subtitle hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 inline-block absolute invisible z-20 py-1.5 px-2.5 bg-gray-900 text-xs text-white rounded-lg whitespace-nowrap dark:bg-neutral-700" role="tooltip">
                        Users
                        </span>
                    </a>
                </button>
                </div>
                <div class="hs-tooltip inline-block [--placement:right]">
                <button type="button" class="hs-tooltip-toggle w-[2.375rem] h-[2.375rem] bg-gray-800 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-500 hover:bg-gray-800">
                    <a href="adminProducts.php">
                        <svg class="sidebar-svg" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0,0,256,256">
                            <g fill="#f3f4f6" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(10.66667,10.66667)"><path d="M5.75,3c-0.35548,0.00019 -0.68414,0.18906 -0.86328,0.49609l-1.75,3c-0.08939,0.1529 -0.13657,0.32679 -0.13672,0.50391v12c0,1.09306 0.90694,2 2,2h14c1.09306,0 2,-0.90694 2,-2v-12c-0.00015,-0.17711 -0.04733,-0.35101 -0.13672,-0.50391l-1.75,-3c-0.17914,-0.30704 -0.5078,-0.4959 -0.86328,-0.49609zM6.32422,5h11.35156l1.16602,2h-13.68359zM5,9h14v10h-14zM9,11v2h6v-2z"></path></g></g>
                            </svg>
                        <span class="font-subtitle hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 inline-block absolute invisible z-20 py-1.5 px-2.5 bg-gray-900 text-xs text-white rounded-lg whitespace-nowrap dark:bg-neutral-700" role="tooltip">
                        Products
                        </span>
                    </a>
                </button>
                </div>
                <div class="hs-tooltip inline-block [--placement:right]">
                <button type="button" class="hs-tooltip-toggle w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-500 hover:bg-gray-800">
                    <a href="adminQuotation.php">
                        <svg class="sidebar-svg" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0,0,256,256">
                            <g fill="#f3f4f6" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(10.66667,10.66667)"><path d="M6,2c-1.09425,0 -2,0.90575 -2,2v16c0,1.09426 0.90575,2 2,2h12c1.09426,0 2,-0.90574 2,-2v-12l-6,-6zM6,4h7v5h5v11h-12z"></path></g></g>
                            </svg>
                        <span class="font-subtitle hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 inline-block absolute invisible z-20 py-1.5 px-2.5 bg-gray-900 text-xs text-white rounded-lg whitespace-nowrap dark:bg-neutral-700" role="tooltip">
                        Quotation
                        </span>
                    </a>
                </button>
                </div>
                <div class="hs-tooltip inline-block [--placement:right]">
                <button type="button" class="hs-tooltip-toggle w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-500 hover:bg-gray-800">
                    <a href="adminBooking.php">
                        <svg class="sidebar-svg" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0,0,256,256">
                            <g fill="#f3f4f6" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(10.66667,10.66667)"><path d="M5,3c-1.103,0 -2,0.897 -2,2v14c0,1.103 0.897,2 2,2h14c1.103,0 2,-0.897 2,-2v-9.75781l-2,2l0.00195,7.75781h-14.00195v-14h11.75781l2,-2zM21.29297,3.29297l-10.29297,10.29297l-3.29297,-3.29297l-1.41406,1.41406l4.70703,4.70703l11.70703,-11.70703z"></path></g></g>
                            </svg>
                        <span class="font-subtitle hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 inline-block absolute invisible z-20 py-1.5 px-2.5 bg-gray-900 text-xs text-white rounded-lg whitespace-nowrap dark:bg-neutral-700" role="tooltip">
                        Bookings
                        </span>
                    </a>
                </button>
                </div>
                <div class="w-8 h-1 bg-gray-50 opacity-70 rounded-full my-5"></div>
                <div class="hs-tooltip inline-block [--placement:right]">
                <button type="button" class="hs-tooltip-toggle w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-500 hover:bg-gray-800">
                    <a href="../public/login.php?logout=<?php echo $user_id; ?>" onclick="return confirm('are your sure you want to logout?');">
                    <svg class="sidebar-svg" aria-hidden="true" fill="none" viewBox="0 0 22 21" xmlns="http://www.w3.org/2000/svg"><path d="m17 16 4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1" stroke="#f3f4f6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="stroke-374151"></path></svg>
                        <span class="font-subtitle hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 inline-block absolute invisible z-20 py-1.5 px-2.5 bg-gray-900 text-xs text-white rounded-lg whitespace-nowrap dark:bg-neutral-700" role="tooltip">
                        Logout
                        </span>
                    </a>
                </button>
                </div>
            </div>
          </div>
      </aside>
      <!-- End Sidebar -->

      <main>
        <div class="p-4 sm:ml-64">
          <div class="mb-5">
            <a href="adminProducts.php" class="text-green-nav flex flex-row font-subtitle bold-18 hover:text-gray-800 hover:underline">
              <svg class="w-[1.75rem] h-[1.75rem]" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 512 512"><path d="M352 128.4 319.7 96 160 256l159.7 160 32.3-32.4L224.7 256z" fill="#3f532c" class="fill-000000"></path></svg>
              Back
            </a>
          </div>
        <?php
        $select = mysqli_query(
          $conn,
          "SELECT * FROM products WHERE product_id = '$id'"
        );
        while ($row = mysqli_fetch_assoc($select)) { ?>
            
            <form action="" method="post" enctype="multipart/form-data">
            <h3 class="bold-32 font-subtitle mb-5">Edit Product</h3>
            <div class="border-2 border-gray-300">
                <div class="m-3">  
                <div class="">
                    <label for="product_description" class="font-subtitle regular-18">Product Name</label>
                    <input type="text" class="input" name="product_name" value="<?php echo $row[
                      'product_name'
                    ]; ?>" placeholder="Enter product name">
                </div>

                <div class="">
                    <label for="product_description" class="font-subtitle regular-18">Product Description</label>
                    <input type="text" class="input" name="product_description" value="<?php echo $row[
                      'product_description'
                    ]; ?>" placeholder="Enter product description">
                </div>
                
                <div class="">
                    <label for="product_image" class="font-subtitle regular-18">Product Image/s</label>
                    <input type="file" name="product_image" class="block w-full font-subtitle text-sm text-gray-500
                        file:me-4 file:py-2 file:px-4
                        file:rounded-lg file:border-0
                        file:text-sm file:font-semibold
                        file:bg-green-main file:text-white
                        hover:file:opacity-80 hover:file:cursor-pointer
                        file:disabled:opacity-50 file:disabled:pointer-events-none
                    ">
                </div>

                <div class="">
                    <label for="color" class="font-subtitle regular-18">Color/s (Optional)</label>
                    <input type="text" name="color" value="<?php echo $row[
                      'color'
                    ]; ?>" placeholder="Enter available colors (separate by comma ',' ex: 'blue,red,pink')" class="input">
                </div>

                <div class="">
                    <label for="variation" class="font-subtitle regular-18">Product Variations (Optional)</label>
                    <input type="text" name="variation" value="<?php echo $row[
                      'variation'
                    ]; ?>" placeholder="Enter product variations (separate by comma ',' ex: 'black-out,sheer')" class="input">
                </div>

                <div>
                    <input type="submit" value="Update product" name="submit" class="bg-red-700 shadow-md text-white font-subtitle bold-18 py-1 cursor-pointer hover:bg-red-900 mt-2" onclick="return confirm('Are you sure you want to update product');">
                </div>

                <div class="w-full h-2 bg-gray-700 rounded-full my-5"></div>

                <div class="">
                    <a href="adminProducts.php" class="flex flex-row gap-2 items-center rounded-lg justify-center bg-gray-800 shadow-md text-white font-subtitle bold-18 py-1 px-2 cursor-pointer hover:bg-gray-900 hover:shadow-md mt-2">
                        <svg class="sidebar-svg" fill="#f3f4f6" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 26.676 26.676" xml:space="preserve" stroke="#f3f4f6"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M26.105,21.891c-0.229,0-0.439-0.131-0.529-0.346l0,0c-0.066-0.156-1.716-3.857-7.885-4.59 c-1.285-0.156-2.824-0.236-4.693-0.25v4.613c0,0.213-0.115,0.406-0.304,0.508c-0.188,0.098-0.413,0.084-0.588-0.033L0.254,13.815 C0.094,13.708,0,13.528,0,13.339c0-0.191,0.094-0.365,0.254-0.477l11.857-7.979c0.175-0.121,0.398-0.129,0.588-0.029 c0.19,0.102,0.303,0.295,0.303,0.502v4.293c2.578,0.336,13.674,2.33,13.674,11.674c0,0.271-0.191,0.508-0.459,0.562 C26.18,21.891,26.141,21.891,26.105,21.891z"></path> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> </g> </g></svg>
                    <span>Go back</span>
                    </a>
                </div>

                </div>
            </div>
                
                
            </form>
            
            <?php }
        ?>
        </div>
      </main>

      <script src="../node_modules/preline/dist/preline.js"></script>
   </body>
</html>