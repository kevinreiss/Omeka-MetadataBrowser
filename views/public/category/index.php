<?php
// List all top level browsing categories and provide links to the them
?>
<?php $head = array('title' => 'Browse digitalMETRO by Category', 'bodyclass' => 'metadatabrowser'); 
	  head($head);
?>



<div id="primary">

	<h1><?php echo $head['title']; ?></h1>

	<ul class="navigation item-tags" id="secondary-nav">
		<li>Browse by: </li>
		<?php echo nav(array('Title' => uri('items/browse', array('sortby'=>'dc.title')), 'Category' => uri('category'), 'Tag' => uri('items/tags'), 'Creator' => uri('items/browse', array('sortby'=>'dc.creator')), 'Most Recent' => uri('items/browse', array('sortby'=>'omeka.modified', 'sortorder'=>'desc')))); ?>
	</ul>
	
	<div class='metadataBrowserDisplay'>
		<ul class='categories'>
		<?php foreach ($categories as $category): ?>
			<li class="top-category">		
				<a title="<?php echo html_escape($category->getCategoryDescription()); ?>" href="<?php echo uri("category/" . $category->slug); ?>" title="Browse  <?php echo html_escape($category->display_name); ?>"><?php echo html_escape($category->display_name); ?> (<?php echo $category->getCategoryCount(); ?>)</a>
			</li>
		<?php endforeach; ?>
		</ul>
	</div>
</div>

<?php foot(); ?>
