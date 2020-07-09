<?php
ini_set('display_errors', 1);

opcache_reset();
opcache_invalidate(1);

session_start();

include_once("vendor/autoload.php");

if (isset($_POST["login"])) $_SESSION["login"] = $_POST;
else if (isset($_POST["cancel"])){
  unset($_SESSION['login']);
  header("Location: index.php");
}

/* Database functions */ 

// Credentials check for login
function credentialsCheck($email){
  try {
    $client = new MongoDB\Client("mongodb://192.168.0.172:27017");
    
    $collection = $client->FACEBOOK_LITE->MEMBER; // MEMBER collection
    
    // Check if the login email exists
    $count = $collection->countDocuments(['email' => $email]);
    if ($count == 1){ // if exists
      echo "Login as: " . $email . "\n"; 
    } else {
      header("Location: index.php"); // return to login page
    }

  } catch (MongoDB\Driver\Exception\Exception $e) {
    $filename = basename(__FILE__);

    echo "The $filename script has experienced an error.\n";
    echo "It failed with the following exception:\n";

    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";
  }
}


// Update account
function updateAccount($email, $update){
  try {
    $client = new MongoDB\Client("mongodb://192.168.0.172:27017");
    
    $collection = $client->FACEBOOK_LITE->MEMBER; // MEMBER collection
    
    // New data
    $new_data = array(
      '$set' => array(
        'sname' => $update['sname'],
        'status' => $update['status'],
        'location' => $update['location'],
        'visibility' => $update['visibility']
      )
    );

    // Update user information
    $updateOneResult = $collection->updateOne(
      array('email' => $email), // Criteria
      $new_data
    );

    // Check if updated
    if ($updateOneResult->getModifiedCount() == 1) {
      header("Location: main.php"); // Redirect to home page
    } else echo "ERROR";
    
  } catch (MongoDB\Driver\Exception\Exception $e) {
    $filename = basename(__FILE__);

    echo "The $filename script has experienced an error.\n";
    echo "It failed with the following exception:\n";

    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";
  }
}


// Create new user
function createNewUser($new_user){
  try {
    $client = new MongoDB\Client("mongodb://192.168.0.172:27017");
    
    $collection = $client->FACEBOOK_LITE->MEMBER; // MEMBER collection

    // Create new user
    $insertOneResult = $collection->insertOne([
      'email' => $new_user['email'],
      'fname' => $new_user['fname'],
      'sname' => $new_user['sname'],
      'dob' => $new_user['dob'],
      'gender' => $new_user['gender'],
      'status' => $new_user['status'],
      'visibility' => $new_user['visibility'],
      'location' => $new_user['location'],
      'friend_requests' => array(),
      'friends' => array()
    ]);
    
    // Storing login information and log in with the newly create account
    $_SESSION['login']['_id'] = $insertOneResult->getInsertedId();
    $_SESSION['login']['email'] = $new_user['email'];
    header("Location: main.php");
    
  } catch (MongoDB\Driver\Exception\Exception $e) {
    $filename = basename(__FILE__);

    echo "The $filename script has experienced an error.\n";
    echo "It failed with the following exception:\n";

    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";
  }
}


// Add new post
function addNewPost($email, $new_post){
  try {
    $client = new MongoDB\Client("mongodb://192.168.0.172:27017");
    
    $collection = $client->FACEBOOK_LITE->POST_COMMENT; // POST_COMMENT collection

    // Create new post
    $insertOneResult = $collection->insertOne([
      'member_email' => $email,
      'timestamp' => new MongoDB\BSON\UTCDateTime(),
      'body' => $new_post['body'],
      'parent' => null, // Post doesn't have parent ID. Comment does have parent ID
      'comments' => array(),
      'likes' => array()
    ]);
    
    // Reload home page after adding new post
    header("Location: main.php");
    
  } catch (MongoDB\Driver\Exception\Exception $e) {
    $filename = basename(__FILE__);

    echo "The $filename script has experienced an error.\n";
    echo "It failed with the following exception:\n";

    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";
  }
}


// Get all posts in ASC timestamp order
function loadAllPosts(){
  try {
    $client = new MongoDB\Client("mongodb://192.168.0.172:27017");
    
    $collection = $client->FACEBOOK_LITE->POST_COMMENT; // POST_COMMENT collection
    
    // Retrieve data
    $cursor = $collection->find(
      [
        'parent' => null // Find posts that its 'parent' is null. Because only comments have parent's postID
      ]
    );
    
    // Parse cursor
    $iterator = new \IteratorIterator($cursor);
    $iterator->rewind();

    while($doc = $iterator->current()){
      echo postModule($doc);

      $iterator->next();
    }

  } catch (MongoDB\Driver\Exception\Exception $e) {
    $filename = basename(__FILE__);

    echo "The $filename script has experienced an error.\n";
    echo "It failed with the following exception:\n";

    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";
  }
}


// Add new comment/response
function addNewComment($email, $new_comment){
  try {
    $client = new MongoDB\Client("mongodb://192.168.0.172:27017");
    
    $collection = $client->FACEBOOK_LITE->POST_COMMENT; // POST_COMMENT collection

    // Create new comment
    $body = $new_comment['body'];
    $postID = $new_comment['postID'];

    $insertOneResult = $collection->insertOne([
      'member_email' => $email,
      'timestamp' => new MongoDB\BSON\UTCDateTime(),
      'body' => $body,
      'parent' => new MongoDB\BSON\ObjectId($postID), // Post doesn't have parent ID. Comment does have parent ID
    ]);
    
    // Reload home page after adding new post
    header("Location: main.php");
  } catch (MongoDB\Driver\Exception\Exception $e) {
    $filename = basename(__FILE__);

    echo "The $filename script has experienced an error.\n";
    echo "It failed with the following exception:\n";

    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";
  }
}


// Add new like
function addNewLike($email, $new_like){
  try {
    $client = new MongoDB\Client("mongodb://192.168.0.172:27017");
    
    $collection = $client->FACEBOOK_LITE->POST_COMMENT; // POST_COMMENT collection

    /* 
    * Add like to post/comment with the user email
    * 
    * We use $new_like['postID'] to find the post in the POST_COMMENT collection 
    * Then we add the $email of the liker to the 'likes' array
    *
    */
    $postID = $new_like['postID'];

    $updateOneResult = $collection->updateOne(
      [
        '_id' => new MongoDB\BSON\ObjectId($postID)
      ],
      [
        '$addToSet' => ['likes' => $email]
      ]
    );
    
    // Reload home page after adding new post
    header("Location: main.php");
    
  } catch (MongoDB\Driver\Exception\Exception $e) {
    $filename = basename(__FILE__);

    echo "The $filename script has experienced an error.\n";
    echo "It failed with the following exception:\n";

    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";
  }
}


// Accept friend request 
function acceptFriendRequest($requester){
  try {
    $client = new MongoDB\Client("mongodb://192.168.0.172:27017");
    
    $collection = $client->FACEBOOK_LITE->MEMBER; // MEMBER collection

    // Accept friend request and add new friend for document in member collection
    $recepient = $_SESSION['login']['email'];

    $updateOneResult = $collection->updateOne(
      [
        'email' => $recepient // Recipient's email
      ],
      [
        '$addToSet' => ['friends' => $requester],  // update new friend for the document
        '$pull' => ['friend_requests' => $requester] // remove requests after accepting
      ]
    );
    
    // Reload home page after adding new post
    header("Location: main.php");
    
  } catch (MongoDB\Driver\Exception\Exception $e) {
    $filename = basename(__FILE__);

    echo "The $filename script has experienced an error.\n";
    echo "It failed with the following exception:\n";

    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";
  }
}


// Decline friend request
function declineFriendRequest($requester){
  try {
    $client = new MongoDB\Client("mongodb://192.168.0.172:27017");
    
    $collection = $client->FACEBOOK_LITE->MEMBER; // MEMBER collection

    // Decline friend request
    $recepient = $_SESSION['login']['email'];

    $updateOneResult = $collection->updateOne(
      [
        'email' => $recepient // Recipient's email
      ],
      [
        '$pull' => ['friend_requests' => $requester] // remove requests after declining
      ]
    );
    
    // Reload home page after adding new post
    header("Location: main.php");
    
  } catch (MongoDB\Driver\Exception\Exception $e) {
    $filename = basename(__FILE__);

    echo "The $filename script has experienced an error.\n";
    echo "It failed with the following exception:\n";

    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";
  }
}


// Search result
function search($input){
  try {
    $client = new MongoDB\Client("mongodb://192.168.0.172:27017");
    
    $collection = $client->FACEBOOK_LITE->MEMBER; // MEMBER collection
    
    // Retrieve data
    $findOneResult = $collection->findOne(
      [
          'email' => $input
      ]
    );  

    $result = $findOneResult->email;

  } catch (MongoDB\Driver\Exception\Exception $e) {
    $filename = basename(__FILE__);

    echo "The $filename script has experienced an error.\n";
    echo "It failed with the following exception:\n";

    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";
  }

  // Display result on page
  $html = <<<"HTML"
  <div class="container">
  <b><h2>Search Result: \n</h2></b>
  <form  action="friendSearchProcessing.php" method="post">
    <h4>$result</h4> 
    <input type="hidden" name="result" value=$result />
    <button type="submit" class="btn btn-default" name="send_request" value="Send request">Send friend request</button>
  </form>
  </div>
HTML;
  echo $html;
}


// Send friend request
function sendRequest($recepient){
  try {
    $client = new MongoDB\Client("mongodb://192.168.0.172:27017");
    
    $collection = $client->FACEBOOK_LITE->MEMBER; // MEMBER collection

    $requester = $_SESSION['login']['email'];

    // Send friend request in member collection
    $updateOneResult = $collection->updateOne(
      [
        'email' => $recepient
      ],
      [
        '$addToSet' => ['friend_requests' => $requester] 
      ]
    );
    
    // Reload home page after adding new post
    header("Location: main.php");
    
  } catch (MongoDB\Driver\Exception\Exception $e) {
    $filename = basename(__FILE__);

    echo "The $filename script has experienced an error.\n";
    echo "It failed with the following exception:\n";

    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";
  }
}

// Get the number of likes on a post
function numOfLikes($postID){
  try {
    $client = new MongoDB\Client("mongodb://192.168.0.172:27017");
  
    $collection = $client->FACEBOOK_LITE->POST_COMMENT; // POST_COMMENT collection

    // Find the post in the collection
    $result = $collection->findOne(
      [
          '_id' => new MongoDB\BSON\ObjectId($postID)
      ]
    );
    
    // Count the likes
    $num = $result->likes->count();
    
    return $num;
  }
  catch (MongoDB\Driver\Exception\Exception $e) {
  
      $filename = basename(__FILE__);
  
      echo "The $filename script has experienced an error.\n";
      echo "It failed with the following exception:\n";
  
      echo "Exception:", $e->getMessage(), "\n";
      echo "In file:", $e->getFile(), "\n";
      echo "On line:", $e->getLine(), "\n";
  }
}


// Comments on post 
function commentsOnPost($postID){
  $html = "";

  try {
    $client = new MongoDB\Client("mongodb://192.168.0.172:27017");
    
    $collection = $client->FACEBOOK_LITE->POST_COMMENT; // POST_COMMENT collection
    
    // Retrieve data
    $cursor = $collection->find(
      [
        'parent' => new MongoDB\BSON\ObjectId($postID) // find comments where parent's postID = postID
      ]
    );
    
    // Parse cursor
    $iterator = new \IteratorIterator($cursor);
    $iterator->rewind();

    while($doc = $iterator->current()){
      $body = $doc->body;
      $commenter = $doc->member_email;

      $html = $html . commentModule($body, $commenter); // displaying comments of the post

      $iterator->next();
    }

    
  } catch (MongoDB\Driver\Exception\Exception $e) {
    $filename = basename(__FILE__);

    echo "The $filename script has experienced an error.\n";
    echo "It failed with the following exception:\n";

    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";
  }
  
  return $html;
}

/* Modules */

// Post module
function postModule($post){
  $email = $post->member_email;
  $body = $post->body;

  $postID = $post->_id;
  $likes = numOfLikes($postID);
  $comments = commentsOnPost($postID);

  $html = <<<"HTML"
  <div class="media">
    <div class="media-body">
      <h2 class="media-heading">$email - $likes like(s)</h2>
      <p>$body</p>

      <form action="newLikeCommentProcessing.php" method="post">
        <div class="form-group">
            <label for="comment_body"></label>
            <textarea class="form-control" rows="1" name="body" placeholder="Your thought ..."></textarea>
        </div>

        <input type="hidden" name="postID" value=$postID />

        <button type="submit" class="btn btn-default" name="new_like" value="Like">Like</button>
        <button type="submit" class="btn btn-default" name="new_comment" value="Comment">Comment</button>
      </form>

      <h4>Comments</h4>
      $comments
      <hr>
    </div>
  </div>
HTML;
    return $html;
}


// Comment module
function commentModule($body, $commenter){
  $html = <<<"HTML"
  <h4>$commenter</h4>
  $body
HTML;
  return $html;
}


// Top module of the website
function topModule($pageTitle){
  $html = <<<"HTML"
  <!DOCTYPE html>
    <html lang='en'>
    <div class="container">
    <head>
      <meta charset="utf-8">
      <title>$pageTitle</title>

      <!-- Boostrap setup -->
      <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

      <!-- jQuery library -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

      <!-- Latest compiled JavaScript -->
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> 

      <!-- Page font -->
      <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    
      <!-- CSS -->
      <link type="text/css" rel="stylesheet" href="css/style.css">
    </head>

    <body>
    <div class="jumbotron, text-center">
      <h1>FACEBOOK LITE</h1>      
    </div>

HTML;
  return $html;
}


// Left sidebar module - display user information
function leftSidebarModule($email){
  try {
    $client = new MongoDB\Client("mongodb://192.168.0.172:27017");
    
    $collection = $client->FACEBOOK_LITE->MEMBER; // MEMBER collection
    
    // Retrieve data
    $findOneResult = $collection->findOne(
      [
        'email' => $email
      ]
    );  

    $sname = $findOneResult->sname;
    $dob = $findOneResult->dob;
    $gender = $findOneResult->gender;
    $status = $findOneResult->status;
    $location = $findOneResult->location;
    $visibility = $findOneResult->visibility;

  } catch (MongoDB\Driver\Exception\Exception $e) {
    $filename = basename(__FILE__);

    echo "The $filename script has experienced an error.\n";
    echo "It failed with the following exception:\n";

    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";
  }

  // Display HTML left side bar
  $html = <<<"HTML"
  <div class="leftsidenav sidenav">
    <a>Welcome $sname</a>
    <br><br>
    
    <ul>
      <li>DoB: $dob</li>
      <li>Gender: $gender</li>
      <li>Status: $status</li>
      <li>Location: $location</li>
      <li>Visibility: $visibility</li>
    </ul>

    <hr>
    <a href="profile.php">Manage Your Profile</a>
  </div>
HTML;
  return $html;
}


// Rightt sidebar module - display friend request list, friends list and friend search box
function rightSidebarModule(){
  $requestList = friendRequestsList(); // declared below
  $friendList = friendList(); // declared below

  $html = <<<"HTML"
  <div class="rightsidenav sidenav">
    <a>Friend Requests</a>
    <ul>$requestList</ul>
    
    <hr>
    
    <a>Your Friends</a>
    <ul>$friendList</ul>
    
    <hr>
    
    <form action="friendSearchProcessing.php" method="post">
        <div class="form-group">
            <input class="form-control" type="text" name="input" placeholder="Search for ..."></input>
        </div>

        <button type="submit" class="btn btn-default" name="search" value="Friend search">Search</button>
    </form>
  </div>
HTML;
  return $html;
}


// Friend reqests list
function friendRequestsList(){
  $html ="";

  try {
    $client = new MongoDB\Client("mongodb://192.168.0.172:27017");
    
    $collection = $client->FACEBOOK_LITE->MEMBER; // MEMBER collection

    $email = $_SESSION['login']['email'];

    // Finding user in the collection
    $findOneResult = $collection->findOne(
        [
          'email' => $email
        ]
    );

    // Display friend request array element
    foreach ($findOneResult->friend_requests as $requester) {
      $html = $html . friendRequestItem($requester);
    }
    
  } catch (MongoDB\Driver\Exception\Exception $e) {
    $filename = basename(__FILE__);

    echo "The $filename script has experienced an error.\n";
    echo "It failed with the following exception:\n";

    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";
  }

  return $html;
}


// Friend list
function friendList(){
  $html ="";

  try {
    $client = new MongoDB\Client("mongodb://192.168.0.172:27017");
    
    $collection = $client->FACEBOOK_LITE->MEMBER; // MEMBER collection

    $email = $_SESSION['login']['email'];

    // Finding user in the collection
    $findOneResult = $collection->findOne(
        [
          'email' => $email
        ]
    );

    // Display friends array element
    foreach ($findOneResult->friends as $friend) {
      $html = $html . "<li>". $friend . "</li>";
    }
    
    
  } catch (MongoDB\Driver\Exception\Exception $e) {
    $filename = basename(__FILE__);

    echo "The $filename script has experienced an error.\n";
    echo "It failed with the following exception:\n";

    echo "Exception:", $e->getMessage(), "\n";
    echo "In file:", $e->getFile(), "\n";
    echo "On line:", $e->getLine(), "\n";
  }
  
  return $html;
}


// Friend request item module with accept and decline buttons
function friendRequestItem($requester){
  $html = <<<"HTML"
  <form  action="friendRequestProcessing.php" method="post">
    <li>$requester
      <input type="hidden" name="requester" value=$requester />
      <button type="submit" class="btn btn-default" name="accept" value="Accept request">Accecpt</button>
      <button type="submit" class="btn btn-default" name="decline" value="Decline request">Decline</button>
    </li>
  </form>
HTML;
  return $html;
}


// Print data and shape/structure of data - for debugging in case needed
function preShow( $arr, $returnAsString=false ) {
  $ret  = '<pre>' . print_r($arr, true) . '</pre>';
  if ($returnAsString)
    return $ret;
  else 
    echo $ret; 
}


// Print my source code - for debugging in case needed
function printMyCode() {
  echo "<div class='container'>";
  $lines = file($_SERVER['SCRIPT_FILENAME']);
  echo "<pre class='mycode'>\n";
  foreach ($lines as $lineNo => $lineOfCode)
    printf("%3u: %1s \n", $lineNo, rtrim(htmlentities($lineOfCode)));
  echo "</pre></div>";
}

?>