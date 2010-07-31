<?php
//$elementID = $_GET['id'];
// List all top level browsing categories and provide links to the them
?>
<?php head(array('title' => 'Browse digitalMETRO by ' . $category->display_name, 'bodyclass' => 'metadatabrowser')); ?>

<div id="primary">
	<h1>Browse by <?php echo $category->display_name; ?></h1>

	<p class="explanation"><?php echo html_escape($category->getCategoryDescription()); ?></p>
	
	<!-- you will need to make sure this works with your selected theme -->
	<ul class="navigation item-tags" id="secondary-nav">
		<li>Browse by: </li>
		<?php echo nav(array('Title' => uri('items/browse', array('sortby'=>'dc.title')), 'Category' => uri('category'), 'Tag' => uri('items/tags'), 'Creator' => uri('items/browse', array('sortby'=>'dc.creator')), 'Most Recent' => uri('items/browse', array('sortby'=>'omeka.modified', 'sortorder'=>'desc')))); ?>
	</ul>
	
	<div class='metadataBrowserDisplay'>
		<ul>
		<?php $categoryValues = $category->getAssignedValues(); ?>
			<?php foreach($categoryValues as $value): ?>
				<li class='category'><?php echo metadata_browser_create_link($category->element_id, $value)?></li>
			<?php endforeach; ?>
		</ul>
	</div>

	<?php if($category->getCategoryCount() >= 10): ?>
	<ul class="navigation item-tags" id="secondary-nav">
		<li>Browse by: </li>
		<?php echo nav(array('Title' => uri('items/browse', array('sortby'=>'dc.title')), 'Category' => uri('category'), 'Tag' => uri('items/tags'), 'Creator' => uri('items/browse', array('sortby'=>'dc.creator')), 'Most Recent' => uri('items/browse', array('sortby'=>'omeka.modified', 'sortorder'=>'desc')))); ?>
	</ul>
	<?php endif; ?>
	
</div>

<?php foot(); ?>
