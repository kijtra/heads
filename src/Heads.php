<?php
namespace Kijtra;

use \Kijtra\Heads\Container as HeadsContainer;
use \Kijtra\Heads\Types\Meta as HeadsMeta;
use \Kijtra\Heads\Types\Http as HeadsHttp;
use \Kijtra\Heads\Types\Link as HeadsLink;
use \Kijtra\Heads\Types\Ogp as HeadsOgp;
use \Kijtra\Heads\Types\Facebook as HeadsFacebook;
use \Kijtra\Heads\Types\Twitter as HeadsTwitter;
use \Kijtra\Heads\Types\AppleLink as HeadsAppleLink;

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
            'applelink',
            'link',
        ),
        'namespace' => array(
            'og' => 'http://ogp.me/ns',
            'fb' => 'http://ogp.me/ns/fb',
            'op' => 'http://media.facebook.com/op',
            'music' => 'http://ogp.me/ns/music',
            'video' => 'http://ogp.me/ns/video',
            'article' => 'http://ogp.me/ns/article',
            'book' => 'http://ogp.me/ns/book',
            'profile' => 'http://ogp.me/ns/profile',
            'website' => 'http://ogp.me/ns/website',
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

        $lower = strtolower($name);

        if (0 === strncmp($lower, 'og:', 3)) {
            self::ogp($name, $value, $attrs);
        } elseif (0 === strncmp($lower, 'fb:', 3)) {
            self::facebook($name, $value, $attrs);
        } elseif (0 === strncmp($lower, 'twitter:', 8)) {
            self::twitter($name, $value, $attrs);
        } elseif (0 === strncmp($lower, 'al:', 3)) {
            self::applelink($name, $value, $attrs);
        } elseif (!self::meta($name, $value, $attrs)) {
            if (!self::http($name, $value, $attrs)) {
                if (!self::link($name, $value, $attrs)) {
                    if (0 === strpos(strtolower($name), 'x-')) {
                        HeadsHttp::set($name, $value, $attrs);
                    } elseif (!preg_match('/\A(og|fb|twitter|al):/i', $name)) {
                        HeadsMeta::set($name, $value, $attrs);
                    }
                }
            }
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
        $lower = strtolower($name);

        if (0 === strncmp($lower, 'og:', 3)) {
            HeadsContainer::remove('ogp', HeadsOgp::key($name));
        } elseif (0 === strncmp($lower, 'fb:', 3)) {
            HeadsContainer::remove('facebook', HeadsFacebook::key($name));
        } elseif (0 === strncmp($lower, 'twitter:', 8)) {
            HeadsContainer::remove('twitter', HeadsTwitter::key($name));
        } elseif (0 === strncmp($lower, 'al:', 3)) {
            HeadsContainer::remove('applelink', HeadsAppleLink::key($name));
        } elseif (HeadsMeta::has($name)) {
            HeadsContainer::remove('meta', HeadsMeta::key($name));
        } elseif (HeadsLink::has($name)) {
            HeadsContainer::remove('link', HeadsLink::key($name));
        } elseif (HeadsHttp::has($name)) {
            HeadsContainer::remove('http', HeadsHttp::key($name));
        } else {
            // Other http-equiv
            if (0 === strpos(strtolower($name), 'x-')) {
                HeadsContainer::remove('http', $name);
            } elseif (!preg_match('/\A(og|fb|twitter|al):/i', $name)) {
                HeadsContainer::remove('meta', $name);
            }
        }
    }

    /**
     * Clear all data
     */
    public static function clear()
    {
        HeadsContainer::clear();
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
     * Add OpenGraph data
     * @param string $name   meta name
     * @param mixed  $value  meta value, If set 'false' to remove tag data
     * @param mixed  $attr   Extra attributes
     * @return bool
     */
    public static function ogp($name, $value = null, $attrs = array())
    {
        if (is_array($name)) {
            foreach($name as $key => $val) {
                self::ogp($key, $val);
            }
            return true;
        }

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
    public static function og($name, $value = null, $attrs = array())
    {
        self::ogp($name, $value, $attrs);
    }

    /**
     * Add Facebook OpenGraph data
     * @param string $name   meta name
     * @param mixed  $value  meta value, If set 'false' to remove tag data
     * @param mixed  $attr   Extra attributes
     * @return bool
     */
    public static function facebook($name, $value = null, $attrs = array())
    {
        if (is_array($name)) {
            foreach($name as $key => $val) {
                self::facebook($key, $val);
            }
            return true;
        }

        if (HeadsFacebook::has($name)) {
            if ($data = HeadsFacebook::get($name, $value, $attrs)) {
                HeadsContainer::set('facebook', $data);
            } else {
                HeadsFacebook::set($name, $value, $attrs);
            }
            return true;
        }
        return false;
    }

    /**
     * Alias of facebook()
     * @param string $name   meta name
     * @param mixed  $value  meta value, If set 'false' to remove tag data
     * @param mixed  $attr   Extra attributes
     */
    public static function fb($name, $value = null, $attrs = array())
    {
        self::facebook($name, $value, $attrs);
    }

    /**
     * Add Twitter Card data
     * @param string $name   meta name
     * @param mixed  $value  meta value, If set 'false' to remove tag data
     * @param mixed  $attr   Extra attributes
     * @return bool
     */
    public static function twitter($name, $value = null, $attrs = array())
    {
        if (is_array($name)) {
            if (!empty($name['card'])) {
                foreach($name as $key => $val) {
                    self::twitter($key, $val);
                }
            }
            return true;
        }

        if (HeadsTwitter::has($name)) {
            if ($data = HeadsTwitter::get($name, $value, $attrs)) {
                HeadsContainer::set('twitter', $data);
            } else {
                HeadsTwitter::set($name, $value, $attrs);
            }
            return true;
        }
        return false;
    }

    /**
     * Add Apple Link data
     * @param string $name   meta name
     * @param mixed  $value  meta value, If set 'false' to remove tag data
     * @param mixed  $attr   Extra attributes
     * @return bool
     */
    public static function applelink($name, $value = null, $attrs = array())
    {
        if (is_array($name)) {
            if (!empty($name['ios:url'])) {
                foreach($name as $key => $val) {
                    self::applelink($key, $val);
                }
            }
            return true;
        }

        if (HeadsAppleLink::has($name)) {
            if ($data = HeadsAppleLink::get($name, $value, $attrs)) {
                HeadsContainer::set('applelink', $data);
            } else {
                HeadsAppleLink::set($name, $value, $attrs);
            }
            return true;
        }
        return false;
    }

    /**
     * Alias of applelink()
     * @param string $name   meta name
     * @param mixed  $value  meta value, If set 'false' to remove tag data
     * @param mixed  $attr   Extra attributes
     */
    public static function al($name, $value = null, $attrs = array())
    {
        self::applelink($name, $value, $attrs);
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
                        if (!is_scalar($val)) {
                            $attr[] = $name.'=""';
                        } else {
                            $attr[] = $name.'="'.htmlspecialchars($val, ENT_QUOTES).'"';
                        }
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

    /**
     * Get Open Graph Namespaces
     * @return string  prefix HTML attribute
     */
    public static function namespace()
    {
        $datas = HeadsContainer::datas();
        if (empty($datas) || empty(self::$options['namespace'])) {
            return;
        }

        $options = self::$options['namespace'];
        $prefixes = array();
        foreach($datas as $type => $values) {
            foreach($values as $key => $rows) {
                foreach($rows as $row) {
                    if (empty($row['attrs']['property'])) {
                        continue;
                    }

                    $prop = $row['attrs']['property'];
                    $ns = substr($prop, 0, strpos($prop, ':'));
                    if (!empty($options[$ns])) {
                        $prefixes[$ns] = $ns.': '.$options[$ns].'#';
                    }
                    if ('og:type' === $prop && !empty($row['attrs']['content'])) {
                        $ns = $row['attrs']['content'];
                        if (!empty($options[$ns])) {
                            $prefixes[$ns] = $ns.': '.$options[$ns].'#';
                        }
                    }
                }
            }
        }

        if (empty($prefixes)) {
            return;
        }

        return $prefixes;
    }

    /**
     * Print html prefixes
     * @return string  prefix HTML attribute
     */
    public static function prefix()
    {
        if (!$prefixes = self::namespace()) {
            return;
        }

        $html = 'prefix="'.implode(' ', $prefixes).'"';
        return $html;
    }
}
