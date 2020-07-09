<?php 
ini_set('display_errors', 1);

opcache_reset();
opcache_invalidate(1);

include("tools.php"); 
include_once("vendor/autoload.php");
?>
<?php echo topModule("New Post Processing Page"); ?>

<ul class="breadcrumb">
    <li><a href="main.php">Main Page</a></li>
    <li><a href="profile.php">Profile</a></li>
</ul> 

<?php 
    $email = $_SESSION["login"]["email"];

    if (isset($_POST["new_post"])) {
        $new_post = $_POST;
        addNewPost($email, $new_post);
    }
?>
</main>
</body>
</div>
</html>