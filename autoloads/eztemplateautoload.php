<?php

// Operator autoloading

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] = 
    array( 
        'script' => 'extension/nxc_multioptions/classes/nxcmultioptionsutils.php',
        'class' => 'NXCMultiOptionsUtils',
        'operator_names' => array( 'nmo_in_array', 'nmo_get_html_line' )
    );
?>