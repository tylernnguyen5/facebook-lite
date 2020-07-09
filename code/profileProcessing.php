<?php 
ini_set('display_errors', 1);

opcache_reset();
opcache_invalidate(1);

include("tools.php"); 
include_once("vendor/autoload.php");
?>
<?php echo topModule("Profile Processing Page"); ?>


<?php 
    $email = $_SESSION["login"]["email"];

    if (isset($_POST["update"])) {
        $update = $_POST;
        updateAccount($email, $update);
    } 
?>
</main>
</body>
</div>
</html>