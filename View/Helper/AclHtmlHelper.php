<?php
App::uses('View/Helper', 'View/HtmlHelper');

class AclHtmlHelper extends HtmlHelper
{
    public $helpers = array('Session');

    /**
     * Генерация ссылки с проверкой доступа по ролевой модели
     * @param string $title
     * @param null $url
     * @param array $options
     * @param bool $confirmMessage
     * @return null|string
     */
    function link($title, $url = null, $options = array(), $confirmMessage = false)
    {
        if($this->_aclCheck($url))
        {
            return parent::link($title, $url, $options, $confirmMessage);
        }
        else
        {
            return null;
        }
    }

    /**
     * Проверка доступа по ACL
     * @param $url
     * @return bool
     */

    function _aclCheck($url) {
        $permissions = $this->Session->read('Alaxos.Acl.permissions');
        if(!isset($permissions))
        {
            $permissions = array();
        }

        $aco_path = AclRouter :: aco_path($url);

        return isset($permissions[$aco_path]) && $permissions[$aco_path] == 1;
    }

    /**
     * Проверка группы экшенов для списков меню
     * @param $url
     * @return bool
     */
    function check($url)
    {
        $access = false;

        if(is_array($url)) {
            foreach ($url as $item) {
                $access &= $this->_aclCheck($item);
            }
        } else {
            $access = $this->_aclCheck($url);
        }
        return $access;
    }
}
