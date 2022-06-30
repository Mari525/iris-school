<?php

N2Loader::import('libraries.form.element.list');

class N2ElementEasySocialCategories extends N2ElementList {
    protected $table       = '';
    protected $type        = '';
    protected $clusterType = '';
    protected $orderBy     = 'ordering, id';
    protected $ini         = false;

    public function __construct($parent, $name = '', $label = '', $default = '', $parameters = array()) {
        parent::__construct($parent, $name, $label, $default, $parameters);

        $model = new N2Model('social_categories');

        if (!empty($this->type)) {
            $type = "AND type='" . $this->type . "'";
        } else {
            $type = '';
        }

        if (!empty($this->clusterType)) {
            $cluserType = "AND cluster_type='" . $this->clusterType . "'";
        } else {
            $cluserType = '';
        }

        $categories = $model->db->queryAll("SELECT * FROM #__" . $this->table . " WHERE state = 1 " . $type . $cluserType . "  ORDER BY " . $this->orderBy, false, "object");

        $this->options[0] = n2_('All');

        if (count($categories)) {
            foreach ($categories AS $category) {
                $this->options[$category->id] = $this->runIni($category->title);
            }
        }
    }

    public function setTable($table) {
        $this->table = $table;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setClusterType($clusterType) {
        $this->clusterType = $clusterType;
    }

    public function setOrderBy($orderBy) {
        $this->orderBy = $orderBy;
    }

    public function setIni($ini) {
        $this->ini = true;
    }

    private function runIni($title) {
        if ($this->ini && function_exists('parse_ini_file')) {
            $language = parse_ini_file(JPATH_ROOT . '/language/en-GB/en-GB.com_easysocial.ini');
            if (isset($language[$title])) {
                return $language[$title];
            } else {
                return $title;
            }
        } else {
            return $title;
        }
    }
}