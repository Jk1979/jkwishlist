<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var Jkwishlist $Jkwishlist */
$jkw = $modx->getService('Jkwishlist', 'Jkwishlist', MODX_CORE_PATH . 'components/jkwishlist/model/', $scriptProperties);
if (!$jkw) {
    return 'Could not load Jkwishlist class!';
}

// Do your snippet code here. This demo grabs 5 items from our custom table.
$snippet = $modx->getOption('snippet', $scriptProperties, 'msProducts');
$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);
$getresources = $jkw->get();
//$modx->setPlaceholder('wishlog',json_encode($getresources));

if(!empty($getresources['products']))  $resources = implode(',',$getresources['products']);
else return '';
$data = array(
    'resources'=> $resources
);

$output = $modx->runSnippet($snippet,array_merge($scriptProperties,$data));


// Output
if (!empty($toPlaceholder)) {
    // If using a placeholder, output nothing and set output to specified placeholder
    $modx->setPlaceholder($toPlaceholder, $output);

    return '';
}
// By default just return output

return $output;
