<?php 
ini_set('display_errors', 1);

opcache_reset();
opcache_invalidate(1);

include("tools.php"); 
include_once("vendor/autoload.php");
?>
<?php echo topModule("Friend Request Processing Page"); ?>


<?php 
    if (isset($_POST["search"])) {
        $input = $_POST['input'];
        search($input);
    } else if (isset($_POST["send_request"])) {
        $result = $_POST['result'];
        sendRequest($result);
    }
?>

</main>
</body>
</div>
</html>