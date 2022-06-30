<?php
N2Loader::import('libraries.slider.generator.abstract', 'smartslider');

class N2GeneratorEasySocialPages extends N2GeneratorAbstract {

    protected $layout = 'article';

    public function renderFields($form) {
        parent::renderFields($form);

        $filter = new N2Tab($form, 'filter', n2_('Filter'));

        $source = new N2ElementGroup($filter, 'source', n2_('Source'));
        new N2ElementEasySocialCategories($source, 'easysocialcategories', n2_('Categories'), 0, array(
            'isMultiple' => true,
            'size'       => 10,
            'table'      => 'social_clusters_categories',
            'type'       => 'page'
        ));

        $limit = new N2ElementGroup($filter, 'limit', n2_('Limit'), array(
            'rowClass' => 'n2-expert'
        ));

        new N2ElementFilter($limit, 'featured', n2_('Featured'), 0);

        new N2ElementText($limit, 'allowed-users', n2_('Allowed user IDs'), '', array('tip' => n2_('Separate them by comma.')));

        new N2ElementText($limit, 'banned-users', n2_('Banned user IDs'), '', array('tip' => n2_('Separate them by comma.')));

        new N2ElementList($limit, 'accesstype', n2_('Type'), 0, array(
            'options' => array(
                '0' => n2_('All'),
                '1' => n2_('Public'),
                '2' => n2_('Private'),
                '3' => n2_('Invite Only')
            )
        ));

        new N2ElementList($limit, 'notification', n2_('Notification'), 0, array(
            'options' => array(
                '0' => n2_('All'),
                '1' => n2_('Both'),
                '2' => n2_('Email Only'),
                '3' => n2_('Internal Only'),
                '4' => n2_('None')
            )
        ));

        $_order = new N2Tab($form, 'order', n2_('Order by'));
        $order  = new N2ElementMixed($_order, 'easysocialorder', n2_('Order'), 'a.created|*|desc');
        new N2ElementList($order, 'easysocialorder-1', n2_('Field'), '', array(
            'options' => array(
                ''          => n2_('None'),
                'a.title'   => n2_('Title'),
                'a.created' => n2_('Creation time'),
                'a.hits'    => n2_('Hits'),
                'a.id'      => 'ID'
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
            "a.cluster_type = 'page'",
            "a.state = '1'"
        );

        $category = array_map('intval', explode('||', $this->data->get('easysocialcategories', '')));

        if (!in_array('0', $category)) {
            $where[] = 'a.category_id IN (' . implode(',', $category) . ')';
        }

        switch ($this->data->get('featured', 0)) {
            case 1:
                $where[] = 'a.featured = 1';
                break;
            case -1:
                $where[] = 'a.featured = 0';
                break;
        }

        $type = $this->data->get('accesstype', 0);
        if ($type != 0) {
            $where[] = 'a.type = ' . $type;
        }

        $type = $this->data->get('notification', 0);
        if ($type != 0) {
            $where[] = 'a.notification = ' . $type;
        }

        $allowedUsers = $this->data->get('allowed-users', '');
        if (!empty($allowedUsers)) {
            $where[] = "a.creator_uid IN (" . $allowedUsers . ")";
        }

        $bannedUsers = $this->data->get('banned-users', '');
        if (!empty($bannedUsers)) {
            $where[] = "a.creator_uid NOT IN (" . $bannedUsers . ")";
        }

        $query = "SELECT
                  a.title, a.description, a.created, a.hits, a.category_id, a.id, a.alias,                  
                  c.small, c.medium, c.square, c.large, c.uid,
                  (SELECT photo_id FROM #__social_covers WHERE uid = a.id and type='page' LIMIT 1) AS photo_id
                  FROM #__social_clusters AS a
                  LEFT JOIN #__social_avatars AS c ON c.uid = a.id
                  WHERE " . implode(' AND ', $where) . "  ";

        $order = N2Parse::parse($this->data->get('easysocialorder', 'a.created|*|desc'));
        if ($order[0]) {
            $query .= 'ORDER BY ' . $order[0] . ' ' . $order[1] . ' ';
        }

        $query .= 'LIMIT ' . $startIndex . ', ' . $count;

        $result = $model->db->queryAll($query);
        $root   = N2Uri::getBaseUri();

        if (!class_exists('FRoute')) {
            $file = JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_easysocial' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'easysocial.php';
            if (file_exists($file)) {
                require_once($file);
            }
            require_once(JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_easysocial' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'router.php');
        }

        $urlOptions = array(
            'layout'   => 'item',
            'external' => false,
            'sef'      => true
        );

        $avatar = ES::table('Avatar');
        $photo  = ES::table('Photo');

        $data = array();
        for ($i = 0; $i < count($result); $i++) {
            $urlOptions['id'] = $result[$i]['id'];
            $photo->load($result[$i]['photo_id']);
            $avatar->load(array(
                'uid'  => $result[$i]['uid'],
                'type' => 'page'
            ));
            $r = array(
                'title'       => $result[$i]['title'],
                'description' => $result[$i]['description']
            );

            $r['thumbnail'] = $photo->getSource('thumbnail');
            $r['image']     = N2JoomlaImageFallBack::fallback($root, array(
                $photo->getSource('original'),
                $photo->getSource('large')
            ), array());

            if ($r['thumbnail'] == '' && $r['image'] != '') {
                $thumbnail      = $photo->getSource('thumbnail');
                $r['thumbnail'] = !empty($thumbnail) ? $thumbnail : $r['image'];
            }

            $r += array(
                'large_image'         => $photo->getSource('large'),
                'avatar_small_image'  => $avatar->getSource('small'),
                'avatar_medium_image' => $avatar->getSource('medium'),
                'avatar_square_image' => $avatar->getSource('square'),
                'avatar_large_image'  => $avatar->getSource('large'),
                'url'                 => FRoute::pages($urlOptions, true),
                'hits'                => $result[$i]['hits'],
                'creation_time'       => $result[$i]['created'],
                'alias'               => $result[$i]['alias'],
                'category_id'         => $result[$i]['category_id'],
                'id'                  => $result[$i]['id']
            );

            $data[] = $r;
        }

        return $data;
    }
}