<?php

/* 
 * @copyright New York Metropolitan Library Council, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package MetadataBrowser
 * @ Author: Kevin Reiss kevin.reiss@gmail.com
 * 
 */

class MetadataBrowserCategoryTable extends Omeka_Db_Table
{
	
	public function findAllPagesOrderBySlug()
    {
        $select = $this->getSelect()->order('slug');
        return $this->fetchObjects($select);
    }   
	
    public function findAllPagesOrderByDisplayName()
    {
        $select = $this->getSelect()->order('display_name');
        return $this->fetchObjects($select);
    }   
    
	public function findByElementID($elementId)
    {
        $select = $this->getSelect()->where('element_id = ?', (int)$elementId);
	    return $this->fetchObject($select);
        
    }
    
    public function findActiveCategories()
    {
    	$select = $this->getSelect()->where('active = 1')->order('display_name');
    	return $this->fetchObjects($select);
    }
   
   
}
   	
?>