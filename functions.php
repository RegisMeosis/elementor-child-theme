<?php

// @ini_set('display_errors', 'on');
// @error_reporting(E_ALL);

/******************************************************************************
    WIDGET ELFSIGHT
******************************************************************************/

function elfsight() { 
    ob_start();?>
    <script src="https://static.elfsight.com/platform/platform.js" data-use-service-core defer></script>
<div class="elfsight-app-28d2bf69-bc93-4653-82a8-e2f4289d1585" data-elfsight-app-lazy></div>
    <?php return ob_get_clean();
}
add_shortcode('elfsight_shortcode', 'elfsight');

/******************************************************************************
    MEO CONFIG -- NE PAS SUPPRIMER --
******************************************************************************/
require_once(get_stylesheet_directory() .'/meo-config/meo-functions.php');
// require_once(get_stylesheet_directory() .'/meo-config/meo-avis.php');