<h6 class="mt-2"><?= $evaluation_item["name"] ?> <?= $evaluation_item["explanation"] ?></h6>

<?
foreach($evaluation_grades as $evaluation_grade)
{
	$evaluation_id = "evaluation${assignment['id']}${evaluation_category['id']}-${evaluation_item['id']}-${evaluation_grade['id']}";
?>
<div class="form-check form-check-inline">
	<input class="form-check-input" type="radio" name="evaluation[<?= $evaluation_category["id"] ?>][<?= $evaluation_item["id"] ?>]" id="<?= $evaluation_id ?>" value="<?= $evaluation_grade["id"] ?>" required>
	<label class="form-check-label" for="<?= $evaluation_id ?>"><?= $evaluation_grade["id"] ?> <?= $evaluation_grade["explanation"] ?></label>
</div>
<? } ?>
