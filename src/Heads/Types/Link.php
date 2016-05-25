<?php
namespace Kijtra\Heads\Types;

use \Kijtra\Heads\Container as HeadsContainer;
use \Kijtra\Heads\Types as HeadsTypes;

class Link extends HeadsTypes
{
    /**
     * Tag name
     * @var string
     */
    protected static $tag = 'link';


    /**
     * Container data setter
     * @param  string $name   meta name
     * @param  string $value  meta value
     * @param  array  $attr   Extra attributes
     */
    public static function set($name, $value, array $attr = array())
    {
        $attrs['rel'] = $name;
        $attrs['href'] = $value;
        $data = self::data($name, $attrs);
        HeadsContainer::set('link', $data);
    }


    // Data methods ////////////////////////////////////////////////////////////


    /**
     * link next
     * @param  mixed $value  If array to string
     * @param  array $attr   Extra attributes
     * @return array         Tag data
     */
    protected static function _next($value, array $attrs = array())
    {
        $attrs['rel'] = 'next';
        $attrs['href'] = $value;
        return self::data(__FUNCTION__, $attrs);
    }

    /**
     * link prev
     * @param  mixed $value  If array to string
     * @param  array $attr   Extra attributes
     * @return array         Tag data
     */
    protected static function _prev($value, array $attrs = array())
    {
        $attrs['rel'] = 'prev';
        $attrs['href'] = $value;
        return self::data(__FUNCTION__, $attrs);
    }
}
