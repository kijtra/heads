<?php
namespace Kijtra\Heads\Types;

use \Kijtra\Heads\Container as HeadsContainer;
use \Kijtra\Heads\Types as HeadsTypes;

class Ogp extends HeadsTypes
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
        'url',
        'type',
        'title',
        'image',
        'description',
        'sitename',
        'locale',
    );

    /**
     * Normalize key name (override)
     * @param  string $name  meta name
     * @return string
     */
    public static function key($name)
    {
        return parent::key(preg_replace('/\Aog:/i', '', $name));
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
        $attrs['property'] = 'og:'.preg_replace('/\Aog:/i', '', $name);
        $attrs['content'] = $value;
        $data = self::data($_name, $attrs);
        HeadsContainer::set('ogp', $data);
    }


    // Data methods ////////////////////////////////////////////////////////////


    /**
     * OGP title
     * @param  mixed $value  If array to string
     * @param  array $attr   Extra attributes
     * @return array         Tag data
     */
    private static function _title($value, array $attrs = array())
    {
        $attrs['property'] = 'og:title';
        $attrs['content'] = $value;
        return self::data(__FUNCTION__, $attrs);
    }

    /**
     * OGP image
     * @param  mixed $value  If array to string
     * @param  array $attr   Extra attributes
     * @return array         Tag data
     */
    private static function _image($value, array $attrs = array())
    {
        if ('/' !== $value{0} && !filter_var($value, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
            return;
        }

        $attrs['property'] = 'og:image';
        $attrs['content'] = $value;
        return self::data(__FUNCTION__, $attrs);
    }
}
