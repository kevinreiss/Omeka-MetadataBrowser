<?php
// create a form for metadata browsing configuration options 
echo '<div id="metadata_browser_config_form">';
echo '<label for="metadata_browser_configuration">Activate Menu</label>';
echo __v()->formCheckbox('metadata_browser_configuration', true, 
    array('checked'=>(boolean)get_option('metadata_browser_configuration')));

//echo text(array('name'=>'metadata_browser_browse_points'), get_option('metadata_browser_browse_points'), null);
echo "<label for='metadata_browser_browse_points'>List Elements to Be Browsed (Use ID Values):</label>";
echo "<br/>";             
echo text(array('name'=>'metadata_browser_browse_points'), get_option('metadata_browser_browse_points'), null);



echo '</div>';
?>