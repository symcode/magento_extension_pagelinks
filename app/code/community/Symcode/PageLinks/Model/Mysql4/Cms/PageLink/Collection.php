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
 * pageLink entity collection
 *
 * @category  Symcode
 * @package   Symcode_PageLinks
 * @author    Symcode Team <info@symcode.de>
 * @copyright 2013 Symcode Team (http://www.symcode.de). All rights reserved.
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */
class Symcode_PageLinks_Model_Mysql4_Cms_PageLink_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	public function _construct()
	{
		$this->_init('pageLinks/cms_pageLink');
	}
	
    /**
     * 
     * @param string $area
     * @return Symcode_PageLinks_Model_Mysql4_Cms_PageLink_Collection
     */
	public function loadLinksByArea($area)
	{
        // filter current store & "all store"
		$storeIds = array(
			Mage_Core_Model_App::ADMIN_STORE_ID,
			Mage::app()->getStore()->getId()
		);
		
		$this->getSelect()
			->join(
				array('cms_table' => $this->getTable('cms/page')),
				'main_table.page_id = cms_table.page_id',
				array('title', 'identifier')
			)
			->join(
				array('cms_store' => $this->getTable('cms/page_store')),
				'cms_table.page_id = cms_store.page_id',
				array()
			)
			->where('cms_store.store_id IN (?)', $storeIds)
			->group('main_table.page_id')
		;
		
		$this->addFieldToFilter('show_link', $area);
		
		$this->load();
		
		return $this;
	}
}