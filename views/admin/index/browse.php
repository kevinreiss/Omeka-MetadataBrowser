<?php
// admin that shows current browing choices
?>
<?php head(array('title' => 'Metadata Browser Current Browsing Options', 'bodyclass' => 'metadata-browser primary', 'content_class' => 'horizontal-nav')); ?>

<h1>Available Metadata Browsing Options</h1>


<div id="primary">
<?php echo flash(); ?>
<form id="metadata-browser-browse" action="<?php echo html_escape(uri('metadata-browser/index/power-edit')); ?>" method="post" accept-charset="utf-8">

<table>
<tr>
	<th>Display Name</th>
	<th>Slug</th>
	<th>Element Set</th>
	<th>Active</th>
	<th>Browse Values</th>
</tr>
<?php echo metadata_browser_generate_element_select(); ?>

</table>
<fieldset>
    <input type="submit" class="submit submit-medium" id="save-changes" name="submit" value="Update Categories" />
</fieldset>
</form>
</div>

<?php foot(); ?>
