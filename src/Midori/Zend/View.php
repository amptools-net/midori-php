<?php
class Midori_View extends Zend_View
{
    private static $instance;
    
    public static function get()
    {
        if(self::$instance == null)
            self::$instance = new Midori_View();
        return self::$instance;
    }
    
    
}
?>