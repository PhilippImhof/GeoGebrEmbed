<?php
/**
 * English language file for the geogebrembed plugin
 *
 * @author Philipp Imhof <dev@imhof.cc>
 */

$lang['config_url'] = 'Path to GeoGebra\'s <code>deployggb.js</code> script. It is generally fine to leave this option as is, but you might want to use your own copy of that script, e.g. for privacy reasons. Please note that the given resource will be linked as a <code>&lt;script&gt;</code> in all pages that make use of this plugin. Default: <code>https://cdn.geogebra.org/apps/deployggb.js</code>';
$lang['config_threshold'] = 'The plugin will automatically interpret the content as GeoGebra material ID if it is alphanumeric and <i>not longer</i> than this value. Otherwise it will interpret it as base64 encoded string. Default: <code>12</code>.';

$lang['default_width'] = 'Default width of the GeoGebra applet. Default: <code>800</code>';
$lang['default_height'] = 'Default height of the GeoGebra applet. Default: <code>600</code>';
$lang['default_appName'] = 'Type of GeoGebra applet to be used. Default: <code>classic</code>';
$lang['default_borderColor'] = 'Color of the border line drawn around the GeoGebra applet as hex triplet. Default: <code>#808080</code>.';
$lang['default_enableRightClick'] = 'Whether GeoGebra applet accepts right clicks. Setting this parameter to <code>false</code> disables context menus, properties dialogs and right-click-zooming. Default: <code>true</code>.';
$lang['default_enableLabelDrags'] = 'Whether labels may be dragged. Default: <code>true</code>';
$lang['default_enableShiftDragZoom'] = 'Whether the view should be moveable and zoomable. Default: <code>true</code>.';
$lang['default_showZoomButtons'] = 'Whether the zoom buttons (zoom in, zoom out, home) should be shown. Default: <code>false</code>.';
$lang['default_showMenuBar'] = 'Whether GeoGebra\'s menubar should be shown in the applet. Default: <code>false</code>.';
$lang['default_showToolBar'] = 'Whether GeoGebra\'s toolbar should be shown in the applet. Default: <code>false</code>.';
$lang['default_showAlgebraInput'] = 'Whether GeoGebra\'s input bar (input field, greek letters and command list) should be shown in the applet. Default: <code>false</code>.';
$lang['default_showResetIcon'] = 'Whether the small reset icon should be shown in the top right corner. Default: <code>false</code>.';
$lang['default_playButton'] = 'Whether a preview image and a play button should be rendered in place of the applet. The user has to click that button in order to initialize the applet. Default: <code>false</code>.';
$lang['default_showAnimationButton'] = 'Whether the animation button (play/pause) should be visible. Regardless of this setting, the button will not be shown, if there is no animation in the applet. Default: <code>true</code>.';
$lang['default_showFullscreenButton'] = 'Whether the fullscreen button should be visible. Default: <code>false</code>';
