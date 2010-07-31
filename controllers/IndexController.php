<?php

/* 
 * @copyright New York Metropolitan Library Council, 2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package MetadataBrowser
 * @ Author: Kevin Reiss kevin.reiss@gmail.com
 * 
 */

/* actions for administrative theme views for plugin
 * 
 */


class MetadataBrowser_IndexController extends Omeka_Controller_Action
{	
	
	public function init()
    {
        // Set the model class so this controller can perform some functions, 
        // such as $this->findById()
        $this->_modelClass = 'MetadataBrowserCategory';
    }
	
	
	// list top-level browing categories selected via plug-in menu
	public function indexAction() {
		//$this->view;
		$this->redirect->goto('browse');
        return;
	}
	
	
	// show the results for an individual category
	public function showAction() {
		$this->view;
		$elementID = $this->_getParam('element_id');
		$category = $this->getTable('MetadataBrowserCategory')->findByElementID($elementID);
		//$this->view;
		$this->view->category = $category;
	}
	
	public function browseAction() {
		$this->view;	
		
	}
	
	public function editAction() {
		$category = $this->findById();
        $this->_processCategoryForm($category, 'edit');
	}
	
	public function addAction() {
		$category = new MetadataBrowserCategory();
		$elementID = $this->_getParam('id');
		$element = $this->getTable('Element')->find($elementID);
		$category->element_id = $element->id;
		$category->display_name = $element->name;
		$category->slug = metadata_browser_generate_slug($element->name);
		$this->_processCategoryForm($category, 'add');
	}

public function powerEditAction()
    {
        /*POST in this format:
                     categories[1][active],
                    categories[1][id],
                    categories[2]...etc
        */
        if (empty($_POST)) {
            $this->redirect->goto('browse');
        }
        try {
        	if ($categoryArray = $this->_getParam('categories')) {
                    
                //Loop through the IDs given and toggle
                foreach ($categoryArray as $k => $fields) {
                    
                    if(!array_key_exists('id', $fields) or
                    !array_key_exists('active', $fields)) { 
                        throw new Exception( 'Power-edit request was mal-formed!' ); 
                    }
                    
                    //$category = $this->findById($fields['id']);

                   	$category = $this->getTable('MetadataBrowserCategory')->find($fields['id']);
                    /*
                     * Following routine handles different cases of active/inactive values.
                     */
                   	if ($fields[active] == 1)
                   	{
                   		if(!$category->active)
                   		{
                   			$category->active = 1;
                   			$category->save();	
                   		}
                   	}
                   	else
                   	{
                   		if($category->active)
                   		{
                   			$category->active = 0;
                   			$category->save();
                   		}
                   	}
                    //$category->active = 0;
                    
                    
                }
            }
            
            $this->flashSuccess('Categories were successfully updated!');
            
        } catch (Exception $e) {
            $this->flash($e->getMessage());
        }
        
        $this->redirect->gotoUrl($_SERVER['HTTP_REFERER']);
    	
    }

private function _processCategoryForm($category, $action)
    {
        // Attempt to save the form if there is a valid POST. If the form 
        // is successfully saved, set the flash message, unset the POST, 
        // and redirect to the browse action.
        try {
            if ($category->saveForm($_POST)) {
                if ('add' == $action) {
                    $this->flashSuccess("The category \"$category->display_name\" has been added.");
                } else if ('edit' == $action) {
                    $this->flashSuccess("The category \"$category->display_name\" has been edited.");
                }
                unset($_POST);
                $this->redirect->goto('browse');
                return;
            }
        // Catch validation errors.
        } catch (Omeka_Validator_Exception $e) {
            $this->flashValidationErrors($e);
        // Catch any other errors that may occur.
        } catch (Exception $e) {
            $this->flash($e->getMessage());
        }
        // Set the page object to the view.
        $this->view->category = $category;
    }
}
?>
