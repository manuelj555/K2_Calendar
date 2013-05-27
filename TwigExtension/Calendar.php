<?php

namespace K2\Calendar\TwigExtension;

use K2\Kernel\App;

class Calendar extends \Twig_Extension
{

    protected $calendars = array();

    public function getName()
    {
        return "k2_calendar_extension";
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('calendar', array($this, 'calendar'), array(
                'is_safe' => array('html'),
                    )
            ),
            new \Twig_SimpleFunction('calendar_css', array($this, 'css'), array(
                'is_safe' => array('html'),
                    )
            ),
            new \Twig_SimpleFunction('calendar_js', array($this, 'js'), array(
                'is_safe' => array('html'),
                    )
            ),
            new \Twig_SimpleFunction('calendar_ids', array($this, 'getIds')),
        );
    }

    public function calendar($view = '@K2Calendar/calendar.twig', $id = null)
    {
        $id || $id = 'k2_calendar_container_' . uniqid();

        $this->calendars[$id] = $id;

        return App::get('twig')->render($view, array(
                    'id' => $id,
        ));
    }

    public function css()
    {
        return App::get('twig')->render('@K2Calendar/css.twig');
    }

    public function js($controllerUrl = '@K2Calendar/event')
    {
        return App::get('twig')->render('@K2Calendar/js.twig', array(
                    'calendars' => $this->calendars,
                    'controller_url' => $controllerUrl,
        ));
    }

    public function getIds()
    {
        return $this->calendars;
    }

}
