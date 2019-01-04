<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    $dev = MODX_BASE_PATH . 'Extras/Jkwishlist/';
    /** @var xPDOCacheManager $cache */
    $cache = $modx->getCacheManager();
    if (file_exists($dev) && $cache) {
        if (!is_link($dev . 'assets/components/jkwishlist')) {
            $cache->deleteTree(
                $dev . 'assets/components/jkwishlist/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_ASSETS_PATH . 'components/jkwishlist/', $dev . 'assets/components/jkwishlist');
        }
        if (!is_link($dev . 'core/components/jkwishlist')) {
            $cache->deleteTree(
                $dev . 'core/components/jkwishlist/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_CORE_PATH . 'components/jkwishlist/', $dev . 'core/components/jkwishlist');
        }
    }
}

return true;