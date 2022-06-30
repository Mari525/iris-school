<?php
N2Loader::import('libraries.slider.generator.abstract', 'smartslider');


class N2GeneratorJEventsRepeatingEvents extends N2GeneratorAbstract {

    protected $layout = 'event';

    public function renderFields($form) {
        parent::renderFields($form);

        $filter = new N2Tab($form, 'filter', n2_('Filter'));

        $source = new N2ElementGroup($filter, 'source', n2_('Source'));
        new N2ElementJEventsCategories($source, 'sourcecategories', n2_('Category'), 0, array(
            'isMultiple' => true
        ));
        new N2ElementJEventsCalendars($source, 'sourcecalendars', 'Calendar', 0, array(
            'isMultiple' => true
        ));

        $limit = new N2ElementGroup($filter, 'limit', n2_('Limit'), array(
            'rowClass' => 'n2-expert'
        ));
        new N2ElementFilter($limit, 'noendtime', 'Specified end time', 0);

        new N2ElementText($limit, 'location', n2_('Location'), '*');
        new N2ElementText($limit, 'dateformat', n2_('Date format'), n2_('m-d-Y'));
        new N2ElementText($limit, 'timeformat', n2_('Time format'), 'G:i');
        new N2ElementMenuItems($limit, 'itemid', n2_('Menu item (item ID)'), 0);
        new N2ElementOnOff($filter, 'multiimages', 'JEvents Standard Image and File Uploads plugin', 0);

        $_order = new N2Tab($form, 'order', n2_('Order by'));
        $order  = new N2ElementMixed($_order, 'jeventsorder', n2_('Order'), 'a.dtstart|*|desc');
        new N2ElementList($order, 'jeventsorder-1', n2_('Field'), '', array(
            'options' => array(
                ''           => n2_('None'),
                'a.dtstart'  => n2_('Start date'),
                'a.dtend'    => n2_('End date'),
                'b.created'  => n2_('Creation time'),
                'a.modified' => n2_('Modification time'),
                'a.summary'  => n2_('Title'),
                'a.hits'     => n2_('Hits'),
                'b.ev_id'    => 'ID',
            )
        ));

        new N2ElementRadio($order, 'jeventsorder-2', n2_('order'), '', array(
            'options' => array(
                'asc'  => n2_('Ascending'),
                'desc' => n2_('Descending')
            )
        ));

    }

    private function formatDate($datetime, $dateOrTime, $format) {
        if ($dateOrTime == 1 || $datetime != '0000-00-00 00:00:00') {
            return date($format, strtotime($datetime));
        } else {
            return '0000-00-00';
        }
    }

    protected function _getData($count, $startIndex) {

        $categories = array_map('intval', explode('||', $this->data->get('sourcecategories', '')));
        $calendars  = array_map('intval', explode('||', $this->data->get('sourcecalendars', '')));

        $dateFormat = $this->data->get('dateformat', 'Y-m-d');
        if (empty($dateFormat)) {
            $dateFormat = 'Y-m-d';
        }

        $timeFormat = $this->data->get('timeformat', 'H:i:s');
        if (empty($timeFormat)) {
            $timeFormat = 'H:i:s';
        }

        $itemId = $this->data->get('itemid', '0');
        $model  = new N2Model('jevents_vevent');

        $innerWhere = array();
        if (!in_array('0', $categories)) {
            $innerWhere[] = ' catid IN(' . implode(', ', $categories) . ')';
        }
        if (!in_array('0', $calendars)) {
            $innerWhere[] = ' icsid IN(' . implode(', ', $calendars) . ')';
        }

        if (!empty($innerWhere)) {
            $innerWhereStrAll = 'WHERE';
            $innerWhereStrAll .= implode(' AND ', $innerWhere);
        } else {
            $innerWhereStrAll = '';
        }

        $where = array(
            "a.evdet_id IN (SELECT ev_id FROM #__jevents_vevent " . $innerWhereStrAll . ")",
            "a.evdet_id IN (SELECT eventid FROM #__jevents_repetition GROUP BY eventid HAVING COUNT(eventid) > 1)",
            "b.state = '1'"
        );

        if (N2Filesystem::existsFile(JPATH_SITE . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'jevents' . DIRECTORY_SEPARATOR . 'jevfiles' . DIRECTORY_SEPARATOR . 'jevfiles.php') && $this->data->get('multiimages', 0)) {
            $multi = true;
        } else {
            $multi = false;
        }

        $folder = '';
        if ($multi) {
            $plugin = JPluginHelper::getPlugin('jevents', 'jevfiles');
            $params = new JRegistry($plugin->params);
            $folder .= rtrim(JURI::root(false), '/') . '/' . trim($params->get('image_path', 'images'), '/') . '/' . trim($params->get('folder'), '/');
        }

        switch ($this->data->get('noendtime', 0)) {
            case 1:
                $where[] = 'a.noendtime = 0';
                break;
            case -1:
                $where[] = 'a.noendtime = 1';
                break;
        }

        $location = $this->data->get('location', '*');
        if ($location != '*' && !empty($location)) {
            $where[] = "location = '" . $location . "'";
        }

        $order = N2Parse::parse($this->data->get('jeventsorder', 'a.dtstart|*|asc'));
        if ($order[0]) {
            $orderBy = 'ORDER BY ' . $order[0] . ' ' . $order[1] . ' ';
        }

        $query = 'SELECT d.rp_id, b.ev_id, FROM_UNIXTIME(a.dtstart) AS event_start,
                    FROM_UNIXTIME(a.dtend) AS event_end, a.description, a.location, a.summary,
                    a.contact, a.hits, a.extra_info ';

        $query .= ' FROM #__jevents_vevdetail AS a LEFT JOIN #__jevents_vevent
                    AS b ON a.evdet_id = b.detail_id ';

        $query .= 'LEFT JOIN #__jevents_repetition AS d ON a.evdet_id = d.eventid ';

        $query .= ' WHERE ' . implode(' AND ', $where) . ' GROUP BY b.ev_id ' . $orderBy . ' LIMIT ' . $startIndex . ', ' . $count;

        $result = $model->db->queryAll($query);

        $data = array();

        if ($multi) {
            $query = "SELECT ev_id,";
            for ($i = 1; $i < 30; $i++) {
                $query .= "imagename" . $i . ",";
            }
            $query          .= "imagename30 FROM #__jev_files_combined WHERE ev_id IN (SELECT eventid FROM #__jevents_repetition GROUP BY eventid HAVING COUNT(eventid) > 1)";
            $jevfilesresult = $model->db->queryAll($query);
            foreach ($jevfilesresult AS $files) {
                $event_id = $files['ev_id'];
                unset($files['ev_id']);
                foreach ($files AS $file) {
                    if (!empty($file)) {
                        $jffile[$event_id][]           = $folder . '/' . $file;
                        $jffileoriginals[$event_id][]  = $folder . '/originals/orig_' . $file;
                        $jffilethumbnails[$event_id][] = $folder . '/thumbnails/thumb_' . $file;
                    }
                }
            }
        }

        foreach ($result AS $res) {
            $r = array(
                'title'       => $res['summary'],
                'description' => $res['description']
            );

            $image     = array();
            $thumbnail = array();
            if ($multi) {
                $i = 0;
                if (isset($jffile[$res['ev_id']])) {
                    $images = array();
                    foreach ($jffile[$res['ev_id']] AS $jff) {
                        $images += array(
                            'image_' . $i       => $jff,
                            'image_orig_' . $i  => $jffileoriginals[$res['ev_id']][$i],
                            'image_thumb_' . $i => $jffilethumbnails[$res['ev_id']][$i]

                        );
                        if (empty($image)) {
                            $image     = array( $images['image_orig_' . $i] );
                            $thumbnail = array( $images['image_thumb_' . $i] );
                        }
                        $i++;
                    }
                }
            }

            $r['image'] = N2JoomlaImageFallBack::fallback($folder . '/', $image, array(
                $res['description']
            ));

            $thumbnail[]    = $r['image'];
            $r['thumbnail'] = N2JoomlaImageFallBack::fallback($folder . '/', $thumbnail);

            $r      += array(
                'url'        => 'index.php?option=com_jevents&task=icalrepeat.detail&evid=' . $res['rp_id'] . '&Itemid=' . $itemId,
                'start_date' => $this->formatDate($res['event_start'], 0, $dateFormat),
                'start_time' => $this->formatDate($res['event_start'], 1, $timeFormat),
                'end_date'   => $this->formatDate($res['event_end'], 0, $dateFormat),
                'end_time'   => $this->formatDate($res['event_end'], 1, $timeFormat),
                'location'   => $res['location'],
                'contact'    => $res['contact'],
                'hits'       => $res['hits'],
                'extra_info' => $res['extra_info'],
                'ev_id'      => $res['ev_id'],
                'rp_id'      => $res['rp_id']
            );

            if ($multi) {
                $r = array_merge($r, $images);
            }
            $data[] = $r;
        }

        return $data;
    }
}