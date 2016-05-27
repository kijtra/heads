<?php
namespace Kijtra\Heads\Types;

use \Kijtra\Heads\Container as HeadsContainer;
use \Kijtra\Heads\Types as HeadsTypes;

class Meta extends HeadsTypes
{
    /**
     * Tag name
     * @var string
     */
    protected static $tag = 'meta';

    /**
     * Available meta name (no '-', no '_', lower)
     * @var array
     */
    protected static $availables = array(
        'description'
    );

    /**
     * Container data setter
     * @param  string $name   meta name
     * @param  string $value  meta value
     * @param  array  $attr   Extra attributes
     */
    public static function set($name, $value, array $attr = array())
    {
        if (false !== strpos($name, ':')) {
            $type = 'ogp';
            $attrs['property'] = $name;
        } else {
            $type = 'meta';
            $attrs['name'] = $name;
        }
        $attrs['content'] = $value;
        if (is_string($attr)) {
            $attrs['title'] = $attr;
        }
        $data = self::data($name, $attrs);
        HeadsContainer::set($type, $data);
    }


    // Data methods ////////////////////////////////////////////////////////////


    /**
     * meta keywords
     * @param  mixed $value  If array to string
     * @param  array $attr   Extra attributes
     * @return array         Tag data
     */
    protected static function _keywords($value, array $attrs = array())
    {
        $attrs['name'] = 'keywords';
        $attrs['content'] = self::duplicate('meta', __FUNCTION__, 'content', $value);
        return self::data(__FUNCTION__, $attrs);
    }

    /**
     * Alias of _keywords()
     * @param  mixed $value  If array to string
     * @param  array $attr   Extra attributes
     * @return array         Tag data
     */
    protected static function _keyword($value, array $attrs = array())
    {
        return self::_keywords($value, $attrs);
    }

    /**
     * meta charset
     * @param  string $value  meta value
     * @param  array $attr   Extra attributes
     * @return array         Tag data
     */
    protected static function _charset($value, array $attrs = array())
    {
        $attrs['charset'] = $value;
        return self::data(__FUNCTION__, $attrs);
    }

    /**
     * meta viewport
     * @param  mixed $value  If array to string
     * @param  array $attr   Extra attributes
     * @return array         Tag data
     */
    protected static function _viewport($value, array $attrs = array())
    {
        $attrs['name'] = 'viewport';
        $attrs['content'] = self::duplicate('meta', __FUNCTION__, 'content', $value);
        return self::data(__FUNCTION__, $attrs);
    }

    /**
     * meta apple-itunes-app
     * @param  mixed $value  If array to string
     * @param  array $attr   Extra attributes
     * @return array         Tag data
     */
    protected static function _appleItunesApp($value, array $attrs = array())
    {
        $attrs['name'] = 'apple-itunes-app';
        $attrs['content'] = self::duplicate('meta', __FUNCTION__, 'content', $value);
        return self::data(__FUNCTION__, $attrs);
    }
}
