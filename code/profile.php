<?php 
ini_set('display_errors', 1);

opcache_reset();
opcache_invalidate(1);

include("tools.php"); 
include_once("vendor/autoload.php");
?>
<?php echo topModule("Profile Page"); ?>

<ul class="breadcrumb">
    <li><a href="main.php">Main Page</a></li>
    <li><a href="profile.php">Profile</a></li>
</ul>

<main>

<div class="container, text-center">
    <form class="form-horizontal" action="profileProcessing.php" method="post">
        <!-- Screen-name -->
        <div class="form-group">
            <label class="control-label col-sm-6" for="sname">Screen-name:</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" name="sname" placeholder="Enter screen-name">
            </div>
        </div>

        <!-- Status -->
        <div class="form-group">
            <label class="control-label col-sm-6" for="status">Status:</label>
            <div class="col-sm-3">
                <select name="status">
                    <option></option>
                    <option value="single">Single</option>
                    <option value="married">Married</option>
                    <option value="divorced">Divorced</option>
                </select>
            </div>
        </div>

        <!-- Location -->
        <div class="form-group">
            <label class="control-label col-sm-6" for="location">Location:</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" name="location" placeholder="Enter location">
            </div>
        </div>
        
        <!-- Visbility level -->
        <div class="form-group">
            <label class="control-label col-sm-6" for="visibility">Visibility Level:</label>
            <div class="col-sm-3">
                <select name="visibility">
                    <option></option>
                    <option value="public">Public</option>
                    <option value="friend-only">Friend-only</option>
                    <option value="private">Private</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default" name='update' value='update account'>Update</button>
            </div>
        </div>
    </form> 
    
</div>
</main>
</body>
</div>
</html>