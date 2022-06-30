<?php

class N2LinkParser {

    public static function parse($url, &$attributes, $isEditor = false) {
        if ($url == '#' || $isEditor) {
            $attributes['onclick'] = "return false;";
        }

        preg_match('/^([a-zA-Z]+)\[(.*)]$/', $url, $matches);
        if (!empty($matches)) {
            $class = 'N2Link' . $matches[1];
            if (class_exists($class, false)) {
                $url = call_user_func_array(array(
                    $class,
                    'parse'
                ), array(
                    $matches[2],
                    &$attributes,
                    $isEditor
                ));
            }
        } else {
            $url = N2ImageHelper::fixed($url);
        }

        return $url;
    }
}
class N2LinkLightbox {

    public static function parse($argument, &$attributes, $isEditor = false) {
        if (!$isEditor && !empty($argument)) {
            $attributes['n2-lightbox'] = '';

            if (!isset($attributes['class'])) $attributes['class'] = '';
            $attributes['class'] .= " n2-lightbox-trigger";
            $attributes['class'] .= " nolightbox";
            $attributes['class'] .= " no-lightbox";

            N2AssetsPredefined::loadLiteBox();

            $realUrls = array();
            $titles   = array();

            //JSON V2 storage
            if ($argument[0] == '{') {
                $data = json_decode($argument, true);

                $argument = N2ImageHelper::fixed(array_shift($data['urls']));
                if (!empty($data['titles'][0])) {
                    $attributes['data-title'] = array_shift($data['titles']);
                }

                if (count($data['urls'])) {
                    if ($data['autoplay'] > 0) {
                        $attributes['data-autoplay'] = intval($data['autoplay']);
                    }

                    for ($i = 0; $i < count($data['urls']); $i++) {
                        if (!empty($data['urls'][$i])) {
                            $realUrls[] = N2ImageHelper::fixed($data['urls'][$i]);
                            $titles[]   = !empty($data['titles'][$i]) ? $data['titles'][$i] : '';
                        }
                    }
                    $attributes['n2-lightbox-urls']   = implode(',', $realUrls);
                    $attributes['n2-lightbox-titles'] = implode('|||', $titles);
                    $attributes['data-litebox-group'] = md5(uniqid(mt_rand(), true));
                }

            } else {

                $urls     = explode(',', $argument);
                $parts    = explode(';', array_shift($urls), 2);
                $argument = N2ImageHelper::fixed($parts[0]);
                if (!empty($parts[1])) {
                    $attributes['data-title'] = $parts[1];
                }

                if (count($urls)) {
                    if (intval($urls[count($urls) - 1]) > 0) {
                        $attributes['data-autoplay'] = intval(array_pop($urls));
                    }
                    for ($i = 0; $i < count($urls); $i++) {
                        if (!empty($urls[$i])) {
                            $parts      = explode(';', $urls[$i], 2);
                            $realUrls[] = N2ImageHelper::fixed($parts[0]);
                            $titles[]   = !empty($parts[1]) ? $parts[1] : '';
                        }
                    }
                    $attributes['n2-lightbox-urls']   = implode(',', $realUrls);
                    $attributes['n2-lightbox-titles'] = implode('|||', $titles);
                    $attributes['data-litebox-group'] = md5(uniqid(mt_rand(), true));
                }
            }
        }

        return $argument;
    }
}



class N2LinkScrollToAlias {

    public static function parse($argument, &$attributes, $isEditor = false) {

        return N2LinkScrollTo::parse('[data-alias=\"' . $argument . '\"]', $attributes, $isEditor);
    }
}

class N2LinkScrollTo {

    private static function init() {
        static $inited = false;
        if (!$inited) {
            N2JS::addInline('window.n2ScrollSpeed=' . json_encode(intval(N2SmartSliderSettings::get('smooth-scroll-speed', 400))) . ';');
            $inited = true;
        }
    }

    public static function parse($argument, &$attributes, $isEditor = false) {
        if (!$isEditor) {
            self::init();
            switch ($argument) {
                case 'top':
                    $onclick = 'n2ss.scroll(event, "top");';
                    break;
                case 'bottom':
                    $onclick = 'n2ss.scroll(event, "bottom");';
                    break;
                case 'beforeSlider':
                    $onclick = 'n2ss.scroll(event, "before", N2Classes.$(this).closest(".n2-ss-slider").addBack());';
                    break;
                case 'afterSlider':
                    $onclick = 'n2ss.scroll(event, "after", N2Classes.$(this).closest(".n2-ss-slider").addBack());';
                    break;
                case 'nextSlider':
                    $onclick = 'n2ss.scroll(event, "next", this, ".n2-ss-slider");';
                    break;
                case 'previousSlider':
                    $onclick = 'n2ss.scroll(event, "previous", this, ".n2-ss-slider");';
                    break;
                default:
                    if (is_numeric($argument)) {
                        $onclick = 'n2ss.scroll(event, "element", "#n2-ss-' . $argument . '");';
                    } else {
                        $onclick = 'n2ss.scroll(event, "element", "' . $argument . '");';
                    }
                    break;
            }
            $attributes['onclick'] = $onclick;
        }

        return '#';
    }
}