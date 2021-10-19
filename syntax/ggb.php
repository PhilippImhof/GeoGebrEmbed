<?php
/**
 * DokuWiki Plugin geogebrembed (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Philipp Imhof <dev@imhof.cc>
 */
class syntax_plugin_geogebrembed_ggb extends \dokuwiki\Extension\SyntaxPlugin {
    // track whether we have already imported GeoGebra's deployggb.js
    private $import_done = false;

    // count GeoGebra applets on any given page
    private $count = 0;

    // each applet gets its own set of parameters
    private $params = '';

    // track whether we have already imported GeoGebra's deployggb.js
    private $center = '';

    // each applet can have custom additional CSS classes 
    private $css_classes = '';
    
    /** @inheritDoc */
    public function getType() {
        return 'protected';
    }

    /** @inheritDoc */
    public function getPType() {
        return 'block';
    }

    /** @inheritDoc */
    public function getSort() {
        return 200;
    }

    /** @inheritDoc */
    public function connectTo($mode) {
        $this->Lexer->addEntryPattern('< ?ggb(?!ref|caption).*?>(?=.*?</ggb>)', $mode, 'plugin_geogebrembed_ggb');
    }

    /** @inheritDoc */
    public function postConnect() {
        $this->Lexer->addExitPattern('</ggb>', 'plugin_geogebrembed_ggb');
    }

    /** @inheritDoc */
    public function handle($match, $state, $pos, Doku_Handler $handler) {
        switch ($state) {
        case DOKU_LEXER_ENTER :
            // check whether the applet should be centered
            if (substr($match, 0, 2) == '< ') {
                $this->center = 'center';
                $match = str_replace('< ggb', '<ggb', $match);
            }
            else {
                $this->center = '';
            }

            // replace short form parameters by their official syntax
            $substitutions = array(
                '/\bfsb\b/' => 'showFullscreenButton=true',
                '/\bnofsb\b/' => 'showFullscreenButton=false',
                '/\brc\b/' => 'enableRightClick=true',
                '/\bnorc\b/' => 'enableRightClick=false',
                '/\bld\b/' => 'enableLabelDrags=true',
                '/\bnold\b/' => 'enableLabelDrags=false',
                '/\bsdz\b/' => 'enableShiftDragZoom=true',
                '/\bnosdz\b/' => 'enableShiftDragZoom=false',
                '/\bzb\b/' => 'showZoomButtons=true',
                '/\bnozb\b/' => 'showZoomButtons=false',
                '/\bab\b/' => 'showAnimationButton=true',
                '/\bnoab\b/' => 'showAnimationButton=false',
                '/\bmb\b/' => 'showMenuBar=true',
                '/\bnomb\b/' => 'showMenuBar=false',
                '/\btb\b/' => 'showToolBar=true',
                '/\bnotb\b/' => 'showToolBar=false',
                '/\bri\b/' => 'showResetIcon=true',
                '/\bnori\b/' => 'showResetIcon=false',
                '/\bai\b/' => 'showAlgebraInput=true',
                '/\bnoai\b/' => 'showAlgebraInput=false',
                '/\bsb\b/' => 'allowStyleBar=true',
                '/\bnosb\b/' => 'allowStyleBar=false',
                '/\bpb\b/' => 'playButton=true',
                '/\bnopb\b/' => 'playButton=false',
                '/\bborder\b/' => 'borderColor',
                '/\bbc\b/' => 'borderColor'
            );
            $params_raw = preg_replace(array_keys($substitutions), array_values($substitutions), $match);

            // search for optional CSS classes
            $regex = '/class=(["\'])([^\1]+?)\1/';
            if (preg_match($regex, $params_raw, $content)) {
                $this->css_classes = $content[2];
                $params_raw = preg_replace($regex, '', $params_raw);
            }
            else {
                $this->css_classes = '';
            }

            // split params at whitespace
            $params = preg_split('/\s/', substr($params_raw, 4, -1), -1, PREG_SPLIT_NO_EMPTY);

            // size, if specified in its short form, must be the first parameter
            // e.g. 400 (explicit width, auto height) or 400x300 (width x height)
            $size = array();
            if (preg_match('/^(\d+)(?:x(\d+))?$/', $params[0], $size)) {
                $params[0] = "width=$size[1]";
                if (count($size)==3) {
                    $params[0] .= ", height=$size[2], autoHeight=false";
                }
                else {
                    $params[0] .= ', autoHeight=true';
                }
            }

            // store parameter string 
            $this->params = implode(', ', str_replace('=', ': ', $params));
            return array($state, array('center' => $this->center,
                                       'css_classes' => $this->css_classes, 'params' => $this->params));
            
        case DOKU_LEXER_UNMATCHED :
            if (substr($match, 0, 2) == '{{') {
                $path = ml(preg_replace('/^\{\{([^|]+).*\}\}$/', '\1', $match));
                $this->params .= ", filename: \"$path\"";
            }
            // force interpretation as GeoGebra material ID
            else if (substr($match, 0, 3) == 'id:') {
                $material_id = substr($match, 3);
                $this->params .= ", material_id: \"$material_id\"";
            }
            else if (preg_match('/^[A-Z0-9]{0,'.$this->getConf('config_threshold').'}$/i', $match)) {
                $material_id = $match;
                $this->params .= ", material_id: \"$material_id\"";
            }
            else {
                if (base64_decode($match, true)) {
                    $this->params .= ", ggbBase64: \"$match\"";
                }
            }
            return array($state, array('center' => $this->center,
                                       'css_classes' => $this->css_classes, 'params' => $this->params));

        case DOKU_LEXER_EXIT :
            // load configuration, if needed
            if (!$this->configloaded) {
                $this->loadConfig();
            }

            // find unset parameters that have a pre-configured default value
            $default_settings = str_replace('default_', '', array_keys($this->conf));
            $current_settings = $this->params;
            foreach ($default_settings as $s) {
                // if the parameter is already set or if its name contains an underscore: discard it
                if (strstr($current_settings, $s) or strstr($s, '_')) continue;

                // do not set height if autoHeight is set to true
                if ($s == "height" and strstr($current_settings, 'autoHeight')) continue;

                // do not set width and height, if scaleContainerClass is used
                if ($s == "width" and strstr($current_settings, 'scaleContainerClass')) continue;
                if ($s == "height" and strstr($current_settings, 'scaleContainerClass')) continue;

                $current_settings .= ", $s: ";
                $val = $this->conf["default_$s"];
                switch (gettype($val)) {
                case "string":
                    $current_settings .= '"'.$val.'"';
                    break;
                case "integer":
                    if ($val === 0) {
                        $current_settings .= 'false';
                    }
                    else if ($val === 1) {
                        $current_settings .= 'true';
                    }
                    else {
                        $current_settings .= $val;
                    }
                }
            }

            $this->params = trim($current_settings, ' ,');
            return array($state, array('center' => $this->center,
                                       'css_classes' => $this->css_classes, 'params' => $this->params));

        default:
            return array();
        }
    }

    /** @inheritDoc */
    public function render($mode, Doku_Renderer $renderer, $data) {
        if ($mode !== 'xhtml') {
            return false;
        }

        // for first invocation: import deployment script and reset counter to zero
        if (!$this->import_done) {
            $url = $this->getConf('config_url');
            $renderer->doc .= "<script src=\"$url\" async></script>";
            $this->import_done = true;
            $this->count = 0;
            return true;
        } 

        // end of current GeoGebra applet: add div and inject applet
        if ($data[0] === DOKU_LEXER_EXIT) {
            $renderer->doc .= <<<GGB
<div class="geogebrembed-wrapper {$data[1]['center']}">
<div id="ggb-$this->count" class="geogebrembed {$data[1]['css_classes']}"></div>
</div>
<script> 
   window.addEventListener('load', () => {
      let import_$this->count = new GGBApplet({
         {$data[1]['params']}
      }, true);
      import_$this->count.inject("ggb-$this->count");
   });
</script>
GGB;

            // step the counter
            $this->count++;
            return true;
        }

        return true;
    }
}

