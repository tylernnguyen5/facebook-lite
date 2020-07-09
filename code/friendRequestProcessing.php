<?php 
ini_set('display_errors', 1);

opcache_reset();
opcache_invalidate(1);

include("tools.php"); 
include_once("vendor/autoload.php");
?>
<?php echo topModule("Friend Request Processing Page"); ?>

<?php 
    $recepient = $_SESSION["login"]["email"];
    
    if (isset($_POST["accept"])) {
        $requester = $_POST['requester'];
        acceptFriendRequest($requester);
    } else if (isset($_POST["decline"])){
        $requester = $_POST['requester'];
        declineFriendRequest($requester);
    }
?>

</main>
</body>
</div>
</html>