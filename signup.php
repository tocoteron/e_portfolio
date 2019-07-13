<?php
require_once('database_connection.php'); 
require_once('functions.php');

session_start();

$error_message = "";

// サインアップボタンが押された場合
if (isset($_POST["signup"]))
{
	// ユーザIDの入力チェック
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

		if($stmt->fetch(PDO::FETCH_ASSOC))
		{
			$error_message = "User ID is already used.";
		}
		else
		{
			//prepare("INSERT INTO テーブル名 (name, value) VALUES (:name, :value)");
			$stmt = $dbh->prepare("INSERT INTO users (id, password, student_id, name, permission_level, class_id, created_at) VALUES (:id, :password, :student_id, :name, :permission_level, :class_id, :created_at)");

			$stmt->bindValue(":id", $user_id);
			$stmt->bindValue(":password", password_hash($password, PASSWORD_BCRYPT));
			$stmt->bindValue(":student_id", $student_id);
			$stmt->bindValue(":name", $name);
			$stmt->bindValue(":permission_level", 0);
			$stmt->bindValue(":class_id", $class_id);
			$stmt->bindValue(":created_at", date('Y-m-d H:i:s'));

			$stmt->execute();

			login($dbh, $user_id);

			header("Location: assignments.php");
		}
	}
}
?>

<?php require_once('header.php'); ?>
<?php require_once('sidebar.php'); ?>

<main class="col-md-10 ml-sm-auto" role="main">
	<form class="form-signup" action="" method="post">
		<div class="text-center mb-4">
			<h1 class="h3 mb-3 font-weight-normal">Sign up</h1>
			<p>If you have an account, you can <a href="signin.php">signin here.</a></p> 
		</div>

<?php
if($error_message) {
	echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
}
?>

		<div class="form-label-group">
			<input type="text" id="input-user-id" class="form-control" name="user_id" placeholder="User ID" maxlength="16" required autofocus>
			<label for="input-user-id">User ID</label>
		</div>
		
		<div class="form-label-group">
			<input type="password" id="input-password" class="form-control" name="password" placeholder="Password" maxlength="16" required>
			<label for="input-password">Password</label>
		</div>

		<div class="form-label-group">
			<input type="text" id="input-student-id" class="form-control" name="student_id" placeholder="Student ID (Number)" maxlength="16" required>
			<label for="input-student-id">Student ID (Number)</label>
		</div>
		
		<div class="form-label-group">
			<input type="text" id="input-name" class="form-control" name="name" placeholder="Name" maxlength="32" required>
			<label for="input-password">Name</label>
		</div>

		<div class="form-label-group">
			<input type="text" id="input-class-id" class="form-control" name="class_id" placeholder="Class ID" maxlength="16" required>
			<label for="input-class-id">Class ID</label>
		</div>

		<button class="btn btn-lg btn-primary btn-block" type="submit" name="signup">Sign up</button>
	</form>
</main>

<?php require_once('footer.php'); ?>
