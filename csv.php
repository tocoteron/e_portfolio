<?
require_once('database_connection.php');
require_once("functions.php");

session_start();

ini_set('display_errors', "On");

if (!hasLoggedIn())
{
    header("Location: signin.php");
}

$hasSelectedUserId = !empty($_GET["user_id"]);
if(!$hasSelectedUserId)
{
    header("Location: signin.php");
}

$evaluator_id = "admin";
$user_id = $_GET["user_id"];

$user_info = getUserInformation($dbh, $user_id);
$filename = $user_info["name"] . "(" . $user_info["student_id"] . ").csv";

$csv = [];
$csv = array_merge($csv, getEvaluationCSV($dbh, $user_id, $user_id));
$csv[] = [];
$csv = array_merge($csv, getEvaluationCSV($dbh, $evaluator_id, $user_id));

// CSV出力用ヘッダ
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=" . $filename);
header("Content-Transfer-Encoding: binary");

// CSVを出力
foreach($csv as $row)
{
	foreach($row as $cell)
	{
		echo '"' . $cell . '",';
	}
	echo "\n";
}
