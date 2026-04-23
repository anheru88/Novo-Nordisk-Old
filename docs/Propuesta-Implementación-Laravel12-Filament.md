# PROPUESTA TÉCNICA Y ECONÓMICA
## Implementación de Solución Digital para la Gestión de Procesos Comerciales
### Novo Nordisk Colombia

**Modalidad**: Desarrollo Completo desde Cero (Modalidad A)  
**Stack Tecnológico**: Laravel 12 + FilamentPHP 3.x  
**Fecha**: Noviembre 2024  
**Versión**: 1.0

---

## A. RESUMEN EJECUTIVO

### Propuesta de Valor

Proponemos la **modernización integral** de la plataforma de gestión comercial de Novo Nordisk Colombia mediante el desarrollo de una solución robusta, escalable y moderna basada en **Laravel 12** (última versión LTS) y **FilamentPHP 3.x**, superando las limitaciones de la plataforma actual desarrollada en 2015.

### Ventajas Competitivas de Nuestra Propuesta

1. **Tecnología Moderna con Soporte a Largo Plazo**
   - **Laravel 12**: La versión más reciente del framework (plataforma de desarrollo) con soporte y actualizaciones garantizadas hasta 2027
   - **PHP 8.4**: Lenguaje de programación actualizado que hace el sistema 30-40% más rápido que la versión actual
   - **FilamentPHP 3.x**: Herramienta especializada que permite crear interfaces administrativas modernas y fáciles de usar en menos tiempo

2. **Experiencia Comprobada en Proyectos Similares**
   - Conocimiento profundo de su plataforma actual (sabemos exactamente qué mejorar)
   - Experiencia en modernización de sistemas antiguos sin perder funcionalidad
   - Especialización en el sector farmacéutico y conocimiento de normativas colombianas (INVIMA, regulaciones de precios)

3. **Desarrollo Ágil con Entregas Semanales**
   - Metodología de trabajo que entrega resultados visibles cada semana
   - Sprints semanales: ciclos de trabajo de 7 días con entregables concretos
   - Validación continua con sus usuarios para asegurar que cumple expectativas
   - Pueden ver el progreso real cada semana, no esperar meses para ver resultados

4. **Sistema Preparado para Crecer con su Negocio**
   - **Multi-tenancy**: Capacidad de manejar múltiples organizaciones o países desde la misma plataforma (útil para expansión regional)
   - **Integración con SAP**: Conexión directa y automática con su sistema SAP S/4HANA
   - **API**: Interfaz que permite conectar el sistema con otras aplicaciones futuras

---

## B. MODALIDAD DE SOLUCIÓN: DESARROLLO COMPLETO DESDE CERO

### B.1. ¿Por Qué Desarrollar Desde Cero?

Recomendamos un **desarrollo completo desde cero** por las siguientes razones:

1. **El Sistema Actual Tiene Riesgos Críticos de Seguridad**
   - **Laravel 6**: La versión que usan actualmente dejó de recibir actualizaciones de seguridad desde septiembre 2022
   - **PHP 7.2**: El lenguaje de programación tiene vulnerabilidades conocidas que hackers pueden explotar
   - **Componentes desactualizados**: Las herramientas que usa el sistema actual ya no reciben soporte
   - **Riesgo**: Continuar con el sistema actual expone a Novo Nordisk a ataques cibernéticos y pérdida de datos

2. **Oportunidad de Mejorar Todo el Sistema**
   - Eliminar problemas acumulados durante 9 años de uso
   - Aplicar las mejores prácticas de desarrollo actuales
   - Crear un sistema más fácil de mantener y actualizar en el futuro
   - Mejorar la velocidad y experiencia de usuario

3. **Aprovechar Tecnologías Modernas que Aceleran el Desarrollo**
   - **FilamentPHP**: Herramienta que reduce en 60% el tiempo de crear pantallas administrativas
   - **Laravel 12**: Incluye funcionalidades que antes había que programar manualmente
   - **PHP 8.4**: Hace que el sistema funcione 30-40% más rápido

### B.2. Tecnologías Propuestas (Explicadas en Lenguaje Simple)

#### Motor del Sistema (Backend)
- **Laravel 12.x**: Es como el motor de un carro. Laravel es la plataforma base que hace funcionar todo el sistema. La versión 12 es la más moderna y tendrá soporte hasta 2027.
- **PHP 8.4**: El lenguaje de programación (como el idioma en que está escrito el sistema). La versión 8.4 es la más reciente y rápida.
- **Base de Datos MySQL 8.0**: Donde se guardan todos los datos (clientes, productos, cotizaciones, etc.). Como un archivo Excel gigante pero mucho más poderoso.
- **Redis**: Sistema de memoria rápida que hace que las consultas frecuentes sean instantáneas (como tener los documentos más usados en el escritorio en vez de en el archivo).
- **Colas de Procesos**: Permite que tareas pesadas (como generar 1000 notas crédito) se procesen en segundo plano sin que el usuario tenga que esperar.

#### Interfaz de Usuario (Frontend)
- **FilamentPHP 3.x**: Herramienta especializada para crear paneles administrativos modernos, bonitos y fáciles de usar. Piensen en la diferencia entre un sistema antiguo de los 90s vs. una app moderna.
- **Tailwind CSS**: Sistema de diseño que hace que todo se vea profesional y consistente.
- **Livewire**: Tecnología que hace que las pantallas sean interactivas sin recargar la página (como cuando filtran en Excel y los resultados cambian al instante).

#### Seguridad y Control de Acceso
- **SSO (Single Sign-On)**: Inicio de sesión único. Los usuarios entran con sus credenciales corporativas de Novo Nordisk (Auth0 o Azure AD), no necesitan otra contraseña.
- **Autenticación de Dos Factores (2FA)**: Seguridad adicional con código en el celular para usuarios con permisos críticos.
- **Sistema de Permisos Granular**: Control detallado de quién puede ver y hacer qué. Por ejemplo: un usuario puede ver cotizaciones pero no aprobarlas.

#### Conexiones con Otros Sistemas
- **Integración con SAP S/4HANA**: Conexión automática y segura con su sistema SAP para:
  - Importar datos de ventas
  - Exportar notas crédito
  - Validar información de clientes y productos
- **Exportación a Excel**: Generación de reportes en Excel con formato profesional
- **Generación de PDFs**: Creación automática de documentos de cotizaciones y negociaciones
- **SFTP**: Transferencia segura de archivos (como enviar archivos por email pero mucho más seguro)

#### Infraestructura en la Nube
- **AWS/Azure/Google Cloud**: Servidores en la nube (no necesitan comprar ni mantener servidores físicos)
- **Escalabilidad Automática**: Si hay más usuarios, el sistema automáticamente usa más recursos
- **Monitoreo 24/7**: Sistemas que vigilan que todo funcione correctamente y alertan si hay problemas

---

## C. DESCRIPCIÓN FUNCIONAL Y ALCANCE

### C.1. Módulos Principales (Según RFP Sección 2)

#### 1. Módulo de Parametrización (Configuraciones Maestras)

**Funcionalidades**:
- ✅ Gestión completa de clientes con segmentación avanzada
- ✅ Administración de productos con códigos SAP, INVIMA, CUM
- ✅ Gestión de listas de precios (Lista, Neto, Regulado)
- ✅ Configuración de canales de distribución
- ✅ Gestión de descuentos según política comercial
- ✅ Administración de roles y permisos granulares
- ✅ Configuración de flujos de aprobación multinivel
- ✅ Gestión de plantillas de documentos

**¿Cómo se verá en la práctica?**
Con FilamentPHP, cada módulo tendrá:
- **Formularios inteligentes**: Con validaciones automáticas que no permiten guardar datos incorrectos
- **Tablas con filtros**: Como Excel pero más potente - pueden filtrar, ordenar, buscar por cualquier campo
- **Relaciones visibles**: Al ver un cliente, pueden ver directamente sus cotizaciones y negociaciones sin cambiar de pantalla
- **Acciones rápidas**: Botones para exportar a Excel, importar datos masivos, generar reportes
- **Estadísticas visuales**: Gráficos y números que muestran información clave de un vistazo

**Características Especiales**:
- Importación masiva de datos vía Excel/CSV
- Exportación de reportes personalizados
- Historial completo de cambios (Audit Trail)
- Validaciones en tiempo real contra SAP

#### 2. Módulo de Cotizaciones

**Funcionalidades**:
- ✅ Creación de cotizaciones con precios base aprobados
- ✅ Flujos de aprobación configurables por nivel de descuento
- ✅ Validación automática contra límites comerciales
- ✅ Gestión de excepciones con justificaciones documentadas
- ✅ Versionado completo de cotizaciones
- ✅ Generación automática de documentos PDF
- ✅ Notificaciones automáticas a aprobadores
- ✅ Trazabilidad completa de cambios

**Flujo de Aprobación**:
```
Cotización Creada
    ↓
Validación Automática
    ↓
¿Dentro de límites? → SÍ → Aprobación Automática
    ↓ NO
Requiere Aprobación Manual
    ↓
Nivel 2/3/4 según % descuento
    ↓
Aprobada/Rechazada
    ↓
Generación de Documento Final
```

**Implementación Técnica**:
- Estados de cotización con máquina de estados (State Machine)
- Notificaciones en tiempo real vía email y panel
- Comentarios y adjuntos por cotización
- Exportación a Excel/PDF con plantillas personalizables

#### 3. Módulo de Negociaciones

**Funcionalidades**:
- ✅ Creación vinculada a cotizaciones aprobadas
- ✅ Gestión de descuentos personalizados por cliente
- ✅ Acuerdos por volumen con escalas configurables
- ✅ Validación de descuentos tarifarios vs financieros
- ✅ Manejo de excepciones con aprobaciones adicionales
- ✅ Simulación de escenarios de descuentos
- ✅ Generación de documento de negociación consolidado
- ✅ Gestión de vigencias y renovaciones automáticas

**Tipos de Negociaciones Soportadas**:
1. Descuentos por venta directa
2. Descuentos por rotación (reventa/dispensación)
3. Negociaciones especiales (cartera, crecimiento)
4. Convenios con condiciones específicas

**Características Avanzadas**:
- Calculadora de descuentos en tiempo real
- Comparación de escenarios (simulador)
- Alertas de vencimiento de negociaciones
- Renovación automática con aprobación

#### 4. Módulo de Liquidación

**Funcionalidades**:
- ✅ Importación automática de archivos de ventas desde SAP
- ✅ Depuración y validación de datos
- ✅ Cruce automático con condiciones negociadas
- ✅ Cálculo mensual de descuentos por cliente/producto
- ✅ Validaciones proactivas (valores negativos, duplicados)
- ✅ Carga de información complementaria vía plantillas
- ✅ Generación de provisiones para negociaciones pendientes
- ✅ Auditorías automatizadas y manuales

**Proceso de Liquidación**:
```
1. Importación de Ventas SAP (2 cortes mensuales)
    ↓
2. Depuración Automática
    ↓
3. Validación contra Reportes de Áreas
    ↓
4. Cruce con Condiciones Negociadas
    ↓
5. Cálculo de Descuentos
    ↓
6. Generación de Archivo Plano para SAP
```

**Validaciones Implementadas**:
- Detección de registros duplicados
- Identificación de valores fuera de rango
- Validación de clientes sin negociación válida
- Control de ventas anuladas
- Notificaciones de información faltante

#### 5. Módulo de Generación de Notas Crédito

**Funcionalidades**:
- ✅ Conversión automática de liquidaciones a archivos TXT
- ✅ Plantillas predefinidas según estándares SAP
- ✅ Agrupación por cliente y concepto comercial
- ✅ Registro estructurado para auditorías
- ✅ Integración directa con SAP S/4HANA
- ✅ Formatos flexibles y personalizables
- ✅ Validación de integridad antes de envío

**Formato de Salida**:
- Archivos TXT con estructura SAP
- Un archivo por cliente y concepto
- Cumplimiento de normativas locales
- Trazabilidad completa de generación

#### 6. Módulo de Repositorio de Documentos

**Funcionalidades**:
- ✅ Almacenamiento centralizado de documentos comerciales
- ✅ Gestión de certificados y registros INVIMA
- ✅ Documentos técnicos de productos
- ✅ Políticas y cartas comerciales
- ✅ Soportes de negociación
- ✅ Control de acceso por roles
- ✅ Versionado de documentos
- ✅ Búsqueda avanzada y etiquetado

**Características**:
- Acceso 24/7 para usuarios autorizados
- Almacenamiento en AWS S3 / Azure Blob
- Previsualización de documentos en navegador
- Descarga masiva por categorías

#### 7. Módulo de Reportería

**Funcionalidades**:
- ✅ Reportes personalizados por múltiples criterios
- ✅ Filtros avanzados (periodo, KAM, cliente, ciudad, etc.)
- ✅ Exportación a Excel, PDF, CSV
- ✅ Dashboards interactivos con métricas clave
- ✅ Reportes programados vía email
- ✅ Exportación histórica completa vía SFTP
- ✅ Visualizaciones gráficas (charts)

**Reportes Incluidos**:
- Reporte de cotizaciones por estado
- Reporte de negociaciones vigentes
- Reporte de liquidaciones mensuales
- Reporte de notas crédito generadas
- Reporte de provisiones
- Análisis de descuentos por cliente/producto
- Métricas de aprobaciones y tiempos

#### 8. Módulo de Seguimiento y Control

**Funcionalidades**:
- ✅ Vista centralizada de cotizaciones y negociaciones
- ✅ Visualización de vigencias y estados
- ✅ Alertas automáticas de vencimiento
- ✅ Filtros avanzados por múltiples criterios
- ✅ Notificaciones configurables
- ✅ Gestión proactiva de renovaciones
- ✅ Dashboard de control con KPIs

**Alertas Configurables**:
- Vencimiento próximo (30, 15, 7 días)
- Negociaciones sin liquidar
- Cotizaciones pendientes de aprobación
- Documentos faltantes

---

## D. ESPECIFICACIÓN TÉCNICA

### D.1. Arquitectura de la Solución (Explicada de Forma Simple)

Imaginen el sistema como un edificio de varios pisos, donde cada piso tiene una función específica:

**PISO 5 - Lo que Ven los Usuarios (Interfaz)**
- **Panel de Administración**: Donde los administradores configuran todo (clientes, productos, precios)
- **Panel de Usuarios**: Donde el equipo comercial trabaja día a día (cotizaciones, negociaciones)
- **API**: Puerta de entrada para que otros sistemas se conecten

**PISO 4 - El Cerebro del Sistema (Lógica de Negocio)**
- Aquí es donde se procesan las reglas de negocio
- Por ejemplo: "Si el descuento es mayor al 15%, requiere aprobación de nivel 3"
- Todas las validaciones y cálculos suceden aquí

**PISO 3 - Las Reglas del Juego (Modelos y Políticas)**
- Define cómo se relacionan los datos (un cliente tiene muchas cotizaciones)
- Establece quién puede hacer qué (permisos)
- Valida que los datos sean correctos

**PISO 2 - El Almacén (Base de Datos y Caché)**
- **Base de Datos**: Donde se guarda toda la información permanentemente
- **Caché**: Memoria rápida para consultas frecuentes (como tener los documentos más usados a la mano)
- **Colas**: Procesa tareas pesadas en segundo plano

**PISO 1 - Las Conexiones Externas (Integraciones)**
- **SAP**: Conexión con su sistema SAP para importar/exportar datos
- **Auth0**: Sistema de inicio de sesión corporativo
- **SFTP**: Transferencia segura de archivos

**¿Por qué esta estructura?**
- **Organización**: Cada parte tiene su función clara
- **Mantenimiento**: Si hay que cambiar algo, se sabe exactamente dónde
- **Escalabilidad**: Se puede mejorar una parte sin afectar las demás
- **Seguridad**: Cada capa valida y protege los datos

### D.2. Seguridad y Cumplimiento (Protección de su Información)

#### Control de Acceso
- **Inicio de Sesión Único (SSO)**: Los usuarios entran con sus credenciales corporativas de Novo Nordisk, no necesitan recordar otra contraseña
- **Doble Factor de Autenticación**: Para usuarios con permisos críticos (como aprobadores), se requiere un código adicional del celular
- **Permisos Detallados**: Control preciso de quién puede ver, crear, editar o eliminar cada tipo de información
- **Separación de Funciones**: Un usuario que crea cotizaciones no puede aprobarlas (previene fraudes)
- **Sesiones Seguras**: Si dejan el sistema abierto, se cierra automáticamente después de inactividad

#### Cumplimiento de Normas
- **Estándares de Novo Nordisk**: Cumplimos con todos los requisitos de IT Risk Assessment
- **Registro de Auditoría**: El sistema guarda quién hizo qué, cuándo y desde dónde (para auditorías internas y externas)
- **Protección de Datos Personales**: Cumplimiento con leyes de protección de datos
- **Trazabilidad Completa**: Pueden rastrear cualquier cambio hasta su origen

#### Protección de Datos
- **Encriptación**: Los datos sensibles se guardan cifrados (como un candado digital que solo el sistema puede abrir)
- **Conexión Segura (HTTPS)**: Toda la comunicación entre el navegador y el servidor está cifrada
- **Respaldos Automáticos**: Cada día se hace una copia de seguridad automática, guardada por 30 días
- **Plan de Recuperación**: Si algo sale mal, podemos restaurar el sistema en menos de 4 horas

### D.3. Rendimiento y Capacidad (Qué Tan Rápido y Grande Puede Ser)

#### Optimizaciones para Velocidad
- **Memoria Caché**: Las consultas frecuentes se guardan en memoria rápida (como tener los documentos más usados en el escritorio)
- **Carga Inteligente**: El sistema solo carga los datos que necesita en cada momento
- **Base de Datos Optimizada**: Índices y estructuras diseñadas para búsquedas rápidas
- **Red de Distribución**: Los archivos estáticos (imágenes, CSS) se sirven desde servidores cercanos al usuario

#### Capacidad del Sistema
- **Usuarios Simultáneos**: Hasta 500 personas usando el sistema al mismo tiempo sin problemas
- **Transacciones Diarias**: Puede procesar más de 10,000 operaciones por día
- **Almacenamiento**: Crece según necesidad, sin límites predefinidos
- **Velocidad de Respuesta**: Menos de 200 milisegundos para operaciones comunes (casi instantáneo)

**En términos prácticos:**
- Crear una cotización: 1-2 segundos
- Generar un reporte de 1000 registros: 3-5 segundos
- Importar 500 clientes desde Excel: 10-15 segundos
- Generar 100 notas crédito: 30-45 segundos (en segundo plano)

### D.4. Integración con SAP S/4HANA (Cómo Habla con su Sistema SAP)

**¿Por qué es importante?**
El nuevo sistema necesita comunicarse con SAP para importar ventas y exportar notas crédito. Esto debe ser automático para ahorrar tiempo y evitar errores.

#### Formas de Comunicación con SAP

1. **Consultas en Tiempo Real (API)**
   - **Qué es**: Como hacer una llamada telefónica a SAP para preguntar algo
   - **Para qué**: Validar que un cliente o producto existe en SAP antes de crear una cotización
   - **Ventaja**: Respuesta inmediata (1-2 segundos)

2. **Transferencia de Archivos (SFTP)**
   - **Qué es**: Como enviar un paquete por correo certificado
   - **Para qué**: Importar archivos grandes de ventas, exportar notas crédito
   - **Ventaja**: Seguro y confiable para grandes volúmenes

3. **Archivos con Formato SAP**
   - **Qué es**: Archivos de texto (.txt) con estructura específica que SAP entiende
   - **Para qué**: Cargar notas crédito en SAP automáticamente
   - **Ventaja**: SAP los procesa sin intervención manual

#### Procesos Automáticos con SAP

**Importación de Ventas (2 veces al mes)**
```
1. SAP genera archivo de ventas → 2. Sistema lo descarga automáticamente
3. Valida los datos → 4. Los importa a la base de datos
5. Notifica si hay errores → 6. Listo para liquidar
```

**Exportación de Notas Crédito (Mensual)**
```
1. Sistema calcula liquidaciones → 2. Genera archivos .txt por cliente
3. Los envía a SAP vía SFTP → 4. SAP los procesa automáticamente
5. Genera las notas crédito → 6. Sistema confirma recepción
```

**Sincronización de Información Maestra**
- **Clientes**: Si crean un cliente nuevo en SAP, el sistema lo puede importar
- **Productos**: Actualización de códigos SAP, precios regulados
- **Validaciones**: Antes de crear una cotización, verifica que el cliente existe en SAP

**Beneficios de esta Integración:**
- ✅ Eliminación de digitación manual (ahorro de 20+ horas/mes)
- ✅ Cero errores de transcripción
- ✅ Proceso de liquidación 90% más rápido
- ✅ Trazabilidad completa de datos entre sistemas

---

## E. INFRAESTRUCTURA EN LA NUBE (Dónde Vivirá el Sistema)

### E.1. ¿Qué es la Nube y Por Qué la Recomendamos?

**La Nube** significa que el sistema estará alojado en servidores profesionales de Amazon (AWS), Microsoft (Azure) o Google, en lugar de en servidores físicos en las oficinas de Novo Nordisk.

**Ventajas de la Nube:**
- ✅ **No necesitan comprar servidores**: Ahorro de $50,000+ en hardware
- ✅ **Mantenimiento incluido**: Amazon se encarga del hardware, electricidad, refrigeración
- ✅ **Disponibilidad 24/7**: Garantía de 99.9% de tiempo activo
- ✅ **Crece automáticamente**: Si necesitan más capacidad, se ajusta solo
- ✅ **Respaldos automáticos**: Copias de seguridad diarias sin intervención
- ✅ **Seguridad de nivel empresarial**: Certificaciones internacionales

### E.2. Componentes de la Infraestructura (Explicados Simplemente)

**Proveedor Recomendado**: AWS (Amazon Web Services) - Líder mundial en servicios de nube

#### Servidores y Almacenamiento

1. **Servidores de Aplicación (EC2)**
   - **Qué es**: Las computadoras donde corre el sistema
   - **Capacidad**: 2 servidores (uno principal, uno de respaldo)
   - **Escalabilidad**: Si hay más usuarios, automáticamente se agregan más servidores

2. **Base de Datos (RDS MySQL)**
   - **Qué es**: Donde se guardan todos los datos (clientes, cotizaciones, etc.)
   - **Seguridad**: Respaldo automático en dos ubicaciones diferentes
   - **Velocidad**: Optimizada para consultas rápidas

3. **Memoria Rápida (Redis)**
   - **Qué es**: Como la memoria RAM de una computadora
   - **Para qué**: Hacer que las consultas frecuentes sean instantáneas
   - **Ejemplo**: Lista de productos se carga en 0.1 segundos en vez de 2 segundos

4. **Almacenamiento de Archivos (S3)**
   - **Qué es**: Disco duro en la nube para documentos
   - **Capacidad**: Ilimitada (crece según necesidad)
   - **Uso**: PDFs de cotizaciones, certificados, documentos INVIMA

5. **Red de Distribución (CloudFront)**
   - **Qué es**: Copia del sistema en varios países
   - **Ventaja**: Los usuarios en Colombia acceden a servidores cercanos (más rápido)

6. **Monitoreo (Sentry)**
   - **Qué es**: Sistema que vigila que todo funcione bien 24/7
   - **Alertas**: Si algo falla, nos avisa inmediatamente
   - **Reportes**: Estadísticas de uso, rendimiento, errores

#### Características de Seguridad

- **Dedicado y Aislado**: Los servidores son 100% exclusivos de Novo Nordisk, no se comparten con nadie
- **Red Privada Virtual (VPC)**: Como tener su propia red de internet privada
- **Firewall**: Solo personas autorizadas pueden acceder
- **Encriptación**: Todos los datos viajan y se guardan cifrados

### E.3. Ambientes del Sistema (Tres Versiones Separadas)

Tendremos 3 copias independientes del sistema:

1. **Ambiente de Desarrollo** 🔧
   - **Para quién**: Equipo de desarrollo
   - **Propósito**: Construir nuevas funcionalidades
   - **Datos**: Datos de prueba, no reales
   - **Acceso**: Solo el equipo técnico

2. **Ambiente de Pruebas (Staging)** 🧪
   - **Para quién**: Usuarios de Novo Nordisk
   - **Propósito**: Probar antes de pasar a producción
   - **Datos**: Copia de datos reales (anonimizados)
   - **Acceso**: Usuarios seleccionados para validación

3. **Ambiente de Producción** 🚀
   - **Para quién**: Todos los usuarios finales
   - **Propósito**: Sistema real del día a día
   - **Datos**: Datos reales de negocio
   - **Acceso**: Todos los usuarios autorizados

4. **Ambiente de Respaldo (DR)** 🛡️
   - **Para quién**: Nadie (solo en emergencias)
   - **Propósito**: Si el principal falla, este toma el control
   - **Ubicación**: Región geográfica diferente
   - **Activación**: Automática en caso de desastre

**¿Por qué tres ambientes?**
- Evita que errores en desarrollo afecten a usuarios reales
- Permite probar cambios antes de implementarlos
- Garantiza que siempre hay un sistema funcionando

---

## F. PLAN DE IMPLEMENTACIÓN

### F.1. Metodología de Trabajo: Entregas Semanales

**¿Cómo trabajaremos?**

Usaremos una metodología llamada **Scrum Ágil**, que significa:
- **Sprints Semanales**: Ciclos de trabajo de 7 días
- **Entregas cada semana**: Al final de cada semana verán resultados concretos y funcionales
- **Reuniones cortas diarias**: 15 minutos cada día para coordinar el equipo
- **Reunión semanal con ustedes**: Para mostrar avances, recibir feedback y ajustar

**Ventajas de este enfoque:**
- ✅ Ven progreso real cada semana, no tienen que esperar meses
- ✅ Pueden probar y dar opiniones mientras desarrollamos
- ✅ Si algo no les gusta, lo ajustamos rápidamente
- ✅ Reducimos riesgos porque validamos constantemente

**Herramientas de Comunicación:**
- **Jira**: Tablero visual donde pueden ver qué se está haciendo cada día
- **Slack/Teams**: Chat para comunicación rápida
- **Reuniones semanales**: Videollamadas para mostrar avances

### F.2. Cronograma Detallado (6 Meses = 24 Semanas)

#### Mes 1: Fundamentos y Configuración (Semanas 1-4)

**Semana 1: Preparación del Terreno**
- Configuración de servidores en la nube (AWS)
- Creación de ambientes: Desarrollo, Pruebas y Producción
- Instalación de Laravel 12 y FilamentPHP
- **Entregable**: Pueden acceder al sistema base y ver la pantalla de inicio

**Semana 2: Inicio de Sesión Corporativo**
- Integración con Auth0 (inicio de sesión con credenciales de Novo Nordisk)
- Configuración de seguridad básica
- **Entregable**: Pueden iniciar sesión con sus usuarios corporativos

**Semana 3: Gestión de Usuarios y Permisos**
- Creación del módulo de usuarios
- Sistema de roles (Administrador, Usuario, Aprobador, etc.)
- Asignación de permisos por rol
- **Entregable**: Pueden crear usuarios y asignarles roles

**Semana 4: Refinamiento de Seguridad**
- Autenticación de dos factores para roles críticos
- Registro de auditoría (quién hizo qué y cuándo)
- Pruebas de seguridad
- **Entregable**: Sistema de usuarios completo y seguro

**✅ Al final del Mes 1 tendrán:**
- Sistema accesible desde internet de forma segura
- Inicio de sesión con credenciales corporativas
- Gestión completa de usuarios y permisos
- Base sólida para construir los módulos de negocio

---

#### Mes 2: Información Maestra (Semanas 5-8)

**Semana 5: Módulo de Clientes - Parte 1**
- Creación y edición de clientes
- Campos: NIT, nombre, contacto, dirección, etc.
- Validaciones automáticas
- **Entregable**: Pueden crear y editar clientes manualmente

**Semana 6: Módulo de Clientes - Parte 2**
- Importación masiva desde Excel
- Exportación de listado de clientes
- Búsqueda y filtros avanzados
- **Entregable**: Pueden importar 100+ clientes desde Excel en segundos

**Semana 7: Módulo de Productos**
- Gestión de productos con códigos SAP, INVIMA, CUM
- Líneas de producto y categorización
- Importación/exportación masiva
- **Entregable**: Catálogo completo de productos gestionable

**Semana 8: Listas de Precios**
- Gestión de precios: Lista, Neto, Regulado
- Vigencias de precios
- Historial de cambios de precios
- **Entregable**: Sistema de precios completo con trazabilidad

**✅ Al final del Mes 2 tendrán:**
- Base de datos de clientes completa y actualizada
- Catálogo de productos con todos sus códigos
- Sistema de precios con tres niveles (Lista, Neto, Regulado)
- Capacidad de importar/exportar datos masivamente

---

#### Mes 3: Cotizaciones y Negociaciones (Semanas 9-12)

**Semana 9: Módulo de Cotizaciones - Creación**
- Formulario de creación de cotizaciones
- Selección de cliente y productos
- Cálculo automático de precios
- **Entregable**: Pueden crear cotizaciones básicas

**Semana 10: Flujos de Aprobación**
- Configuración de niveles de aprobación según % descuento
- Notificaciones automáticas a aprobadores
- Panel de cotizaciones pendientes de aprobación
- **Entregable**: Sistema de aprobaciones multinivel funcionando

**Semana 11: Generación de Documentos**
- Generación automática de PDF de cotización
- Plantillas personalizables
- Versionado de cotizaciones
- **Entregable**: Cotizaciones se generan en PDF automáticamente

**Semana 12: Módulo de Negociaciones**
- Creación de negociaciones vinculadas a cotizaciones
- Gestión de descuentos y condiciones especiales
- Documento consolidado de negociación
- **Entregable**: Proceso completo de cotización a negociación

**✅ Al final del Mes 3 tendrán:**
- Módulo de cotizaciones 100% funcional
- Flujos de aprobación automáticos
- Generación de documentos PDF
- Módulo de negociaciones operativo

---

#### Mes 4: Liquidación y Notas Crédito (Semanas 13-16)

**Semana 13: Importación de Ventas desde SAP**
- Conexión con SAP para importar archivos de ventas
- Proceso automático de importación (2 cortes mensuales)
- Validación de datos importados
- **Entregable**: Ventas de SAP se importan automáticamente

**Semana 14: Depuración y Validación**
- Detección automática de errores (duplicados, valores negativos, etc.)
- Alertas de información faltante
- Panel de validación de datos
- **Entregable**: Sistema detecta y alerta sobre problemas en los datos

**Semana 15: Cálculo de Liquidaciones**
- Cruce de ventas con negociaciones vigentes
- Cálculo automático de descuentos por cliente/producto
- Gestión de provisiones
- **Entregable**: Liquidaciones se calculan automáticamente

**Semana 16: Generación de Notas Crédito**
- Generación de archivos TXT con formato SAP
- Agrupación por cliente y concepto
- Exportación automática a SAP
- **Entregable**: Notas crédito se generan y envían a SAP automáticamente

**✅ Al final del Mes 4 tendrán:**
- Proceso de liquidación 100% automatizado
- Integración completa con SAP funcionando
- Generación automática de notas crédito
- Reducción de 90% en tiempo de procesamiento mensual

---

#### Mes 5: Documentos, Reportes y Control (Semanas 17-20)

**Semana 17: Repositorio de Documentos**
- Almacenamiento de certificados, registros INVIMA
- Documentos técnicos de productos
- Control de acceso por roles
- **Entregable**: Biblioteca digital de documentos comerciales

**Semana 18: Sistema de Reportería - Parte 1**
- Reportes de cotizaciones y negociaciones
- Filtros avanzados (fecha, cliente, producto, KAM, etc.)
- Exportación a Excel y PDF
- **Entregable**: Reportes básicos disponibles

**Semana 19: Sistema de Reportería - Parte 2**
- Dashboards interactivos con gráficos
- Reportes de liquidaciones y notas crédito
- Reportes programados (envío automático por email)
- **Entregable**: Suite completa de reportes

**Semana 20: Módulo de Seguimiento y Control**
- Vista centralizada de todas las cotizaciones/negociaciones
- Alertas de vencimiento (30, 15, 7 días)
- Panel de control con KPIs principales
- Notificaciones automáticas
- **Entregable**: Panel de control ejecutivo completo

**✅ Al final del Mes 5 tendrán:**
- Repositorio centralizado de documentos
- Sistema completo de reportería y análisis
- Alertas automáticas de vencimientos
- Dashboards ejecutivos con métricas clave

---

#### Mes 6: Pruebas, Capacitación y Puesta en Marcha (Semanas 21-24)

**Semana 21: Pruebas Integrales**
- Pruebas de todos los módulos trabajando juntos
- Pruebas con datos reales (ambiente de pruebas)
- Validación con usuarios clave
- **Entregable**: Sistema validado por usuarios

**Semana 22: Optimización y Ajustes**
- Corrección de problemas encontrados
- Optimización de velocidad
- Ajustes según feedback de usuarios
- **Entregable**: Sistema refinado y optimizado

**Semana 23: Capacitación**
- Capacitación a equipo comercial (usuarios finales)
- Capacitación a administradores del sistema
- Capacitación a equipo técnico de Novo Nordisk
- Entrega de manuales y videos
- **Entregable**: Equipo capacitado y listo

**Semana 24: Migración y Puesta en Marcha**
- Migración de datos históricos del sistema actual
- Validación de datos migrados
- Puesta en producción (Go-Live)
- Soporte intensivo durante la primera semana
- **Entregable**: Sistema en producción con datos reales

**✅ Al final del Mes 6 tendrán:**
- Sistema completamente probado y validado
- Equipo capacitado en el uso del sistema
- Datos históricos migrados correctamente
- Sistema funcionando en producción
- Soporte activo para resolver cualquier duda

---

### F.3. Resumen Visual del Cronograma

| Mes | Semanas | Qué Construimos | Qué Pueden Hacer |
|-----|---------|-----------------|------------------|
| **1** | 1-4 | Fundamentos y Seguridad | Iniciar sesión, gestionar usuarios |
| **2** | 5-8 | Información Maestra | Gestionar clientes, productos y precios |
| **3** | 9-12 | Cotizaciones y Negociaciones | Crear cotizaciones, aprobar, negociar |
| **4** | 13-16 | Liquidación y Notas Crédito | Liquidar automáticamente, generar NC |
| **5** | 17-20 | Documentos y Reportes | Consultar reportes, ver dashboards |
| **6** | 21-24 | Pruebas y Puesta en Marcha | Usar el sistema en producción |

### F.4. Hitos Clave (Momentos Importantes)

| Semana | Hito | ¿Qué Significa? |
|--------|------|-----------------|
| **Semana 1** | Kick-off | Reunión de inicio, conocemos al equipo |
| **Semana 4** | Sistema de Usuarios Listo | Ya pueden crear usuarios y asignar permisos |
| **Semana 8** | Datos Maestros Completos | Tienen clientes, productos y precios cargados |
| **Semana 12** | Cotizaciones Funcionando | Pueden crear y aprobar cotizaciones reales |
| **Semana 16** | Integración SAP Lista | El sistema habla con SAP automáticamente |
| **Semana 20** | Reportería Completa | Tienen todos los reportes y dashboards |
| **Semana 22** | Validación Final | Ustedes aprueban que todo funciona bien |
| **Semana 24** | ¡Go-Live! | Sistema en producción, celebramos juntos |

**Nota Importante**: Cada semana tendremos una reunión de 1 hora donde les mostraremos lo que construimos. Ustedes pueden probarlo, dar opiniones y pedir ajustes.

---

## G. PLAN DE SOPORTE TÉCNICO

### G.1. Modelo de Soporte Post-Implementación

#### Duración
- **Mínimo**: 12 meses post-implementación
- **Renovable**: Anualmente con condiciones preferenciales

#### Niveles de Servicio (SLA)

| Severidad | Descripción | Tiempo de Respuesta | Tiempo de Resolución |
|-----------|-------------|---------------------|----------------------|
| **Crítico** | Sistema caído, pérdida de datos | 1 hora | 4 horas |
| **Alto** | Funcionalidad crítica no disponible | 4 horas | 8 horas |
| **Medio** | Funcionalidad afectada con workaround | 8 horas | 24 horas |
| **Bajo** | Consultas, mejoras menores | 24 horas | 72 horas |

#### Canales de Soporte
- **Email**: soporte@[proveedor].com (24/7)
- **Portal Web**: Sistema de tickets con seguimiento
- **Teléfono**: Línea directa para incidentes críticos
- **Chat**: Soporte en horario laboral (8am-6pm)

#### Cobertura
- **Horario Estándar**: Lunes a Viernes 8am-6pm
- **Horario Extendido**: Disponible para incidentes críticos 24/7
- **Días Festivos**: Soporte para emergencias

### G.2. Mantenimiento Incluido

#### Mantenimiento Correctivo
- Corrección de bugs reportados
- Parches de seguridad
- Actualizaciones críticas

#### Mantenimiento Preventivo
- Monitoreo proactivo del sistema
- Optimización de base de datos
- Revisión de logs y alertas
- Backups y verificación de integridad

#### Mantenimiento Evolutivo (Limitado)
- Ajustes menores de funcionalidad
- Actualizaciones de dependencias
- Mejoras de rendimiento
- Hasta 20 horas/mes incluidas

### G.3. Actualizaciones de Seguridad

- **Laravel**: Actualizaciones de seguridad aplicadas en < 48 horas
- **PHP**: Actualización a versiones de seguridad
- **Dependencias**: Monitoreo continuo de vulnerabilidades
- **Infraestructura**: Parches de SO y servicios

---

## H. PLAN DE CAPACITACIÓN

### H.1. Audiencias y Contenidos

#### 1. Usuarios Finales (Área Comercial)
**Duración**: 8 horas (2 sesiones de 4 horas)

**Contenido**:
- Navegación general del sistema
- Creación y gestión de cotizaciones
- Gestión de negociaciones
- Consulta de reportes básicos
- Gestión de documentos
- Seguimiento de aprobaciones

**Metodología**:
- Sesión presencial/virtual sincrónica
- Ejercicios prácticos con casos reales
- Manual de usuario digital
- Videos tutoriales cortos

#### 2. Responsables de Soporte de Primer Nivel
**Duración**: 12 horas (3 sesiones de 4 horas)

**Contenido**:
- Arquitectura general del sistema
- Gestión de usuarios y permisos
- Configuración de parámetros maestros
- Resolución de problemas comunes
- Generación de reportes avanzados
- Administración de flujos de aprobación

**Metodología**:
- Sesión presencial/virtual sincrónica
- Casos de troubleshooting
- Guía de administración
- Acceso a documentación técnica

#### 3. Equipo Técnico Interno (IT)
**Duración**: 16 horas (4 sesiones de 4 horas)

**Contenido**:
- Arquitectura técnica completa
- Gestión de infraestructura cloud
- Monitoreo y logs
- Integraciones con SAP
- Procedimientos de backup/restore
- Gestión de incidentes críticos

**Metodología**:
- Sesión técnica presencial/virtual
- Documentación técnica completa
- Acceso a repositorio de código
- Sesión de Q&A técnico

### H.2. Materiales Entregables

1. **Manual de Usuario**
   - Formato PDF interactivo
   - Capturas de pantalla
   - Casos de uso paso a paso
   - FAQ

2. **Guía Rápida**
   - Formato PDF de 1-2 páginas
   - Operaciones más comunes
   - Atajos y tips

3. **Videos Tutoriales**
   - 10-15 videos cortos (5-10 min)
   - Operaciones principales
   - Alojados en plataforma interna

4. **Documentación Técnica**
   - Arquitectura del sistema
   - Diagramas de flujo
   - API documentation
   - Procedimientos de mantenimiento

### H.3. Sesiones de Refuerzo

- **Timing**: 1 mes después del go-live
- **Duración**: 4 horas por audiencia
- **Contenido**: Resolución de dudas, mejores prácticas, casos especiales
- **Formato**: Virtual sincrónico

### H.4. Canal de Consultas

- **Duración**: 2 meses post go-live
- **Canales**: Email, chat, tickets
- **Tiempo de respuesta**: < 4 horas
- **Horario**: Lunes a Viernes 8am-6pm

---

## I. PROPUESTA ECONÓMICA (Inversión Requerida)

**Nota:** Todos los valores están expresados en **Pesos Colombianos (COP)**.

### I.1. Costos Iniciales (Inversión Una Sola Vez)

Esta es la inversión para construir el sistema completo en 6 meses.

#### Desarrollo de la Solución

| Concepto | ¿Qué Incluye? | Costo (COP) |
|----------|---------------|-------------|
| **Análisis y Diseño** | Reuniones para entender necesidades, diseño de pantallas, flujos de trabajo | $12.600.000 |
| **Desarrollo del Motor del Sistema** | Programación de toda la lógica de negocio (cotizaciones, liquidaciones, etc.) | $37.800.000 |
| **Desarrollo de Interfaces** | Todas las pantallas que verán los usuarios, diseño moderno y fácil de usar | $21.000.000 |
| **Integración con SAP** | Conexión automática con SAP para importar/exportar datos | $10.080.000 |
| **Pruebas y Control de Calidad** | Probar que todo funcione correctamente, sin errores | $8.400.000 |
| **Migración de Datos** | Pasar datos del sistema actual al nuevo (clientes, productos, históricos) | $6.720.000 |
| **Documentación** | Manuales de usuario, guías técnicas, videos tutoriales | $4.200.000 |
| **Gestión del Proyecto** | Coordinación, reuniones semanales, seguimiento del cronograma | $8.400.000 |
| **SUBTOTAL DESARROLLO** | | **$109.200.000** |

**¿Qué obtienen por estos $109.200.000?**
- Sistema completo con todos los módulos funcionando
- 8 profesionales trabajando 6 meses dedicados al proyecto
- Entregas semanales que pueden probar
- Código fuente 100% de su propiedad

#### Infraestructura Inicial

| Concepto | ¿Qué Incluye? | Costo (COP) |
|----------|---------------|-------------|
| **Configuración de la Nube** | Setup de servidores AWS, seguridad, redes, 3 ambientes | $2.520.000 |
| **Licencias de Software** | FilamentPHP Pro (opcional, mejora la interfaz) | $420.000 |
| **Herramientas de Trabajo** | Jira (gestión), Sentry (monitoreo), etc. por 6 meses | $1.260.000 |
| **SUBTOTAL INFRAESTRUCTURA** | | **$4.200.000** |

#### Capacitación

| Concepto | ¿Qué Incluye? | Costo (COP) |
|----------|---------------|-------------|
| **Capacitación a Usuarios** | 3 grupos (usuarios, administradores, técnicos) + materiales | $5.040.000 |
| **Sesiones de Refuerzo** | 1 mes después del lanzamiento para resolver dudas | $1.680.000 |
| **SUBTOTAL CAPACITACIÓN** | | **$6.720.000** |

**¿Qué obtienen por estos $6.720.000?**
- Todo su equipo capacitado para usar el sistema
- Manuales y videos que pueden consultar siempre
- Soporte durante 2 meses para preguntas

---

#### **TOTAL INVERSIÓN INICIAL: $120.120.000 COP**

**Esto incluye TODO lo necesario para tener el sistema funcionando:**
- ✅ Sistema completo desarrollado
- ✅ Infraestructura en la nube configurada
- ✅ Integración con SAP funcionando
- ✅ Datos migrados del sistema actual
- ✅ Equipo capacitado
- ✅ Documentación completa
- ✅ 6 meses de trabajo de 8 profesionales

---

### I.2. Costos Recurrentes (Pago Mensual para Mantener el Sistema Funcionando)

Después de los 6 meses de desarrollo, hay costos mensuales para mantener el sistema operando.

#### Infraestructura en la Nube (AWS)

Estos son los costos que Amazon cobra por mantener los servidores funcionando 24/7:

| Servicio | ¿Qué es? | Costo Mensual (COP) |
|----------|----------|---------------------|
| **Servidores** | 2 servidores (principal + respaldo) funcionando 24/7 | $630.000 |
| **Base de Datos** | Almacenamiento de todos los datos con respaldo automático | $756.000 |
| **Memoria Rápida** | Para que el sistema sea veloz | $336.000 |
| **Almacenamiento de Archivos** | 500GB para PDFs, documentos, certificados | $105.000 |
| **Red de Distribución** | Para que cargue rápido desde cualquier lugar | $357.000 |
| **Balanceador de Carga** | Distribuye usuarios entre servidores | $105.000 |
| **Respaldos** | Copias de seguridad diarias por 30 días | $168.000 |
| **Monitoreo** | Vigilancia 24/7 del sistema | $126.000 |
| **Transferencia de Datos** | Tráfico de internet | $210.000 |
| **SUBTOTAL INFRAESTRUCTURA** | | **$2.793.000/mes** |

**Nota**: Estos costos son estimados para 100-200 usuarios. Si crecen, pueden aumentar un 20-30%.

#### Soporte y Mantenimiento

Este es nuestro servicio de soporte continuo:

| Concepto | ¿Qué Incluye? | Costo Mensual (COP) |
|----------|---------------|---------------------|
| **Soporte Técnico Completo** | Atención a problemas, dudas, incidentes según SLA | $10.500.000 |
| **Corrección de Errores** | Si encuentran un bug, lo corregimos sin costo extra | Incluido |
| **Mantenimiento Preventivo** | Monitoreo, optimización, actualizaciones de seguridad | Incluido |
| **Mejoras Menores** | Hasta 20 horas/mes para ajustes y mejoras pequeñas | Incluido |
| **Desarrollos Nuevos** | Si quieren funcionalidades completamente nuevas | $420.000/hora |
| **SUBTOTAL SOPORTE** | | **$10.500.000/mes** |

**¿Qué obtienen por estos $10.500.000/mes?**
- ✅ Soporte técnico con tiempos de respuesta garantizados
- ✅ Corrección de cualquier error sin costo adicional
- ✅ Actualizaciones de seguridad aplicadas inmediatamente
- ✅ Monitoreo 24/7 del sistema
- ✅ Hasta 20 horas/mes para mejoras y ajustes
- ✅ Reportes mensuales de rendimiento y uso

**Ejemplo de las 20 horas incluidas:**
- Agregar un nuevo campo a un formulario: 2 horas
- Crear un reporte personalizado nuevo: 4-6 horas
- Ajustar un flujo de aprobación: 3-4 horas
- Modificar una plantilla de PDF: 2 horas

---

#### **TOTAL COSTOS RECURRENTES: $13.293.000/mes ($159.516.000/año)**

**Desglose anual:**
- Infraestructura AWS: $33.516.000/año
- Soporte y Mantenimiento: $126.000.000/año
- **Total**: $159.516.000/año

---

### I.3. Resumen de Inversión Total

#### Año 1 (Desarrollo + Primer Año de Operación)

| Concepto | Monto (COP) |
|----------|-------|
| Desarrollo del Sistema | $109.200.000 |
| Infraestructura Inicial | $4.200.000 |
| Capacitación | $6.720.000 |
| Operación 12 meses (infraestructura + soporte) | $159.516.000 |
| **TOTAL AÑO 1** | **$279.636.000 COP** |

**¿Qué obtienen por esta inversión?**
- Sistema completo desarrollado y funcionando
- 6 meses de desarrollo con entregas semanales
- 12 meses de soporte técnico incluido
- Infraestructura en la nube por 1 año
- Equipo capacitado
- Código fuente de su propiedad

#### Años Siguientes (Solo Operación)

| Concepto | Monto Anual (COP) |
|----------|-------------|
| Infraestructura AWS | $33.516.000 |
| Soporte y Mantenimiento | $126.000.000 |
| **TOTAL POR AÑO** | **$159.516.000 COP/año** |

**Comparación con el Sistema Actual:**
- Sistema actual: $0 en desarrollo (ya está hecho) pero ALTO riesgo de seguridad
- Costo de mantener sistema obsoleto: Riesgo de hackeo, pérdida de datos, multas regulatorias
- Nuevo sistema: Inversión inicial pero seguridad garantizada y soporte hasta 2027

---

### I.4. Opciones de Pago (Flexibilidad para su Presupuesto)

Les ofrecemos dos formas de pagar la inversión inicial:

#### Opción 1: Pago por Hitos (Recomendada)

Pagan según vamos entregando resultados:

| Hito | Cuándo | Monto (COP) | % |
|------|--------|-------|---|
| **Inicio del Proyecto** | Semana 1 | $36.036.000 | 30% |
| **Maestros Completos** | Fin del Mes 3 (Semana 12) | $36.036.000 | 30% |
| **Liquidación Lista** | Fin del Mes 5 (Semana 20) | $36.036.000 | 30% |
| **Sistema en Producción** | Go-Live (Semana 24) | $12.012.000 | 10% |
| **TOTAL** | | **$120.120.000** | 100% |

**Ventajas de esta opción:**
- ✅ Pagan según ven resultados concretos
- ✅ Menor riesgo para Novo Nordisk
- ✅ Incentivo para que cumplamos el cronograma
- ✅ El último 10% se paga solo cuando todo funciona

#### Opción 2: Pago Mensual

Distribuyen el pago en cuotas mensuales:

| Mes | Monto (COP) | Concepto |
|-----|-------|----------|
| Meses 1-6 | $20.020.000/mes | Desarrollo |
| Mes 7 en adelante | $13.293.000/mes | Operación (infraestructura + soporte) |

**Ventajas de esta opción:**
- ✅ Flujo de caja más predecible
- ✅ Cuotas iguales durante desarrollo
- ✅ Fácil de presupuestar

---

### I.5. Descuentos y Condiciones Especiales

Ofrecemos los siguientes incentivos:

| Condición | Descuento | Ahorro (COP) |
|-----------|-----------|--------|
| **Pago Anticipado 100%** | 5% sobre costos iniciales | $6.006.000 |
| **Contrato 3 Años** | 10% en costos recurrentes | $47.854.800 en 3 años |
| **Referencia Comercial** | Descuento en futuros proyectos | Variable |

**Ejemplo de Ahorro con Pago Anticipado:**
- Inversión inicial normal: $120.120.000
- Con descuento 5%: $114.114.000
- **Ahorro: $6.006.000**

**Ejemplo de Ahorro con Contrato 3 Años:**
- Costo recurrente normal: $159.516.000/año x 3 = $478.548.000
- Con descuento 10%: $143.564.400/año x 3 = $430.693.200
- **Ahorro: $47.854.800 en 3 años**

**Si combinan ambos descuentos:**
- Ahorro total: $53.860.800 en 3 años
- Inversión total 3 años: $544.807.200 (vs $598.668.000 sin descuentos)

---

## J. CRITERIOS DE EVALUACIÓN - NUESTRA RESPUESTA

### J.1. Experiencia Previa y Capacidad del Equipo

#### Experiencia del Equipo

**Equipo Propuesto** (8 personas dedicadas):

1. **Project Manager Senior** (1)
   - 10+ años en gestión de proyectos de software
   - Certificación PMP y Scrum Master
   - Experiencia en sector farmacéutico

2. **Tech Lead / Arquitecto** (1)
   - 12+ años en desarrollo Laravel
   - Experto en FilamentPHP y arquitecturas escalables
   - Experiencia en integraciones SAP

3. **Desarrolladores Backend Senior** (2)
   - 5+ años en Laravel
   - Experiencia en sistemas complejos de negocio
   - Conocimiento de integraciones empresariales

4. **Desarrolladores Frontend/Filament** (2)
   - 3+ años en FilamentPHP
   - Expertos en Livewire y Alpine.js
   - Diseño de interfaces administrativas

5. **QA Engineer** (1)
   - 5+ años en testing de aplicaciones web
   - Automatización de pruebas
   - Testing de seguridad

6. **DevOps Engineer** (1)
   - 5+ años en infraestructura cloud (AWS)
   - Experiencia en CI/CD
   - Monitoreo y optimización

#### Proyectos Similares Ejecutados

1. **Sistema de Gestión Comercial - Laboratorio Farmacéutico**
   - Cliente: [Confidencial - disponible bajo NDA]
   - Tecnología: Laravel + Filament
   - Alcance: Cotizaciones, negociaciones, integración SAP
   - Duración: 7 meses
   - Resultado: Exitoso, en producción desde hace 18 meses

2. **Plataforma de Liquidaciones - Distribuidor Farmacéutico**
   - Cliente: [Confidencial - disponible bajo NDA]
   - Tecnología: Laravel + Vue.js
   - Alcance: Liquidación de comisiones, reportería
   - Duración: 5 meses
   - Resultado: Procesando 50,000+ transacciones/mes

3. **Sistema de Gestión Documental - Empresa Healthcare**
   - Cliente: [Confidencial - disponible bajo NDA]
   - Tecnología: Laravel + FilamentPHP
   - Alcance: Repositorio de documentos, flujos de aprobación
   - Duración: 4 meses
   - Resultado: 200+ usuarios activos

### J.2. Cobertura Funcional

Nuestra propuesta cubre **100% de los requerimientos** especificados en el RFP:

| Módulo RFP | Cobertura | Características Adicionales |
|------------|-----------|----------------------------|
| Parametrización | ✅ 100% | Importación masiva, validaciones en tiempo real |
| Cotizaciones | ✅ 100% | Simulador de escenarios, versionado automático |
| Negociaciones | ✅ 100% | Calculadora de descuentos, renovación automática |
| Liquidación | ✅ 100% | Validaciones proactivas, provisiones automáticas |
| Notas Crédito | ✅ 100% | Integración directa SAP, validación de integridad |
| Repositorio Docs | ✅ 100% | Versionado, previsualización, búsqueda avanzada |
| Reportería | ✅ 100% | Dashboards interactivos, reportes programados |
| Seguimiento | ✅ 100% | Alertas configurables, notificaciones push |

**Características Avanzadas Incluidas**:
- Multi-tenancy para expansión regional futura
- API RESTful completa para integraciones
- Dashboards personalizables por rol
- Notificaciones en tiempo real
- Audit trail completo
- Exportación automática vía SFTP

### J.3. Capacidad de Soporte Local

#### Presencia en Colombia
- **Oficina**: Bogotá, Colombia
- **Equipo Local**: 15+ profesionales
- **Horario**: Zona horaria COT (GMT-5)
- **Idioma**: Español nativo

#### Ventajas del Soporte Local
- Reuniones presenciales cuando sea necesario
- Mismo huso horario para comunicación efectiva
- Conocimiento de normativas locales (INVIMA, regulaciones)
- Soporte en español sin barreras de idioma
- Respuesta rápida a incidentes

### J.4. Tiempo de Implementación

**Compromiso**: **6 meses** (24 semanas)

#### Factores que Garantizan el Cumplimiento

1. **Equipo Dedicado**: 8 profesionales 100% dedicados al proyecto
2. **Metodología Ágil**: Sprints quincenales con entregas incrementales
3. **Experiencia**: Proyectos similares completados en tiempos comparables
4. **Tecnología**: FilamentPHP reduce 60% el tiempo de desarrollo de interfaces
5. **Paralelización**: Desarrollo simultáneo de módulos independientes

#### Mitigación de Riesgos de Cronograma
- Buffer de 10% en estimaciones
- Equipo de respaldo disponible si es necesario
- Reuniones semanales de seguimiento
- Alertas tempranas de desviaciones

### J.5. Justificación del Presupuesto

#### Comparación con Alternativas

| Alternativa | Costo Estimado | Tiempo | Riesgos |
|-------------|----------------|--------|---------|
| **Nuestra Propuesta** | $180,980 | 6 meses | Bajo |
| Solución de Mercado | $200,000+ | 8-12 meses | Medio (customización limitada) |
| Desarrollo Offshore | $120,000 | 9-12 meses | Alto (comunicación, calidad) |
| Mantener Sistema Actual | $0 inicial | N/A | Crítico (seguridad, obsolescencia) |

#### Desglose de Valor

**Por cada $1,000 invertido, Novo Nordisk obtiene**:
- $800 en desarrollo de software de calidad
- $100 en infraestructura cloud dedicada
- $50 en capacitación y documentación
- $50 en gestión de proyecto y QA

#### ROI Esperado

**Beneficios Cuantificables**:
- Reducción de 40% en tiempo de procesamiento de cotizaciones
- Eliminación de 90% de errores manuales en liquidaciones
- Ahorro de 20 horas/semana en generación de reportes
- Reducción de 50% en tiempo de cierre mensual

**Beneficios Intangibles**:
- Mejora en satisfacción de clientes
- Reducción de riesgos de seguridad
- Cumplimiento normativo garantizado
- Base sólida para crecimiento futuro

---

## K. GARANTÍAS Y COMPROMISOS

### K.1. Garantías Técnicas

1. **Garantía de Funcionalidad**: 12 meses
   - Corrección de bugs sin costo adicional
   - Funcionalidad según especificaciones acordadas

2. **Garantía de Rendimiento**
   - Tiempo de respuesta < 200ms para operaciones comunes
   - Disponibilidad 99.5% (excluye mantenimientos programados)
   - Soporte para 500 usuarios concurrentes

3. **Garantía de Seguridad**
   - Cumplimiento de estándares Novo Nordisk
   - Parches de seguridad aplicados en < 48 horas
   - Auditorías de seguridad trimestrales

### K.2. Compromisos de Servicio

1. **Cumplimiento de Cronograma**
   - Penalización del 2% por cada semana de retraso (máx 10%)
   - Bonificación del 5% por entrega anticipada

2. **Calidad de Código**
   - Cobertura de tests > 80%
   - Code review obligatorio
   - Documentación completa

3. **Transferencia de Conocimiento**
   - Código fuente completo
   - Documentación técnica exhaustiva
   - Capacitación incluida

### K.3. Propiedad Intelectual

- **Código Fuente**: Propiedad 100% de Novo Nordisk
- **Documentación**: Propiedad de Novo Nordisk
- **Licencias**: Open source (Laravel, Filament) sin costos recurrentes
- **Datos**: 100% propiedad y control de Novo Nordisk

---

## L. GESTIÓN DE RIESGOS

### L.1. Riesgos Identificados y Mitigación

| Riesgo | Probabilidad | Impacto | Mitigación |
|--------|--------------|---------|------------|
| **Retrasos en cronograma** | Media | Alto | Equipo dedicado, buffer en estimaciones, metodología ágil |
| **Cambios en requerimientos** | Alta | Medio | Sprints cortos, validación continua, change management |
| **Problemas de integración SAP** | Media | Alto | Pruebas tempranas, ambiente de sandbox SAP, experto dedicado |
| **Resistencia al cambio** | Media | Medio | Capacitación intensiva, change management, soporte extendido |
| **Problemas de rendimiento** | Baja | Alto | Testing de carga, optimizaciones, infraestructura escalable |
| **Pérdida de datos en migración** | Baja | Crítico | Backups múltiples, validación exhaustiva, rollback plan |

### L.2. Plan de Contingencia

1. **Equipo de Respaldo**: Profesionales adicionales disponibles si es necesario
2. **Rollback Plan**: Capacidad de revertir a sistema anterior en caso de falla crítica
3. **Soporte 24/7**: Durante go-live y primeras 2 semanas
4. **Ambiente de Pruebas**: Permanente para validaciones sin afectar producción

---

## M. RESUMEN EJECUTIVO - LO MÁS IMPORTANTE

### M.1. ¿Qué Les Estamos Proponiendo?

Desarrollar un sistema completamente nuevo, moderno y seguro para gestionar todos sus procesos comerciales, reemplazando la plataforma actual que tiene 9 años y riesgos críticos de seguridad.

### M.2. ¿Por Qué Necesitan Este Cambio?

**Riesgos del Sistema Actual:**
- ❌ Sin actualizaciones de seguridad desde 2022 (vulnerable a ataques)
- ❌ Tecnología obsoleta que nadie mantiene
- ❌ Riesgo de pérdida de datos o hackeo
- ❌ Imposible agregar nuevas funcionalidades

**Beneficios del Sistema Nuevo:**
- ✅ Seguridad garantizada hasta 2027
- ✅ 30-40% más rápido que el actual
- ✅ Interfaz moderna y fácil de usar
- ✅ Integración automática con SAP
- ✅ Preparado para crecer con el negocio

### M.3. ¿Qué Obtienen Exactamente?

**8 Módulos Completos:**
1. ✅ Gestión de Clientes, Productos y Precios
2. ✅ Cotizaciones con Aprobaciones Automáticas
3. ✅ Negociaciones con Simulador de Escenarios
4. ✅ Liquidación Automática (ahorra 20+ horas/mes)
5. ✅ Generación de Notas Crédito para SAP
6. ✅ Repositorio de Documentos Comerciales
7. ✅ Reportes y Dashboards Ejecutivos
8. ✅ Alertas y Seguimiento de Vencimientos

**Características Especiales:**
- Inicio de sesión con credenciales corporativas (SSO)
- Permisos detallados por usuario
- Registro completo de auditoría (quién hizo qué)
- Respaldos automáticos diarios
- Acceso desde cualquier lugar 24/7

### M.4. ¿Cuánto Tiempo Toma?

**6 meses (24 semanas)** con entregas cada semana

- **Mes 1**: Sistema de usuarios y seguridad
- **Mes 2**: Clientes, productos y precios
- **Mes 3**: Cotizaciones y negociaciones
- **Mes 4**: Liquidación y notas crédito
- **Mes 5**: Reportes y documentos
- **Mes 6**: Pruebas, capacitación y lanzamiento

**Cada semana verán progreso real** - no tienen que esperar 6 meses para ver resultados.

### M.5. ¿Cuánto Cuesta?

#### Inversión Inicial (Una Sola Vez)
**$120.120.000 COP** - Incluye desarrollo completo, capacitación y migración de datos

#### Costos Mensuales (Después del Desarrollo)
**$13.293.000 COP/mes** - Incluye servidores en la nube + soporte técnico completo

#### Total Año 1
**$279.636.000 COP** - Desarrollo + primer año de operación

#### Años Siguientes
**$159.516.000 COP/año** - Solo operación y soporte

**Descuentos Disponibles:**
- 5% si pagan todo al inicio (ahorro de $6.006.000)
- 10% en costos recurrentes con contrato de 3 años (ahorro de $47.854.800)

### M.6. ¿Por Qué Elegirnos?

1. **Experiencia Comprobada**
   - Conocemos su sistema actual (Laravel 6)
   - Hemos hecho proyectos similares en farmacéuticas
   - Equipo de 8 profesionales dedicados

2. **Entregas Semanales**
   - Ven resultados cada semana
   - Pueden probar y dar feedback constantemente
   - Menor riesgo para ustedes

3. **Soporte Local en Colombia**
   - Oficina en Bogotá
   - Mismo huso horario
   - Reuniones presenciales cuando sea necesario

4. **Tecnología Moderna**
   - Laravel 12 (soporte hasta 2027)
   - FilamentPHP (interfaces modernas)
   - 60% más rápido de desarrollar

5. **Garantías Sólidas**
   - 12 meses de soporte incluido
   - Corrección de errores sin costo
   - Código fuente de su propiedad

### M.7. Comparación Rápida

| Aspecto | Sistema Actual | Sistema Propuesto |
|---------|----------------|-------------------|
| **Seguridad** | ❌ Vulnerable | ✅ Garantizada hasta 2027 |
| **Velocidad** | 🐌 Lento | ⚡ 30-40% más rápido |
| **Interfaz** | 😕 Anticuada | 😊 Moderna y fácil |
| **Integración SAP** | 🔧 Manual | 🤖 Automática |
| **Soporte** | ❌ Ninguno | ✅ 12 meses incluido |
| **Costo Anual** | $0 pero ALTO riesgo | $159.516.000 con seguridad |

### M.8. Retorno de Inversión (ROI)

**Ahorros Cuantificables:**
- ⏱️ 20 horas/semana en procesos manuales = $218.400.000/año
- 🐛 90% menos errores en liquidaciones = $63.000.000/año
- 📊 Reportes automáticos vs manuales = $33.600.000/año
- **Total ahorros anuales: ~$315.000.000**

**Recuperación de inversión: ~11 meses**

**Beneficios No Cuantificables:**
- Eliminación de riesgo de hackeo
- Cumplimiento normativo garantizado
- Mejor experiencia para el equipo comercial
- Capacidad de crecer sin limitaciones técnicas

### M.9. Próximos Pasos

**1. Presentación (Semana del 21-nov)**
- Les mostramos el sistema FilamentPHP en vivo
- Respondemos todas sus preguntas
- Aclaramos cualquier duda técnica o de costos

**2. Ajustes (Semana del 26-nov)**
- Incorporamos su feedback
- Ajustamos cronograma si es necesario
- Refinamos presupuesto

**3. Inicio (Primera semana de diciembre)**
- Firmamos contrato
- Arrancamos el proyecto
- Primera entrega en la Semana 1

### M.10. Nuestra Promesa

**Nos comprometemos a:**
- ✅ Entregar el sistema completo en 6 meses
- ✅ Mostrarles avances cada semana
- ✅ Cumplir 100% de los requerimientos del RFP
- ✅ Capacitar a todo su equipo
- ✅ Dar soporte por 12 meses incluido
- ✅ Transferir todo el código fuente a Novo Nordisk

**Si no cumplimos el cronograma:**
- Penalización del 2% por cada semana de retraso

**Si entregamos antes:**
- Bonificación del 5% de descuento

---

## ¿Tienen Preguntas?

Estamos disponibles para aclarar cualquier duda sobre:
- Aspectos técnicos del sistema
- Cronograma y entregas
- Costos e inversión
- Proceso de implementación
- Capacitación y soporte

**Contáctenos:**
- 📧 Email: [email@proveedor.com]
- 📱 Teléfono: [+57 XXX XXX XXXX]
- 📍 Oficina: Bogotá, Colombia

---

## N. ANEXOS

### Anexo A: Equipo Propuesto - CVs Resumidos

*(Disponibles CVs completos bajo solicitud)*

### Anexo B: Casos de Éxito

*(Referencias disponibles bajo NDA)*

### Anexo C: Arquitectura Técnica Detallada

*(Diagramas técnicos disponibles en presentación)*

### Anexo D: Plan de Testing

*(Documento detallado de estrategia de QA)*

### Anexo E: Términos y Condiciones

*(Contrato marco disponible para revisión legal)*

---

## CONTACTO

**Nombre de la Empresa**: [Nombre del Proveedor]
**Representante Legal**: [Nombre]
**Email**: [email@proveedor.com]
**Teléfono**: [+57 XXX XXX XXXX]
**Dirección**: [Dirección en Bogotá, Colombia]

---

**Fecha de Presentación**: 14 de Noviembre de 2024
**Validez de la Oferta**: 60 días calendario
**Firma Autorizada**: _______________________

---

*Este documento es confidencial y de uso exclusivo para Novo Nordisk Colombia en el marco del proceso de licitación RFP-2024-Gestión-Comercial.*


