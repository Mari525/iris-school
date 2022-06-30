<?php

N2Loader::import('libraries.slider.generator.abstract', 'smartslider');

class N2GeneratorFacebookPostsByPage extends N2GeneratorAbstract {

    protected $layout = 'image';

    public function renderFields($form) {
        parent::renderFields($form);

        $filter = new N2Tab($form, 'filter', n2_('Filter'));

        new N2ElementText($filter, 'page', n2_('Page'), 'Nextendweb');
        new N2ElementRadio($filter, 'endpoint', n2_('Type'), 'posts', array(
            'options' => array(
                'posts' => n2_('Posts'),
                'feed'  => n2_('Feed')
            )
        ));

        $group = new N2ElementGroup($filter, 'configuration', n2_('Configuration'));
        new N2ElementText($group, 'dateformat', n2_('Date format'), 'm-d-Y');
        new N2ElementText($group, 'timeformat', n2_('Time format'), 'H:i:s');
        new N2ElementOnOff($group, 'remove_spec_chars', n2_('Remove special characters'), 0);
        new N2ElementTextarea($filter, 'sourcetranslatedate', n2_('Translate date and time'), 'January->January||February->February||March->March', array(
            'fieldStyle' => 'width:300px;height: 100px;'
        ));
    }

    protected function _getData($count, $startIndex) {

        $api = $this->group->getConfiguration()
                           ->getApi();

        $data = array();
        try {
            $result = $api->sendRequest('GET', $this->data->get('page', 'nextendweb') . '/' . $this->data->get('endpoint', 'feed'), array(
                'offset' => $startIndex,
                'limit'  => $count,
                'fields' => implode(',', array(
                    'from',
                    'updated_time',
                    'attachments',
                    'picture',
                    'message',
                    'story',
                    'full_picture'
                ))
            ))
                          ->getDecodedBody();

            for ($i = 0; $i < count($result['data']); $i++) {
                $post = $result['data'][$i];

                $attachments       = $post['attachments'];
                $remove_spec_chars = $this->data->get("remove_spec_chars", 0);

                if (isset($attachments)) {

                    $record['link'] = isset($attachments['data'][0]['url']) ? $attachments['data'][0]['url'] : '';

                    $record['description'] = '';
                    $description_raw       = $attachments['data'][0]['description'];
                    if ($remove_spec_chars) {
                        if (isset($description_raw) && !empty($description_raw)) {
                            $description           = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $this->makeClickableLinks($description_raw));
                            $record['description'] = str_replace("\n", "<br/>", $description);
                        } else {
                            $record['description'] = "";
                        }
                    } else {
                        $record['description'] = isset($description_raw) ? str_replace("\n", "<br/>", $this->makeClickableLinks($description_raw)) : '';
                    }

                    $record['type']   = isset($attachments['data'][0]['type']) ? $attachments['data'][0]['type'] : '';
                    $record['source'] = isset($attachments['data'][0]['media']['source']) ? $attachments['data'][0]['media']['source'] : '';

                }

                if ($remove_spec_chars) {
                    if (isset($post['message']) && !empty($post['message'])) {
                        $message           = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $this->makeClickableLinks($post['message']));
                        $record['message'] = str_replace("\n", "<br/>", $message);
                    } else {
                        $record['message'] = "";
                    }
                } else {
                    $record['message'] = isset($post['message']) ? str_replace("\n", "<br/>", $this->makeClickableLinks($post['message'])) : '';
                }
                if (!isset($record['description'])) {
                    $record['description'] = $record['message'];
                }

                $record['story'] = isset($post['story']) ? $this->makeClickableLinks($post['story']) : '';
                $record['image'] = isset($post['full_picture']) ? $post['full_picture'] : '';


                $sourceTranslate = $this->data->get('sourcetranslatedate', '');
                $translateValue  = explode('||', $sourceTranslate);
                $translate       = array();
                if ($sourceTranslate != 'January->January||February->February||March->March' && !empty($translateValue)) {
                    foreach ($translateValue AS $tv) {
                        $translateArray = explode('->', $tv);
                        if (!empty($translateArray) && count($translateArray) == 2) {
                            $translate[$translateArray[0]] = $translateArray[1];
                        }
                    }
                }
                $record['date'] = $this->translate(date($this->data->get('dateformat', 'Y-m-d'), strtotime($result['data'][$i]['updated_time'])), $translate);
                $record['time'] = date($this->data->get('timeformat', 'H:i:s'), strtotime($result['data'][$i]['updated_time']));


                $data[$i] = &$record;
                unset($record);
            }
        } catch (Exception $e) {
            N2Message::error($e->getMessage());
        }

        return $data;
    }

    private function translate($from, $translate) {
        if (!empty($translate) && !empty($from)) {
            foreach ($translate AS $key => $value) {
                $from = str_replace($key, $value, $from);
            }
        }

        return $from;
    }
}