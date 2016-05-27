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
        'card',
        'site',
        'creator',
        'title',
        'description',
        'image',
        'image:alt',
        'image:width',
        'image:height',
        'url',
        'player',
        'player:width',
        'player:height',
        'player:stream',
        'player:stream:content_type',
        'app:country',
        'app:id:iphone',
        'app:id:ipad',
        'app:id:googleplay',
        'app:url:iphone',
        'app:url:ipad',
        'app:url:googleplay',
        'app:name:iphone',
        'app:name:ipad',
        'app:name:googleplay',
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


    /**
     * Twitter Gallery Card setter
     * @see https://dev.twitter.com/cards/types/gallery
     * @param  mixed $lists  Image data(image url or multiple array data)
     */
    public static function _gallery($lists = array())
    {
        if (is_string($lists)) {
            return self::data('image0', array(
                'property' => 'twitter:image0',
                'content' => $lists,
            ));
        }

        $datas = array();
        $count = 0;
        foreach($lists as $val) {
            if (is_string($val)) {
                $datas[] = self::data('image'.$count, array(
                    'property' => 'twitter:image'.$count,
                    'content' => $val,
                ));
                ++$count;
            } elseif(!empty($val['src']) || !empty($val['url'])) {
                $url = (!empty($val['src']) ? $val['src'] : $val['url']);
                $datas[] = self::data('image'.$count, array(
                    'property' => 'twitter:image'.$count,
                    'content' => $url,
                ));
                if (!empty($val['width'])) {
                    $datas[] = self::data('image'.$count.':width', array(
                        'property' => 'twitter:image'.$count.':width',
                        'content' => $val['width'],
                    ));
                }
                if (!empty($val['height'])) {
                    $datas[] = self::data('image'.$count.':height', array(
                        'property' => 'twitter:image'.$count.':height',
                        'content' => $val['height'],
                    ));
                }
                if (!empty($val['alt'])) {
                    $datas[] = self::data('image'.$count.':alt', array(
                        'property' => 'twitter:image'.$count.':alt',
                        'content' => $val['alt'],
                    ));
                }
                ++$count;
            }
        }

        return $datas;
    }

    /**
     * Twitter Product Card setter
     * @see https://dev.twitter.com/cards/types/product
     * @param  string $lists  ['label' => 'data'] format
     */
    public static function _product(array $lists = array())
    {
        $datas = array();
        $count = 1;
        foreach($lists as $label => $value) {
            $datas[] = self::data('label'.$count, array(
                'property' => 'twitter:label'.$count,
                'content' => $label,
            ));
            $datas[] = self::data('data'.$count, array(
                'property' => 'twitter:data'.$count,
                'content' => $value,
            ));
            ++$count;
        }
        return $datas;
    }
}
