<?php

namespace K2\Calendar\Model;

use ActiveRecord\Config\Config;
use ActiveRecord\Config\Parameters;
use KumbiaPHP\ActiveRecord\ActiveRecord;

class Event extends ActiveRecord
{

    protected $connection = 'k2_calendar';

}

if (!Config::has('k2_calendar')) {
    Config::add(new Parameters('k2_calendar', array(
                'name' => __DIR__ . '/../config/k2_calendar.db',
                'type' => 'sqlite',
            )));
}


