<?php

/* 
 * @copyright New York Metropolitan Library Council, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package MetadataBrowser
 * @ Author: Kevin Reiss kevin.reiss@gmail.com
 * 
 */

/* Controller handles public theme views for plugin
 * 
 * index Action displays a list of all active browsing category
 * it is routed to show up at WEB_ROOT/category s
 * 
 * browse Action depends on a valid route being defined
 * for the slug of the category in question.
 * 
 */

class MetadataBrowser_CategoryController extends Omeka_Controller_Action
{
	
	public function indexAction() 
	{
		$categories = $this->getTable('MetadataBrowserCategory')->findActiveCategories();
		//$this->view;
		$this->view->categories = $categories;
	}
	
	public function browseAction()
	{
		$id = $this->_getParam('id');
		//$catID = 46;
        $category = $this->getTable('MetadataBrowserCategory')->find($id);
	 //Restrict access to the page when it is not published.
	 // is this valid && !$this->isAllowed('show-unpublished')
        if (!$category->active) {
            $this->redirect->gotoUrl('403');
            return;
        }
        
        
		//$slug = $this->_getParam('slug');
		$this->view->category = $category;
	}
	
	
}

?>