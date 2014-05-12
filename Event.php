<?php

namespace Plugin\AsdTranslate;

class Event
{
    public static function ipBeforeController()
    {
        if( ipIsManagementState() ) {
            ipAddJs('Plugin/AsdTranslate/assets/js/translate.js');
        }
    }
}
