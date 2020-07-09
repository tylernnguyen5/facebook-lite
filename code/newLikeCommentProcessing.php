<?php 
ini_set('display_errors', 1);

opcache_reset();
opcache_invalidate(1);

include("tools.php"); 
include_once("vendor/autoload.php");
?>
<?php echo topModule("New Like/Comment Processing Page"); ?>

<?php 
    $email = $_SESSION["login"]["email"];

    if (isset($_POST["new_comment"])) {
        $new_comment = $_POST;
        addNewComment($email, $new_comment);
    } else if (isset($_POST["new_like"])){
        $new_like = $_POST;
        addNewLike($email, $new_like);
    }
?>
</main>
</body>
</div>
</html>