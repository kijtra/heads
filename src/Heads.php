<?php
namespace Kijtra;

use \Kijtra\Heads\Container as HeadsContainer;
use \Kijtra\Heads\Types\Meta as HeadsMeta;
use \Kijtra\Heads\Types\Http as HeadsHttp;
use \Kijtra\Heads\Types\Link as HeadsLink;
use \Kijtra\Heads\Types\Ogp as HeadsOgp;
use \Kijtra\Heads\Types\Twitter as HeadsTwitter;

/**
 * @link https://www.w3.org/TR/html5/document-metadata.html
 */
class Heads
{
    /**
     * Options
     * @var array
     */
    private static $options = array(
        'indent' => '',
        'break' => PHP_EOL,
        'timezone' => 'GMT',
        'order' => array(
            'meta',
            'http',
            'ogp',
            'facebook',
            'twitter',
            'link',
        ),
    );

    /**
     * Options method
     * @param string $key    Setting key or Getting key
     * @param string $value  Setting value
     * @return mixed
     */
    public static function option()
    {
        $num = func_num_args();
        $args = func_get_args();
        if (2 === $num) {
            if (!empty($args[1])) {
                self::$options[$args[0]] = $args[1];
            }
        } elseif (1 === $num) {
            if (is_array($args[0])) {
                self::$options = array_merge(self::$options, $args[0]);
            } elseif (!empty(self::$options[$args[0]])) {
                return self::$options[$args[0]];
            }
        } elseif (0 === $num) {
            self::$options;
        }
    }

    /**
     * Add heads data
     * @param string $name   meta name
     * @param mixed  $value  meta value, If set 'false' to remove tag data
     * @param mixed  $attr   Extra attributes
     */
    public static function add($name, $value, $attrs = array())
    {
        // Remove
        if (false === $value) {
            self::remove($name);
            return;
        }

        // Add <meta>
        if (self::meta($name, $value, $attrs)) {
            return;
        }

        // Add http-equiv (<meta>)
        if (self::http($name, $value, $attrs)) {
            return;
        }

        // Add <link>
        if (self::link($name, $value, $attrs)) {
            return;
        }

        // Add OGP (<meta>)
        if (self::ogp($name, $value, $attrs)) {
            return;
        }

        // Add Other (force <meta>)
        if (0 === strpos(strtolower($name), 'x-')) {
            HeadsHttp::set($name, $value, $attrs);
        } elseif (!preg_match('/\A(og|fb|twitter):/i', $name)) {
            HeadsMeta::set($name, $value, $attrs);
        }
    }

    /**
     * Replace heads data
     * @param string $name   meta name
     * @param mixed  $value  meta value, If set 'false' to remove tag data
     * @param mixed  $attr   Extra attributes
     */
    public static function replace($name, $value, $attrs = array())
    {
        self::remove($name);
        self::add($name, $value, $attrs);
    }

    /**
     * Remove heads data
     * @param string $name   meta name
     */
    public static function remove($name)
    {
        if (HeadsMeta::has($name)) {
            HeadsContainer::remove('meta', HeadsMeta::key($name));
        } elseif (HeadsLink::has($name)) {
            HeadsContainer::remove('link', HeadsLink::key($name));
        } elseif (HeadsHttp::has($name)) {
            HeadsContainer::remove('http', HeadsHttp::key($name));
        } elseif (HeadsOgp::has($name)) {
            HeadsContainer::remove('ogp', HeadsOgp::key($name));
        } else {
            // Other http-equiv
            if (0 === strpos(strtolower($name), 'x-')) {
                HeadsContainer::remove('http', $name);
            } elseif (!preg_match('/\A(og|fb|twitter):/i', $name)) {
                HeadsContainer::remove('meta', $name);
            }
        }
    }


    /**
     * Add meta data
     * @param string $name   meta name
     * @param mixed  $value  meta value, If set 'false' to remove tag data
     * @param mixed  $attr   Extra attributes
     * @return bool
     */
    public static function meta($name, $value, $attrs = array())
    {
        if (HeadsMeta::has($name)) {
            if ($data = HeadsMeta::get($name, $value, $attrs)) {
                HeadsContainer::set('meta', $data);
            } else {
                HeadsMeta::set($name, $value, $attrs);
            }
            return true;
        }
        return false;
    }

    /**
     * Add http data
     * @param string $name   meta name
     * @param mixed  $value  meta value, If set 'false' to remove tag data
     * @param mixed  $attr   Extra attributes
     * @return bool
     */
    public static function http($name, $value, $attrs = array())
    {
        if (HeadsHttp::has($name)) {
            if ($data = HeadsHttp::get($name, $value, $attrs)) {
                HeadsContainer::set('http', $data);
            } else {
                HeadsHttp::set($name, $value, $attrs);
            }
            return true;
        }
        return false;
    }

    /**
     * Add link data
     * @param string $name   meta name
     * @param mixed  $value  meta value, If set 'false' to remove tag data
     * @param mixed  $attr   Extra attributes
     * @return bool
     */
    public static function link($name, $value, $attrs = array())
    {
        if (HeadsLink::has($name)) {
            if ($data = HeadsLink::get($name, $value, $attrs)) {
                HeadsContainer::set('link', $data);
            } else {
                HeadsLink::set($name, $value, $attrs);
            }
            return true;
        }
        return false;
    }

    /**
     * Add link data
     * @param string $name   meta name
     * @param mixed  $value  meta value, If set 'false' to remove tag data
     * @param mixed  $attr   Extra attributes
     * @return bool
     */
    public static function ogp($name, $value, $attrs = array())
    {
        if (HeadsOgp::has($name)) {
            if ($data = HeadsOgp::get($name, $value, $attrs)) {
                HeadsContainer::set('ogp', $data);
            } else {
                HeadsOgp::set($name, $value, $attrs);
            }
            return true;
        }
        return false;
    }

    /**
     * Alias of ogp()
     * @param string $name   meta name
     * @param mixed  $value  meta value, If set 'false' to remove tag data
     * @param mixed  $attr   Extra attributes
     */
    public static function og($name, $value, $attrs = array())
    {
        self::ogp($name, $value, $attrs);
    }

    /**
     * Get head html
     * @param  array  $options  Build option
     * @return string           heads HTML
     */
    public static function html(array $options = array())
    {
        $datas = HeadsContainer::datas();
        $htmls = array();

        // charset is always on top
        if ($charset = HeadsContainer::get('meta', 'charset')) {
            $attr = array();
            foreach($charset[0]['attrs'] as $name => $val) {
                $attr[] = $name.'="'.htmlspecialchars($val, ENT_QUOTES).'"';
            }
            $htmls['charset'][] = '<'.$charset[0]['tag'].' '.implode(' ', $attr).'>';
        }

        foreach($datas as $type => $values) {
            $htmls[$type] = array();
            foreach($values as $key => $rows) {
                // charset is always on top
                if ('meta' === $type && 'charset' === $key) {
                    continue;
                }

                foreach($rows as $row) {
                    $attr = array();
                    foreach($row['attrs'] as $name => $val) {
                        $attr[] = $name.'="'.htmlspecialchars($val, ENT_QUOTES).'"';
                    }
                    $htmls[$type][] = '<'.$row['tag'].' '.implode(' ', $attr).'>';
                }
            }
        }

        if (empty($htmls)) {
            return;
        }

        $indent = self::option('indent');
        if (!empty($options['indent'])) {
            $indent = $options['indent'];
        };

        $break = self::option('break');
        if (!empty($options['break'])) {
            $break = $options['break'];
        };

        $html = $break;
        foreach(array_merge(array('charset'), self::option('order')) as $type) {
            if (!empty($htmls[$type])) {
                $html .= $indent.implode($break.$indent, $htmls[$type]).$break;
            }
        }

        return $html;
    }


    /**
     * Print head tags
     * @param  array  $options  Build option
     */
    public static function print(array $options = array())
    {
        echo self::html($options);
    }
}
