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
 * Model to manipulate the menu links (catalog navigation)
 *
 * @category  Symcode
 * @package   Symcode_PageLinks
 * @author    Symcode Team <info@symcode.de>
 * @copyright 2013 Symcode Team (http://www.symcode.de). All rights reserved.
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */
class Symcode_PageLinks_Model_Cms_MenuLink_Node extends Mage_Core_Model_Abstract
{
    /**
     * convert node collection to array
     * 
     * @param Varien_Data_Tree_Node $node
     * @return array
     */
    public function getChildNodesAsArray(Varien_Data_Tree_Node $node)
    {
        $childsAsArray = array();
        
        $nodeChilds = $node->getChildren();

        $counter = 1;

        foreach($nodeChilds as $key => $child)
        {
            // remove from collection to set memory free
            $node->removeChild($child);

            $child->setSort($counter * 10);

            $childsAsArray[$key] = $child;

            $counter++;
        }
        
        return $childsAsArray;
    }
    
    /**
     * get array of pagelink nodes
     * 
     * @param Varien_Data_Tree $tree
     * @param Mage_Cms_Model_Page $page
     * @return array
     */
    public function getPageLinkNodes(Varien_Data_Tree $tree, Mage_Cms_Model_Page $page)
    {
        $nodes      = array();
        
		$collection = Mage::getSingleton('pageLinks/cms_pageLink')->getResourceCollection();
        /* @var $collection Symcode_PageLinks_Model_Mysql4_Cms_PageLink_Collection */

		$collection->loadLinksByArea('top_menu');
		
		foreach($collection as $pageLink)
		{
            // set active class
			if($page->getId() == $pageLink->getPageId())
			{
				$isActive = true;
			}
			else
			{
				$isActive = false;
			}
            
            // create node
            $nodeId = 'pagelink-' . $pageLink->getPageId();
            
			$node = new Varien_Data_Tree_Node(
				array(
					'name'		=> $pageLink->getTitle(),
					'id'		=> $nodeId,
					'url'		=> Mage::getUrl($pageLink->getIdentifier()),
					'is_active'	=> $isActive,
				),
				'id',
				$tree
			);
            
            $node->setSort($pageLink->getLinkPosition());
            
            $nodes[$nodeId] = $node;
		}
        
        return $nodes;
    }
}