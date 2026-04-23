RFP para la Implementación de una Solución Digital para la Gestión de Procesos Comerciales en Novo Nordisk Colombia

1. CONTEXTO ACTUAL DE LA NECESIDAD
   1.1. Antecedentes

Novo Nordisk es una compañía global de cuidado de la salud consolidada en 1923 y con sede central en Dinamarca. Nuestro propósito es generar un cambio significativo para combatir la diabetes y otras enfermedades crónicas graves como la obesidad, enfermedades raras endocrinas y hematológicas. En Colombia, nuestra operación se centra exclusivamente en la importación y comercialización de productos, garantizando el acceso a terapias innovadoras.

1.2. Necesidad del área comercial

En la actualidad, nuestra área comercial cuenta con una plataforma para la gestión de operaciones comerciales, especialmente relacionada con la parametrización de las variables incluidas en la operación comercial, que dan como resultado la elaboración de cotizaciones y negociaciones con clientes y la respectiva liquidación de acuerdos comerciales vía Notas Crédito. La solución existente fue desarrollada en 2015 como un desarrollo local customizado. Si bien ha permitido la continuidad de los procesos hasta la fecha, presenta limitaciones significativas en términos de escalabilidad y mantenimiento tecnológico (lenguaje de programación obsoleto). Debido a esto, se ha decidido realizar una licitación para implementar una nueva solución que modernice la gestión de procesos comerciales, permitiendo superar las limitaciones actuales mediante una herramienta más escalable, eficiente y compatible con las necesidades del negocio.

1.3. Objetivo estratégico

Implementar una solución digital para centralizar y optimizar la gestión de los procesos comerciales de la filial, garantizando eficiencia, trazabilidad y alineación con la normativa aplicable.

1.4. Tipo de solución

Para referencia de los proveedores participantes en este RFP, la solución digital podrá plantearse bajo dos modalidades:

A. Adopción de una herramienta existente en el mercado

El proveedor podrá proponer una solución ya disponible, siempre que cumpla con los requerimientos funcionales y regulatorios descritos en este documento.

B. Desarrollo a la medida/Customizado

En este caso, el proveedor deberá presentar la propuesta integral basada en la tecnología y arquitectura que el proveedor considere adecuada.

2. NECESIDAD / REQUERIMIENTOS DEL SERVICIO A CONTRATAR
   2.1. Requerimientos De Negocio

El sistema propuesto para la gestión comercial de Novo Nordisk debe cubrir integralmente la parametrización, controles, validaciones, trazabilidad para llevar a cabo el ciclo comercial el cual contempla la elaboración de cotización, negociación y liquidación de descuentos y condiciones comerciales. Entre sus funcionalidades principales se destacan:

Imagen 1. Etapas del proceso comercial

2.2. Parametrización

Hace referencia a las funcionalidades a contemplar dentro del esquema comercial de la compañía y contempla entre otros aspectos la creación y administración de clientes y sus condiciones, creación de productos, manejo de listas de precios según segmentos y todas las variables comerciales definidas en la política comercial de la compañía para el desarrollo de la actividad comercial.

2.3. Módulo de cotizaciones

Este módulo permitiría crear las cotizaciones específicas por cliente, generando el precio unitario de facturación inicial, además de incluir términos y condiciones generales como las condiciones de pago. La cotización establece la base para cualquier negociación posterior y es el documento principal que guía las interacciones comerciales iniciales con el cliente.

Principales funcionalidades:

Generación de cotizaciones con precios de lista o precios base previamente aprobados para cada cliente, según condiciones establecidas en la política comercial.

Flujos de Aprobación configurables: Validación automática para asegurar que la cotización se mantenga dentro de los límites comerciales autorizados. En los casos donde haya excepciones (como descuentos mayores al rango permitido), el sistema activará flujos de aprobación adicionales.

Trazabilidad y Versionado: Cada actualización o cambio en una cotización se registra automáticamente con los detalles del usuario que realiza la modificación, garantizando un historial completo de todas las acciones.

2.4. Módulo de negociaciones

Creación de las negociaciones vinculadas a cotizaciones aprobadas, gestionando descuentos personalizados, acuerdos por volumen y otras condiciones comerciales específicas para el cliente.

Principales funcionalidades:

Validación de Descuentos: Verifica que los descuentos aplicados respeten los límites de la política comercial, tomando en cuenta descuentos tarifarios (que necesitan aprobación) y descuentos financieros preaprobados.

Manejo de Excepciones: En escenarios de negociaciones fuera del rango permitido (precio entre precio lista y precio neto mínimo), el sistema solicitará aprobaciones adicionales con justificaciones documentadas.

Sincronización con Cotizaciones: Toda negociación será generada a partir de una cotización aprobada, permitiendo que los cambios negociados se reflejen en las siguientes etapas del proceso comercial.

Generación de documento final: De acuerdo con el marco descrito previamente, el resultado debería ser la generación del documento de negociación consolidado con las condiciones comerciales aplicables y el clausulado correspondiente.

2.5. Módulo de Liquidación

Este módulo automatiza la liquidación mensual de los descuentos que se deben aplicar a cada cliente según las condiciones negociadas. Este proceso lleva lugar en dos cortes a final de mes. Este proceso implica recibir archivos de ventas desde SAP, realizar una depuración y validar los totales contra reportes de diferentes áreas para asegurar la calidad de la información. Una vez depurada la venta, se cruza cada transacción con las condiciones negociadas (precios, descuentos, escalas, etc.) y genera automáticamente las notas de crédito correspondientes. Ciertas negociaciones con condiciones especiales requieren liquidaciones flexibles por lo que la herramienta debe permitir cargar información complementaria (como consumos, crecimiento de ventas o condiciones de cartera) mediante plantillas estandarizadas.

El resultado debe ser un archivo plano listo para cargar en SAP y generar automáticamente notas de crédito agrupadas por concepto de negociación y por cliente (formato a consolidar según los requisitos de Novo Nordisk).

Adicionalmente, el sistema debe estar en capacidad de realizar las provisiones correspondientes a negociaciones que se encuentran aprobadas pero que son sujetas a la entrega de información por parte de un cliente para su respectiva liquidación final.

Principales funcionalidades:

Validación Proactiva y control: Realiza validaciones automáticas para asegurar que no existan inconsistencias (ejemplo: valores negativos, exclusión de registros duplicados, etc.).
Identifica errores en datos clave (ejemplo: cliente sin una negociación válida, ventas anuladas o valores fuera de rango) antes de generar las notas crédito.

Notificaciones proactivas en caso de que falte información necesaria para completar la liquidación.

Cálculo Mensual de Descuentos: Procesa ventas y consumos por cliente y producto, aplicando descuentos previamente configurados (por ejemplo, en función de volúmenes alcanzados o acuerdos específicos de escala).

Consolidación de Datos: Agrupa todos los descuentos aplicados por cliente y concepto en plantillas predefinidas.

Agrupación por Conceptos Comerciales: Permite distinguir entre descuentos financieros y tarifarios en cada liquidación, asegurando que se respete la estructura negociada.

Auditorías Automatizadas y Manuales: Genera reportes para revisión manual (en casos selectos) o auditorías automáticas sobre la correcta aplicación de descuentos y liquidaciones previas.

2.6. Módulo de Generación de Notas Crédito

El último módulo convierte las liquidaciones aprobadas en archivos planos con una plantilla predefinida de nota crédito listas para su carga en el ERP SAP.

Principales funcionalidades:

Producción Automatizada de Archivos TXTs: Genera un archivo por cada cliente y concepto, siguiendo los estándares definidos por SAP y cumpliendo con las normativas locales.

Registro Estructurado: Detalla cada descuento aplicado, relacionándolo con la venta o consumo especificado, para un fácil seguimiento en auditorías futuras.

Integración con SAP: Sincronización directa para garantizar que las notas crédito se procesen correctamente en el sistema financiero principal de Novo Nordisk.

Formatos Flexibles: Configuración de plantillas personalizables según necesidades internas y requerimientos de los clientes.

2.7. Módulo de repositorio de documentos

Adicional a los módulos descritos para soportar el proceso comercial, se propone que la herramienta cuente con un módulo repositorio de documentos que centralice y almacene documentos comerciales clave y públicos de Novo Nordisk (certificados, documentos técnicos de productos, registros, políticas, cartas y soportes de negociación), permitiendo acceso rápido y seguro 24/7 para usuarios autorizados de la herramienta.

2.8. Módulo de reportería

El módulo de reportería permite crear y consultar reportes personalizados sobre clientes, productos, condiciones comerciales, cotizaciones, negociaciones, notas crédito y provisiones. Incluye filtros por periodo, Key Account manager asociado, cliente, ciudad, departamento, segmento, tipo de negociación y otros criterios relevantes, facilitando la toma de decisiones y el cumplimiento de políticas comerciales.

Opción para exportar archivos planos con toda la información histórica (sábana de datos) de todos los módulos, de forma periódica, para su transferencia vía SFTP.

2.9. Módulo de Seguimiento y Control de Negociaciones y Cotizaciones

Módulo centralizado para el seguimiento integral de cotizaciones y negociaciones, que permite visualizar de forma clara las vigencias, el estado actualizado de cada proceso y recibir alertas automáticas de vencimiento. Incluye filtros avanzados por cliente, tipo de negociación, estado y periodo de vigencia, así como notificaciones configurables para anticipar vencimientos próximos y facilitar la gestión proactiva de renovaciones y extensiones.

2.10. Configuraciones Maestras

Para asegurar la flexibilidad y la alineación del sistema con las necesidades comerciales, se exponen a continuación las configuraciones maestras y setups:

Clientes y Segmentación: Administración de los perfiles de clientes, categorizándolos según criterios internos de Novo Nordisk.

Gestión de descuentos según la Política Comercial: Configuración avanzada de límites de descuentos por cliente, canal y concepto, con validación automática de las excepciones aplicadas.

Productos y Precios: Mantenimiento de productos, líneas de producto y listas de precios (precios lista, precios netos y precios regulados).

Tres tipos de precios son definidos para cada producto dentro del esquema comercial. Entre el precio de lista y el precio de regulación se define el rango de precios o margen de negociación, cuya validez se determina mediante un flujo de aprobación dependiendo del tipo de descuento aplicado. En caso de haber un descuento tarifario, este también se somete a un flujo de aprobación. Todos estos casos deben ser consideradas definiciones condicionales en la herramienta a implementar, considerando el Audit Trail aplicable que documente el flujo de aprobación para cada proceso, así como los documentos anexos que hagan parte de esta aprobación.

Imagen 2. Descripción gráfica de la relación entre los precios netos, de lista y de regulación del proceso comercial

Roles y Permisos: Administración de roles diferenciados, asegurando que cada usuario solo pueda realizar acciones dentro de sus responsabilidades asignadas.

Formatos de Documentos: Plantillas para cotizaciones, negociaciones, liquidaciones y notas crédito según los requerimientos de Novo Nordisk.

Gestión de cambios masivos: El sistema debe permitir actualizar masivamente precios regulados (por ejemplo, ante cambios normativos gubernamentales), alertando al equipo comercial sobre las cotizaciones afectadas y generando recordatorios para su actualización o renegociación.

Reportes y trazabilidad: Se debe permitir la generación de reportes personalizados de las diferentes funcionalidades de la herramienta (clientes, cotizaciones, negociaciones, etc).

2.11. Tipos de negociaciones y niveles de flujo de aprobación

Existen varios tipos de negociaciones: descuentos asociados a venta directa, descuentos por rotación (cuando el cliente revende o dispensa a terceros), negociaciones especiales (como acuerdos de cartera o crecimiento), y convenios con condiciones específicas para ciertos canales o clientes.

Los niveles de flujo de aprobación dependen del porcentaje de descuento solicitado: hay diferentes niveles (por ejemplo, nivel 2, 3 o 4) según el monto o porcentaje de descuento, y cada nivel requiere la aprobación correspondiente antes de generar la cotización.

Si se solicita un cambio fuera del rango permitido (por ejemplo, por debajo del precio mínimo o por encima del precio de lista siempre y cuando no exceda el máximo regulado), se requiere una justificación y una aprobación especial, quedando todo documentado para fines de auditoría.

2.12. Generación de reportes

Reportes exportables configurables para análisis comercial y cumplimiento regulatorio.

2.13. Trazabilidad y Audit trail

El sistema debe garantizar la trazabilidad completa de todas las acciones realizadas, con reportes específicos para cumplir con requisitos internos y externos:

Generar un audit trail completo que registre:

Quién realizó cada acción (cotización, negociación, liquidación o emisión de nota crédito).

Cambios realizados a precios, descuentos y condiciones comerciales.

Detalles de las aprobaciones otorgadas en cada nivel del flujo.

2.14. Descripción a alto nivel del proceso de gestión comercial de Novo Nordisk

El proceso inicia con la definición y aprobación de precios, estableciendo límites claros: precio de lista, precio neto y precio de regulación. Estos controles aseguran que las cotizaciones y negociaciones se mantengan dentro de los rangos permitidos, evitando ventas por encima del precio regulado o por debajo del mínimo autorizado.

A partir de la aprobación de precios, se genera la cotización, que incluye un flujo de aprobación y la obligación de justificar cualquier excepción o modificación respecto al precio preaprobado. El sistema debe garantizar trazabilidad, documentación de soportes y comentarios, y la generación automática del documento de cotización una vez cumplidos los controles.

Luego, se avanza al módulo de negociaciones, donde cada negociación está vinculada a una cotización vigente. Las negociaciones pueden ser simples o complejas, permitiendo simular diferentes escenarios de descuentos y condiciones comerciales por cliente. Si una negociación requiere condiciones fuera del rango permitido, el sistema debe exigir aprobaciones adicionales y justificaciones documentadas.

La configuración de descuentos está alineada con la Política Comercial de Novo Nordisk, de modo que el sistema controle que los descuentos aplicados a cada cliente respeten los límites definidos y permitan personalización según el rango autorizado.

En la etapa de liquidación, el sistema toma la información de ventas, consumos y variables de descuentos para calcular mensualmente, por producto y cliente, los montos a liquidar. El resultado es un archivo plano que se integra a SAP para la generación automática de notas de crédito, eliminando procesos manuales y asegurando la trazabilidad.

Finalmente, el proceso contempla la capacidad de realizar cambios masivos en los precios de regulación, por ejemplo, ante una circular gubernamental. El sistema debe alertar al equipo comercial sobre todas las cotizaciones afectadas, indicando cuáles requieren actualización y generando recordatorios para asegurar el cumplimiento en los plazos establecidos.

Imagen 3. Proceso comercial para la generación de cotización

Imagen 4. Proceso comercial para la generación de negociación

Imagen 5. Proceso de liquidación y generación de archivo plano nota crédito

2.15. Requerimientos Técnicos
Seguridad y Autenticación

Integración con SSO como parte de un requerimiento estándar de seguridad de Novo Nordisk.

Roles y permisos ajustables, segregación de funciones y cumplimiento de los estándares en desarrollos customizados de TI de Novo Nordisk (IT Risk Assessment).

Escalabilidad y Compatibilidad Tecnológica

Configuración flexible para expansión del portafolio y nuevos clientes.

Posibles estándares de integración con SAP.

Infraestructura en la Nube

La propuesta deberá incluir la infraestructura (servidores, hosting, bases de datos) que se consideren pertinentes, que deberá ser gestionada por el proveedor como parte del mantenimiento y soporte. La infraestructura debe ser Cloud y dedicada exclusivamente a Novo Nordisk, lo que significa que estará aislada de otros clientes del proveedor. Esto garantiza un mejor rendimiento, mayor seguridad y cumplimiento de las normativas aplicables a los datos manejados.

2.16. Consideraciones de tiempos y coberturas por parte del servicio
Plazo de Implementación

El proyecto debe completarse en un período no mayor a 6 meses, con entregables intermedios diseñados para garantizar avance continuo, revisiones periódicas y adaptabilidad. Se espera que el proveedor proponga entregas semanales o quincenales que reflejen hitos claros en el desarrollo, configuración e implementación de la solución.

NOTA: El plazo de implementación será considerado un criterio clave de calificación para la evaluación de las propuestas, por lo que se insta a los proveedores a detallar minuciosamente su plan de trabajo, los tiempos proyectados para cada etapa del proyecto y los recursos asignados para garantizar el cumplimiento del cronograma propuesto. El incumplimiento del plazo establecido será evaluado críticamente durante el proceso de selección.

Plan de Soporte Técnico

Debe incluir un soporte técnico robusto por un mínimo de 12 meses post-implementación, con un SLA claro que estipule tiempos de respuesta para incidentes.

3. PROPUESTA TECNICA/ECONOMICA
   3.1. Estructura de la Propuesta

El proveedor deberá presentar su propuesta siguiendo la siguiente estructura mínima.

A. Resumen ejecutivo

Breve descripción de la solución propuesta

B. Modalidad de propuesta de solución

El proveedor deberá indicar con claridad bajo cuál de las siguientes modalidades participa:

Modalidad A – Desarrollo completo desde cero

Propuesta de construcción total sin depender del código base entregado. Se deberá detallar la arquitectura sugerida, lenguajes, frameworks y bases de datos, junto con la justificación técnica de estas elecciones.

El proveedor deberá detallar:

Descripción funcional de la solución y alcance de la cobertura para los procesos de negocio descritos en el inciso 2.1.

Especificación técnica (arquitectura, integraciones, seguridad, cumplimiento regulatorio).

Plan de implementación (incluyendo fases de análisis, construcción, pruebas, validación regulatoria y despliegue, tiempos, metodología, equipo).

Propuesta económica (costos de desarrollo, mantenimiento, servicios de infraestructura en la nube, soporte, propiedad intelectual).

Modalidad B – Solución de mercado existente

El proveedor deberá presentar:

Descripción funcional de la solución y alcance de la cobertura para los procesos de negocio descritos en el inciso 2.1.

Descripción técnica de la plataforma (arquitectura, módulos, integraciones).

Plan de implementación.

Casos de referencia en la industria farmacéutica (si aplica, preferiblemente en Colombia o LATAM).

Modelo de licenciamiento y política de soporte.

Propuesta económica (licencias, implementación, soporte, costos recurrentes).

C. Integraciones requeridas

En ambas modalidades, el proveedor deberá describir el nivel de integración que tendrá la solución digital con SAP S/4HANA.

D. Plan de soporte

Modelo de soporte post-implementación.

Niveles de servicio (SLA).

E. Plan de capacitación

El proveedor deberá incluir un plan de capacitación que cumpla como mínimo con lo siguiente:

Audiencias mínimas a capacitar: Equipo de usuarios finales (área comercial), responsables de soporte de primer nivel, y equipo técnico interno (si aplica, para integraciones).

Contenido mínimo: Uso de la solución para generación y gestión de cotizaciones, gestión de precios y descuentos complejos, flujos de aprobación y trazabilidad, reportes y consultas básicas.

Metodología mínima: Al menos 1 sesión presencial o virtual sincrónica por audiencia.

Entrega de manual de usuario y guía rápida en formato digital.

Sesión de refuerzo posterior al arranque productivo.

Disponibilidad de un canal de consultas durante al menos 1 mes posterior al arranque.

F. Propuesta económica

El proveedor deberá presentar el presupuesto detallado y desglosado por los siguientes componentes, para garantizar transparencia y flexibilidad al considerar diferentes escenarios tecnológicos. Para cada modalidad presentada (A o B), detallar:

Costos iniciales (licencias, desarrollo de la solución, implementación técnica).

Costos recurrentes desglosados (soporte técnico, mantenimiento, hosting y servicios de infraestructura en la nube).

4. CRITERIOS DE EVALUACIÓN

Las propuestas serán evaluadas con base en los siguientes criterios, asegurando que cada uno se analice de manera rigurosa:

Experiencia previa y capacidad del equipo propuesto.

Cobertura funcional (pricing avanzado, cotizaciones, flujos de aprobación, trazabilidad).

Capacidad de soporte local.

Tiempo de implementación.

Justificación del presupuesto presentado, junto con los costos totales y flexibilidad del modelo de licenciamiento.

5. CRONOGRAMA DE ACTIVIDADES
   No.	Actividad	Responsable	Deadline
   1	Lanzamiento de sourcing event COUPA	Procurement NNCO	28-oct
   2	Envío Q&A bajo formato enviado	Proveedor	30-oct
   3	Respuesta Q&A	NNCO	31-oct
   4	Envío de propuestas	Proveedor	14-nov
   5	Análisis de ofertas	NNCO	19-nov
   6	Sustentación propuesta	Proveedor	21-nov
   7	Revisión + Ajustes	Proveedor + NNCO	26-nov
   8	Notificaciones	Procurement NNCO	05-dic

Si quieres, puedo generarte también una versión con índice automático, o organizarlo por secciones para Obsidian (con links internos).