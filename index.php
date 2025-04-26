<?php
           $page = "Home.php"; // Default page
           $p = "Home"; // Default value for $p
           $checkout = false;
           if(isset($_GET['p'])){
               $p = $_GET['p'];
               switch($p){
                   case 'Product':
                       $page = "Product.php";
                       break;
                   case 'Contact':
                       $page = "Contact.php";
                       break;
                   case 'About':
                       $page = "About.php";
                       break;
                   case 'Shoppingcart':
                       $page = "Shoppingcart.php";
                       break;
                   case 'CheckOut':
                       $page = "CheckOut.php";
                       break;
                   
                   default:
                       $page = "Home.php";
                       break;
               }
           }

?>


<!DOCTYPE html>
<html lang="en">
<?php include 'include/head.php'; ?>
<body class="bg-gray-100">
    <!-- Navbar -->
    <?php include 'include/nav.php'; ?>
    <div id="toast" class="fixed bottom-4 right-4 flex flex-col space-y-2"></div>
    <!-- Slider Section -->
    <?php include 'include/slider.php'; ?>
    <!-- Product Section -->
    <?php include "$page" ?>
   <!-- Footer Section -->
    <?php include 'include/footer.php'; ?>
    <!-- JavaScript -->
    <?php include 'include/script.php'; ?>
</body>
</html>
