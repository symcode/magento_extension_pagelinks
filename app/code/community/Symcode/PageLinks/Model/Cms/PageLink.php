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
 * pageLink entity
 *
 * @category  Symcode
 * @package   Symcode_PageLinks
 * @author    Symcode Team <info@symcode.de>
 * @copyright 2013 Symcode Team (http://www.symcode.de). All rights reserved.
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */
class Symcode_PageLinks_Model_Cms_PageLink extends Mage_Core_Model_Abstract
{
    // Dont show element as link
	const DONT_SHOW = 'no';
    
    // Show element is top links
	const SHOW_IN_TOPLINKS = 'top_links';
    
    // Show element in menu links (navigation)
	const SHOW_IN_TOPMENU = 'top_menu';
    
    // Show element is footer links
	const SHOW_IN_FOOTERLINKS = 'footer_links';


	public function _construct()
	{
		$this->_init('pageLinks/cms_pageLink');
	}
	
    /**
     * options to sho link on page
     * 
     * @return array
     */
	public function getAvailableShowLinks()
	{
		$options = array(
			self::DONT_SHOW => Mage::helper('pageLinks')->__('Do not show'),
			self::SHOW_IN_TOPLINKS => Mage::helper('pageLinks')->__('Show in top links'),
			self::SHOW_IN_TOPMENU => Mage::helper('pageLinks')->__('Show in menu links'),
			self::SHOW_IN_FOOTERLINKS => Mage::helper('pageLinks')->__('Show in footer links'),
		);
		
		return $options;
	}
}