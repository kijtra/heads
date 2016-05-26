<?php
namespace Kijtra\Heads\Types;

use \Kijtra\Heads as HeadsMain;
use \Kijtra\Heads\Container as HeadsContainer;
use \Kijtra\Heads\Types as HeadsTypes;

class Facebook extends HeadsTypes
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
        'appid',
        'articlestyle',
    );

    /**
     * Normalize key name (override)
     * @param  string $name  meta name
     * @return string
     */
    public static function key($name)
    {
        return parent::key(preg_replace('/\Afb:/i', '', $name));
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
        if (false !== array_search($_name, self::$availables)) {
            $attrs['property'] = 'fb:'.preg_replace('/\Afb:/i', '', $name);
            $attrs['content'] = $value;
            $data = self::data($_name, $attrs);
            HeadsContainer::set('facebook', $data);
        } else {
            HeadsMain::ogp('og:'.preg_replace('/\Afb:/i', '', $name), $value, $attr);
        }
    }


    // Data methods ////////////////////////////////////////////////////////////



}
