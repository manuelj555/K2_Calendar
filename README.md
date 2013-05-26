K2_Calendar
========


![Ejemplo Calendar](https://raw.github.com/manuelj555/K2_Calendar/master/calendar.png)

Instalacion
-----------

la instalación más sencilla es mediante composer, agregar el paquete al composer.json del proyecto:

```json
    {
        "require" : {
            "k2/calendar": "dev-master"
        }
    }
```                        
                        
Ejecutar el comando:

``` 
    composer install
```

Luego de tener los archivos descargados correctamente se debe agregar el módulo en el app/config/modules.php:

```php

<?php //archivo app/config/modules.php

/* * *****************************************************************
 * Iinstalación de módulos
 */
App::modules(array(
    '/' => include APP_PATH . '/modules/Index/config.php',
    '/calendar' => include composerPath('k2/calendar', 'K2/Calendar'),
));
```

Con esto ya debemos tener el Módulo instalado en el sistema, sin embargo aun faltan configurar algunas cosas para que todo funcione bien.

Con esto ya hemos registrado el módulo en nuestra aplicación, sin embargo aun faltan configurar algunas cosas para que todo funcione bien.

1. ejecutar el comando **php app/console asset:install** estando hubicados en la carpeta default de K2.
2. Verificar que en nuestro template se esté cargando el jquery.

Con esto ya debemos tener corriendo el calendario en la aplicación.

Podemos probar entrando a http://dirProyecto/calendar, y nos debe aparecer el calendario de la imagen.

Cualquier duda, error ó problema, dejarlo como un `issue <https://github.com/manuelj555/K2_Calendar/issues>`_ en el repo.

Cualquier persona que desea colaborar con el desarrollo es bienvenida :-)

Usando el Calendario en mi propia vista
---------------------------------------

Si deseamos incluir el calendario en una vista ó template particular, solo debemos añadirlo como una funcion twig, ejemplo:

```html+jinja
{% extends "default.twig" %}

{% block css %}
{{ parent() }}
{{ calendar_css() }}{# añadimos los css necesarios para el calendario usando la función calendar_css() #}
{% endblock %}

{% block javascript %}
{{ calendar_js() }}{# añadimos los js necesarios para el calendario usando la función calendar_js() #}
{% endblock %}

{% block content %}
{{ calendar() }}{# añadimos el calendario #}
{% endblock %}

```

Podemos llamar a la funcion calendar() varias veces, con lo que se crearán varios calendarios en una misma página

Función calendar()
------------------

Esta función genera el calendario y acepta como parametro la vista con la lógica que lo crea, por si queremos cambiarla y además acepta el id que queramos que tenga el div que contiene al calendario.

```html+jinja
{{ calendar('@MiModulo/mi_vista_calendario', 'mi_propio_id') }}{# añadimos el calendario usando la vista y el id especificados #}
{{ calendar(id='mi_id') }}{# añadimos el calendario con el id mi_id #}

```

Función calendar_js()
------------------

Esta función incluye los javascripts necesarios para que funcione el calendario, y además se le puede pasar un string con la url hasta el controlador que va a manejar la lógica del guardado de los eventos.

```html+jinja
{{ calendar_js() }}
{{ calendar_js('@MiModulo/controlador') }} {# acá especificamos cual será el controlador que manejara la lógica del guardado de los eventos #}

```
