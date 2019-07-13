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

$sql_for_get_classes = "SELECT class_id FROM users WHERE permission_level = 0 GROUP BY class_id";
$stmt = $dbh->prepare($sql_for_get_classes);
$stmt->execute();
$classes = $stmt->fetchAll();

$sql_for_get_assignments = "SELECT * FROM assignments WHERE class_id = :class_id ORDER BY updated_at DESC";
$stmt = $dbh->prepare($sql_for_get_assignments);
$stmt->bindValue(":class_id", $class_id);
$stmt->execute();
$assignments = $stmt->fetchAll();

// フォームの情報を取得
if (isset($_POST["assignment_add"]))
{
    $sql_for_add_assignment = "INSERT INTO assignments (class_id, title, explanation, updated_at) VALUES (:class_id, :title, :explanation, :updated_at)";
    
    $stmt = $dbh->prepare($sql_for_add_assignment);
    
    $stmt->bindValue(":class_id", $_POST["class_id"]);
    $stmt->bindValue(":title", $_POST["title"]);
    $stmt->bindValue(":explanation", $_POST["explanation"]);
	$stmt->bindValue(":updated_at", date('Y-m-d H:i:s'));
    
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF'] . "?class=" . $class_id);
}
else if(isset($_POST["assignment_update"]))
{
    $sql_for_update_assignment = "UPDATE assignments SET title = :title, explanation = :explanation, updated_at = :updated_at WHERE class_id = :class_id AND id = :id";
    
    $stmt = $dbh->prepare($sql_for_update_assignment);
    
    $stmt->bindValue(":title", $_POST["title"]);
    $stmt->bindValue(":explanation", $_POST["explanation"]);
    $stmt->bindValue(":class_id", $_POST["class_id"]);
    $stmt->bindValue(":id", $_POST["assignment_id"]);
	$stmt->bindValue(":updated_at", date('Y-m-d H:i:s'));
    
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF'] . "?class=" . $class_id);
}
else if(isset($_POST["assignment_delete"]))
{
    // 指定された課題に関する全ての評価データ等も同時に削除する

    // 課題を削除
    $sql_for_delete_assignments = "DELETE FROM assignments WHERE class_id = :class_id AND id = :id";
    $stmt = $dbh->prepare($sql_for_delete_assignments);
    $stmt->bindValue(":class_id", $_POST["class_id"]);
    $stmt->bindValue(":id", $_POST["assignment_id"]);
    $stmt->execute();

    // 課題に対する評価を削除
    $sql_for_delete_evaluations = "DELETE FROM evaluations WHERE class_id = :class_id AND assignment_id = :assignment_id";
    $stmt = $dbh->prepare($sql_for_delete_evaluations);
    $stmt->bindValue(":class_id", $_POST["class_id"]);
    $stmt->bindValue(":assignment_id", $_POST["assignment_id"]);
    $stmt->execute();

    // 課題に対するコメントを削除
    $sql_for_delete_comments = "DELETE FROM comments WHERE class_id = :class_id AND assignment_id = :assignment_id";
    $stmt = $dbh->prepare($sql_for_delete_comments);
    $stmt->bindValue(":class_id", $_POST["class_id"]);
    $stmt->bindValue(":assignment_id", $_POST["assignment_id"]);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF'] . "?class=" . $class_id);
}

?>

<? require_once('header.php'); ?>
<? $page = basename(__FILE__); require_once('sidebar.php'); ?>

<main class="col-md-10 ml-sm-auto" role="main">
	<div class="jumbotron jumbotron-fluid">
		<div class="container">
			<h1 class="display-4">New assignment</h1>
			<p class="lead">Teacher adds assignment on this page.<br>When the assignments is added, students will be able to check the assignment and do it.</p>
            <form action="" method="post">
                <input type="hidden" name="class_id" value="<?= $class_id ?>">
                <input type="hidden" name="assignment_id" value="<?= $assignment["id"] ?>">
                <div class="form-group">
                    <label for="new_title">Title</label>
                    <input type="text" class="form-control" id="new_title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="new_explanation">Summary</label>
                    <textarea class="form-control" id="new_explanation" name="explanation" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" name="assignment_add" <? if(!$hasSelectedClass) { echo "disabled"; } ?>>Add</button>
            </form>
		</div>
	</div>
    <div>
<? foreach($classes as $class) { ?>
        <a class="btn <? if($class["class_id"] == $class_id) { echo "btn-primary"; } else { echo "btn-secondary"; } ?>" href="admin_assignments.php?class=<? echo $class["class_id"]; ?>" role="button"><? echo $class["class_id"]; ?></a>
<? } ?>
    </div>
    <div class="accordion" id="accordionExample">
<?
foreach($assignments as $assignment) {
    $assignment_identity = "${assignment["class_id"]}-${assignment["id"]}";
    $collapse_id = "collapse${assignment_identity}";
    $heading_id = "heading${assignment_identity}";
    $title_id = "title${assignment_identity}";
    $summary_id = "summary${assignment_identity}";
?>
        <div class="card">
            <div class="card-header" id="<?= $heading_id ?>">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#<?= $collapse_id ?>" aria-expanded="true" aria-controls="<?= $collapse_id ?>">
                    <?= $assignment["title"] ?>
                </button>
            </div>
            <div id="<?= $collapse_id ?>" class="collapse multi-collapse" aria-labelledby="<?= $heading_id ?>" data-parent="#<?= $collapse_id ?>">
                <div class="card-body">
                    <form action="" method="post">
                        <input type="hidden" name="class_id" value="<?= $class_id ?>">
                        <input type="hidden" name="assignment_id" value="<?= $assignment["id"] ?>">
                        <div class="form-group">
                            <label for="<?= $title_id ?>">Title</label>
                            <input type="text" class="form-control" id="<?= $title_id ?>" name="title" value="<?= $assignment["title"] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="<?= $summary_id ?>">Summary</label>
                            <textarea class="form-control" id="<?= $summary_id ?>" name="explanation" rows="3" required><?= $assignment["explanation"] ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-info" name="assignment_update">Update</button>
                        <button type="submit" class="btn btn-danger float-right" name="assignment_delete">Delete</button>
                    </form>
                </div>
            </div>
        </div>
<? } ?>
    </div>
</main>

<? require_once('footer.php'); ?>
