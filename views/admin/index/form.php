<?php 
/* form.php 
 * Form to edit metadata browser category
 */
?>
<div class="field">
    <?php echo $this->formLabel('display_name', 'Category Name'); ?>
    <div class="inputs">
        <?php echo $this->formText('display_name', 
                                   $category->display_name, 
                                   array('id'    => 'metadata-browser-display-name', 
                                         'class' => 'textinput', 
                                         'size'  => 40)); ?>
        <p class="explanation">The Name that should be displayed for the category (required). Defaults
        to value given to Element in Element Set definition. Can be changed to provide better context for the user.</p>
    </div>
</div>
<div class="field">
    <?php echo $this->formLabel('slug', 'Slug'); ?>
    <div class="inputs">
        <?php echo $this->formText('slug', 
                                   $category->slug, 
                                   array('id'    => 'metadata-browser-slug', 
                                         'class' => 'textinput', 
                                         'size'  => 40)); ?>
        <p class="explanation">The URL slug for the category. Allowed characters: alphanumeric, 
        underscores, dashes, and forward slashes.</p>
    </div>

</div>


<div class="field">
    <?php echo $this->formLabel('active', 'Show in Public Category Browse?'); ?>
    <div class="inputs">
        <?php echo $this->formCheckbox('active', 
                                       $category->active, 
                                       array('id' => 'metadata-browser-active'), 
                                       array(1, 0)); ?>
    </div>
</div>

