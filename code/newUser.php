<?php 
ini_set('display_errors', 1);

opcache_reset();
opcache_invalidate(1);

include("tools.php"); 
include_once("vendor/autoload.php");
?>
<?php echo topModule("Sign Up Page"); ?>

<ul class="breadcrumb">
    <li><a href="index.php">Login</a></li>
    <li><a href="newUser.php">Sign Up</a></li>
</ul> 

<main>

<div class="container, text-center">
    <h1>Sign Up</h1>

    <form class="form-horizontal" action="newUserProcessing.php" method="post">
        <!-- Email -->
        <div class="form-group">
            <label class="control-label col-sm-6" for="email">Email:</label>
            <div class="col-sm-3">
                <input type="email" class="form-control" name="email" placeholder="Enter email" required>
            </div>
        </div>

        <!-- Full-name -->
        <div class="form-group">
            <label class="control-label col-sm-6" for="fname">Full-name:</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" name="fname" placeholder="Enter full-name">
            </div>
        </div>

        <!-- Screen-name -->
        <div class="form-group">
            <label class="control-label col-sm-6" for="sname">Screen-name:</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" name="sname" placeholder="Enter screen-name">
            </div>
        </div>

        <!-- DOB -->
        <div class="form-group">
            <label class="control-label col-sm-6" for="dob">Date of Birth:</label>
            <div class="col-sm-3">
                <input type="date" class="form-control" name="dob" placeholder="Enter your Date of Birth">
            </div>
        </div>

        <!-- Gender-->
        <div class="form-group">
            <label class="control-label col-sm-6" for="gender">Gender:</label>
            <div class="col-sm-3">
                <select name="gender">
                    <option></option>
                    <option value="female">Female</option>
                    <option value="male">Male</option>
                    <option value="other">Other</option>
                </select>
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
                    <option value="public">Public</option>
                    <option value="friend">Friend-only</option>
                    <option value="private">Private</option>
                </select>
            </div>
        </div>

        <!-- Submit button -->
        <button type="submit" class="btn btn-default" name="new_user" value="new user">Sign Up</button>
    </form> 
    
</div>

</main>
</body>
</div>
</html>