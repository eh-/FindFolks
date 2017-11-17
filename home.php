<?php
//check for session
session_start();
if(isset($_SESSION['username'])) {

}
else {
    header("Location: /index.php");
    die();
}

if(isset($_SESSION['username'])) {
    $user = $_SESSION['username'];
    echo "Welcome $user!";
}

?>

<!DOCTYPE html>
<html>
<body>
    <a href="/phpScripts/logout.php">Logout</a>
    <br><br>
    <a href="/createGroup.php">Create Group</a>
    <br><br>
    <a href="/viewGroups.php">View Groups</a>
    <br><br>
    <a href="/phpScripts/viewMyUpcoming.php">View My Upcoming Events</a>
    <br><br>
    <a href="/signupEvent.php">Sign Up for Event</a>
    <br><br>
    <a href="/searchInterest.php">Search For Events of Interest</a>
    <br><br>
    <a href="/rateEvent.php">Rate Past Events</a>
    <br><br>
    <a href="/phpScripts/displayAvgRatings.php">See Average Ratings For Events In Groups You Are In</a>
    <br><br>
    <a href="/createEvent.php">Create Event</a>
    <br><br>
    <a href="/addInterest.php">Add an Interest</a>
    <br><br>
    <a href="/joinGroup.php">Join a Group</a>
    <br><br>
    <a href="/friends.php">Friends</a>
    <br><br>
    <a href="/recommendEvent.php">Recommend an Event to a Friend</a>
    <br><br>
    <a href="/viewRecommendations.php">View My Recommendations</a>
    <br><br>
    <a href="/changePassword.php">Change Your Password</a>
    <br><br>

  <?php
    echo "<h3> Select Interests to see Groups </h3>";
    include './phpScripts/displayInterests.php';
  
    echo "<h3> Events for the Next Three Days </h3>";
    include './phpScripts/displayEvents.php';
  ?>
  <br>

</body>
</html>
