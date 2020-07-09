<?php 
ini_set('display_errors', 1);

opcache_reset();
opcache_invalidate(1);

include("tools.php"); 
include_once("vendor/autoload.php");
?>
<?php echo topModule("Login Page"); ?>


<ul class="breadcrumb">
    <li><a href="index.php">Login</a></li>
    <li><a href="newUser.php">Sign Up</a></li>
</ul> 

<main>

<div class="container, text-center">
    <h1>Login</h1>

    <form class="form-horizontal" action="main.php" method="post">
        <div class="form-group">
            <label class="control-label col-sm-6" for="email">Email:</label>
            <div class="col-sm-3">
                <input type="email" class="form-control" name="email" placeholder="Enter email" required>
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label col-sm-6" for="pwd">Password:</label>
            <div class="col-sm-3">
                <input type="password" class="form-control" name="pwd" placeholder="Enter password" required>
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default" name="login" value="login">Login</button>
            </div>
        </div>
    </form> 
    
</div>

</main>
</body>
</div>
</html>