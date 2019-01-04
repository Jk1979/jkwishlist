<?php
/** @var modX $modx */
switch ($modx->event->name) {


    case 'OnMODXInit':
        // Load extensions
        /** @var miniShop2 $miniShop2 */
        if ($jkw = $modx->getService('Jkwishlist', 'Jkwishlist', MODX_CORE_PATH . 'components/jkwishlist/model/')) {
            $jkw->initialize();
        }

        break;
    case 'OnHandleRequest':
        // Handle ajax requests
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
        if (empty($_REQUEST['jkw_action']) || !$isAjax) {
            return;
        }
        /** @var miniShop2 $miniShop2 */
        if ($jkw = $modx->getService('Jkwishlist', 'Jkwishlist', MODX_CORE_PATH . 'components/jkwishlist/model/')) {
            $response = $jkw->handleRequest($_REQUEST['jkw_action'], @$_POST);
            //@session_write_close();
            exit($response);
        }
        break;




}