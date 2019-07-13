<?
require_once('database_connection.php');
require_once('functions.php');

session_start();

// ログイン状態チェック
if (!isset($_SESSION["user_id"]))
{
    header("Location: signin.php");
    exit;
}
else
{
	$user_id = $_SESSION["user_id"];
	$class_id = $_SESSION["class_id"];
}

// メッセージの初期化
$error_message= "";
$success_message = "";

// Updateボタンが押された場合
if (isset($_POST["settings"]))
{
	if (empty($_POST["user_id"]))
	{
		$error_message = "User ID is not entered.";
	}
	else if (empty($_POST["password"]))
	{
		$error_message = "Passowrd is not entered.";
	}
	else if (empty($_POST["student_id"]))
	{
		$error_message = "Student ID (Number) is not entered.";
	}
	else if (empty($_POST["name"]))
	{
		$error_message = "Name is not entered.";
	}
	else if (empty($_POST["class_id"]))
	{
		$error_message = "Class ID is not entered.";
	}

	if (!$error_message)
	{
		$user_id = htmlspecialchars($_POST["user_id"]);
		$password = $_POST["password"];
		$student_id = htmlspecialchars($_POST["student_id"]);
		$name = htmlspecialchars($_POST["name"]);
		$class_id = htmlspecialchars($_POST["class_id"]);

		$stmt = $dbh->prepare("SELECT * FROM users WHERE id = :id");
		$stmt->bindValue(":id", $user_id);
		$stmt->execute();

		if(!$stmt->fetch(PDO::FETCH_ASSOC))
		{
			$error_message = "User ID is not found";
		}
		else
		{
			$stmt = $dbh->prepare("UPDATE users SET password = :password, student_id = :student_id, name = :name, permission_level = :permission_level, class_id = :class_id WHERE id = :id");

			$stmt->bindValue(":id", $user_id);
			$stmt->bindValue(":password", password_hash($password, PASSWORD_BCRYPT));
			$stmt->bindValue(":student_id", $student_id);
			$stmt->bindValue(":name", $name);
			$stmt->bindValue(":permission_level", 0);
			$stmt->bindValue(":class_id", $class_id);

			$stmt->execute();

			login($dbh, $user_id);

			$success_message = "User information has updated.";
		}
	}
}

?>

<? require_once('header.php'); ?>
<? $page = basename(__FILE__); require_once('sidebar.php'); ?>

<main class="col-md-10 ml-sm-auto" role="main">
	<form class="form-settings" action="" method="post">
		<div class="text-center mb-4">
			<h1 class="h3 mb-3 font-weight-normal">Account settings</h1>
		</div>

<?
if($error_message)
{
	echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
}
else if($success_message)
{
	echo '<div class="alert alert-success" role="alert">' . $success_message . '</div>';
}
?>

		<div class="form-label-group">
			<input type="text" id="input-user-id" class="form-control" name="user_id" placeholder="User ID" value="<?= htmlspecialchars($_SESSION["user_id"]) ?>" maxlength="16" required readonly>
			<label for="input-user-id">User ID</label>
		</div>
		
		<div class="form-label-group">
			<input type="password" id="input-password" class="form-control" name="password" placeholder="Password" value="" maxlength="16" autofocus required>
			<label for="input-password">Password</label>
		</div>

		<div class="form-label-group">
			<input type="text" id="input-student-id" class="form-control" name="student_id" placeholder="Student ID (Number)" value="<?= htmlspecialchars($_SESSION["student_id"]) ?>" maxlength="16" required>
			<label for="input-student-id">Student ID (Number)</label>
		</div>
		
		<div class="form-label-group">
			<input type="text" id="input-name" class="form-control" name="name" placeholder="Name" value="<?= htmlspecialchars($_SESSION["user_name"]) ?>" maxlength="32" required>
			<label for="input-password">Name</label>
		</div>

		<div class="form-label-group">
			<input type="text" id="input-class-id" class="form-control" name="class_id" placeholder="Class ID" value="<?= htmlspecialchars($_SESSION["class_id"]) ?>" maxlength="16" required>
			<label for="input-class-id">Class ID</label>
		</div>

		<button class="btn btn-lg btn-primary btn-block" type="submit" name="settings">Update</button>
	</form>
</main>

<? require_once('footer.php'); ?>
