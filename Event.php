<?php

namespace Plugin\AsdTranslate;

class Event
{
    public static function ipBeforeController()
    {
        if( ipIsManagementState() ) {
            ipAddCss('Plugin/AsdTranslate/assets/css/style.css');
            ipAddJs('Plugin/AsdTranslate/assets/js/translate.js');
        }
    }
}
