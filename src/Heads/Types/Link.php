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
     * Available meta name (no '-', no '_', lower)
     * @var array
     */
    protected static $availables = array(
        'made',
        'copyright',
        'alternate',
        'alternatestylesheet',
        'me',
        'archives',
        'index',
        'start',
        'search',
        'self',
        'first',
        'next',
        'prev',
        'previous',
        'last',
        'shortlink',
        'canonical',
        'amphtml',
        'edituri',
        'pingback',
        'webmention',
        'manifest',
        'author',
        'import',
        'dnsprefetch',
        'preconnect',
        'prefetch',
        'prerender',
        'subresource',
        'preload',
        'appletouchicon',
        'appletouchiconprecomposed',
        'appletouchstartupimage',
        'maskicon',
        'chromewebstoreitem',
    );


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
        if (is_string($attrs)) {
            $attrs['title'] = $attr;
        }
        $data = self::data($name, $attrs);
        HeadsContainer::set('link', $data);
    }


    // Data methods ////////////////////////////////////////////////////////////


    /**
     * link CSS
     * @param  mixed $value  If array to multiple
     * @param  array $attr   Extra attributes
     * @return array         Multiple Tag data
     */
    protected static function _css($value, array $attrs = array())
    {
        return self::set('stylesheet', $value, $attrs);
    }

    /**
     * link Favicon
     * @param  mixed $value  If array to multiple
     * @param  array $attr   Extra attributes
     * @return array         Multiple Tag data
     */
    protected static function _icon($value, array $attrs = array())
    {
        if (!is_array($value)) {
            $value = array($value);
        }

        $datas = array();
        foreach($value as $key => $val) {
            $ext = strtolower(pathinfo($val, PATHINFO_EXTENSION));
            if (empty($ext) || !preg_match('/\A(ico|png|gif)\z/', $ext)) {
                continue;
            }

            $_attrs = $attrs;
            $_attrs['rel'] = 'icon';
            $_attrs['href'] = $val;

            if ('ico' === $ext) {
                $_attrs['type'] = 'image/x-icon';
            } else {
                $_attrs['type'] = 'image/'.$ext;
            }

            if (is_numeric($key) &&  $key >= 16) {
                $_attrs['sizes'] = $key.'x'.$key;
            } elseif(preg_match('/\A\d+x\d+\z/', $key)) {
                $_attrs['sizes'] = $key;
            }

            $datas[] = self::data(__FUNCTION__, $_attrs, true);
            // HeadsContainer::set('link', $data);
        }

        return $datas;
    }

    /**
     * Alias of _icon()
     * @param  mixed $value  If array to multiple
     * @param  array $attr   Extra attributes
     * @return array         Multiple Tag data
     */
    protected static function _favicon($value, array $attrs = array())
    {
        return self::_icon($value, $attrs);
    }

    /**
     * link RSS
     * @param  string $value  RSS URL
     * @param  mixed  $attr   Extra attributes or title
     * @return array          Tag data
     */
    protected static function _rss($value, $attrs = array())
    {
        $_attrs = array();
        if (is_array($attrs)) {
            $_attrs = $attrs;
        }
        $_attrs['rel'] = 'alternate';
        $_attrs['href'] = $value;
        $_attrs['type'] = 'application/rss+xml';
        if (is_string($attrs)) {
            $_attrs['title'] = $attrs;
        }
        return self::data(__FUNCTION__, $_attrs, true);
    }

    /**
     * link Atom
     * @param  string $value  Atom URL
     * @param  mixed  $attr   Extra attributes or title
     * @return array          Tag data
     */
    protected static function _atom($value, $attrs = array())
    {
        $_attrs = array();
        if (is_array($attrs)) {
            $_attrs = $attrs;
        }
        $_attrs['rel'] = 'alternate';
        $_attrs['href'] = $value;
        $_attrs['type'] = 'application/atom+xml';
        if (is_string($attrs)) {
            $_attrs['title'] = $attrs;
        }
        return self::data(__FUNCTION__, $_attrs, true);
    }

    /**
     * link Search schema XML
     * @param  string $value  XML URL
     * @param  mixed  $attr   Extra attributes or title
     * @return array          Tag data
     */
    protected static function _search($value, $attrs = array())
    {
        $_attrs = array();
        if (is_array($attrs)) {
            $_attrs = $attrs;
        }
        $_attrs['rel'] = 'search';
        $_attrs['href'] = $value;
        $_attrs['type'] = 'application/opensearchdescription+xml';
        if (is_string($attrs)) {
            $_attrs['title'] = $attrs;
        }
        return self::data(__FUNCTION__, $_attrs, true);
    }

    /**
     * link EditURI
     * @param  string $value  URL
     * @param  mixed  $attr   Extra attributes or title
     * @return array          Tag data
     */
    protected static function _edit($value, $attrs = array())
    {
        $_attrs = array();
        if (is_array($attrs)) {
            $_attrs = $attrs;
        }
        $_attrs['rel'] = 'EditURI';
        $_attrs['href'] = $value;
        $_attrs['type'] = 'application/rsd+xml';
        if (is_string($attrs)) {
            $_attrs['title'] = $attrs;
        }
        return self::data(__FUNCTION__, $_attrs, true);
    }

    /**
     * Alias of _edit()
     * @param  string $value  URL
     * @param  mixed  $attr   Extra attributes or title
     * @return array          Tag data
     */
    protected static function _edituri($value, $attrs = array())
    {
        return self::_edit($value, $attrs);
    }

    /**
     * link Author
     * @param  string $value  Author info
     * @param  array $attr    Extra attributes or title
     * @return array          Tag data
     */
    protected static function _me($value, array $attrs = array())
    {
        $attrs['rel'] = 'me';
        $attrs['href'] = $value;
        return self::data(__FUNCTION__, $attrs, true);
    }

    /**
     * Alias of _icon()
     * @param  mixed $value  If array to multiple
     * @param  mixed $attr   Extra attributes
     * @return array         Multiple Tag data
     */
    protected static function _oembed($value, $attrs = array())
    {
        if (!filter_var($value, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
            return;
        }

        $_attrs = array(
            'rel' => $value,
        );

        if (is_string($attrs)) {
            $attrs = strtolower($attrs);
            if (false !== strpos($attrs, 'json')) {
                $_attrs['type'] = 'application/json+oembed';
            } elseif (false !== strpos($attrs, 'xml')) {
                $_attrs['type'] = 'application/xml+oembed';
            } else {
                $_attrs['type'] = $attrs;
            }
        } elseif(!empty($attrs)) {
            $_attrs = $attrs;
        } else {
            $_value = strtolower($value);
            if (false !== strpos($_value, 'format=json')) {
                $_attrs['type'] = 'application/json+oembed';
            } elseif (false !== strpos($_value, 'format=xml')) {
                $_attrs['type'] = 'application/xml+oembed';
            }
        }

        return self::data(__FUNCTION__, $_attrs, true);
    }
}
