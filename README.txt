/*
 * Metadata Browser Plugin for digitalMETRO Project
 * [http://nycdigital.org/]
 *
 * Kevin Reiss
 * 7.30.2010 
 * Create Browseable Omeka Categories
 * 
 * readme.txt
 * Related Plugins - SortBrowseResults
 *
 * Covers how to use the MetadataBrowser plugin to create linking for selected omeka 
 * elements in "yourcurrenttheme/items/show.php or "yourcurrenttheme/items/browse.php" 
 * and add category browsing to your secondary theme navigation. At present these two steps
 * need to be done manually.
 *  
 * Tested on Omeka 1.2
 *
 * Refactors the Omeka Elements table to produce browsable views of element content
 * in both the Admin and Public Interface. The elements have been renamed "categories"
 * in the interest of using terms that will be familiar to general users.
 *
 * The MetadataBrowserCategory class enables the user to denote categories as "public"
 * as well as change the value of the "slug" and descriptive "label" that is displayed for
 * context in the interface when the category is displayed. Each "category" is traced back to
 * original element declaration by the "element_id" value assigned to each category. This
 * value is taken directly from the "Elements" table by the "metadata_browser_generate_element_select"
 * utility method in plugin.php for the plugin.
 *
 *  See file *howtolink.txt* on how to activate browsing in theme "show" and "browse" views.
 *
 *  To Do:
 *  1. Add a description field to class to enable user-supplied definition rather than depend
 *     on element definition which can be very technical.
 *  2. Routes for individual element values a la wordpress
 *  3. Reliable value counting method for each value assigned to a given category.
 *     Advanced search and database query for string production different values.
 *  4. Automatic way to activate browsing by value for active categories in "show" and
 *     "browse" pages. This now needs to be done manually. See "howtolink.txt" for
 *     more information on how to currently do this.
 */

 
 
 *******Linking Instructions for Theme***************
 In item/browse.php or item/show.php you currently need to go through each element value one-by-one in order to
 activate browsing for selected elements manually. It would be better to find a way
 to do this automatically via the show_item_metadata() helper function but at present 
 this is the way to do it. These linking instructions also work in the browse.php file 
 in an Omeka theme. Once a plug-in is installed all you need to do is insert the following:

Generic Model:
 
 <?php if ($element = item('My Element Set', 'My Element Name', array('all'=>'true'))): ?>
	<div id="my-element-name" class="element">
		<h3>My Element Name (Desired Public Value)</h3>
        <?php foreach($element as $value) {?>
            <div class="element-text"><?php echo metadata_browser_element_link("My Element Name", $value);?></div>
        <?php }?>
    </div>
<?php endif; ?>

Example for the Dublin Core Element "Creator":

<?php if ($creator = item('Dublin Core', 'Creator', array('all'=>'true'))): ?>
	<div id="dublin-core-creator" class="element">
		<h3>Creator</h3>
        <?php foreach($creator as $value) {?>
            <div class="element-text"><?php echo metadata_browser_element_link("Creator", $value);?></div>
        <?php }?>
    </div>
<?php endif; ?>

***********Add Category Browsing to your Theme's Navigation Options************
Adding the top-level public "/category" view where all active categories 
selected in the admin module are displayed to a secondary browsing option needs to 
be done manually as well. You will need to execute something like the following
where you wish the secondary navigations options to appear in your theme, such as the 
browse.php file.

Example from browse.php:

<ul class="items-nav navigation" id="secondary-nav">
	<li>Browse by: </li>
	<?php echo nav(array('Title' => uri('items/browse', array('sortby'=>'dc.title')), 'Category' => uri('category'), 'Tag' => uri('items/tags'), 'Creator' => uri('items/browse', array('sortby'=>'dc.creator')), 'Most Recent' => uri('items/browse', array('sortby'=>'omeka.modified', 'sortorder'=>'desc')))); ?>
</ul>

The value 'Category' => uri('category') needs to be added to the array passed to the nav() function. 

Note: The above example expects you have the "SortBrowseResults" plugin installed to enable sorting by
field values like 'dc.title' and 'dc.creater'.



 



