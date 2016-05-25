<?php
namespace Kijtra\Heads;

use \Kijtra\Heads as HeadsMain;
use \Kijtra\Heads\Container as HeadsContainer;

class Types
{

    /**
     * Normalize key name
     * @param  string $name  meta name
     * @return string
     */
    public static function key($name)
    {
        return str_replace(array('-', '_'), '', strtolower($name));
    }

    /**
     * Detect has heads name
     * @param  string $name  meta name
     * @return bool
     */
    public static function has($name)
    {
        $name = self::key($name);
        if (method_exists(get_called_class(), '_'.$name)) {
            return true;
        } elseif(!empty(static::$names)) {
            return (false !== array_search($name, static::$names));
        }
        return false;
    }

    /**
     * Get method data to pass Heads class
     * @param  string $name   meta name
     * @param  mixed  $value  meta value
     * @param  mixed  $attr   Extra attributes
     * @return mixed          If exist method in class to return method data
     */
    public static function get($name, $value, $attr = array())
    {
        $method = '_'.self::key($name);
        if (method_exists(get_called_class(), $method)) {
            return static::$method($value, $attr);
        }
    }

    /**
     * Get data format
     * @param  string $key         Container data key name
     * @param  array $attr         Extra attributes
     * @param  bool $is_multiple   Is Multiple data
     * @return array
     */
    protected static function data($key, $attrs, $is_multiple = false)
    {
        return array(
            'tag' => static::$tag,
            'key' => self::key($key),
            'attrs' => $attrs,
            'multiple' => $is_multiple,
        );
    }

    /**
     * Get duplicate value
     * @param  string $category  Container category name
     * @param  string $key       Container key name
     * @param  array $attr       Extra attributes
     * @param  mixed $value      If array to string
     * @return array
     */
    protected static function duplicate($category, $key, $attr, $value)
    {
        $key = self::key($key);

        if (is_string($value)) {
            $value = str_replace(array(' ,', ', '), ',', $value);
            $value = explode(',', $value);
        }

        if ($datas = HeadsContainer::get($category, $key)) {
            $current = $datas[0]['attrs'][$attr];
            $current = explode(',', $current);
            $value = array_merge($current, $value);
        }

        $value = array_filter($value);
        $value = array_unique($value);
        $value = implode(',', $value);

        return $value;
    }

    /**
     * Format date value
     * @param  string $value  Date content
     * @return string
     */
    protected static function date($value)
    {
        if ($tz = HeadsMain::option('timezone')) {
            $date = new \DateTime($value, new \DateTimeZone($tz));
        } else {
            $date = new \DateTime($value);
        }

        if (!empty($date)) {
            return $date->format('r');
        }
    }
}
