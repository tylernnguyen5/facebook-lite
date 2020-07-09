<?php 
ini_set('display_errors', 1);

opcache_reset();
opcache_invalidate(1);

include("tools.php"); 
include_once("vendor/autoload.php");
?>

<?php echo topModule("Main Page"); ?>


<ul class="breadcrumb">
    <li><a href="main.php">Main Page</a></li>
    <li><a href="profile.php">Profile</a></li>
    <li><a href="index.php">Log Out</a></li>
</ul> 

<main>


<?php 
  $email = $_SESSION['login']['email'];
  credentialsCheck($email);
?>


<!-- Left sidebar -->
<?php 
  $email = $_SESSION["login"]["email"];
  echo leftSidebarModule($email);
?>

<!-- Right sidebar -->
<?php echo rightSidebarModule(); ?>


<!-- New post text box -->
<div class="container">
    <h2>Create new post</h2>
    <form action="newPostProcessing.php" method="post">
        <div class="form-group">
            <label for="post_body">New post:</label>
            <textarea class="form-control" rows="5" name="body"></textarea>
        </div>

        <button type="submit" class="btn btn-default" name="new_post" value="Create new post">Create new post</button>
    </form>
</div>


<!-- Posts -->
<div class="container">
  <h1>Posts</h1><hr>

  <?php loadAllPosts(); ?>

</div>

</main>
</body>
</div>
</html>