<?php

include "../public/config.php";
session_start();
$user_id = $_SESSION['user_id'];
$id = $_GET['view'];

$db = $conn;
$tableName = "quotation";
$columns = [
  "quotation_id",
  "name",
  "email",
  "contact_number",
  "quotation_status",
  "date_created",
];

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
   <title>Customer Info</title>
   <link rel="stylesheet" href="../styles.css">

</head>
   <body>

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
                    <a href="adminProducts.php" class="sidebar-a group">
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
                    <a href="adminBooking.php" class="sidebar-a group bg-gray-800">
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
        <aside class="sm:disabled sm:hidden sm:opacity-0">
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
                <button type="button" class="hs-tooltip-toggle w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-500 hover:bg-gray-800">
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
                <button type="button" class="hs-tooltip-toggle w-[2.375rem] h-[2.375rem] bg-gray-800 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-500 hover:bg-gray-800">
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
            <a href="adminBooking.php" class="text-green-nav flex flex-row font-subtitle bold-18 hover:text-gray-800 hover:underline">
                <svg class="w-[1.75rem] h-[1.75rem]" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 512 512"><path d="M352 128.4 319.7 96 160 256l159.7 160 32.3-32.4L224.7 256z" fill="#3f532c" class="fill-000000"></path></svg>
                Back
            </a>
            </div>
          <h2 class="bold-32 font-subtitle mb-5">Booking ID '<?php echo $id; ?>'</h2>
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
      </main>

      <script src="../node_modules/preline/dist/preline.js"></script>
   </body>
</html>