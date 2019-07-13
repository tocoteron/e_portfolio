<?php
require_once('database_connection.php');
require_once('functions.php');

session_start();

// エラーメッセージの初期化
$error_message= "";

// サインインボタンが押された場合
if (isset($_POST["signin"]))
{
	// 1. ユーザIDの入力チェック
	if (empty($_POST["user_id"]))
	{  // emptyは値が空のとき
		$error_message= "User ID is not entered.";
	}
	else if (empty($_POST["password"]))
	{
		$error_message= "Password is not entered.";
	}

	if (!empty($_POST["user_id"]) && !empty($_POST["password"]))
	{
		$user_id = $_POST["user_id"];
		$password = $_POST["password"];

		$stmt = $dbh->prepare("SELECT * FROM users WHERE id = :user_id");
		$stmt->bindValue(":user_id", $user_id);
		$stmt->execute();

		if ($user = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			if (password_verify($password, $user["password"]))
			{
				login($dbh, $user_id);

				header("Location: assignments.php");
			}
			else
			{
				// 認証失敗
				$error_message = "User ID or Password is wrong.";
			}
		}
		else
		{
			// 4. 認証成功なら、セッションIDを新規に発行する
			// 該当データなし
			$error_message = "User ID or Password is wrong.";
		}
	}
}
?>

<?php require_once('header.php'); ?>
<?php require_once('sidebar.php'); ?>

<main class="col-md-10 ml-sm-auto" role="main">
	<form class="form-signin" action="signin.php" method="post">
		<div class="text-center mb-4">
			<h1 class="h3 mb-3 font-weight-normal">Sign in</h1>
			<p>If you do not have an account yet, you can <a href="signup.php">signup here.</a></p> 
		</div>

<?php
if($error_message) {
	echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
}
?>

		<div class="form-label-group">
			<input type="text" id="input-user-id" class="form-control" name="user_id" placeholder="User ID" required autofocus>
			<label for="input-user-id">User ID</label>
		</div>
		
		<div class="form-label-group">
			<input type="password" id="input-password" class="form-control" name="password" placeholder="Password" required>
			<label for="input-password">Password</label>
		</div>

		<button class="btn btn-lg btn-primary btn-block" type="submit" name="signin">Sign in</button>
	</form>
</main>

<?php require_once('footer.php'); ?>
