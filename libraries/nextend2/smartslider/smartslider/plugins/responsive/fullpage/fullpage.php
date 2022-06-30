<?php
class N2SSPluginResponsiveFullPage extends N2SSPluginSliderResponsive {

    protected $name = 'fullpage';

    public $ordering = 3;

    public function getPath() {
        return dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->name . DIRECTORY_SEPARATOR;
    }

    public function getLabel() {
        return n2_x('Fullpage', 'Slider responsive mode');
    }

    public function renderFields($form) {
        $settings = new N2Tab($form, 'smartslider-responsive-full-page');

        new N2ElementNumberAutocomplete($settings, 'responsiveSlideWidthMax', n2_('Maximum slide width'), 3000, array(
            'style'  => 'width:40px;',
            'unit'   => 'px',
            'values' => array(
                3000,
                980
            )
        ));


        $forceFullWidth = new N2ElementGroup($settings, 'responsive-force-full-width', n2_('Force full width'), array(
            'tip' => n2_('The slider tries to fill the full width of the browser.')
        ));
        new N2ElementOnOff($forceFullWidth, 'responsiveForceFull', n2_('Enable'), 1);
        new N2ElementRadio($forceFullWidth, 'responsiveForceFullOverflowX', n2_('Horizontal mask'), 'body', array(
            'options' => array(
                'body' => 'body',
                'html' => 'html',
                'none' => n2_('None')
            )
        ));

        new N2ElementText($settings, 'responsiveForceFullHorizontalSelector', n2_('Adjust slider width to parent selector'), 'body', array(
            'tip' => n2_('When the jQuery selector of one of the slider\'s parent elements is specified, the slider tries to have the width and fill up that element instead of the window.')
        ));
        new N2ElementOnOff($settings, 'responsiveConstrainRatio', n2_('Constrain ratio'), 0, array(
            'tip' => n2_('The size of the slide proportionately changes together with its layers.')
        ));

        new N2ElementRadio($settings, 'sliderHeightBasedOn', n2_('Slider height based on'), 'real', array(
            'options' => array(
                'real'  => n2_('Real height'),
                '100vh' => n2_('CSS 100vh')
            )
        ));

        $verticalOffset = new N2ElementGroup($settings, 'vertical-offset', n2_('Decrease slider height'));

        if (!$form->has('responsive-focus') && $form->has('responsiveHeightOffset')) {
            $old = $form->get('responsiveHeightOffset');

            $oldDefault = '';

            if ($old !== $oldDefault) {
                $form->set('responsive-focus', 1);
                $form->set('responsive-focus-top', $old);
            }
        }


        new N2ElementNumber($verticalOffset, 'responsiveDecreaseSliderHeight', n2_('By Constant'), 0, array(
            'unit' => 'px',
            'wide' => 4,
        ));

        new N2ElementList($verticalOffset, 'responsive-focus', n2_('By CSS selectors'), 0, array(
            'options'       => array(
                0 => n2_('Use global focus selectors'),
                1 => n2_('Use local selectors')
            ),
            'relatedFields' => array(
                'sliderresponsive-focus-top',
                'sliderresponsive-focus-bottom'
            )
        ));
        new N2ElementText($verticalOffset, 'responsive-focus-top', n2_('Top') . ' - ' . n2_('CSS selector (sum of heights)'), '', array(
            'style' => 'width:400px;'
        ));
        new N2ElementText($verticalOffset, 'responsive-focus-bottom', n2_('Bottom') . ' - ' . n2_('CSS selector (sum of heights)'), '', array(
            'style' => 'width:400px;'
        ));
    }

    public function parse($params, $responsive, $features) {
        $responsive->scaleDown = 1;
        $responsive->scaleUp   = 1;

        $features->align->align = 'normal';

        $responsive->maximumSlideWidth = intval($params->get('responsiveSlideWidthMax', 3000));

        $responsive->forceFull          = intval($params->get('responsiveForceFull', 1));
        $responsive->forceFullOverflowX = $params->get('responsiveForceFullOverflowX', 'body');

        $responsive->forceFullHorizontalSelector = $params->get('responsiveForceFullHorizontalSelector', 'body');
        $responsive->constrainRatio              = intval($params->get('responsiveConstrainRatio', 0));

        $responsive->sliderHeightBasedOn     = $params->get('sliderHeightBasedOn', 'real');
        $responsive->responsiveDecreaseSliderHeight = intval($params->get('responsiveDecreaseSliderHeight', 0));
    }
}

N2SSPluginSliderResponsive::addType(new N2SSPluginResponsiveFullPage);
