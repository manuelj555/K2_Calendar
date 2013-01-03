K2_Calendar
========


![Ejemplo Calendar](https://raw.github.com/manuelj555/K2_Calendar/master/calendar.png)

Instalacion
-----------

Solo debemos descargar e instalar la lib en **vendor/k2/K2/Calendar** y registrarla en el [AppKernel](https://github.com/manuelj555/k2/blob/master/doc/app_kernel.rst):

```php

//archivo app/AppKernel.php

protected function registerModules()
{
    $modules = array(
        ...
        new K2\Calendar\Calendar(),
    );

    protected function registerRoutes()
    {
        return array(
            '/'                 => 'Index',
            ...
            '/calendar'                 => 'K2/Calendar',
        );
    }
}
```

Con esto ya hemos registrado el módulo en nuestra aplicación, sin embargo aun faltan configurar algunas cosas para que todo funcione bien.

1. Copiar el contenido de la carpeta public del Calendar en la carpeta public del Proyecto (para tener los css, img y js).
2. Verificar que en nuestro template se esté cargando el jquery (antes de incluir el calendario).

Con esto ya debemos tener corriendo el calendario en la aplicación.

Podemos probar entrando a http://dirProyecto/calendar, y nos debe aparecer el calendario de la imagen.

Cualquier duda, error ó problema, dejarlo como un `issue <https://github.com/manuelj555/K2_Calendar/issues>`_ en el repo.

Cualquier persona que desea colaborar con el desarrollo es bienvenida :-)

Usando el Calendario en mi propia vista
---------------------------------------

Si deseamos incluir el calendario en una vista ó template particular, solo debemos añadirlo como un partial, ejemplo:

```html+php

<?php
//debemos haber cargado el jquery antes de incluir el calendar, de lo contrario no funcionará.
//lo podemos incluir en el head con un Tag::printJs("jquery/jquery.min");

//solo se debe incluir el partial y queda listo.
K2\View\View::partial("K2/Calendar:calendar");

//Tambien podemos darle un id especifico al div contenedor del calendario:
K2\View\View::partial("K2/Calendar:calendar", false, array('id' => 'mi_calendario'));
```

Si incluimos el partial en nuestra vista, se caragará allí el calendario, ademas podemos incluir el partial varias veceso, con lo que tendremos varios calendarios en una misma vista.