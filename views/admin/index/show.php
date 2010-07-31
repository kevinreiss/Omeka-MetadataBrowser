<?php $head = array('title' => 'Browse Current Values for ' . $category->display_name, 'bodyclass' => 'metadata-browser primary', 'content_class' => 'horizontal-nav'); 
	  head($head);
?>
<h1><?php echo $head['title']; ?></h1>


<div id="primary">
	<h2><a class="edit" href="<?php echo html_escape(uri('metadata-browser/index/edit/id/' . $category->id)); ?>">Edit Category Options for <?php echo $category->display_name; ?></a></h2>
  
	<h3>Total Number of Values Assigned to this Category <?php echo $category->getCategoryCount(); ?></h3>
		<ul>
			<?php $categoryValues = $category->getAssignedValues(); ?>
				<?php foreach($categoryValues as $value): ?>
					<li class='category'><?php echo metadata_browser_create_link($category->element_id, $value)?></li>
				<?php endforeach; ?>
		</ul>
</div>
<?php foot(); ?>
