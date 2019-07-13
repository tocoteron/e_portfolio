<?
function login($dbh, $user_id)
{
    $stmt = $dbh->prepare("SELECT * FROM users WHERE id = :user_id");
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();

    if ($user = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        session_regenerate_id(true);

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["student_id"] = $user["student_id"];
        $_SESSION["user_name"] = $user["name"];
        $_SESSION["class_id"] = $user["class_id"];
        $_SESSION["permission_level"] = $user["permission_level"];

        return true;
    }

    return false;
}

function hasLoggedIn()
{
    return isset($_SESSION["user_id"]);
}

function hasLoggedInAsAdmin()
{
    return hasLoggedIn() && getPermissionLevel() > 0;
}

function getPermissionLevel()
{
    return $_SESSION["permission_level"];
}

function getUserInformation($dbh, $user_id)
{
	$sql_for_get_user_information = "SELECT * FROM users WHERE id = :user_id";
	$stmt = $dbh->prepare($sql_for_get_user_information);
	$stmt->bindValue(":user_id", $user_id);
	$stmt->execute();
	$user_information = $stmt->fetchAll()[0];

	return $user_information;
}

function getEvaluationCategories($dbh)
{
	$sql_for_get_evaluation_categories = "SELECT * FROM evaluation_categories ORDER BY id";
	$stmt = $dbh->prepare($sql_for_get_evaluation_categories);
	$stmt->execute();
	$evaluation_categories = $stmt->fetchAll();

	return $evaluation_categories;
}

function getEvaluationItems($dbh)
{
	$evaluation_items = [];
	$evaluation_categories = getEvaluationCategories($dbh);

	foreach($evaluation_categories as $evaluation_category)
	{
		$sql_for_get_evaluation_items  = "SELECT * FROM evaluation_items WHERE category_id = :category_id ORDER BY id";
		$stmt = $dbh->prepare($sql_for_get_evaluation_items);
		$stmt->bindValue(":category_id", $evaluation_category["id"]);
		$stmt->execute();
		$evaluation_items[$evaluation_category["id"]] = $stmt->fetchAll();
	}

	return $evaluation_items;
}

// 評価カテゴリラベルを生成
function getEvaluationCategoriesLabelForCSV($dbh)
{
	$row = [];

	$evaluation_categories = getEvaluationCategories($dbh);
	$evaluation_items = getEvaluationItems($dbh);

	$row[] = "";
	foreach($evaluation_categories as $evaluation_category)
	{
		$row[] = $evaluation_category["name"];
		for($i = 1; $i < count($evaluation_items[$evaluation_category["id"]]); $i++)
		{
			$row[] = "";
		}
	}

	return $row;
}

// 課題のタイトルラベルと評価項目ラベルを生成
function getEvaluationItemsLabelForCSV($dbh)
{
	$row = [];

	$evaluation_categories = getEvaluationCategories($dbh);
	$evaluation_items = getEvaluationItems($dbh);

	$row[] = "Title";
	foreach($evaluation_categories as $evaluation_category)
	{
		foreach($evaluation_items[$evaluation_category["id"]] as $evaluation_item)
		{
			$row[] = $evaluation_item["name"];
		}
	}

	return $row;
}

// 評価欄を生成
function getEvaluationRowsForCSV($dbh, $evaluator_id, $user_id)
{
	// ユーザーが提出済みの課題ID一覧を取得
	$sql_for_get_submitted_assignments_ids= "SELECT assignment_id FROM evaluations WHERE evaluator_id = :evaluator_id AND user_id = :user_id GROUP BY assignment_id ORDER BY assignment_id";
	$stmt = $dbh->prepare($sql_for_get_submitted_assignments_ids);
	$stmt->bindValue(":evaluator_id", $evaluator_id);
	$stmt->bindValue(":user_id", $user_id);
	$stmt->execute();
	$submitted_assignments_ids = $stmt->fetchAll();

	$rows = [];

	foreach($submitted_assignments_ids as $submitted_assignment_id)
	{
		$assignment_id = $submitted_assignment_id["assignment_id"];

		// 課題のタイトルを取得
		$sql_for_get_assignment_title = "SELECT title FROM assignments WHERE id = :assignment_id";
		$stmt = $dbh->prepare($sql_for_get_assignment_title);
		$stmt->bindValue(":assignment_id", $assignment_id);
		$stmt->execute();
		$assignment_title = $stmt->fetchAll()[0]["title"];

		// 課題に対する評価一覧を取得
		$sql_for_get_evaluations_for_assignment = "SELECT * FROM evaluations WHERE assignment_id = :assignment_id AND evaluator_id = :evaluator_id AND user_id = :user_id ORDER BY category_id, item_id";
		$stmt = $dbh->prepare($sql_for_get_evaluations_for_assignment);
		$stmt->bindValue(":assignment_id", $assignment_id);
		$stmt->bindValue(":evaluator_id", $evaluator_id);
		$stmt->bindValue(":user_id", $user_id);
		$stmt->execute();
		$evaluations_for_assignment = $stmt->fetchAll();

		//var_dump($self_evaluations_for_assignment);

		$row = [];
		$row[] = $assignment_title;

		foreach($evaluations_for_assignment as $evaluation_for_assignment)
		{
			$row[] = $evaluation_for_assignment["grade_id"];
		}

		$rows[] = $row;
	}

	return $rows;
}

function getEvaluationCSV($dbh, $evaluator_id, $user_id)
{
	$evaluator_information = getUserInformation($dbh, $evaluator_id);
	$user_information = getUserInformation($dbh, $user_id);

	$csv = [];
	$csv[] = ["Evaluator", $evaluator_information["name"], $evaluator_information["student_id"]];
	$csv[] = ["User", $user_information["name"], $user_information["student_id"]];
	$csv[] = getEvaluationCategoriesLabelForCSV($dbh);
	$csv[] = getEvaluationItemsLabelForCSV($dbh);
	$csv = array_merge($csv, getEvaluationRowsForCSV($dbh, $evaluator_id, $user_id));
	
	return $csv;
}
?>
