<?php

/* 
 * @copyright New York Metropolitan Library Council, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package MetadataBrowser
 * @ Author: Kevin Reiss kevin.reiss@gmail.com
 * 
 */

// Require the MetadataBrowserCategory table class.
require_once 'MetadataBrowserCategoryTable.php';


class MetadataBrowserCategory extends Omeka_Record
{
    // automatic ID
	public $element_id; // element id that category links up with
	public $display_name;
	//public $parent_id; // if this a parent
	public $slug; // how will the slugs work out?
	public $active;
	
	
	/*
	public function getCategoryId()
	{
	    return $this->id;
	}
	
	public function getElementId()
	{
		return $this->element_id;
	}
	
	public function getCatSlug() 
	{
	    return $this->cat_slug;
	}
	
	// let's you change the public display name of the categry for the end user
	public function getDisplayName()
	{
		return $this->display_name;
	}
	*/
	public function isActive()
	{	
		if($this->active)	{
			return $this->active;
		}
		else { return 0; }
		
	}
	

    public function getAssignedValues()
    {
		$db = get_db();
		$texttable = $db->getTable('ElementText');
		$element_text_select = $texttable->getSelect()->where('ie.element_id = ?', (int)$this->element_id)->group('ie.text')->order('ie.text');
		$element_texts = $texttable->fetchObjects($element_text_select); 
		$values = array();
		
		foreach ($element_texts as $text) {
			if (!$text->isHTML()) {
				$value = $text->getText();
				array_push($values, $value);
			} // should see if an html version exists that can be processed 	
		}
		return $values;
    }
    
    public function getCategoryCount()
    {
    	$db = get_db();
		$texttable = $db->getTable('ElementText');
		$select = $texttable->getSelect()->where('ie.element_id =' . (int)$this->element_id)->group('ie.text')->order('ie.text');
		$rows = $texttable->fetchAll($select);
		/*
		$element_text_select = $texttable->getSelect()->where('ie.element_id = ?', (int)$this->element_id)->group('ie.text')->order('ie.text');
		
		$element_text_count = $texttable->fetchObjects($element_text_select)->count(); 
		*/
		return count($rows);
    }
    
    /*
     * Returns the description assigned to an Omeka element for a selected category.
     * Could be rewritten to allow the user to include a more colloquiol definition.
     */
    public function getCategoryDescription()
	{
		$db = get_db();
		$element = $db->getTable('Element')->find($this->element_id);
		return $element->description;
	}
	
}
?>