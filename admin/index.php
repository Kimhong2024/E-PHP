<?php
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: ./auth/login.php');
            exit();
        }

           $page = "Dashboard.php"; // Default page
           $p = "Dashboard"; // Default value for $p
   
           if(isset($_GET['p'])){
               $p = $_GET['p'];
               switch($p){
                   case 'Product':
                       $page = "Product.php";
                       break;
                   case 'Order':
                       $page = "Order.php";
                       break;
                   case 'Customer':
                       $page = "Customer.php";
                       break;
                   case 'SalesReports':
                       $page = "SalesReports.php";
                       break;
                   case 'Coupons':
                       $page = "Coupons.php";
                       break;
                   case 'Payment':
                       $page = "Payment.php";
                       break;
                   case 'Shipping':
                       $page = "Shipping.php";
                       break;
                   case 'Reviews':
                       $page = "Reviews.php";
                       break;
                   case 'UserRoles':
                       $page = "UserRoles.php";
                       break;
                   case 'Settings':
                       $page = "Settings.php";
                       break;
                   default:
                       $page = "Dashboard.php";
                       break;
               }
           }

?>

<!DOCTYPE html>
<html lang="en">
 
 <?php include 'include/head.php'; ?>

  <body>
    <div class="wrapper">
      <!-- Sidebar -->
       <?php include 'include/sidebar.php'; ?>
      <!-- End Sidebar -->

      <div class="main-panel">
        <?php include 'include/header.php'; ?>

        <?php include "$page" ?>

       <?php include 'include/foot.php'; ?>
      </div>
    </div>
    <?php include 'include/footer.php'; ?>
    <script src="assets/js/customer.js"></script>
    <script src="assets/js/product.js"></script>
    <script src="assets/js/order.js"></script>
    <script src="assets/js/salesreport.js"></script>
    <script src="assets/js/coupons.js"></script>
    <script src="assets/js/payments.js"></script>
    <script src="assets/js/reviews.js"></script>
    <script src="assets/js/userroles.js"></script>
    <script src="assets/js/settings.js"></script>
  </body>
</html>
