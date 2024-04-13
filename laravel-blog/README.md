# Blog

Aplicación de ejemplo Laravel.

## Puesta en marcha

1. Arranca [dockerbox](https://github.com/ijaureguialzo/dockerbox).

2. Clona este repositorio de modo que quede en `sites/blog`.
3. Abre el directorioz `blog` en PhpStorm.

4. Crea una entrada para `blog.dockerbox.test` en el fichero `hosts` de tu sistema operativo.
5. Recarga la configuración con `make reload`.

6. Entra en el workspace con `make workspace` y navega al directorio `blog`.

7. Instala las dependencias de PHP con `composer install`.

8. Copia el fichero `.env.example` a `.env`.
9. Crea la clave de aplicación con `php artisan key:generate`.
10. Copia el fichero `.env` a `.env.testing`.

11. Accede a `phpmyadmin.dockerbox.test` y crea dos usuarios con sus bases de datos asociadas: `blog` y `test`.
12. Modifica los ficheros de configuración `.env` y `.env.testing` para que apunten cada uno a su base de datos.

13. Lanza las migraciones de la base de datos con `php artisan migrate`.
14. Inserta datos de ejemplo con `php artisan db:seed`.

15. Accede a la web en `https://blog.dockerbox.test`.

16. Lanza los tests con `vendor/bin/phpunit`.
17. Genera el informe de cobertura de test
    con `XDEBUG_MODE=coverage vendor/bin/phpunit --colors --stop-on-failure --coverage-html coverage`.
