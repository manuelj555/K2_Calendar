<?php

namespace K2\Calendar\TwigExtension;

class Calendar extends \Twig_Extension
{

    protected $calendars = array();

    /**
     *
     * @var \Twig_Environment
     */
    protected $twig;

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->twig = $environment;
    }

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
        );
    }

    public function calendar($view = '@K2Calendar/calendar.twig', $id = null)
    {
        $id || $id = 'k2_calendar_container_' . uniqid();

        $this->calendars[$id] = $id;

        return $this->twig->render($view, array(
                    'id' => $id,
        ));
    }

    public function css($view = null)
    {
        if (!$view) {
            $view = <<<CSS
<link rel="stylesheet" href="{{ asset("@K2Calendar/css/fullcalendar.css") }}" type="text/css" />
<link rel="stylesheet" href="{{ asset("@K2Calendar/css/jquery-ui.custom.min.css") }}" type="text/css" />
<style>
    .k2_calendar_form dd{margin: 10px 0 0 0;font-weight: bold;}
    .k2_calendar_form dt *{width: 100%}
    .k2_calendar_form textarea{min-height: 80px}
    .k2_calendar_form [type=color]{width: 80px}
    .k2_calendar_errors{padding-left: 10px;display: none}
</style>
CSS;
        }

        return $this->twig->render($view);
    }

    public function js($view = null, $controllerUrl = '@K2Calendar/event')
    {
        if (!$view) {
            $view = <<<JS
<script type="text/javascript" src="{{ asset("@K2Calendar/js/jquery-ui.custom.min.js") }}"></script>
<script type="text/javascript" src="{{ asset("@K2Calendar/js/fullcalendar.min.js") }}"></script>
<script type="text/javascript" src="{{ asset("@K2Calendar/js/k2_calendar.js") }}"></script>
<script type="text/javascript">
    $(function(){
        {% set url = url(controller_url) %}
        {% for id in calendars %}
            new K2Calendar("#{{ id }}", "{{ url }}/");
        {% endfor %}
    })
</script>
JS;
        }

        return $this->twig->render($view, array(
            'calendars' => $this->calendars,
            'controller_url' => $controllerUrl,
        ));
    }

}