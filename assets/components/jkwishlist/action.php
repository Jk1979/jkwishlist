<?php

if (empty($_REQUEST['action']) && empty($_REQUEST['jkw_action'])) {
    die('Access denied');
}

if (!empty($_REQUEST['action'])) {
    $_REQUEST['jkw_action'] = $_REQUEST['action'];
}

/** @noinspection PhpIncludeInspection */
require dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php';

