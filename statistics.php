<?
require_once('database_connection.php'); 
require_once('functions.php');

session_start();

// ログイン状態チェック
if (!hasLoggedIn())
{
    header("Location: signin.php");
}
else
{
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
}


// 自己評価一覧を取得
$sql_for_get_evaluations = "SELECT * FROM evaluations INNER JOIN assignments ON evaluations.assignment_id = assignments.id AND evaluator_id = :user_id AND user_id = :user_id ORDER BY assignment_id";
$stmt = $dbh->prepare($sql_for_get_evaluations);
$stmt->bindValue(":user_id", $user_id);
$stmt->execute();
$self_evaluations = $stmt->fetchAll();

// 自己評価(コメント)一覧を取得
$sql_for_get_comments = "SELECT * FROM comments WHERE commenter_id = :user_id AND user_id = :user_id ORDER BY assignment_id";
$stmt = $dbh->prepare($sql_for_get_comments);
$stmt->bindValue(":user_id", $user_id);
$stmt->execute();
$self_comments = $stmt->fetchAll();

// 教員評価一覧を取得
$sql_for_get_evaluations = "SELECT * FROM evaluations INNER JOIN assignments ON evaluations.assignment_id = assignments.id AND evaluator_id != :user_id AND user_id = :user_id ORDER BY assignment_id";
$stmt = $dbh->prepare($sql_for_get_evaluations);
$stmt->bindValue(":user_id", $user_id);
$stmt->execute();
$teacher_evaluations = $stmt->fetchAll();

// 教員評価(コメント)一覧を取得
$sql_for_get_comments = "SELECT * FROM comments WHERE commenter_id != :user_id AND user_id = :user_id ORDER BY assignment_id";
$stmt = $dbh->prepare($sql_for_get_comments);
$stmt->bindValue(":user_id", $user_id);
$stmt->execute();
$teacher_comments = $stmt->fetchAll();

// JavaScriptに渡すためのデータ作成
$params = [];
$params["self_evaluation"] = [];
foreach($self_evaluations as $evaluation)
{
    $assignment_id = $evaluation["assignment_id"];
    $category_id = $evaluation["category_id"];
    $item_id = $evaluation["item_id"];
    //echo gettype($evaluation["grade_id"]);
    $params["self_evaluation"][$assignment_id][$category_id][$item_id] = intval($evaluation["grade_id"]);
    $params["title"][$assignment_id] = $evaluation["title"];
}

foreach($self_comments as $comment)
{
    $assignment_id = $comment["assignment_id"];
    // ユーザーの入力のみエスケープを行う
    $params["self_evaluation"][$assignment_id]["comments"] = htmlspecialchars($comment["comment"], ENT_QUOTES, "UTF-8");
}

// JavaScriptに渡すためのデータ作成
$params["teacher_evaluation"] = [];
foreach($teacher_evaluations as $evaluation)
{
    $assignment_id = $evaluation["assignment_id"];
    $category_id = $evaluation["category_id"];
    $item_id = $evaluation["item_id"];
    $params["teacher_evaluation"][$assignment_id][$category_id][$item_id] = intval($evaluation["grade_id"]);
    $params["title"][$assignment_id] = $evaluation["title"];
}

foreach($teacher_comments as $comment)
{
    $assignment_id = $comment["assignment_id"];
    // 教師側ではシングルクォーテーションとダブルクオーテーションのみエスケープを行う
    $replacedComment = $comment["comment"];
    $replacedComment = str_replace("'", "&#039;", $replacedComment);
    $replacedComment = str_replace('"', "&quot;", $replacedComment);
    $params["teacher_evaluation"][$assignment_id]["comments"] = $replacedComment;
}

$loadJavaScriptList = array(
    array("name" => "statistics_chart.js", "params" => $params)
);
?>

<? require_once('header.php'); ?>
<? $page = basename(__FILE__); require_once('sidebar.php'); ?>

<main class="col-md-10 ml-sm-auto" role="main">
    <div class="overall-score pl-5 py-4">
        <h5 class="text-muteda"><u id="over-all-score">Overall score: / 5 points</u></h5>
    </div>
    <div class="assignment-buttons pl-5">
<?
for($i = 0; $i < 2; ++$i)
{
    echo "<div>";
    echo "<h3>Assignment" . ($i + 1) . "</h3>";
    
    foreach($params["self_evaluation"] as $assignment_id => $assignment)
    {
        echo "<button type=\"button\" class=\"btn btn-info m-2\" onClick=\"selectEvaluation(${i}, 'self', ${assignment_id});\">" . $params['title'][$assignment_id] . "</button>";
    }

    foreach($params["teacher_evaluation"] as $assignment_id => $assignment)
    {
        echo "<button type=\"button\" class=\"btn btn-success m-2\" onClick=\"selectEvaluation(${i}, 'teacher', ${assignment_id});\">" . $params['title'][$assignment_id] . "</button>";
    }

    echo "</div>";
}
?>
    </div>
    <div class="row charts p-5">
        <canvas id="chart1-1" class="chart pr-4" width="400" height="400"></canvas>
        <canvas id="chart1-2" class="chart pr-4" width="400" height="400"></canvas>
        <canvas id="chart1-3" class="chart pr-4" width="400" height="400"></canvas>
    </div>
    <div>
        <div class="comments px-5">
            <h4>Comments 1</h4>
            <p id="comments1"></p>
        </div>
        <div class="comments px-5">
            <h4>Comments 2</h4>
            <p id="comments2"></p>
        </div>
    </div>
    <!--
    <form>
        <div class="form-group comments px-5">
            <label for="comments1">Comments1</label>
            <textarea class="form-control" id="comments1" rows="3" readonly></textarea>
        </div>
        <div class="form-group comments px-5">
            <label for="comments2">Comments2</label>
            <textarea class="form-control" id="comments2" rows="3" readonly></textarea>
        </div>
    </form>
    -->
</main>

<? require_once('footer.php'); ?>