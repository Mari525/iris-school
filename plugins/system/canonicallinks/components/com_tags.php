<?php
/**
 * @package      Canonical Links All in One
 * @copyright    Marko Dedovic / ManageCMS.com. All rights reserved.
 * @license      GNU GPLv2 <http://www.gnu.org/licenses/gpl.html> or later
 */
defined('_JEXEC') or die;

JLoader::register('TagsHelperRoute', JPATH_SITE . '/components/com_tags/helpers/route.php');
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_tags/models', 'TagsModel');

class CanonicallinksCom_tags extends PlgSystemCanonicallinks
{
    public $input;
    protected $tagMenuId;

    // override inherited constructor
    public function __construct()
    {
        $this->app = JFactory::getApplication();
        $this->input = JFactory::getApplication()->input;
    }

    public function setCanonical($parsed)
    {
        $jVersion = new JVersion();
        if(version_compare($jVersion->getShortVersion(), '3.8.0', '<')) {
            return;
        }
        if($parsed['view'] == 'tags' || ($parsed['view'] == 'tag') && !empty($parsed['id'])) {
            // $parsed['id'] can be array when direct menu to a tag is used, but multiple tags can be chosen in parameters
            if($parsed['view'] == 'tag' && is_array($parsed['id']) && count($parsed['id']) == 1) {
                $parsed['id'] = $parsed['id'][0];
            }
            else if($parsed['view'] == 'tag' && is_array($parsed['id'])) {
                // when multiple tags are used, ignore it
                return;
            }
            $canonical = $this->getCanonical($parsed);
            if(!empty($canonical)) {
                $limitstart = 0;
                if(!empty($parsed['limitstart'])) {
                    $limitstart = $parsed['limitstart'];
                }
                $this->setPaginationLinks($limitstart, $canonical, $parsed['view']);
                if(!empty($limitstart)) {
                    if(self::sh404sefEnabled()) {
                        $canonical .= '&limitstart=' . $limitstart;
                    }
                    else {
                        $canonical .= '&start=' . $limitstart;
                    }
                }
                $this->addCanonicalLink($canonical);
            }
        }
    }

    public function setPaginationLinks($limitstart, $link, $view)
    {
        $params = $this->app->getParams();
        $menuParams = new JRegistry;

        if(!empty($this->tagMenuId)) {
            $menu = $this->app->getMenu()->getItem($this->tagMenuId);
            if($menu) {
                $menuParams->loadString($menu->params);
            }
        }
        else if($menu = $this->app->getMenu()->getActive()) {
                $menuParams->loadString($menu->params);
            }
        $mergedParams = clone $menuParams;
        $mergedParams->merge($params);
        if($view == 'tags') {
            if($mergedParams->get('show_pagination_limit')) {
                $limit = $this->app->getUserStateFromRequest('global.list.limit', 'limit', $this->app->get('list_limit'), 'uint');
            }
            else {
                $limit = $mergedParams->get('maximum', 20);
            }
            $model = JModelLegacy::getInstance('Tags', 'TagsModel');
            $total = $model->getTotal();
        }
        else {
            $model = JModelLegacy::getInstance('Tag', 'TagsModel');
            $total = $model->getTotal();
            $limit = 20;
        }
        $this->addPaginationLinks($total, $limitstart, $limit, $link);
    }

    public function getCanonical($parsed)
    {
        JHtml::addIncludePath(JPATH_SITE . '/com_tags/helpers');
        if($parsed['view'] == 'tag' && !empty($parsed['id'])) {
            // first let's check if there is a direct menu item for a tag
            $itemId = $this->findItemId($parsed['view'], $parsed['id']);
            if(!empty($itemId)) {
                return 'index.php?option=com_tags&view=tag&id=' . $parsed['id'] . '&Itemid=' . $itemId;
            }
            else {
                // if not, then check if there is a menu for all tags, and attach alias so the url is pretty
                $itemId = $this->findItemId('tags');
                $alias = $this->findTagAlias($parsed['id']);
                if(!empty($itemId) && !empty($alias)) {
                    return 'index.php?option=com_tags&view=tag&id=' . $parsed['id'] . '-' . $alias . '&Itemid=' . $itemId;
                }
            }
        }
        else if($parsed['view'] == 'tags') {
            return TagsHelperRoute::getTagsRoute();
        }
        return false;
    }

    public function findTagAlias($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('alias')
            ->from($db->quoteName('#__tags'))
            ->where($db->quoteName('id') . ' = ' . $db->quote($id));

        return $db->setQuery($query)->loadResult();
    }

    public function findItemId($view, $id = 0)
    {
        $currentLanguage = JFactory::getLanguage()->getTag();
        $component = JComponentHelper::getComponent('com_tags');
        $menus = JFactory::getApplication()->getMenu('site');
        $tagMenus = $menus->getItems('component_id', $component->id);
        if(!empty($tagMenus)) {
            foreach($tagMenus as $menu) {
                if($view == 'tag' && !empty($id)) {
                    if('index.php?option=com_tags&view=tag&id[0]=' . $id === $menu->link) {
                        if($menu->language == '*' || $menu->language == $currentLanguage) {
                            $this->tagMenuId = $menu->id;
                            return $menu->id;
                        }
                    }
                }
                else if($view == 'tags') {
                    if('index.php?option=com_tags&view=tags' === $menu->link) {
                        if($menu->language == '*' || $menu->language == $currentLanguage) {
                            $this->tagMenuId = $menu->id;
                            return $menu->id;
                        }
                    }
                }
            }
        }
        return false;
    }
}
