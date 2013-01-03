<?php

namespace K2\Calendar\Model;

use ActiveRecord\Config\Config;
use ActiveRecord\Config\Parameters;
use K2\ActiveRecord\ActiveRecord;

class Event extends ActiveRecord
{

    const DATE_FORMAT = 'Y-m-d H:i:s';

    protected $connection = 'k2_calendar';

    protected function beforeSave()
    {
        $this->start = self::dateFormat($this->start);
        $this->end = self::dateFormat($this->end);
    }
    
    public static function dateFormat($date)
    {
        return date(self::DATE_FORMAT, strtotime(substr($date, 0, 24)));
    }

}

if (!Config::has('k2_calendar')) {
    Config::add(new Parameters('k2_calendar', array(
                'name' => __DIR__ . '/../config/k2_calendar.db',
                'type' => 'sqlite',
            )));
}


