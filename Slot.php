<?php
namespace Plugin\AsdTranslate;

class Slot {
    public static function translate($params)
    {
        $data['type'] = $params['type'];
        $data['language'] = $params['language'];
        $data['name'] = $params['name'];
        $data['translate'] = $params['translate'];
        $data['value'] = $params['translation'];
        return ipView( 'view/slot_translate.php', $data )->render();
    }
}