<?php
include("config.php");
session_start();

// Assuming doctor name is stored in the session after login
$doctor_name = $_SESSION['doctor_name'];

// Fetch appointments for the logged-in doctor
$query = "SELECT * FROM appointments WHERE doctor = '$doctor_name' ORDER BY appointment_date";
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
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

</head>

<body class="index-page">

  <header id="header" class="header sticky-top">

    <div class="topbar d-flex align-items-center">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">
          <!-- <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:contact@example.com">contact@example.com</a></i>
          <i class="bi bi-phone d-flex align-items-center ms-4"><span>+1 5589 55488 55</span></i> -->
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
          <!-- Uncomment the line below if you also wish to use an image logo -->
          <!-- <img src="assets/img/logo.png" alt=""> -->
          <h1 class="sitename">Karpagam</h1>
        </a>
        <div class="branding">
        <h1></h1>
        <?php echo $_SESSION['doctor_name']; ?>
        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>

</header>

<main class="main">

    <section>
        <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Appointments</h2>
      <p>"Wherever the art of medicine is loved, there is also a love of humanity."</p>
    </div><!-- End Section Title -->

        <div class="container my-3">
            <!-- <h2 class="text-center"></h2> -->
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Patient Name</th>
                    <th>Patient Email</th>
                    <th>Patient Contact</th>
                    <th>Appointment Date</th>
                    <th>Message</th>
                  
                    <th>Action</th>
                    <th>Rescheduled</th>
                </tr>
                </thead>
                <tbody>
                <?php
                // $serialNumber = 1;
                while ($appointment = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                    <td><?php echo htmlspecialchars($appointment['id']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['email']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['phone']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['message']); ?></td>
                        
                        <td>
                            <?php if ($appointment['status'] === 'Pending'): ?>
                                <button class="btn btn-success btn-sm" onclick="openConfirmationModal(<?php echo $appointment['id']; ?>, 'Accepted')">Accept</button>
                                <button class="btn btn-danger btn-sm" onclick="openConfirmationModal(<?php echo $appointment['id']; ?>, 'Rejected')">Reject</button>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($appointment['status']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                          <?php if($appointment['reschedule']==='false'):?>
                                <p>No</p>
                            <?php else: ?>
                              <p>Yes</p>
                              <?php endif; ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to <span id="actionText"></span> this appointment?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmButton">Confirm</button>
                </div>
            </div>
        </div>
    </div>

</main>
      
<footer id="footer" class="footer light-background">

<div class="container footer-top">
  <div class="row gy-4">
    <div class="col-lg-4 col-md-6 footer-about">
      <a href="index.html" class="logo d-flex align-items-center">
        <span class="sitename">Karpagam</span>
      </a>
      <div class="footer-contact pt-3">
        <p>Pollachi Main Rd, Ottakkalmandapam</p>
        <p> Tamil Nadu 641032</p>
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
      <!-- <div id="preloader"></div> -->
      
   <!-- Vendor JS Files -->
   <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
      <script src="assets/vendor/php-email-form/validate.js"></script>
      <script src="assets/vendor/aos/aos.js"></script>
      <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
      <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
      <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    
      <!-- Main JS File -->
      <script src="assets/js/main.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let appointmentId;
    let appointmentStatus;

    function openConfirmationModal(id, status) {
        appointmentId = id;
        appointmentStatus = status;
        document.getElementById('actionText').innerText = status.toLowerCase();
        $('#confirmationModal').modal('show');
    }

    document.getElementById('confirmButton').addEventListener('click', function () {
        window.location.href = 'update_status.php?id=' + appointmentId + '&status=' + appointmentStatus;
    });
</script>
</body>
</html>
