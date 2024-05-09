<?php
	// session_start();

	// $pdo = null;
	// $DB_HOST = 'localhost';
	// $DB_USER = 'root';
	// $DB_PASS = '';
	// $DB_NAME = 'thesis';

	// try {
	// 	$pdo = new PDO('mysql:host=' . $DB_HOST . ';dbname=' . $DB_NAME . ';charset=utf8', $DB_USER, $DB_PASS);
	// } catch (PDOException $exception) {
	// 	exit('Failed to connect to database!');
	// }

?>

<?php
	session_start();

	$pdo = null;
	$DB_HOST = 'localhost';
	$DB_USER = 'root';
	$DB_PASS = '';
	$DB_NAME = 'icp_database';

	try {
		$pdo = new PDO('mysql:host=' . $DB_HOST . ';dbname=' . $DB_NAME . ';charset=utf8', $DB_USER, $DB_PASS);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		
		
		
	} catch (PDOException $exception) {
		exit('Failed to connect to database!');
	}
?>
<?php
$hostname="localhost";
$username="root";
$password="";
$dbname="icp_database";

$conn=mysqli_connect($hostname, $username,$password,$dbname);

?>