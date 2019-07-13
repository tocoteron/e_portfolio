<?
require_once('database_connection.php');
require_once("functions.php");

session_start();

if(!hasLoggedInAsAdmin())
{
    header("Location: signin.php");
}

$hasSelectedClass = !empty($_GET["class"]);
$hasSelectedUser = !empty($_GET["user"]);

$class_id = "";
if($hasSelectedClass)
{
    $class_id = $_GET["class"];
}

$user_id = "";
if($hasSelectedUser)
{
    $user_id = $_GET["user"];
}

$sql_for_get_assignments = "SELECT * FROM assignments WHERE class_id = :class_id ORDER BY updated_at DESC";
$stmt = $dbh->prepare($sql_for_get_assignments);
$stmt->bindValue(":class_id", $class_id);
$stmt->execute();
$assignments = $stmt->fetchAll();

$sql_for_get_evaluation_categories = "SELECT * FROM evaluation_categories ORDER BY id";
$stmt = $dbh->prepare($sql_for_get_evaluation_categories);
$stmt->execute();
$evaluation_categories = $stmt->fetchAll();

$sql_for_get_evaluation_grades = "SELECT * FROM evaluation_grades ORDER BY id";
$stmt = $dbh->prepare($sql_for_get_evaluation_grades);
$stmt->execute();
$evaluation_grades = $stmt->fetchAll();

$evaluation_items = [];
foreach($evaluation_categories as $evaluation_category)
{
	$sql_for_get_evaluation_items  = "SELECT * FROM evaluation_items WHERE category_id = :category_id ORDER BY id";
	$stmt = $dbh->prepare($sql_for_get_evaluation_items);
	$stmt->bindValue(":category_id", $evaluation_category["id"]);
	$stmt->execute();
	$evaluation_items[$evaluation_category["id"]] = $stmt->fetchAll();
}

// メッセージの初期化
$error_message= "";
$success_message = "";

// Submitボタンが押された場合
if (isset($_POST["evaluation_submit"]))
{
	if (empty($_POST["class_id"]))
	{
		$error_message = "Class ID is not entered.";
	}
	else if (empty($_POST["user_id"]))
	{
		$error_message = "User ID is not entered.";
	}
	else if (empty($_POST["assignment_id"]))
	{
		$error_message = "Assignment ID is not entered.";
	}
	else if (empty($_POST["evaluation"]))
	{
		$error_message = "Evaluation is not entered.";
	}
	
	if (!$error_message)
	{
		$class_id = $_POST["class_id"];
		$user_id = $_POST["user_id"];
		$assignment_id = $_POST["assignment_id"];
		$evaluation = $_POST["evaluation"];
		$comments = $_POST["comments"];

		$stmt = $dbh->prepare("SELECT * FROM assignments WHERE id = :id");
		$stmt->bindValue(":id", $assignment_id);
		$stmt->execute();

		if(!$stmt->fetch(PDO::FETCH_ASSOC))
		{
			$error_message = "Assignment is not found";
		}
		else
		{
			// 各評価項目の内容をDBに追加/更新
			foreach($evaluation as $category_id => $category)
			{
				foreach($category as $item_id => $item)
				{
					$sql = "INSERT INTO evaluations (class_id, evaluator_id, user_id, assignment_id, category_id, item_id, grade_id, updated_at) VALUES ";
					$sql .= "(:class_id, :evaluator_id, :user_id, :assignment_id, :category_id, :item_id, :grade_id, :updated_at) ";
					$sql .= "ON DUPLICATE KEY UPDATE grade_id = VALUES (grade_id)";
					
					$stmt = $dbh->prepare($sql);
					$stmt->bindValue(":class_id", $class_id);
					$stmt->bindValue(":evaluator_id", $_SESSION["user_id"]);
					$stmt->bindValue(":user_id", $user_id);
					$stmt->bindValue(":assignment_id", $assignment_id);
					$stmt->bindValue(":category_id", $category_id);
					$stmt->bindValue(":item_id", $item_id);
					$stmt->bindValue(":grade_id", $item);
					$stmt->bindValue(":updated_at", date('Y-m-d H:i:s'));

					$stmt->execute();
				}
			}

			// コメント欄の内容をDBに追加/更新
			$sql = "INSERT INTO comments (class_id, commenter_id, user_id, assignment_id, comment, updated_at) VALUES ";
			$sql .= "(:class_id, :commenter_id, :user_id, :assignment_id, :comment, :updated_at) ";
			$sql .= "ON DUPLICATE KEY UPDATE comment = VALUES(comment), updated_at = VALUES (updated_at)";
			
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(":class_id", $class_id);
			$stmt->bindValue(":commenter_id", $_SESSION["user_id"]);
			$stmt->bindValue(":user_id", $user_id);
			$stmt->bindValue(":assignment_id", $assignment_id);
			$stmt->bindValue(":comment", $comments);
			$stmt->bindValue(":updated_at", date('Y-m-d H:i:s'));

			$stmt->execute();

			//
			$success_message = "You have submitted assignment !";
		}
	}
}

// 評価済み課題のIDリストを取得
$sql_for_get_submitted_assginment_ids = "SELECT assignment_id FROM evaluations WHERE evaluator_id = :evaluator_id AND user_id = :user_id GROUP BY assignment_id";
$stmt = $dbh->prepare($sql_for_get_submitted_assginment_ids);
$stmt->bindValue(":evaluator_id", $_SESSION["user_id"]);
$stmt->bindValue(":user_id", $user_id);
$stmt->execute();
$submitted_assignment_ids = $stmt->fetchAll();

$has_submitted_list = [];
foreach($submitted_assignment_ids as $submitted_assignment_id)
{
	$has_submitted_list[$submitted_assignment_id[0]] = true;
}

?>

<? require_once('header.php'); ?>
<? $page = basename(__FILE__); require_once('sidebar.php'); ?>

<main class="col-md-10 ml-sm-auto" role="main">
	<div class="jumbotron jumbotron-fluid">
		<div class="container">
			<h1 class="display-4">Assignments</h1>
			<p class="lead">The teacher adds assignments. This page shows the assignments.<br>You have to answer the assignments and evaluate yourself.</p>
		</div>
	</div>
	<div class="accordion" id="accordionExample">

<?
if($success_message)
{
	echo '<div class="alert alert-success" role="alert">' . $success_message . '</div>';
}
?>

<?
foreach($assignments as $assignment)
{
	require("assignment.php");
}
?>

	</div>
</main>

<? require_once('footer.php'); ?>