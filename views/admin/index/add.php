<?php
$head = array('bodyclass' => 'metadata-browser primary', 
              'title' => html_escape('Metadata Browser | Category'),
			  'content_class' => 'horizontal-nav');
head($head);
?>
<h1><?php echo $head['title']; ?></h1>
<div id="primary">
    <?php //echo flash(); ?>
    <form method="post">
        <?php include 'form.php'; ?>
        <?php echo $this->formSubmit('metadata-browser-add-submit', 
                                     'Add Category', 
                                     array('id'    => 'metadata-browser-add-submit', 
                                           'class' => 'submit submit-medium')); ?>
    </form>
</div>
<?php foot(); ?>
