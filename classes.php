<?
require_once('database_connection.php');

session_start();

?>

<? require_once('header.php'); ?>
<? $page = basename(__FILE__); require_once('sidebar.php'); ?>

<main class="col-md-10 ml-sm-auto" role="main">
    <h2>Classes</h2>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Cras justo odio</li>
        <li class="list-group-item">Dapibus ac facilisis in</li>
        <li class="list-group-item">Morbi leo risus</li>
        <li class="list-group-item">Porta ac consectetur ac</li>
        <li class="list-group-item">Vestibulum at eros</li>
    </ul>
</main>

<? require_once('footer.php'); ?>