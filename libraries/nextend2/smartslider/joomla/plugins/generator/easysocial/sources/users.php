<?php
N2Loader::import('libraries.slider.generator.abstract', 'smartslider');

class N2GeneratorEasySocialUsers extends N2GeneratorAbstract {

    protected $layout = 'article';

    private function removeSpaces($str) {
        return str_replace(' ', '', $str);
    }

    private function runIni($title) {
        if (function_exists('parse_ini_file')) {
            $language = parse_ini_file(JPATH_ROOT . '/administrator/language/en-GB/en-GB.com_easysocial.ini');
            if (isset($language[$title])) {
                return $this->removeSpaces($language[$title]);
            } else {
                return $title;
            }
        } else {
            return $title;
        }
    }

    public function renderFields($form) {
        parent::renderFields($form);

        $filter = new N2Tab($form, 'filter', n2_('Filter'));

        $source = new N2ElementGroup($filter, 'source', n2_('Source'));

        new N2ElementEasySocialCategories($source, 'easysocialprofiles', n2_('Profiles'), 0, array(
            'isMultiple' => true,
            'size'       => 10,
            'table'      => 'social_profiles'
        ));

        new N2ElementEasySocialCategories($source, 'easysocialbadges', n2_('Badges'), 0, array(
            'isMultiple' => true,
            'size'       => 10,
            'table'      => 'social_badges',
            'orderBy'    => 'id',
            'ini'        => 1
        ));

        $limit = new N2ElementGroup($filter, 'limit', n2_('Limit'));
        new N2ElementText($limit, 'maxphotos', 'Maximum photos asked down per user', '3');

        new N2ElementText($limit, 'allowed-users', n2_('Allowed user IDs'), '', array('tip' => n2_('Separate them by comma.')));

        new N2ElementText($limit, 'banned-users', n2_('Banned user IDs'), '', array('tip' => n2_('Separate them by comma.')));

        $_order = new N2Tab($form, 'order', n2_('Order by'));
        $order  = new N2ElementMixed($_order, 'easysocialorder', n2_('Order'), 'u.registerDate|*|desc');
        new N2ElementList($order, 'easysocialorder-1', n2_('Field'), '', array(
            'options' => array(
                ''                => n2_('None'),
                'u.registerDate'  => n2_('Register date'),
                'u.lastvisitDate' => n2_('Last visit date'),
                'points'          => n2_('Points'),
                'u.name'          => n2_('Name'),
                'u.id'            => 'ID'
            )
        ));

        new N2ElementRadio($order, 'easysocialorder-2', n2_('order'), '', array(
            'options' => array(
                'asc'  => n2_('Ascending'),
                'desc' => n2_('Descending')
            )
        ));

    }

    protected function _getData($count, $startIndex) {

        $model = new N2Model('EasySocial_Events');

        $where = array(
            "su.state <> '0'"
        );

        $profiles = array_map('intval', explode('||', $this->data->get('easysocialprofiles', '')));
        if (!in_array('0', $profiles)) {
            $where[] = 'spm.profile_id IN (' . implode(',', $profiles) . ')';
        }

        $badges = array_map('intval', explode('||', $this->data->get('easysocialbadges', '')));
        if (!in_array('0', $badges)) {
            $where[] = 'u.id IN (SELECT user_id FROM #__social_badges_maps WHERE badge_id IN (' . implode(',', $badges) . '))';
        }

        $allowedUsers = $this->data->get('allowed-users', '');
        if (!empty($allowedUsers)) {
            $where[] = "u.id IN (" . $allowedUsers . ")";
        }

        $bannedUsers = $this->data->get('banned-users', '');
        if (!empty($bannedUsers)) {
            $where[] = "u.id NOT IN (" . $bannedUsers . ")";
        }

        $query = "SELECT u.name, u.username, u.email, u.id, SUM(sph.points) AS points FROM #__social_users AS su
                  LEFT JOIN #__users AS u ON u.id = su.user_id 
                  LEFT JOIN #__social_profiles_maps AS spm ON spm.user_id = su.user_id
                  LEFT JOIN #__social_points_history AS sph ON sph.user_id = su.user_id    
                  WHERE " . implode(' AND ', $where) . "  ";

        $order = N2Parse::parse($this->data->get('easysocialorder', 'u.registerDate|*|desc'));

        if ($order[0]) {
            $query .= 'GROUP BY su.user_id ORDER BY ' . $order[0] . ' ' . $order[1] . ' ';
        }

        $query  .= 'LIMIT ' . $startIndex . ', ' . $count;
        $result = $model->db->queryAll($query);

        if (!class_exists('FRoute')) {
            $file = JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_easysocial' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'easysocial.php';
            if (file_exists($file)) {
                require_once($file);
            }
            require_once(JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_easysocial' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'router.php');
        }

        $avatar    = ES::table('Avatar');
        $photo     = ES::table('Photo');
        $maxPhotos = intval(N2Parse::parse($this->data->get('maxphotos', '3')));
        $data      = array();
        for ($i = 0; $i < count($result); $i++) {
            $r = array(
                'title'    => $result[$i]['name'],
                'name'     => $result[$i]['name'],
                'username' => $result[$i]['username'],
                'email'    => $result[$i]['email'],
                'id'       => $result[$i]['id']
            );

            if (!empty($result[$i]['points'])) {
                $r['points'] = $result[$i]['points'];
            } else {
                $r['points'] = 0;
            }

            $query = "SELECT id FROM #__social_photos WHERE uid = '" . $result[$i]['id'] . "' ORDER BY id DESC";
            if (!empty($maxPhotos)) {
                $query .= " LIMIT " . $maxPhotos;
            }
            $photo_ids = $model->db->queryAll($query);
            $j         = 0;
            foreach ($photo_ids AS $photo_id) {
                $j++;
                $photo->load($photo_id);
                $original       = $photo->getSource('original');
                $large          = $photo->getSource('large');
                $thumbnail      = $photo->getSource('thumbnail');
                $r              += array(
                    'photo' . $j . '_original'  => $original,
                    'photo' . $j . '_large'     => $large,
                    'photo' . $j . '_thumbnail' => $thumbnail,
                );
                $r['image']     = $original;
                $r['thumbnail'] = $thumbnail;
            }
            $avatar->load(array(
                'uid'  => $result[$i]['id'],
                'type' => 'user'
            ));

            if ($avatar->uid == $result[$i]['id']) {

                $avatar_small  = $avatar->getSource('small');
                $avatar_medium = $avatar->getSource('medium');
                $avatar_square = $avatar->getSource('square');
                $avatar_large  = $avatar->getSource('large');

                if (empty($r['image'])) {
                    $r['image'] = $avatar_large;
                }
                if (empty($r['thumbnail'])) {
                    $r['thumbnail'] = $avatar_square;
                }

                $r += array(
                    'avatar_small_image'  => $avatar_small,
                    'avatar_medium_image' => $avatar_medium,
                    'avatar_square_image' => $avatar_square,
                    'avatar_large_image'  => $avatar_large
                );
            }

            $user = Foundry::user($result[$i]['id']);
            $r    += array(
                'url' => JRoute::_($user->getPermalink('', true))
            );

            $query      = "SELECT sf.title, sfd.datakey, sfd.data FROM #__social_fields_data AS sfd LEFT JOIN #__social_fields AS sf ON sfd.field_id = sf.id WHERE uid = '" . $result[$i]['id'] . "' AND type = 'user'";
            $user_datas = $model->db->queryAll($query);
            $j          = 0;
            foreach ($user_datas AS $user_data) {
                if (!empty($user_data['title'])) {
                    $user_data['title'] = $this->removeSpaces($this->runIni($user_data['title']));
                }
                if (!empty($user_data['datakey'])) {
                    $user_data['datakey'] = $this->removeSpaces($user_data['datakey']);
                    $r                    += array(
                        $user_data['title'] . '_' . $user_data['datakey'] => $user_data['data']
                    );
                } else if (!empty($user_data['data']) && !empty($user_data['title'])) {
                    $r += array(
                        $user_data['title'] => $user_data['data']
                    );
                } else if (!empty($user_data['data'])) {
                    $j++;
                    $r += array(
                        'user_data' . $j => $user_data['data']
                    );
                }
            }

            $data[] = $r;
        }

        return $data;
    }
}