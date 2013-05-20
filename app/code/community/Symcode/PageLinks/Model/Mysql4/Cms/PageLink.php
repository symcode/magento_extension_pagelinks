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
 * pageLink entity resource
 *
 * @category  Symcode
 * @package   Symcode_PageLinks
 * @author    Symcode Team <info@symcode.de>
 * @copyright 2013 Symcode Team (http://www.symcode.de). All rights reserved.
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */
class Symcode_PageLinks_Model_Mysql4_Cms_PageLink extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('pageLinks/cms_pageLink', 'cms_pagelink_id');
	}
}