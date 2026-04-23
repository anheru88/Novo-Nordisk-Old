

# Novo - Nordisk
## Plataforma de CAML para la creación de cotizaciónes para la empresa

## Entorno de desarollo 

## Requisitos

Laravel 6.9 requiere PHP 7.2 o superior. Este requisito de PHP actualizado nos permite usar nuevas funciones de PHP, como rasgos, para proporcionar interfaces más expresivas para herramientas como Laravel Cashier.
PHP 7.2 también trae mejoras significativas de velocidad y rendimiento sobre PHP 7.1.
[PHP 7.2>]


## Estructura del proyecto

Estructura del proyecto desarollado en laravel 6.9

- **[app]** - la carpeta "app" es una de las carpetas principales del framework y juega un papel fundamental en la estructura y organización de un proyecto Laravel. Esta carpeta contiene los archivos y las clases relacionadas con la lógica de la aplicación.

  - [Console]: Contiene los comandos de consola personalizados de la aplicación. Estos comandos se pueden ejecutar desde la terminal para realizar tareas específicas dentro de la aplicación.

  - [Exceptions]: Aquí se encuentran las excepciones personalizadas de la aplicación. Puedes definir tus propias excepciones para manejar casos específicos y personalizar el comportamiento de la aplicación cuando ocurren errores.

  - [Http]: Contiene los controladores, las solicitudes (requests) y las respuestas (responses) HTTP de la aplicación. Los controladores son responsables de manejar las solicitudes y coordinar la lógica de la aplicación, mientras que las solicitudes y las respuestas encapsulan los datos enviados y recibidos a través del protocolo HTTP.

  - [Models]: Aquí es donde generalmente se almacenan los modelos de la aplicación. Los modelos representan las entidades y las interacciones con la base de datos. Puedes definir tus propios modelos para interactuar con las tablas de la base de datos de manera sencilla y consistente.

  - [Providers]: Contiene los proveedores de servicios de la aplicación. Los proveedores de servicios son responsables de vincular las dependencias, registrar enlaces y realizar otras tareas de inicialización de la aplicación.
  
- **[bootstrap]** - Archivos de configuracion bootstrap el cual Laravel Mix, que es una capa de abstracción sobre el sistema de compilación de assets de Laravel. Laravel Mix permite utilizar diferentes preprocesadores CSS, como Sass o Less, para generar los archivos CSS finales.


- **[config]** - Es uno de los directorios clave del framework y contiene la configuración de la aplicación. Dentro de esta carpeta, se encuentran varios archivos que definen diferentes aspectos de la configuración de Laravel.

- **[database]** -Aquí se encontraran los archivos relacionados con el manejo de la base de datos, donde se encuentran las migraciones de la base de datos y su respectivo backup.

- **[public]** -  Es un directorio fundamental que contiene los archivos accesibles públicamente para el servidor web. Es el punto de entrada para las solicitudes HTTP y contiene los archivos estáticos, como CSS, JavaScript, imágenes y otros recursos públicos.

- **[resources]** - Es un directorio que almacena los recursos utilizados en el desarrollo de la aplicación, como vistas, archivos de idioma, archivos de plantillas, archivos de assets, entre otros. Estos recursos se utilizan para construir la interfaz de usuario y proporcionar la funcionalidad de la aplicación,Dentro de este directorio se encuentran los subdirectorios:

  - [assets] - Este directorio es utilizado para almacenar los archivos de assets de la aplicación, como hojas de estilo CSS, scripts JavaScript, imágenes, archivos de fuentes, etc. Estos archivos pueden ser compilados y procesados utilizando herramientas como Laravel Mix para generar los archivos finales que se sirven al navegador.
      
  - [lang]- Este directorio contiene los archivos de idioma de la aplicación. Aquí se almacenan los archivos de traducción que permiten localizar la aplicación en diferentes idiomas. Los archivos de idioma suelen estar en formato PHP o JSON y contienen arreglos asociativos que mapean claves de traducción a sus correspondientes en los diferentes idiomas.

  - [views]- Este directorio almacena las vistas de la aplicación. Las vistas son archivos de plantillas escritos en el lenguaje de plantillas de Laravel  y se utilizan para generar el HTML que se envía al navegador. Aquí se encuentran los archivos .blade.php que componen las diferentes páginas y componentes de la aplicación.

- **[routes]** -  Es un directorio clave que contiene los archivos de rutas de la aplicación. Las rutas definen cómo responden las diferentes URL a las solicitudes HTTP y establecen la lógica de enrutamiento de la aplicación.

- **[storage]** - es un directorio importante que se utiliza para almacenar archivos generados por la aplicación, como archivos de logs, archivos cargados por los usuarios, vistas almacenadas en caché, archivos de sesiones, entre otros. Esta carpeta está diseñada para almacenar datos que no deben ser accesibles públicamente a través del servidor web.

- **[test]** - Aquí escribiremos los archivos de pruebas que serán ejecutadas posteriormente por phpunit
- **editorconfig** - Es un archivo de configuración utilizado para establecer reglas de formato y estilo de código en un proyecto. Aunque no es específico de Laravel, es una práctica común tener un archivo .editorconfig en un proyecto Laravel para mantener la consistencia en el código y facilitar la colaboración entre desarrolladores.
- **.env** Es un archivo de configuración que almacena variables de entorno específicas del entorno de desarrollo. Este archivo contiene información sensible y confidencial, como credenciales de base de datos, claves de API y otros datos de configuración.
- **.gittattributes**  Es un archivo de configuración utilizado por Git para definir atributos específicos para archivos y rutas dentro de un repositorio. Este archivo se utiliza para establecer opciones de manejo de línea de texto, codificación de caracteres, resolución de conflictos y otras configuraciones relacionadas con los archivos en el repositorio.
- **.styleci.yml** Es un archivo de configuración utilizado por StyleCI, una herramienta de integración continua que ayuda a mantener un estilo de código consistente en un repositorio de Git. StyleCI analiza automáticamente los cambios de código y realiza correcciones según las reglas de estilo definidas en el archivo ".styleci.yml".
- **README.md** - Archivo que contiene la estructura del proyecto y configuracion de como desplegar del mismo.
- **artisan** Artisan es la interfaz de línea de comandos (CLI) incluida en el framework Laravel. Es una herramienta poderosa que permite a los desarrolladores ejecutar y automatizar una variedad de tareas relacionadas con el desarrollo de aplicaciones Laravel.
- **composer.json** El archivo composer.json es utilizado por Composer, una herramienta de administración de dependencias para PHP. En este archivo, se definen las dependencias del proyecto, tanto las requeridas como las opcionales, junto con sus versiones y restricciones.
- **composer.lock** El archivo composer.lock es generado por Composer después de la instalación o actualización de las dependencias. Contiene una lista de las versiones exactas de las dependencias instaladas, asegurando que se utilicen las mismas versiones en diferentes entornos.
- **package-lock.json** El archivo package-lock.json es utilizado por npm (Node Package Manager) para bloquear las versiones exactas de las dependencias instaladas en un proyecto de Node.js. Al igual que el composer.lock, asegura la consistencia en las versiones de las dependencias en diferentes entornos.
- **package.json**  El archivo package.json es utilizado en proyectos de Node.js para definir información sobre el proyecto y sus dependencias de npm. También contiene scripts personalizados, configuraciones y otra información relevante para la construcción y ejecución del proyecto.
- **phpunit.xml** El archivo phpunit.xml es utilizado para configurar PHPUnit, una herramienta de pruebas unitarias para PHP. Este archivo define la configuración de PHPUnit, como la ubicación de los archivos de prueba, las suites de pruebas a ejecutar, opciones de informe, entre otros.
- **server.php** El archivo server.php es un servidor web de desarrollo incorporado en Laravel. Permite ejecutar la aplicación Laravel en un servidor web local sin necesidad de configurar un servidor web completo como Apache o Nginx.
- **webpack.mix.js** El archivo webpack.mix.js es utilizado por Laravel Mix, una herramienta de compilación y concatenación de assets en Laravel. Define la configuración de la mezcla de assets, como la ubicación de los archivos fuente, los assets de destino y las transformaciones que se deben aplicar, como la compilación de archivos Sass o la transpilación de JavaScript.



## Instalacion

Novo Requiere [Node.js](https://nodejs.org/) v14+ para correr
Novo Requiere [Php](https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/7.2.30/xampp-portable-windows-x64-7.2.30-0-VC15-installer.exe/download) [7.2.0] 

para ejecutar Novo Requiere [Composer](https://getcomposer.org) 

para ejecutarNovo Requiere [Postgres](https://www.postgresql.org/download/) [v15] para ejecutar : Recuerda utilizar estas herramientas para tu sistema operativo.

## Configuración de entorno 
Una vez instalemos postgres crearemos una base de datos llamada Novo_dev la cual para hacerlo de forma mas facil sigue este video [Postgres](https://www.youtube.com/watch?v=GruJjmfm_gs&ab_channel=Tecnolog%C3%ADaBinaria%3B)  

### Restaurar base de datos
  Procedemos a restaurar la base de datos desde el pgadmin con el siguiente archivo : [**BackupLocal**](https://drive.google.com/file/d/1mtvJwR2ohdRqGajog__Nzms_b7Mb6zve/view?usp=sharingB)  


## Despliegue local

Luego clonaremos el repositorio en una carpeta establecida donde prefieras guardar el proyecto

Una vez ubicados en esa carpeta procederemos a abrir nuestra terminal y ejecutar los siguientes comandos son los comandos basicos para el despliegue el cual es bajar el servidor instalar las dependencias de composer, actualizar composer y luego generar la llave del proyecto
 
 ### Clonamos el proyecto 
```sh
git init
git clone https://github.com/Brace-Developers/Novo-Nordisk.git
```

### Instalamos dependencias del proyectos
```sh
cd Novo-Nordisk
composer install
composer update
php artisan key:generate
```
## Desplegamos la aplicación
```sh
php artisan serve
@127.126.0.1:8000\login
```
