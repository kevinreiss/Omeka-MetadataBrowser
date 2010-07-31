<?php

/* 
 * @copyright New York Metropolitan Library Council, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package MetadataBrowser
 * @ Author: Kevin Reiss kevin.reiss@gmail.com 
 */


/* Metadata Browser Plugin for digitalMETRO Project
 * [http://nycdigital.org/]
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


// Require the record model for the metadata_browser_category table.
require_once 'MetadataBrowserCategory.php';

// add plugin hooks
add_plugin_hook('install', 'metadata_browser_install');
add_plugin_hook('uninstall', 'metadata_browser_uninstall');
/*
add_plugin_hook('config_form', 'metadata_browser_config_form');
add_plugin_hook('config', 'metadata_browser_config');
*/
// add route hook
add_plugin_hook('define_routes', 'metadata_browser_define_routes'); 

// add filter for admin menu
add_filter('admin_navigation_main', 'metadata_browser_admin_nav');


function metadata_browser_install() {
	/* Create a Table to hold category information */
	$db = get_db(); 
	$db->exec("CREATE TABLE IF NOT EXISTS `$db->MetadataBrowserCategory` (
	 			`id` int(10) unsigned NOT NULL auto_increment,
	 			`element_id` int(10) unsigned NOT NULL,
	 			`display_name` tinytext collate utf8_unicode_ci NOT NULL,
	 			`slug` tinytext collate utf8_unicode_ci NOT NULL,
	 			`active` tinyint(1) NOT NULL,
	 			PRIMARY KEY  (`id`)
      			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");  
		
	/* Populate table with all existing Elements as possible category choices */
	$setelements = $db->getTable('Element')->findall();
	
	$num = 1; // for ID value
	foreach($setelements as $element)
	{
		
		$element_id = $element->id;
		$display_name = $element->name;
		$slug = metadata_browser_generate_slug($element->name); // create a URL-friendly slug
			
		$db->exec("INSERT INTO `$db->MetadataBrowserCategory` VALUES({$num}, {$element_id}, '{$display_name}', '{$slug}', 0);");
		$num++;
	}	
}



function metadata_browser_uninstall() {
	
	// not sure if these are needed
	delete_option('metadata_browser_configuration');
	delete_option('metadata_browser_browse_points');
	// check these
	
	$db = get_db();
    $sql = "DROP TABLE IF EXISTS `$db->MetadataBrowserCategory`";
    $db->query($sql);
}


function metadata_browser_admin_nav($navArray)
{
    //if (has_permission('XmlSitemap_Index', 'index')) {
        $navArray = $navArray + array('Metadata Browser' => uri(array('module'=>'metadata-browser', 'controller' => 'index', 'action'=>'index'), 'default'));
    //}
    //$tabs['Xml Sitemap'] = uri('xml-sitemap');
  	//return $tabs;
    return $navArray;
}

// get the element name
function metadata_browser_get_category_name($id) {
	$db = get_db();
	$element = $db->getTable('Element')->find($id); // lookup via id value
	/*
	$select = $db->getTable('Element')->get_select()->where('id = ?', $id);
	$element = $db->getTable('Element')->fetchObjects($select);
	*/
	return $element->name;
	
}

/*
 * Helper function to return the current ID value of an element in the omeka
 * element table by supplying the name of the element. Used in creating links to 
 * different browsing categories. 
 */
function metadata_browser_get_element_id($name) {
	$db = get_db();
	$elementTable = $db->getTable('Element');
	$elementSelect = $elementTable->getSelect()->where("e.name = '$name'");
	$element =  $elementTable->fetchObject($elementSelect);
	return $element->id;
}

function metadata_browser_get_active_cat($name) {
	$db = get_db();
	$element_id = metadata_browser_get_element_id($name);
	$category = $db->getTable('MetadataBrowserCategory')->findByElementID($element_id);
	if($category->isActive()) {
		return $category; 
	} else { return "Browsing Option is not Active"; }
}
/* 
 * helper function that uses Omeka advanced search parameters
 * to pull together browsing options for a given 
 * string used as an element value
 * $id is an omeka "element_id" value for the element that will be serched
 *
 */
function metadata_browser_create_link($id, $value) {
	// utility function to create browsing link for display in a theme 
	// querystring based on current omeka advanced search linking
	$clean_value = trim($value); // remove whitespace at start or end of string
	$queryURL = uri("items/browse?search=&advanced[0][element_id]=" . $id . "&advanced[0][type]=contains&advanced[0][terms]=" . $clean_value . "&range=&collection=&type=&tags=&submit_search=Search");
 	$resultsLink = '<a class="browse-link" href="' . $queryURL . '" title="Browse ' . $clean_value . '">' . $clean_value . "</a>";
  	return $resultsLink;
}

// create a link when display_name is passed instead of id value


function metadata_browser_create_url($id, $value) {
	/* returns just a URL to browse to a certain element value string
	 * rather than the entirely link.
	 */
	
	$clean_value = trim($value); // remove whitespace at start or end of string
	$queryURL = htmlspecialchars("items/browse?search=&advanced[0][element_id]=" . $id . "&advanced[0][type]=contains&advanced[0][terms]=" . $clean_value . "&range=&collection=&type=&tags=&submit_search=Search");
  	return $queryURL;
}

function metadata_browser_element_link($name,$value) {
	/* takes element name and value to build link
           for use primarily on "item/show page of theme
        */
	$id = metadata_browser_get_element_id($name);
	$clean_value = trim($value); // remove whitespace at start or end of string
        $queryURL = uri("items/browse?search=&advanced[0][element_id]=" . $id . "&advanced[0][type]=contains&advanced[0][terms]=" . $clean_value . "&range=&collection=&type=&tags=&submit_search=Search");
        $resultsLink = '<a class="browse-link" href="' . $queryURL . '" title="Browse ' . $clean_value . '">' . $clean_value . "</a>";
        return $resultsLink;
}

// need to define the routes 
function metadata_browser_define_routes($router) {
	
	// The only parameters here are the id value of the element and the string to be searched. 
	// route goal is convert the following style URL: 
	// 
	//items/browse?advanced[0][element_id]=51&advanced[0][type]=contains&advanced[0][terms]=photographs&submit_search=Search
	// 
	// into something that looks like:
	//
	// items/browse/51/contains/photographs/Search
	// were 51 is the format element type ID and "photographs" is a format element values
	/*
	$route = new Zend_Controller_Router_Route(
    'items/browse/:advanced[0][element_id]/:advanced[0][type]/:advanced[0][terms]/:submit_action',
    array(
        'controller' => 'items',
        'action'     => 'browse',
    	'advanced[0][element_id]' => '51',
    	'advanced[0][type]' => 'contains',
    	'advanced[0][terms]' => 'photographs',
    	'submit_search' => 'Search'
    	)
    );
    */
	
	/* route for public "category" index page displaying 
	 * all current active public categories.
	 */
    $categoryindex = new Zend_Controller_Router_Route(
    '/category/', array('module' => 'metadata-browser',
    					   'controller' => 'category',
    					   'action' => 'index')); //  
    $router->addRoute('index', $categoryindex);

    /* route to pass element_id to browsing routine */
    $admincatid = new Zend_Controller_Router_Route(
    '/metadata-browser/index/show/:element_id', 
    array('module' => 'metadata-browser',
    		'controller' => 'index',
    		'action' => 'show'), array('id' => '[0-9]+'));
    $router->addRoute('adminbrowse', $admincatid);
    
	/*
	 * Create a route for each current active category 
	 * using the slug given to the category
	 * If you want to create route for every possible category
	 * try the findAll() method instead of findActiveCategories().
	 */
    
    $categories = get_db()->getTable('MetadataBrowserCategory')->findActiveCategories();
    foreach($categories as $cat) {
        $router->addRoute(
            'metadata_browser_show_cat_' . $cat->id, 
            new Zend_Controller_Router_Route(
                '/category/' . $cat->slug, 
                array(
                    'module'       => 'metadata-browser', 
                    'controller'   => 'category', 
                    'action'       => 'browse', 
                    'id'           => $cat->id
                )
            )
        );
    }
    
    
    
}

/* slug code taken "simplePagesPage plugin model class"
 * maybe this should be defined as a method for the MetadataBrowserCategory Class
 */
function metadata_browser_generate_slug($seed) {
	$seed = trim($seed);
	$seed = strtolower($seed);
    // Replace spaces with dashes.
  	$seed = str_replace(' ', '-', $seed);
  	$seed = str_replace('/', '-', $seed);
  	// Remove all but alphanumeric characters, underscores, and dashes.
   	return preg_replace('/[^\w\/-]/i', '', $seed);
}


function metadata_browser_generate_element_select() {

	/* Used by "browse" view in admin views directory 
	 * 
	 * this function awkwardly reads the "ElementSet" and 
	 * element tables in order to display all possible
	 * browsing categories based on all available elements
	 * including those that may been added to the set of 
	 * available elements since the the initial installation of 
	 * the plugin.
	 * 
	 */
	$db = get_db();
	$element_sets = $db->getTable('ElementSet')->findall();
	// alternate query to sort element element sets
	
	$setlist = "";
	foreach ($element_sets as $set) {
		
		// how do you do these queries? 
		$setelements = $db->getTable('Element')->findBySet($set->name);
		foreach($setelements as $element)
		{
			// get a category object if one exits with the element id in question
			$category = $db->getTable('MetadataBrowserCategory')->findByElementID($element->id);
			
			$setlist .= "<tr>";
			if ($category)
			{				
				$setlist .= "<td>" . $category->display_name . "</td>";
				$setlist .= "<td>" . $category->slug . "</td>";
				$setlist .= "<td>" . $set->name . "</td>";
				$setlist .= "<td>" . metadata_browser_active_checkbox($category->id) . "</td>";	
				$setlist .= "<td>[<a href='" . html_escape(uri('metadata-browser/index/show/' . $category->element_id)) . "'>View Assigned Values</a>]</td>";
				$setlist .= "<td><a class='edit' href='" . html_escape(uri("metadata-browser/index/edit/id/" . $category->id)) . "'>Edit</a></td>";
			}
			else // if the category does not exist yet use element information to make a skeleton record
			{
				$setlist .= "<td>" . $element->name . "</td>";
				$setlist .= "<td>" . metadata_browser_generate_slug($element->name) . "</td>";
				$setlist .= "<td>" . $set->name . "</td>";
				$setlist .= "<td><a class='add' href='" . html_escape(uri("metadata-browser/index/add/id/" . $element->id)) . "'>Activate</a></td>";	
				$setlist .= "<td>[<a href='" . html_escape(uri('metadata-browser/index/show/' . $element->id)) . "'>View Assigned Values</a>]</td>";
				$setlist .= "<td></td>";
			}
		$setlist .= "</tr>";
		}
	}
	
	return $setlist;	
}

function metadata_browser_get_cat_description($element_id)
	{
		$db = get_db();
		$element = $db->getTable('Element')->find($element_id);
		return $element->description;
	}

function metadata_browser_is_active($id) {
	$db = get_db();
	$element = $db->getTable('MetadataBrowserCategory')->find($id);
	return $element->active;
}

function metadata_browser_active_checkbox($id)
{
	/* generate a checkbox to activate public browsing for a given category
	 * for use within the "browse" view of the admin pages for the plugin.
	 * This is also used by the "powerEdit" method of the index controller
	 * to process category activations in batch mode using the browse view.
	*/
	$checkbox = '';
	$checked = (bool) metadata_browser_is_active($id);
	$checkbox .= hidden($atrributes = array('name' => 'categories[' . $id . '][active]'), 0);
	$checkbox .= checkbox($attributes = array('name' => 'categories[' . $id . '][active]', 'id' => 'categories-' . $id . '-active', 'class' => 'checkbox make-active'), $checked, null);
	$checkbox .= hidden($attributes = array('name' => 'categories[' . $id . '][id]'), $id);	
	
	return $checkbox;
}

?>
