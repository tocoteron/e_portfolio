<?
require_once('database_connection.php');
require_once("functions.php");

session_start();

if(!hasLoggedInAsAdmin())
{
    header("Location: signin.php");
}

$hasSelectedUser = !empty($_GET["user"]);
$hasSelectedUserName = !empty($_GET["name"]);

$user_id = "";
if($hasSelectedUser)
{
    $user_id = $_GET["user"];
}

$user_name = "";
if($hasSelectedUserName)
{
    $user_name = $_GET["name"];
}

if(isset($_POST["user_delete"]))
{
    // 指定されたクラスに関する全てのデータも同時に削除する

    // ユーザーを削除
    $sql_for_delete_assignments = "DELETE FROM users WHERE id = :user_id";
    $stmt = $dbh->prepare($sql_for_delete_assignments);
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();

    // 課題に対する評価を削除
    $sql_for_delete_evaluations = "DELETE FROM evaluations WHERE evaluator_id = :user_id OR user_id = :user_id";
    $stmt = $dbh->prepare($sql_for_delete_evaluations);
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();

    // 課題に対するコメントを削除
    $sql_for_delete_comments = "DELETE FROM comments WHERE commenter_id = :user_id OR user_id = :user_id";
    $stmt = $dbh->prepare($sql_for_delete_comments);
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();

    header("Location: admin_classes.php");
}
else if(isset($_POST["user_delete_cancel"]))
{
    header("Location: admin_classes.php");
}
?>

<? require_once('header.php'); ?>
<? $page = basename(__FILE__); require_once('sidebar.php'); ?>

<main class="col-md-10 ml-sm-auto" role="main">
    <div class="jumbotron jumbotron-fluid">
		<div class="container">
			<h1 class="display-4">Delte user '<?= $user_name ?>'</h1>
			<p class="lead">If you click "Delete" button, the user will be deleted.<br>If you don't want to delete the user, click "Cancel" button.</p>
            <form action="" method="post">
                <input type="hidden" name="class_id" value="<?= $user_id ?>">
                <button type="submit" class="btn btn-danger" name="user_delete">Delete</button>
                <button type="submit" class="btn btn-info" name="user_delete_cancel">Cancel</button>
            </form>
		</div>
	</div>
</main>

<? require_once('footer.php'); ?>