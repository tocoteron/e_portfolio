<?
require_once("database_connection.php");

if($argc == 3)
{
	$user_id = $argv[1];
	$password = $argv[2];

	var_dump($user_id);
	var_dump($password);

	$stmt = $dbh->prepare("UPDATE users SET password = :password WHERE id = :id");

	$stmt->bindValue(":id", $user_id);
	$stmt->bindValue(":password", password_hash($password, PASSWORD_BCRYPT));

	$stmt->execute();
}
else
{
	echo "Arguments are invalid.\n";
	echo "php password_update.php \$user_id \$new_password\n";
}
?>
