<?php
namespace Kijtra\Heads\Types;

use \Kijtra\Heads\Container as HeadsContainer;
use \Kijtra\Heads\Types as HeadsTypes;

class AppleLink extends HeadsTypes
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
        'ios:url',
        'ios:app_store_id',
        'ios:app_name',
        'android:url',
        'android:app_name',
        'android:package',
        'al:web:url',
    );

    /**
     * Normalize key name (override)
     * @param  string $name  meta name
     * @return string
     */
    public static function key($name)
    {
        return parent::key(preg_replace('/\Aal:/i', '', $name));
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
        $attrs['property'] = 'al:'.preg_replace('/\Aal:/i', '', $name);
        $attrs['content'] = $value;
        $data = self::data($_name, $attrs);
        HeadsContainer::set('applelink', $data);
    }


    // Data methods ////////////////////////////////////////////////////////////



}
