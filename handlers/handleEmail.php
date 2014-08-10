<?PHP
/* Submit Email to be Notified
 * Version: 7 July 2013
 * Author: Ruben Martinez Jr.
 */
$email = $_GET['email'];
if($email == null) $email = "empty";

$mysqli = new mysqli("BowdoinStrangers.db.11389874.hostedresource.com", "BowdoinStrangers", "DwS1029Rox!", "BowdoinStrangers");
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
/* create a prepared statement */
if (($stmt = $mysqli->prepare("INSERT INTO dbEmail (email) VALUES (?)"))) {
    /* bind parameters for markers */
    $stmt->bind_param("s", $email);
    /* close statement */
    $stmt->close();
    /* close connection */
    $mysqli->close();
    header('Location: http://DiningWithStrangers.co/?offmail=t');
    exit();
} 
else {
    header('Location: http://DiningWithStrangers.co/?offmail=f');
    exit();
}
?>