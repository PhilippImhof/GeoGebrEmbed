<?php
/**
 * Options for the geogebrembed plugin
 *
 * @author Philipp Imhof <dev@imhof.cc>
 */

$meta['config_url'] = array('string', '_caution' => 'security');
$meta['config_threshold'] = array('numeric');

$meta['default_width'] = array('numeric');
$meta['default_height'] = array('numeric');
$meta['default_appName'] = array('multichoice',
                                 '_choices' => array(
                                     'classic', 'graphing', 'geometry', '3d', 'suite', 'evaluator', 'scientific'
                                 ));
$meta['default_borderColor'] = array('string');
$meta['default_enableRightClick'] = array('onoff');
$meta['default_enableLabelDrags'] = array('onoff');
$meta['default_enableShiftDragZoom'] = array('onoff');
$meta['default_showZoomButtons'] = array('onoff');
$meta['default_showMenuBar'] = array('onoff');
$meta['default_showToolBar'] = array('onoff');
$meta['default_showAlgebraInput'] = array('onoff');
$meta['default_showResetIcon'] = array('onoff');
$meta['default_playButton'] = array('onoff');
$meta['default_showAnimationButton'] = array('onoff');
$meta['default_showFullscreenButton'] = array('onoff');
