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

function build_calendar($month, $year)
{
  $mysqli = new mysqli('localhost', 'root', '', 'cms_db');
  $stmt = $mysqli->prepare(
    "SELECT * FROM bookings WHERE MONTH(booking_date) = ? AND YEAR(booking_date) = ?"
  );
  $stmt->bind_param('ss', $month, $year);
  $bookings = [];
  if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $bookings[] = $row['booking_date'];
      }

      $stmt->close();
    }
  }

  $daysOfWeek = [
    'Sunday',
    'Monday',
    'Tuesday',
    'Wednesday',
    'Thursday',
    'Friday',
    'Saturday',
  ];
  $firstDayOfMonth = mktime(0, 0, 0, (int) $month, 1, $year);
  $numberDays = date('t', $firstDayOfMonth);
  $dateComponents = getdate($firstDayOfMonth);
  $monthName = $dateComponents['month'];
  $dayOfWeek = $dateComponents['wday'];

  $datetoday = date('Y-m-d');

  $calendar = "<table class='border border-collapse border-gray-200'>";
  $calendar .= "<h2 class='font-subtitle bold-32 text-center mb-2'>$monthName $year</h2>";
  $calendar .= "<div class='text-center mb-5'>";
  $calendar .=
    "<a class='bg-gray-700 px-2 py-2 font-subtitle rounded-lg text-white hover:bg-gray-800' href='?month=" .
    date('m', mktime(0, 0, 0, (int) $month - 1, 1, $year)) .
    "&year=" .
    date('Y', mktime(0, 0, 0, (int) $month - 1, 1, $year)) .
    "'> <</a> ";
  $calendar .=
    " <a class='bg-green-main px-2 py-2 font-subtitle rounded-lg text-white hover:bg-gray-700 mx-2' href='?month=" .
    date('m') .
    "&year=" .
    date('Y') .
    "'>Current Month</a> ";
  $calendar .=
    "<a class='bg-gray-700 font-subtitle px-2 py-2 rounded-lg text-white hover:bg-gray-800' href='?month=" .
    date('m', mktime(0, 0, 0, (int) $month + 1, 1, $year)) .
    "&year=" .
    date('Y', mktime(0, 0, 0, (int) $month + 1, 1, $year)) .
    "'> > </a><br>";

  $calendar .= "<tr class='border border-collapse border-gray-200'>";
  foreach ($daysOfWeek as $day) {
    $calendar .= "<th class='font-subtitle py-1 bg-green-main border border-collapse border-gray-200'>$day</th>";
  }

  $currentDay = 1;
  $calendar .= "</tr><tr class='border border-collapse border-gray-200 p-5'>";

  if ($dayOfWeek > 0) {
    for ($k = 0; $k < $dayOfWeek; $k++) {
      $calendar .= "<td></td>";
    }
  }

  $month = str_pad((int) $month, 2, "0", STR_PAD_LEFT);

  while ($currentDay <= $numberDays) {
    if ($dayOfWeek == 7) {
      $dayOfWeek = 0;
      $calendar .=
        "</tr><tr class='border border-collapse border-gray-200 p-5'>";
    }

    $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
    $date = "$year-$month-$currentDayRel";

    $dayname = strtolower(date('l', strtotime($date)));
    $eventNum = 0;
    $today = $date == date('Y-m-d') ? "today" : "";
    if ($date < date('Y-m-d')) {
      $calendar .= "<td class='border border-collapse border-gray-200 text-sm px-2 py-2'><h4 class='mb-2'>$currentDay</h4>";
    } elseif (in_array($date, $bookings)) {
      $calendar .= "<td class='$today px-2 py-2'><h4 class='mb-2'>$currentDay</h4> <button class='bg-red-800 px-1.5 py-1 rounded-lg text-white hover:cursor-not-allowed'>Already Booked</button>";
    } else {
      $calendar .=
        "<td class='$today border border-collapse border-gray-200 text-sm px-2 py-2'><h4 class='mb-2'>$currentDay</h4> <a href='booking-form.php?date=" .
        $date .
        "' class='bg-green-main font-subtitle px-1.5 py-1 rounded-lg gap-2 text-white hover:bg-gray-800 flex items-center'> <svg class='w-4 h-4 text-green-nav' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 16 12'>
        <path stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M1 5.917 5.724 10.5 15 1.5'/>
      </svg> Book Now</a>";
    }

    $calendar .= "</td>";
    $currentDay++;
    $dayOfWeek++;
  }

  if ($dayOfWeek != 7) {
    $remainingDays = 7 - $dayOfWeek;
    for ($l = 0; $l < $remainingDays; $l++) {
      $calendar .= "<td class='empty'></td>";
    }
  }

  $calendar .= "</tr>";
  $calendar .= "</table>";
  echo $calendar;
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

  <main class="mt-5 pb-10 mb-5">
    <div class="max-container mt-5 mx-auto">

      <h3 class="font-subtitle bold-32 mb-2 mx-6 lg:mx-0">Booking</h3>
      <div class="w-full h-1 bg-green-main rounded-full mb-5"></div>

      <div class="flex justify-center">
        <div class="w-3/4 mx-auto ">
          <div class="border-0 lg:border-2 border-gray-800 py-2 px-8 mt-5 flex flex-col justify-center">
            <?php
            if (isset($_GET['month']) && isset($_GET['year'])) {
              $month = $_GET['month'];
              $year = $_GET['year'];
            } else {
              $month = date('m');
              $year = date('Y');
            }
            echo build_calendar($month, $year);
            ?>
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
