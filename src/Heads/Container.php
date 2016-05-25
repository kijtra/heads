<?php
namespace Kijtra\Heads;

class Container
{
    /**
     * Head tags data
     * @var array
     */
    private static $datas = array();

    /**
     * Get all datas
     * @param  string $type  Tag type
     * @return array             Head tags data
     */
    public static function datas($type = null)
    {
        if (!empty($type)) {
            if (!empty(self::$datas[$type])) {
                return self::$datas[$type];
            }
        }
        return self::$datas;
    }

    /**
     * Get one data
     * @param  string $type  Tag type
     * @param  string $key       meta name
     * @return array             Tag data
     */
    public static function get($type, $key)
    {
        $key = strtolower($key);
        if (!empty(self::$datas[$type][$key])) {
            return self::$datas[$type][$key];
        }
    }

    /**
     * Set tag data
     * @param string $type  Tag type
     * @param array $data       Tag data
     */
    public static function set($type, array $data)
    {
        if (empty(self::$datas[$type])) {
            self::$datas[$type] = array();
        }

        $tag = $data['tag'];
        $key = strtolower($data['key']);
        $attrs = $data['attrs'];

        if (!$data['multiple']) {
            self::$datas[$type][$key] = array(
                array(
                    'tag' => $tag,
                    'attrs' => $attrs,
                )
            );
        } else {
            if (empty(self::$datas[$type][$key])) {
                self::$datas[$type][$key] = array();
            }

            self::$datas[$type][$key][] = array(
                'tag' => $tag,
                'attrs' => $attrs,
            );
        }
    }

    /**
     * Remove Tag data
     * @param string $type  Tag type
     * @param  string $key      meta name
     */
    public static function remove($type, $key)
    {
        $key = strtolower($key);
        if (!empty(self::$datas[$type][$key])) {
            unset(self::$datas[$type][$key]);
        }
    }
}
