<?
require_once('database_connection.php');
require_once("functions.php");

session_start();

if(!hasLoggedInAsAdmin())
{
    header("Location: signin.php");
}

$hasSelectedClass = !empty($_GET["class"]);

$class_id = "";
if($hasSelectedClass)
{
    $class_id = $_GET["class"];
}

if(isset($_POST["class_delete"]))
{
    // 指定されたクラスに関する全てのデータも同時に削除する

    // ユーザーを削除
    $sql_for_delete_assignments = "DELETE FROM users WHERE class_id = :class_id";
    $stmt = $dbh->prepare($sql_for_delete_assignments);
    $stmt->bindValue(":class_id", $_POST["class_id"]);
    $stmt->execute();

    // 課題を削除
    $sql_for_delete_assignments = "DELETE FROM assignments WHERE class_id = :class_id";
    $stmt = $dbh->prepare($sql_for_delete_assignments);
    $stmt->bindValue(":class_id", $_POST["class_id"]);
    $stmt->execute();

    // 課題に対する評価を削除
    $sql_for_delete_evaluations = "DELETE FROM evaluations WHERE class_id = :class_id";
    $stmt = $dbh->prepare($sql_for_delete_evaluations);
    $stmt->bindValue(":class_id", $_POST["class_id"]);
    $stmt->execute();

    // 課題に対するコメントを削除
    $sql_for_delete_comments = "DELETE FROM comments WHERE class_id = :class_id";
    $stmt = $dbh->prepare($sql_for_delete_comments);
    $stmt->bindValue(":class_id", $_POST["class_id"]);
    $stmt->execute();

    header("Location: admin_classes.php");
}
else if(isset($_POST["class_delete_cancel"]))
{
    header("Location: admin_classes.php");
}
?>

<? require_once('header.php'); ?>
<? $page = basename(__FILE__); require_once('sidebar.php'); ?>

<main class="col-md-10 ml-sm-auto" role="main">
    <div class="jumbotron jumbotron-fluid">
		<div class="container">
			<h1 class="display-4">Delte class '<?= $class_id ?>'</h1>
			<p class="lead">If you click "Delete" button, the class will be deleted.<br>If you don't want to delete the class, click "Cancel" button.</p>
            <form action="" method="post">
                <input type="hidden" name="class_id" value="<?= $class_id ?>">
                <button type="submit" class="btn btn-danger" name="class_delete">Delete</button>
                <button type="submit" class="btn btn-info" name="class_delete_cancel">Cancel</button>
            </form>
		</div>
	</div>
</main>

<? require_once('footer.php'); ?>