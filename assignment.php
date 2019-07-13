<?
$assignment_identity = "${assignment["class_id"]}-${assignment["id"]}";
$collapse_id = "collapse${assignment_identity}";
$heading_id = "heading${assignment_identity}";
$comments_id = "comments${assignment_identity}";

$has_submitted = isset($has_submitted_list[$assignment["id"]]);
?>

<div class="card">
	<div class="card-header" id="<?= $heading_id ?>">
		<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#<?= $collapse_id ?>" aria-expanded="true" aria-controls="<?= $collapse_id ?>">
			<?= $assignment["title"] ?>
		</button>
<? if($has_submitted) { ?>
		<span class="badge badge-success">Submitted</span>
<? } else { ?>
		<span class="badge badge-danger">New</span>
<? } ?>
	</div>

	<div id="<?= $collapse_id ?>" class="collapse multi-collapse <? if(!$has_submitted) echo 'show'; ?>" aria-labelledby="<?= $heading_id ?>" data-parent="#<?= $collapse_id ?>">
		<div class="card-body">
			<h3>Summary</h3>
			<p><?= $assignment["explanation"] ?></p>
			<h3>Evaluation</h3>
			<form action="" method="post">
				<input type="hidden" name="class_id" value="<?= $class_id ?>">
				<input type="hidden" name="user_id" value="<?= $user_id ?>">
				<input type="hidden" name="assignment_id" value="<?= $assignment["id"] ?>">

<?
foreach($evaluation_categories as $evaluation_category)
{
	echo "<h4 class=\"mt-2\">${evaluation_category["name"]}</h4>";
	foreach($evaluation_items[$evaluation_category["id"]] as $evaluation_item)
	{
		require("evaluation_item.php");
	}
}
?>
				<h4 class="mt-2">Comments</h4>
				<div class="form-group">
					<label for="<?= $comments_id ?>">Comments</label>
					<textarea class="form-control" id="<?= $comments_id ?>" name="comments" rows="3"></textarea>
				</div>
				<button type="submit" class="btn btn-primary" name="evaluation_submit">Submit</button>
			</form>
		</div>
	</div>
</div>
