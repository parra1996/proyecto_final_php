# Parrita Store

## Descripción
Parrita Store es una aplicación de gestión de productos que permite crear, editar, eliminar y visualizar productos. La disponibilidad de estas funcionalidades depende del rol del usuario: algunos pueden administrar productos, mientras que otros solo pueden verlos.  

## Tecnologías utilizadas
- **PHP**  
- **MySQL**  
- **Composer**  
- **XAMPP** (servidor local Apache y MySQL)  
- **Bootstrap** (para el diseño de las vistas)  
- GitHub (control de versiones)  

## Estructura del proyecto
- **config/** → Contiene la configuración de la conexión a la base de datos, leyendo los datos del archivo `.env`.  
- **services/** → Lógica de negocio, incluyendo servicios para usuario, sesión, productos y categorías.  
- **views/** → Vistas de la aplicación (interfaz de usuario).  
- **.env** → Variables de entorno para la configuración de la base de datos.  
- **index.php** → Entrada principal de la aplicación, desde ahi importo $pdo para tenerlo a nivel global y la vista de index que es la que sirve de contenedora de las demas.  

## Instrucciones de instalación
1. Clona el repositorio:  

   git clone https://github.com/parra1996/proyecto_final_php.git

2. Asegúrate de tener instalado XAMPP y activa el servidor Apache.

3. Copia el proyecto dentro de la carpeta htdocs de XAMPP (por ejemplo: C:\xampp\htdocs\proyecto_final).

4. Configura la base de datos en MySQL y crea las tablas necesarias (la base de datos se creó manualmente).

5. Si tu proyecto requiere dependencias de Composer, ejecuta: composer install.

## Uso.
1. Abre tu navegador y accede a: http://localhost/proyecto_final

2. Inicia sesión con tu usuario de prueba (si procede).

3. Navega por las secciones para ver, crear, editar o eliminar productos según tu rol.

## Autor

Juan Pablo Parra Labarca.

## Institución

Instituto Juan de Garay
