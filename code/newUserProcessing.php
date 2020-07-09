<?php 
ini_set('display_errors', 1);

opcache_reset();
opcache_invalidate(1);

include("tools.php"); 
include_once("vendor/autoload.php");
?>
<?php echo topModule("New User Processing Page"); ?>

<?php 
    if (isset($_POST["new_user"])){
        $new_user = $_POST;
        createNewUser($new_user);
    } 
?>
</main>
</body>
</div>
</html>