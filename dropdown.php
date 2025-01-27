<?php
session_start();
include("config.php"); // Make sure this file connects to your database

// Assuming the user's ID is stored in the session after login
$name = $_SESSION['user_name'];

// Fetch appointments for the logged-in user
$query = "SELECT a.id AS appointment_id, a.name, a.doctor, a.department, a.appointment_date, a.status
          FROM appointments a
          WHERE a.name = '$name' 
          ORDER BY a.appointment_date";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Karpagam</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <script>
    function openModal(appointmentId) {
      document.getElementById('appointmentId').value = appointmentId;
      const modal = new bootstrap.Modal(document.getElementById('rescheduleModal'));
      modal.show();
    }
  </script>
</head>

<body class="index-page">

  <header id="header" class="header sticky-top">
    <div class="topbar d-flex align-items-center">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">
          <!-- Uncomment for contact info -->
        </div>
        <div class="social-links d-none d-md-flex align-items-center">
          <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
          <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>
    </div><!-- End Top Bar -->

    <div class="branding d-flex align-items-center">
      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center me-auto">
          <h1 class="sitename">Karpagam</h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="index.html" class="active">Home<br></a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="service.html">Services</a></li>
            <li><a href="department.html">Departments</a></li>
            <li><a href="doctors.html">Doctors</a></li>
            <li class=""><a href="dropdown.php"><span>Appointments Status</span> <i class=""></i></a></li>
            <li><a href="contact.html">Contact</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a class="cta-btn d-none d-sm-block" href="appointment1.php">Make an Appointment</a>
        <a href="logout.php" class="btn btn-danger btn-sm ps-3 ">Logout</a>

      </div>
    </div>
  </header>

  <!-- Status Section -->
  <section id="status" class="status section">
    <div class="container" data-aos="fade-up">
      <div class="section-title">
        <h2>Appointment Status</h2>
        <p>Check the current status of your appointment request.</p>
      </div>

      <!-- Appointment Status Table -->
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Appointment ID</th>
                <th>Patient Name</th>
                <th>Doctor</th>
                <th>Department</th>
                <th>Date & Time</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $serialNumber = 1; while ($appointment = mysqli_fetch_assoc($result)) { ?>
                <tr>
                  <td><?php echo htmlspecialchars($appointment['appointment_id']); ?></td>
                  <td><?php echo htmlspecialchars($appointment['name']); ?></td>
                  <td><?php echo htmlspecialchars($appointment['doctor']); ?></td>
                  <td><?php echo htmlspecialchars($appointment['department']); ?></td>
                  <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                  <td class="<?php echo ($appointment['status'] === 'Accepted') ? 'text-success' : 'text-danger'; ?>">
                    <?php echo htmlspecialchars($appointment['status']); ?>
                  </td>
                  <td>
                    <?php if ($appointment['status'] === 'Accepted') { ?>
                      <button class="btn btn-warning" onclick="openModal(<?php echo $appointment['appointment_id']; ?>)">Reschedule</button>
                    <?php } ?>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section><!-- End Status Section -->

  <!-- Reschedule Modal -->
  <div class="modal fade" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rescheduleModalLabel">Reschedule Appointment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="rescheduleForm" action="reschedule.php" method="POST">
            <input type="hidden" name="appointment_id" id="appointmentId">
            <div class="mb-3">
              <label for="newDate" class="form-label">New Date & Time</label>
              <input type="datetime-local" class="form-control" name="new_date" id="newDate" required>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Reschedule</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <footer id="footer" class="footer light-background">
    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">Karpagam</span>
          </a>
          <div class="footer-contact pt-3">
            <p>Pollachi Main Rd, Ottakkalmandapam</p>
            <p>Tamil Nadu 641032</p>
            <p class="mt-3"><strong>Phone:</strong> <span>075985 09767</span></p>
            <p><strong>Email:</strong> <span>karpagam@gmail.com</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About us</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Terms of service</a></li>
            <li><a href="#">Privacy policy</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><a href="#">Online Booking</a></li>
            <li><a href="#">Doctor Search</a></li>
            <li><a href="#">Appointment Reminders</a></li>
            <li><a href="#">Telemedicine</a></li>
            <li><a href="#">Patient Reviews</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Here is the solution</h4>
          <ul>
            <li><a href="#">We accuse troubles of being lawful</a></li>
            <li><a href="#">We except the most worthy</a></li>
            <li><a href="#">Receives distinction</a></li>
            <li><a href="#">The beloved</a></li>
            <li><a href="#">Let it be what is conceived</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">Karpagam</strong> <span>All Rights Reserved</span></p>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
</body>

</html>
