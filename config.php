<?php

namespace K2\Calendar;

use K2\Calendar\TwigExtension\Calendar;

return array(
    'name' => 'K2Calendar',
    'namespace' => __NAMESPACE__,
    'path' => __DIR__,
    'services' => array(
        'k2.calendar.twig.extension' => function() {
            return new Calendar();
        },
    ),
    'twig_extensions' => array('k2.calendar.twig.extension'),
);