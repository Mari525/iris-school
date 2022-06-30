<?php
N2Loader::import('libraries.slider.slides.slide.itemFactory', 'smartslider');

class N2SSItemJoomlaModule extends N2SSItemAbstract {

    protected $type = 'joomlamodule';

    public function render() {

        return '<div class="n2-ss-item-content n2-ow">{' . $this->data->get('positiontype', '') . ' ' . $this->data->get('positionvalue', '') . '}</div>';
    }

    public function _renderAdmin() {

        return $this->render();
    }
}
