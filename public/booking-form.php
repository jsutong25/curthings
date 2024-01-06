<?php

include 'config.php';
error_reporting(0);
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_GET['date'])) {
  $date = $_GET['date'];
}

if (!isset($_SESSION['user_id'])) {
  echo "<script>alert('User not logged in. Login first.');window.location.href='login.php';</script>";
}

$user_id = $_SESSION['user_id'];

function sendMail(
  $email,
  $date,
  $full_name,
  $contact_number,
  $address,
  $quotation_number,
  $booking_date,
  $booking_refnum
) {
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
    $mail->Subject = "Booking Reference Number: $booking_refnum - Curthings";
    $mail->Body = "<h3 class='text-lg'>Thank you for booking with Curthings! Your booking is scheduled on <b>$date</b> and your booking reference number is <b>$booking_refnum</b>. Please wait for our confirmation email.</h3>
    <br><h4 class='text-lg'>Booking Details:</h4> 
    <h5 class='text-lg'><span class='text-lg'>Full Name:</span> $full_name </h5>
    <h5 class='text-lg'><span class='text-lg'>Contact Number:</span> $contact_number </h5>
    <h5 class='text-lg'><span class='text-lg'>Address:</span> $address </h5>
    <h5 class='text-lg'><span class='text-lg'>Quotation Number:</span> $quotation_number </h5>
    <h5 class='text-lg'><span class='text-lg'>Booking Date:</span> $booking_date </h5>
    
    <br><br><br><h4 class='text-xl'>This serves as your official receipt. Thank you for choosing Curthings!</h4>";

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

if (isset($_POST['submit'])) {
  $full_name = $_POST['full_name'];
  $email = $_POST['email'];
  $contact_number = $_POST['contact_number'];
  $address = $_POST['address'];
  $quotation_number = $_POST['quotation_number'];
  $booking_status = '0';
  $date_created = date('d-m-y h:i:s');
  $booking_refnum = $_POST['booking_refnum'];
  $downpayment_status = '1';
  $downpayment_method = $_POST['downpayment_method'];
  $payment_status = '0';
  $conn = new mysqli('localhost', 'root', '', 'cms_db');

  $query = "SELECT * FROM quotation WHERE quotation_number = '$quotation_number'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    $quotation_id = mysqli_fetch_assoc($result)['quotation_id'];
    $query = "SELECT booking_status FROM bookings WHERE quotation_number = '$quotation_number'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == '1') {
      echo "<script>alert('Quotation number already booked. Please check your quotation file and enter a valid quotation number.');window.location.href='booking.php';</script>";
      die();
    } else {
      $sql = "INSERT INTO bookings(user_id,quotation_id,full_name,email,contact_number,address,quotation_number,booking_date,booking_status,date_created,booking_refnum,downpayment_status,downpayment_method,payment_status)VALUES('$user_id','$quotation_id','$full_name','$email','$contact_number','$address','$quotation_number','$date','$booking_status', '$date_created', '$booking_refnum', '$downpayment_status', '$downpayment_method', '$payment_status')";

      if (
        mysqli_query($conn, $sql) &&
        sendMail(
          $email,
          $date,
          $full_name,
          $contact_number,
          $address,
          $quotation_number,
          $date,
          $booking_refnum
        )
      ) {
        echo "<script>alert('Booked Successfully.');window.location.href='index.php';</script>";
        die();
      } else {
        echo 'Error in executing query: ' . mysqli_error($conn);
      }
    }
  } else {
    echo "<script>alert('Quotation number does not exist. Please check your quotation file and enter a valid quotation number.');window.location.href='booking.php';</script>";
  }
}

if (isset($_GET['logout'])) {
  unset($user_id);
  session_destroy();
  header('location:index.php');
}
?>
<?php
$booking_refnum = mt_rand(1000, 9999);
for ($i = 0; $i < 6; $i++) {
  $booking_refnum .= mt_rand(0, 9);
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
        <a href="booking.php" class="underline decoration-green-main font-bold text-green-main">
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
                <a href="booking.php" class="transition ease-in-out duration-500 text-white hover:font-bold hover:text-green-nav font-bold bg-green-main border-1 border-gray-800 py-4">
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

      <h3 class="font-subtitle bold-32 mb-2">Booking Form</h3>
      <div class="w-full h-1 bg-green-main rounded-full mb-5"></div>
        <div class="mt-5 p-5">
          
          <h1 class="font-subtitle text-center bold-32"> Book for Date: <span class="underline text-green-main"><?php echo date(
            'm/d/Y',
            strtotime($date)
          ); ?></span></h1>
          <p class="text-center italic">(Note: You need to have a quotation done before you can book. Bookings will be based on the quotation.)</p>

          <div class="mt-8 max-container flex justify-center">
            <div class="bg-white w-fit sm:w-1/2 border-2 border-gray-200 p-5 mx-10">
              <?php echo isset($message) ? $message : ''; ?>
                <form action="" method="POST">
                  <h4 class="font-subtitle text-2xl font-bold text-center">Booking Details</h4>
                    <div class="">
                        <label for="" class="font-subtitle regular-18">Full Name:</label>
                        <input type="text" class="input" autocomplete="off" name="full_name" placeholder="Enter full name" required>
                        <input type="hidden" class="" name="booking_refnum" value="<?php echo $booking_refnum; ?>"required>
                    </div>
                    <div class="">
                        <label for="" class="font-subtitle regular-18">Email:</label>
                        <input type="text" class="input" autocomplete="off" name="email" placeholder="Enter email address" required>
                    </div>
                    <div class="">
                        <label for="" class="font-subtitle regular-18">Contact Number:</label>
                        <input type="text" class="input" autocomplete="off" name="contact_number" placeholder="Enter contact number" required>
                    </div>
                    <div class="">
                        <label for="" class="font-subtitle regular-18">Address:</label>
                        <input type="text" class="input" autocomplete="off" name="address" placeholder="Enter home address" required>
                    </div>
                    <div class="">
                        <label for="quotation_number" class="font-subtitle regular-18">Quotation Number: <span class="italic">(Enter quotation number from quotation file sent via email.)</span></label>
                        <input type="text" class="input" autocomplete="off" name="quotation_number" placeholder="Enter quotation number from quotation file sent via email" required>
                    </div>

                    <label for="downpayment" class="font-subtitle regular-18">Downpayment: (Select payment method)</label>
                    <div class="w-1/2 mx-8 mt-2">
                      <div class="flex items-center mb-2">
                        <img src="assets/mastercard-outline-large.svg" alt="" width="25" height="25">
                        <img src="assets/visa-outline-large.svg" alt="" width="25" height="25" class="">
                        <div class="mx-2"></div>
                        <label for="bank-transfer" class="font-subtitle regular-16 inline-block w-full">Bank Transfer</label>
                        <input type="radio" name="downpayment_method" value="Bank Transfer" class="mr-2">
                      </div>
                      <div class="flex items-center">
                        <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="40" height="40" viewBox="0 0 100 100">
                          <path d="M 43 14 C 23.158623 14 7 30.158623 7 50 C 7 69.841377 23.158623 86 43 86 C 51.060131 86 58.705597 83.380252 65.068359 78.435547 C 67.239949 76.748053 67.633291 73.589271 65.947266 71.417969 C 64.261706 69.246908 61.103484 68.850128 58.931641 70.539062 C 54.327496 74.117279 48.832876 76 43 76 C 28.654361 76 17 64.345639 17 50 C 17 35.654361 28.654361 24 43 24 C 48.832876 24 54.327526 25.883734 58.931641 29.462891 C 61.102894 31.15109 64.26156 30.75328 65.947266 28.582031 C 67.635318 26.410966 67.239365 23.251215 65.068359 21.564453 C 58.705556 16.619716 51.059159 14 43 14 z M 43 16 C 50.626841 16 57.818647 18.465268 63.839844 23.144531 C 65.156838 24.16777 65.393088 26.036581 64.369141 27.353516 L 64.367188 27.355469 C 63.34494 28.672173 61.476903 28.906613 60.160156 27.882812 C 55.214271 24.037969 49.265124 22 43 22 C 27.569639 22 15 34.569639 15 50 C 15 65.430361 27.569639 78 43 78 C 49.265124 78 55.214301 75.960971 60.160156 72.117188 C 61.478313 71.092122 63.344746 71.327592 64.367188 72.644531 C 65.391161 73.963229 65.158254 75.830962 63.839844 76.855469 C 57.818606 81.534764 50.627869 84 43 84 C 24.243377 84 9 68.756623 9 50 C 9 31.243377 24.243377 16 43 16 z M 82.189453 25.998047 C 81.563233 25.998047 80.941132 26.148176 80.371094 26.439453 C 78.411676 27.441972 77.630256 29.862773 78.632812 31.822266 C 81.532591 37.491407 83 43.601602 83 50 C 83 56.400473 81.532561 62.50964 78.632812 68.177734 C 78.146552 69.126341 78.059863 70.218014 78.386719 71.230469 C 78.714562 72.243596 79.423648 73.075437 80.371094 73.560547 C 80.93947 73.851556 81.556115 74.001953 82.189453 74.001953 C 83.69502 74.001953 85.069567 73.163486 85.753906 71.822266 L 85.753906 71.820312 C 89.234649 65.020884 91 57.673995 91 50 C 91 42.328371 89.235048 34.980538 85.755859 28.177734 C 85.270792 27.229234 84.435988 26.521154 83.421875 26.193359 C 83.018972 26.063611 82.603939 25.998047 82.189453 25.998047 z M 82.189453 26.998047 C 82.498967 26.998047 82.810138 27.046277 83.115234 27.144531 C 83.883121 27.392737 84.498302 27.915313 84.865234 28.632812 C 88.274046 35.298009 90 42.481629 90 50 C 90 57.521401 88.273995 64.703158 84.863281 71.365234 L 84.863281 71.367188 C 84.347391 72.379192 83.327389 73.001953 82.189453 73.001953 C 81.710791 73.001953 81.257796 72.892866 80.826172 72.671875 C 80.109617 72.304985 79.586047 71.688747 79.337891 70.921875 C 79.090746 70.15633 79.155697 69.352159 79.523438 68.634766 L 79.523438 68.632812 C 82.493689 62.826907 84 56.553527 84 50 C 84 43.448398 82.491706 37.174045 79.521484 31.367188 C 78.766042 29.89068 79.34959 28.085559 80.826172 27.330078 C 81.258134 27.109355 81.721673 26.998047 82.189453 26.998047 z M 43 29 C 31.424828 29 22 38.424828 22 50 C 22 61.575172 31.424828 71 43 71 C 54.575172 71 64 61.575172 64 50 C 64 47.798843 62.201157 46 60 46 L 48 46 C 45.798843 46 44 47.798843 44 50 C 44 52.201157 45.798843 54 48 54 L 55.369141 54 C 53.623758 59.387459 48.658785 63 43 63 C 35.82718 63 30 57.17282 30 50 C 30 42.82718 35.82718 37 43 37 C 45.755221 37 48.392658 37.864469 50.642578 39.505859 L 50.642578 39.503906 C 51.503111 40.131924 52.568046 40.389458 53.617188 40.226562 C 54.669756 40.062526 55.603047 39.490576 56.230469 38.630859 C 57.528145 36.851971 57.136312 34.340646 55.357422 33.042969 C 51.736037 30.400162 47.458425 29 43 29 z M 43 30 C 47.249575 30 51.312963 31.328463 54.767578 33.849609 C 56.108688 34.827932 56.402152 36.701857 55.423828 38.042969 C 54.94925 38.693252 54.258322 39.114318 53.462891 39.238281 C 52.670031 39.361385 51.881889 39.171251 51.232422 38.697266 C 48.816342 36.934656 45.964779 36 43 36 C 35.28482 36 29 42.28482 29 50 C 29 57.71518 35.28482 64 43 64 C 49.084619 64 54.443197 60.10215 56.320312 54.310547 L 56.320312 54.308594 C 56.420709 54.001248 56.364162 53.669951 56.179688 53.414062 C 55.99174 53.15341 55.689557 53 55.369141 53 L 48 53 C 46.341157 53 45 51.658843 45 50 C 45 48.341157 46.341157 47 48 47 L 60 47 C 61.658843 47 63 48.341157 63 50 C 63 61.032828 54.032828 70 43 70 C 31.967172 70 23 61.032828 23 50 C 23 38.967172 31.967172 30 43 30 z M 71.142578 32.998047 C 70.569057 32.998047 69.996891 33.124019 69.462891 33.371094 L 69.460938 33.371094 C 67.463852 34.296072 66.586643 36.684345 67.511719 38.681641 C 69.162877 42.250045 70 46.053783 70 50 C 70 53.946217 69.162858 57.750866 67.511719 61.320312 C 66.586643 63.317608 67.463848 65.705882 69.460938 66.630859 L 69.462891 66.630859 C 69.992327 66.875127 70.55915 67.001953 71.138672 67.001953 C 72.694015 67.001953 74.118338 66.091215 74.771484 64.679688 C 76.913359 60.053055 78 55.110767 78 50 C 78 44.889233 76.91444 39.946988 74.771484 35.320312 C 74.325234 34.353817 73.521484 33.613013 72.523438 33.246094 L 72.521484 33.246094 C 72.072757 33.079644 71.606234 32.998047 71.142578 32.998047 z M 71.142578 33.998047 C 71.490286 33.998047 71.839074 34.058507 72.175781 34.183594 C 72.930993 34.460878 73.52577 35.009248 73.863281 35.740234 C 75.946326 40.23756 77 45.030767 77 50 C 77 54.969233 75.947313 59.762398 73.865234 64.259766 L 73.863281 64.259766 C 73.374428 65.316237 72.303329 66.001953 71.138672 66.001953 C 70.70284 66.001953 70.284829 65.907833 69.882812 65.722656 C 68.377899 65.025634 67.722997 63.244939 68.419922 61.740234 C 70.13083 58.041681 71 54.087783 71 50 C 71 45.912217 70.130811 41.959315 68.419922 38.261719 C 67.722997 36.757015 68.377903 34.974366 69.882812 34.277344 C 70.286812 34.090419 70.7141 33.998047 71.142578 33.998047 z M 43 40 C 37.482814 40 33 44.482814 33 50 C 33 53.804475 35.130732 57.118053 38.263672 58.806641 A 0.50005 0.50005 0 1 0 38.736328 57.927734 C 35.917268 56.408322 34 53.431525 34 50 C 34 45.023186 38.023186 41 43 41 C 44.945275 41 46.736321 41.621135 48.208984 42.669922 A 0.50052346 0.50052346 0 0 0 48.791016 41.855469 C 47.157679 40.692255 45.158725 40 43 40 z M 48.818359 56.996094 A 0.50005 0.50005 0 0 0 48.509766 57.105469 C 48.119007 57.409182 47.701499 57.679557 47.261719 57.917969 A 0.50005 0.50005 0 1 0 47.738281 58.796875 C 48.224501 58.533287 48.687806 58.232818 49.123047 57.894531 A 0.50005 0.50005 0 0 0 48.818359 56.996094 z M 45.5 58.658203 A 0.50005 0.50005 0 0 0 45.367188 58.677734 C 44.610953 58.885534 43.820479 59 43 59 C 42.521854 59 42.050255 58.95211 41.578125 58.876953 A 0.50005 0.50005 0 1 0 41.421875 59.863281 C 41.929745 59.944126 42.454146 60 43 60 C 43.913521 60 44.795048 59.87278 45.632812 59.642578 A 0.50005 0.50005 0 0 0 45.5 58.658203 z"></path>
                        </svg>
                        <div class="mx-[29px]"></div>
                        <label for="gcash" class="font-subtitle regular-16 inline-block w-full">Gcash</label><br>
                        <input type="radio" name="downpayment_method" value="Gcash" class="mr-2">
                        
                      </div>
                    </div>

                    <div class="flex justify-end mt-5">
                      <a href="booking.php" class="py-2 px-8 font-subtitle hover:text-green-main">Cancel</a>
                      <button type="submit" name="submit" class="bg-green-nav px-8 py-2 font-subtitle rounded-full text-white hover:bg-gray-800"> Book </button>
                    </div>
                </form>
              </div>
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
