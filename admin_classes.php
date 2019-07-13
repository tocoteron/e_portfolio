<?
require_once('database_connection.php');
require_once("functions.php");

session_start();

if(!hasLoggedInAsAdmin())
{
    header("Location: signin.php");
}

$sql_for_get_classes = "SELECT class_id, COUNT(*) FROM users WHERE permission_level = 0 GROUP BY class_id";
$stmt = $dbh->prepare($sql_for_get_classes);
$stmt->execute();
$classes = $stmt->fetchAll();

$sql_for_get_students = "SELECT * FROM users WHERE permission_level = 0 ORDER BY class_id";
$stmt = $dbh->prepare($sql_for_get_students);
$stmt->execute();
$students = $stmt->fetchAll();
?>


<? require_once('header.php'); ?>
<? $page = basename(__FILE__); require_once('sidebar.php'); ?>

<main class="col-md-10 ml-sm-auto" role="main">
    <h2>Classes</h2>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Class</th>
                <th scope="col">Number of students</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
<?
$row_num = 0;
foreach($classes as $class)
{
    ++$row_num;
?>
            <tr>
                <th scope="row"><?= $row_num; ?></th>
                <td><?= $class[0] ?></td>
                <td><?= $class[1] ?></td>
                <td>
                    <a class="btn btn-danger" href="delete_class.php?class=<?= $class[0] ?>" role="button">Delete</a>
                </td>
            </tr>
<? } ?>
        </tbody>
    </table>
    <h2>Students</h2>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Class</th>
                <th scope="col">Student ID</th>
                <th scope="col">Name</th>
                <th scope="col">Created at</th>
                <th scope="col">CSV</th>
                <th scope="col">Assignments</th>
                <th scope="col">Statistics</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
<?
$row_num = 0;
foreach($students as $student)
{
    ++$row_num;
?>
            <tr>
                <th scope="row"><?= $row_num; ?></th>
                <td><?= $student["class_id"]; ?></td>
                <td><?= $student["student_id"]; ?></td>
                <td><?= $student["name"]; ?></td>
                <td><?= $student["created_at"]; ?></td>
                <td>
                    <a class="btn btn-success" href="csv.php?user_id=<?= $student["id"] ?>" role="button">CSV</a>
                </td>
                <td>
                    <a class="btn btn-primary" href="student_assignments.php?class=<?= $student["class_id"] ?>&user=<?= $student["id"] ?>" role="button">Assignments</a>
                </td>
                <td>
                    <a class="btn btn-primary" href="statistics.php?class=<?= $student["class_id"] ?>&user=<?= $student["id"] ?>" role="button">Statistics</a>
                </td>
                <td>
                    <a class="btn btn-danger" href="delete_user.php?user=<?= $student["id"] ?>&name=<?= $student["name"] ?>" role="button">Delete</a>
                </td>
            </tr>
<? } ?>
        </tbody>
    </table>
</main>

<? require_once('footer.php'); ?>
