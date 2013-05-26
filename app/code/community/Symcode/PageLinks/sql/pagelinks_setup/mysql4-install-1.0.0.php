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
 * install pagelink database table
 *
 * @category  Symcode
 * @package   Symcode_PageLinks
 * @author    Symcode Team <info@symcode.de>
 * @copyright 2013 Symcode Team (http://www.symcode.de). All rights reserved.
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */
$installer = $this;

$table = $installer->getConnection()
    ->newTable($installer->getTable('pageLinks/cms_pageLink'))
    ->addColumn('cms_pagelink_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
		'unsigned'	=> true,
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Cms PageLink ID')
    ->addColumn('page_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'default'   => '0',
        ), 'Page ID')
    ->addColumn('show_link', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
		'nullable'  => false,
		'default'	=> 'no',
        ), 'Show Link')
    ->addColumn('link_position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'nullable'  => true,
        ), 'Link Position')
    ->addIndex(
        $installer->getIdxName(
            array('cms/page', 'int'),
            array('page_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('page_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName('pageLinks/cms_pageLink', array('show_link')),
        array('show_link'))
    ->addForeignKey(
        $installer->getFkName(
            array('cms/page', 'int'),
            'page_id',
            'cms/page',
            'page_id'
        ),
        'page_id', $installer->getTable('cms/page'), 'page_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE);

$installer->getConnection()->createTable($table);