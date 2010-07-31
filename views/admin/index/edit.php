<?php $head = array('title' => 'Category Display Choices: ' . $category->display_name, 'bodyclass' => 'metadata-browser primary', 'content_class' => 'horizontal-nav'); 
	  head($head);
?>


<h1><?php echo $head['title']?></h1>


<div id="primary">
<?php //echo flash(); ?>
<p>[<a class="category" href='<?php echo html_escape(uri('metadata-browser/index/show/' . $category->element_id)); ?>'>View Current Assigned Values</a>]</p>
	<form method="post">
	  <?php include 'form.php'; ?>
      <?php echo $this->formSubmit('metadata-browser-edit-submit', 
                                     'Save Category', 
                                     array('id'    => 'metadata-browser-edit-submit', 
                                           'class' => 'submit submit-medium')); ?>
	</form>
	
	
	<p id="metadata-browser-delete">
            <a class="delete" href="<?php echo html_escape(uri("metadata-browser/index/delete/id/$category->id")); ?>">Delete This Category</a>
        </p>
	
</div>



<?php foot(); ?>