<?php
namespace Kijtra\Heads\Types;

use \Kijtra\Heads\Container as HeadsContainer;
use \Kijtra\Heads\Types as HeadsTypes;

class Twitter extends HeadsTypes
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
        'summary',
    );

    /**
     * Normalize key name (override)
     * @param  string $name  meta name
     * @return string
     */
    public static function key($name)
    {
        return parent::key(preg_replace('/\Atwitter:/i', '', $name));
    }

    /**
     * Container data setter
     * @param  string $name   meta name
     * @param  string $value  meta value
     * @param  array  $attr   Extra attributes
     */
    public static function set($name, $value, array $attr = array())
    {
        $_name = self::key($name);
        $attrs['property'] = 'twitter:'.preg_replace('/\Atwitter:/i', '', $name);
        $attrs['content'] = $value;
        $data = self::data($_name, $attrs);
        HeadsContainer::set('twitter', $data);
    }


    // Data methods ////////////////////////////////////////////////////////////



}
