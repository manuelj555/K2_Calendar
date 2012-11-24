K2_Calendar
========


![Ejemplo Calendar](https://raw.github.com/manuelj555/K2_Calendar/master/calendar.png)

Instalacion
-----------

Solo debemos descargar e instalar la lib en **vendor/K2/Calendar** y registrarla en el [AppKernel](https://github.com/manuelj555/k2/blob/master/doc/app_kernel.rst):

```php

//archivo app/AppKernel.php

protected function registerModules()
{
    $modules = array(
        'KumbiaPHP'   => __DIR__ . '/../../vendor/kumbiaphp/kumbiaphp/src/',
        'Index'       => __DIR__ . '/modules/',
        ...
        'K2/Calendar'   => __DIR__ . '/../../vendor/',
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

Con esto ya hemos registrado el módulo en nuestra aplicación.