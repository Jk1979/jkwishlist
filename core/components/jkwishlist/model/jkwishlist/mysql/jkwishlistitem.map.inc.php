<?php
$xpdo_meta_map['JkwishlistItem']= array (
  'package' => 'jkwishlist',
  'version' => '1.1',
  'table' => 'jkwishlist_items',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'user' => 0,
    'products' => '',
  ),
  'fieldMeta' => 
  array (
    'user' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'products' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'json',
      'null' => true,
      'default' => '',
    ),
  ),
);
