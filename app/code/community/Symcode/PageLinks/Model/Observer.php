<?php
/**
 * This file is part of the Symcode project.
 *
 * Symcode_PageLinks is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * Observer class to manipulate the page links
 *
 * @category  Symcode
 * @package   Symcode_PageLinks
 * @author    Symcode Team <info@symcode.de>
 * @copyright 2013 Symcode Team (http://www.symcode.de). All rights reserved.
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */
class Symcode_PageLinks_Model_Observer extends Mage_Core_Model_Abstract
{
    /**
     * add new elements to cms/page edit form
     * 
     * @param Varien_Event_Observer $observer
     * @return Symcode_PageLinks_Model_Observer
     */
	public function addShowLinkElement(Varien_Event_Observer $observer)
	{
		$form = $observer->getForm();
		
		if(Mage::getSingleton('admin/session')->isAllowed('cms/page/save'))
		{
			$isElementDisabled = false;
		}
		else
		{
			$isElementDisabled = true;
		}
		
        // get pageLink entity from current page
		$cms		= Mage::registry('cms_page');
		$pageLink	= Mage::getModel('pageLinks/cms_pageLink')->load($cms->getId(), 'page_id');
		
        // set data to object to show in form
		$cms->setData('show_link', $pageLink->getShowLink());
		$cms->setData('link_position', $pageLink->getLinkPosition());
		
        // tab "main" fieldset
		$fieldset = $form->getElements()->searchById('base_fieldset');
		
        // add additional elements
		if($fieldset)
		{
			$fieldset->addField('show_link', 'select', array(
				'label'     => Mage::helper('pageLinks')->__('Show link'),
				'title'     => Mage::helper('pageLinks')->__('Show link'),
				'name'      => 'show_link',
				'required'  => false,
				'options'   => $pageLink->getAvailableShowLinks(),
				'disabled'  => $isElementDisabled,
			));	
			
			$fieldset->addField('link_position', 'text', array(
				'label'     => Mage::helper('pageLinks')->__('Link position'),
				'title'     => Mage::helper('pageLinks')->__('Link position'),
				'name'      => 'link_position',
				'required'  => false,
				'disabled'  => $isElementDisabled,
			));	
		}
		
		return $this;
	}
	
    /**
     * save cms/page link options
     * 
     * @param Varien_Event_Observer $observer
     * @return Symcode_PageLinks_Model_Observer
     */
	public function savePageLink(Varien_Event_Observer $observer)
	{
		$cms		= $observer->getDataObject();
        /* @var $cms Mage_Cms_Model_Page */
		
		$pageLink	= Mage::getModel('pageLinks/cms_pageLink')->load($cms->getId(), 'page_id');
        /* @var $pageLink Symcode_PageLinks_Model_Cms_PageLink */
		
        // set data to pagelink entity
		$pageLink->setShowLink($cms->getShowLink());
		
		$pageLink->setLinkPosition($cms->getLinkPosition());
		
        // set pageid for new object
		$pageLink->setPageId($cms->getId());
		
		$pageLink->save();
		
		return $this;
	}
	
    /**
     * add cms/page link to top links / footer links
     * 
     * @param Varien_Event_Observer $observer
     * @return Symcode_PageLinks_Model_Observer
     */
	public function addTemplateLinks(Varien_Event_Observer $observer)
	{
		$block = $observer->getBlock();
        /* @var $block Mage_Page_Block_Template_Links */
		
		$blockName = $block->getNameInLayout();
		
        // only execute the script if current block is toplinks or footer links
		if($blockName == 'top.links' || $blockName == 'footer_links')
		{
			$collection = Mage::getModel('pageLinks/cms_pageLink')->getResourceCollection();
            /* @var $collection Symcode_PageLinks_Model_Mysql4_Cms_PageLink_Collection */
            
            // set internal name by block name
			if($blockName == 'top.links')
			{
				$area = 'top_links';
			}
			else
			{
				$area = 'footer_links';
			}
			
            // get all links by area
			$collection->loadLinksByArea($area);
			
            // add links to block
			foreach($collection as $pageLink)
			{
				$block->addLink(
					$pageLink->getTitle(),
					$pageLink->getIdentifier(),
					$pageLink->getTitle(), 
					true,
					array(),
					$pageLink->getLinkPosition()	
				);
			}
		}		
		
		return $this;
	}
	
    /**
     * add cms/page link to menu links
     * 
     * @param Varien_Event_Observer $observer
     * @return Symcode_PageLinks_Model_Observer
     */
	public function addMenuLinks(Varien_Event_Observer $observer)
	{ 
		$menu = $observer->getMenu();
        /* @var $menu Varien_Data_Tree_Node */
        
        $tree = $menu->getTree();
        
        $menuLinkNode = Mage::getModel('pageLinks/cms_menuLink_node');
        /* @var $menuLinkNode Symcode_PageLinks_Model_Cms_MenuLink_Node */
        
        // get child nodes from root node as array
        $nodesFirstLevel = $menuLinkNode->getChildNodesAsArray($menu);
		
        // get links by cms page
        $page = Mage::getSingleton('cms/page');
        
        $pageLinksNodes = $menuLinkNode->getPageLinkNodes($tree, $page);
        
        // merge catalog links and cms links
        $nodesFirstLevel = array_merge($nodesFirstLevel, $pageLinksNodes);
        
        // sort links new
        usort($nodesFirstLevel, array(Mage::helper('pageLinks'), 'sortMenuLinks'));
        
        foreach($nodesFirstLevel as $child)
        {
            $menu->addChild($child);
        }
            
		return $this;
	}
}