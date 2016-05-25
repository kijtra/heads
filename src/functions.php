<?php
namespace Kijtra;

if (!function_exists('heads')) {
    function heads($name, $value, array $attrs = array())
    {
        return \Kijtra\Heads::add($name, $value, $attrs);
    }
}
