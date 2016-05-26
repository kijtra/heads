<?php
namespace Kijtra\Heads\Types;

use \Kijtra\Heads\Container as HeadsContainer;
use \Kijtra\Heads\Types as HeadsTypes;

class Http extends HeadsTypes
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
        'defaultstyle',
        'cachecontrol',
        'pragma',
        'cleartype',
        'imagetoolbar',
        'pageenter',
        'pageexit',
        'siteenter',
        'siteexit',
    );

    /**
     * Container data setter
     * @param  string $name   meta name
     * @param  string $value  meta value
     * @param  array  $attr   Extra attributes
     */
    public static function set($name, $value, array $attr = array())
    {
        $name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
        $attrs['http-equiv'] = $name;
        $attrs['content'] = $value;
        $data = self::data($name, $attrs);
        HeadsContainer::set('http', $data);
    }


    // Data methods ////////////////////////////////////////////////////////////


    /**
     * http Refresh
     * @param  string $value  meta value
     * @param  mixed  $attr   Extra attributes or Redirect seconds
     * @return array          Tag data
     */
    protected static function _refresh($value, $attrs = array())
    {
        if (!empty($attrs) && !is_array($attrs) && ctype_digit(strval($attrs))) {
            $value = $attrs."; URL=".$value;
            $attrs = array();
        }

        $attrs['http-equiv'] = 'Refresh';
        $attrs['content'] = $value;
        return self::data(__FUNCTION__, $attrs);
    }

    /**
     * http Set-Cookie
     * @param  string $value  meta value
     * @param  mixed  $attr   Extra attributes
     * @return array          Tag data
     */
    protected static function _setCookie($value, $attrs = array())
    {
        $value = mb_convert_encoding($value, 'utf-8', 'auto');
        $attrs['http-equiv'] = 'Set-Cookie';
        $attrs['content'] = $value;
        return self::data(__FUNCTION__, $attrs);
    }

    /**
     * http Content-Type
     * @param  string $value  meta value
     * @param  mixed  $attr   Extra attributes
     * @return array          Tag data
     */
    protected static function _contentType($value, $attrs = array())
    {
        if (false === strpos($value, 'charset')) {
            if ($data = HeadsContainer::get('meta', '_charset')) {
                $value = trim($value, ' ;').'; charset='.$data[0]['attrs']['charset'];
            }
        }

        $attrs['http-equiv'] = 'Content-Type';
        $attrs['content'] = $value;
        return self::data(__FUNCTION__, $attrs);
    }

    /**
     * http Content-Language
     * @param  string $value  meta value
     * @param  mixed  $attr   Extra attributes
     * @return array          Tag data
     */
    protected static function _contentLanguage($value, $attrs = array())
    {
        $attrs['http-equiv'] = 'Content-Language';
        $attrs['content'] = self::duplicate('http', __FUNCTION__, 'content', $value);

        return self::data(__FUNCTION__, $attrs);
    }

    /**
     * http Expires
     * @param  string $value  meta value (date string)
     * @param  mixed  $attr   Extra attributes
     * @return array          Tag data
     */
    protected static function _expires($value, $attrs = array())
    {
        if ($value = self::date($value)) {
            $attrs['http-equiv'] = 'Expires';
            $attrs['content'] = self::date($value);
            return self::data(__FUNCTION__, $attrs);
        }
    }
}
