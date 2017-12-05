<?php

if (!function_exists('getSelectorByConfig')) {
    /*
     * 根据配置文件生成<select></select>
     */
    function getSelectorByConfig($config, $attr = [], $hasAll = false) {
        $configArr = config($config);
        $html = '<select';
        if (is_array($attr) && count($attr) > 0) {
            foreach ($attr as $key => $val) {
                $html = $html .' ' .$key .'="' .$val .'"';
            }
        }
        $html .= '>';
        if ($hasAll) {
            $html .= '<option>请选择</option>';
        }
        if (is_array($configArr) && count($configArr) > 0) {
            foreach ($configArr as $key => $val) {
                $html = $html .'<option value="' .$key .'">' .$val .'</option>';
            }
        }
        $html .='</select>';
        return $html;
    }
}