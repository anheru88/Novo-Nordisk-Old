# GLOSARIO DE TÉRMINOS TÉCNICOS
## Propuesta Laravel 12 + FilamentPHP - Novo Nordisk

Este documento explica en lenguaje simple todos los términos técnicos usados en la propuesta.

---

## A

**API (Application Programming Interface)**
- **Qué es**: Una "puerta de entrada" que permite que dos sistemas se comuniquen entre sí
- **Ejemplo**: Como un mesero que toma su pedido y lo lleva a la cocina
- **En nuestro caso**: Permite que el sistema hable con SAP automáticamente

**API RESTful**
- **Qué es**: Un tipo específico de API que usa el protocolo HTTP (como las páginas web)
- **Ventaja**: Es el estándar más usado, fácil de integrar
- **En nuestro caso**: Para consultar datos de SAP en tiempo real

**Audit Trail (Registro de Auditoría)**
- **Qué es**: Un registro detallado de todas las acciones en el sistema
- **Incluye**: Quién hizo qué, cuándo, desde dónde
- **Para qué**: Auditorías, cumplimiento normativo, investigación de problemas

**Auth0**
- **Qué es**: Servicio de autenticación empresarial
- **Función**: Permite que los usuarios inicien sesión con sus credenciales corporativas
- **Ventaja**: No necesitan recordar otra contraseña

**AWS (Amazon Web Services)**
- **Qué es**: Servicio de servidores en la nube de Amazon
- **Incluye**: Servidores, bases de datos, almacenamiento, etc.
- **Ventaja**: No necesitan comprar ni mantener servidores físicos

---

## B

**Backend**
- **Qué es**: La parte del sistema que no ven los usuarios (el motor)
- **Función**: Procesa datos, aplica reglas de negocio, se conecta con la base de datos
- **Analogía**: Como el motor de un carro - no lo ves pero hace que todo funcione

**Backup (Respaldo)**
- **Qué es**: Copia de seguridad de todos los datos
- **Frecuencia**: Diaria en nuestra propuesta
- **Para qué**: Recuperar información si algo sale mal

**Base de Datos**
- **Qué es**: Donde se guarda toda la información del sistema
- **Ejemplo**: Como un archivo Excel gigante pero mucho más poderoso
- **En nuestro caso**: MySQL 8.0 - guarda clientes, productos, cotizaciones, etc.

---

## C

**Caché / Cache**
- **Qué es**: Memoria rápida que guarda información usada frecuentemente
- **Analogía**: Como tener los documentos más usados en el escritorio en vez de en el archivo
- **Beneficio**: Hace que el sistema sea mucho más rápido

**CDN (Content Delivery Network)**
- **Qué es**: Red de servidores distribuidos geográficamente
- **Función**: Sirve archivos desde el servidor más cercano al usuario
- **Beneficio**: El sistema carga más rápido

**CI/CD (Continuous Integration/Continuous Deployment)**
- **Qué es**: Proceso automatizado para probar y desplegar código
- **Función**: Cada cambio se prueba automáticamente antes de pasar a producción
- **Beneficio**: Menos errores, despliegues más rápidos

**Cloud (Nube)**
- **Qué es**: Servidores en internet en vez de en su oficina
- **Proveedores**: AWS, Azure, Google Cloud
- **Ventajas**: No compran hardware, pagan solo lo que usan, escalable

**CRUD**
- **Qué es**: Create, Read, Update, Delete (Crear, Leer, Actualizar, Eliminar)
- **Función**: Las 4 operaciones básicas sobre datos
- **Ejemplo**: Crear un cliente, ver un cliente, editar un cliente, eliminar un cliente

---

## D

**Dashboard**
- **Qué es**: Panel de control con información resumida
- **Incluye**: Gráficos, números clave, métricas importantes
- **Ejemplo**: Como el tablero de un carro que muestra velocidad, gasolina, etc.

**Disaster Recovery (DR)**
- **Qué es**: Plan y sistema de respaldo para recuperarse de desastres
- **Incluye**: Servidor de respaldo en otra ubicación
- **Función**: Si el servidor principal falla, el de respaldo toma el control

---

## E

**Encriptación**
- **Qué es**: Proceso de cifrar datos para que nadie más pueda leerlos
- **Analogía**: Como poner información en una caja fuerte con candado
- **En nuestro caso**: AES-256 (estándar militar)

**Excel Import/Export**
- **Qué es**: Capacidad de importar y exportar datos desde/hacia Excel
- **Función**: Cargar 100+ clientes desde un archivo Excel en segundos
- **Beneficio**: No tienen que digitar uno por uno

---

## F

**FilamentPHP**
- **Qué es**: Herramienta para crear paneles administrativos modernos
- **Ventaja**: Reduce 60% el tiempo de desarrollo de interfaces
- **Resultado**: Interfaces bonitas, modernas y fáciles de usar

**Firewall**
- **Qué es**: Barrera de seguridad que controla quién puede acceder al sistema
- **Función**: Bloquea accesos no autorizados
- **Analogía**: Como un guardia de seguridad en la entrada

**Framework**
- **Qué es**: Plataforma base para construir aplicaciones
- **En nuestro caso**: Laravel 12
- **Analogía**: Como los cimientos y estructura de un edificio

**Frontend**
- **Qué es**: La parte del sistema que ven los usuarios (la interfaz)
- **Incluye**: Pantallas, formularios, botones, menús
- **Tecnología**: FilamentPHP, Tailwind CSS

---

## G

**Go-Live**
- **Qué es**: Momento en que el sistema pasa a producción (se lanza)
- **Cuándo**: Semana 24 en nuestra propuesta
- **Significa**: Los usuarios empiezan a usar el sistema real

---

## H

**HTTPS**
- **Qué es**: Protocolo seguro para comunicación en internet
- **Función**: Cifra toda la comunicación entre el navegador y el servidor
- **Analogía**: Como hablar en código secreto que solo ustedes entienden

---

## I

**Integración**
- **Qué es**: Conexión entre dos sistemas diferentes
- **En nuestro caso**: Conexión con SAP S/4HANA
- **Beneficio**: Los sistemas comparten información automáticamente

---

## J

**Jira**
- **Qué es**: Herramienta para gestión de proyectos
- **Función**: Tablero visual donde pueden ver qué se está haciendo cada día
- **Beneficio**: Transparencia total del progreso

**JWT (JSON Web Token)**
- **Qué es**: Método seguro de autenticación
- **Función**: Verifica que el usuario es quien dice ser
- **Ventaja**: Más seguro que cookies tradicionales

---

## K

**KPI (Key Performance Indicator)**
- **Qué es**: Indicador clave de rendimiento
- **Ejemplos**: Número de cotizaciones del mes, tiempo promedio de aprobación
- **Función**: Medir el desempeño del negocio

---

## L

**Laravel**
- **Qué es**: Framework (plataforma) de PHP para desarrollo web
- **Versión propuesta**: Laravel 12 (la más reciente)
- **Ventaja**: Robusto, seguro, con soporte hasta 2027

**Livewire**
- **Qué es**: Tecnología para hacer interfaces interactivas
- **Función**: Actualiza la pantalla sin recargar la página completa
- **Ejemplo**: Cuando filtran una tabla, los resultados cambian al instante

**Load Balancer (Balanceador de Carga)**
- **Qué es**: Distribuye usuarios entre varios servidores
- **Función**: Si hay muchos usuarios, los reparte para que no se sature
- **Beneficio**: El sistema sigue rápido aunque haya muchos usuarios

**LTS (Long Term Support)**
- **Qué es**: Versión con soporte extendido
- **Laravel 12 LTS**: Soporte hasta 2027
- **Beneficio**: Actualizaciones de seguridad garantizadas por años

---

## M

**MFA / 2FA (Multi-Factor Authentication)**
- **Qué es**: Autenticación de dos factores
- **Función**: Además de la contraseña, requiere un código del celular
- **Para quién**: Usuarios con permisos críticos (aprobadores)

**Migración de Datos**
- **Qué es**: Proceso de pasar datos del sistema viejo al nuevo
- **Incluye**: Clientes, productos, cotizaciones históricas
- **Cuándo**: Semana 24 (antes del go-live)

**Multi-tenancy**
- **Qué es**: Capacidad de manejar múltiples organizaciones en el mismo sistema
- **Ejemplo**: Novo Nordisk Colombia, Novo Nordisk Perú, etc.
- **Beneficio**: Preparado para expansión regional

**MySQL**
- **Qué es**: Sistema de base de datos (donde se guardan los datos)
- **Versión**: 8.0 (la más reciente)
- **Ventaja**: Rápido, confiable, usado por millones de aplicaciones

---

## N

**Notificaciones**
- **Qué es**: Alertas automáticas del sistema
- **Ejemplos**: "Tiene una cotización pendiente de aprobar", "Una negociación vence en 7 días"
- **Canales**: Email, panel del sistema, push notifications

---

## O

**OAuth2**
- **Qué es**: Protocolo estándar de autenticación
- **Función**: Permite inicio de sesión seguro
- **Uso**: Para conectar con SAP de forma segura

---

## P

**PDF (Portable Document Format)**
- **Qué es**: Formato de documento universal
- **Uso**: Generar cotizaciones, negociaciones, reportes
- **Ventaja**: Se ve igual en cualquier dispositivo

**PHP**
- **Qué es**: Lenguaje de programación para web
- **Versión propuesta**: 8.4 (la más reciente)
- **Beneficio**: 30-40% más rápido que versiones anteriores

**Producción**
- **Qué es**: Ambiente donde trabajan los usuarios reales con datos reales
- **Vs Desarrollo**: Desarrollo es para probar, producción es el sistema real

---

## Q

**Queue (Cola)**
- **Qué es**: Sistema para procesar tareas en segundo plano
- **Ejemplo**: Generar 1000 notas crédito sin que el usuario tenga que esperar
- **Beneficio**: El usuario puede seguir trabajando mientras se procesa

---

## R

**Redis**
- **Qué es**: Sistema de memoria rápida (caché)
- **Función**: Guarda datos frecuentes en memoria RAM
- **Beneficio**: Consultas 100x más rápidas

**REST API**
- Ver "API RESTful"

**Rollback**
- **Qué es**: Capacidad de revertir cambios
- **Cuándo**: Si algo sale mal después de un cambio
- **Beneficio**: Pueden volver al estado anterior rápidamente

---

## S

**SAP S/4HANA**
- **Qué es**: Sistema ERP de Novo Nordisk
- **Integración**: El nuevo sistema se conectará automáticamente con SAP
- **Función**: Importar ventas, exportar notas crédito

**Scrum**
- **Qué es**: Metodología ágil de trabajo
- **Características**: Sprints semanales, entregas incrementales
- **Beneficio**: Ven progreso cada semana

**SFTP (Secure File Transfer Protocol)**
- **Qué es**: Protocolo seguro para transferir archivos
- **Uso**: Enviar archivos a SAP de forma segura
- **Ventaja**: Más seguro que email o FTP normal

**SLA (Service Level Agreement)**
- **Qué es**: Acuerdo de niveles de servicio
- **Incluye**: Tiempos de respuesta garantizados
- **Ejemplo**: "Incidentes críticos se responden en 1 hora"

**Sprint**
- **Qué es**: Ciclo de trabajo de duración fija
- **En nuestra propuesta**: 1 semana
- **Resultado**: Entregable funcional al final de cada sprint

**SSO (Single Sign-On)**
- **Qué es**: Inicio de sesión único
- **Función**: Usan sus credenciales corporativas
- **Beneficio**: No necesitan recordar otra contraseña

**Staging**
- **Qué es**: Ambiente de pruebas (copia del sistema de producción)
- **Función**: Probar cambios antes de pasarlos a producción
- **Beneficio**: Evita errores en el sistema real

---

## T

**Tailwind CSS**
- **Qué es**: Framework de diseño para interfaces
- **Función**: Hace que todo se vea moderno y profesional
- **Integración**: Viene incluido con FilamentPHP

**Testing / QA (Quality Assurance)**
- **Qué es**: Proceso de probar que todo funcione correctamente
- **Tipos**: Funcional, integración, carga, seguridad
- **Cuándo**: Continuamente durante desarrollo + intensivo en Semana 21-22

---

## U

**UAT (User Acceptance Testing)**
- **Qué es**: Pruebas de aceptación por usuarios
- **Quién**: Usuarios de Novo Nordisk
- **Cuándo**: Semana 21-22
- **Función**: Ustedes validan que todo funcione como esperan

**UI/UX (User Interface / User Experience)**
- **UI**: Cómo se ve el sistema (colores, botones, diseño)
- **UX**: Qué tan fácil es de usar
- **Objetivo**: Sistema bonito Y fácil de usar

---

## V

**VPC (Virtual Private Cloud)**
- **Qué es**: Red privada virtual en la nube
- **Función**: Aísla sus servidores de otros clientes
- **Beneficio**: Mayor seguridad

---

## W

**Webhook**
- **Qué es**: Notificación automática entre sistemas
- **Ejemplo**: Cuando SAP crea un cliente, notifica al sistema automáticamente
- **Beneficio**: Sincronización en tiempo real

---

## Términos de Negocio (No Técnicos pero Importantes)

**Cotización**
- Documento con precios propuestos a un cliente

**Negociación**
- Acuerdo comercial con condiciones específicas

**Liquidación**
- Proceso de calcular descuentos a aplicar

**Nota Crédito**
- Documento que reduce el valor a pagar por el cliente

**Precio Lista**
- Precio base del producto

**Precio Neto**
- Precio mínimo permitido

**Precio Regulado**
- Precio máximo permitido por ley

**Descuento Tarifario**
- Descuento sobre el precio (requiere aprobación)

**Descuento Financiero**
- Descuento por pronto pago (preaprobado)

---

**¿Tienen dudas sobre algún término?**

Este glosario está disponible para consulta durante todo el proyecto. Si encuentran un término que no está aquí, con gusto lo agregamos.


