<?php
/**
 * Default settings for the geogebrembed plugin
 *
 * @author Philipp Imhof <dev@imhof.cc>
 */

// configuration options MUST start with config_
$conf['config_url'] = 'https://cdn.geogebra.org/apps/deployggb.js';
$conf['config_threshold'] = 12;

// default parameters for GeoGebra applets
// these MUST start with default_ and then use the exact spelling of the
// parameter as described on GeoGebra's reference page
$conf['default_width'] = 800;
$conf['default_height'] = 600;
$conf['default_appName'] = 'classic';
$conf['default_borderColor'] = '#808080';
$conf['default_enableRightClick'] = 1;
$conf['default_enableLabelDrags'] = 1;
$conf['default_enableShiftDragZoom'] = 1;
$conf['default_showZoomButtons'] = 0;
$conf['default_showMenuBar'] = 0;
$conf['default_showToolBar'] = 0;
$conf['default_showAlgebraInput'] = 0;
$conf['default_showResetIcon'] = 0;
$conf['default_playButton'] = 0;
$conf['default_showAnimationButton'] = 1;
$conf['default_showFullscreenButton'] = 0;



