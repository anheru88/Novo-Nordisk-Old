# PREGUNTAS FRECUENTES (FAQ)
## Propuesta Laravel 12 + FilamentPHP - Novo Nordisk Colombia

---

## 📋 SOBRE EL PROYECTO

### ¿Por qué necesitamos cambiar el sistema actual?

El sistema actual (Laravel 6, PHP 7.2) fue desarrollado en 2015 y tiene **riesgos críticos**:
- Sin actualizaciones de seguridad desde septiembre 2022
- Vulnerable a ataques cibernéticos
- Imposible agregar nuevas funcionalidades
- 30-40% más lento que tecnologías modernas
- Ningún proveedor ofrece soporte para estas versiones obsoletas

**Riesgo real**: Un hackeo podría exponer datos confidenciales de clientes y negociaciones, con multas regulatorias y daño reputacional.

---

### ¿Por qué no simplemente actualizar el sistema actual?

Actualizar de Laravel 6 a Laravel 12 requiere reescribir casi el 80% del código debido a:
- Cambios incompatibles entre versiones
- Librerías obsoletas que ya no existen
- Arquitectura antigua que no soporta nuevas funcionalidades

**Costo de actualizar vs desarrollar nuevo**: Similar en tiempo y dinero, pero desarrollar nuevo da:
- ✅ Código limpio y moderno
- ✅ Mejor arquitectura
- ✅ Oportunidad de mejorar procesos
- ✅ Sin "deuda técnica" heredada

---

### ¿Qué pasa con el sistema actual durante el desarrollo?

El sistema actual **sigue funcionando normalmente** durante los 6 meses de desarrollo. Solo en la Semana 24 (go-live) hacemos el cambio al nuevo sistema, idealmente un fin de semana para minimizar impacto.

---

## ⏱️ SOBRE EL CRONOGRAMA

### ¿Por qué 6 meses? ¿No se puede hacer más rápido?

6 meses es el tiempo óptimo para:
- Desarrollar 8 módulos completos con calidad
- Probar exhaustivamente cada funcionalidad
- Integrar correctamente con SAP
- Capacitar a todos los usuarios
- Migrar datos históricos de forma segura

**Hacerlo más rápido** aumentaría riesgos de errores y funcionalidades incompletas.

---

### ¿Qué pasa si se atrasan?

Tenemos **penalización del 2% por cada semana de retraso** sobre el costo total del proyecto.

**Ejemplo**: Si nos atrasamos 2 semanas, descontamos $5,720 USD del costo final.

---

### ¿Qué pasa si terminan antes?

Ofrecemos **bonificación del 5% de descuento** si entregamos antes del plazo.

**Ejemplo**: Si terminamos 2 semanas antes, descuentan $7,150 USD.

---

### ¿Qué significa "entregas semanales"?

Cada semana (viernes) tendremos una reunión de 1 hora donde:
1. Les mostramos lo que construimos esa semana
2. Pueden probarlo en el ambiente de pruebas
3. Dan feedback y sugerencias
4. Ajustamos según sus comentarios

**Beneficio**: No esperan 6 meses para ver resultados. Ven progreso real cada semana.

---

## 💰 SOBRE LOS COSTOS

### ¿Por qué $143,000 USD? ¿No es muy caro?

Desglose del costo:
- **8 profesionales** trabajando 6 meses = 48 meses-persona
- Costo promedio por profesional: ~$3,000/mes
- Total: $143,000 incluye desarrollo, capacitación, migración, documentación

**Comparación**:
- Contratar 8 personas por 6 meses: $144,000+
- Nuestra propuesta: $143,000 TODO incluido (infraestructura, herramientas, gestión)

---

### ¿Qué incluyen los $37,980/año recurrentes?

**Infraestructura AWS ($7,980/año)**:
- Servidores funcionando 24/7
- Base de datos con respaldos automáticos
- Almacenamiento de archivos
- Monitoreo y seguridad

**Soporte Técnico ($30,000/año)**:
- Atención a problemas e incidentes
- Corrección de errores sin costo extra
- Actualizaciones de seguridad
- Hasta 20 horas/mes para mejoras menores
- Monitoreo 24/7

**¿Es mucho?** Comparado con:
- Contratar 1 desarrollador full-time: $60,000+/año
- Nuestro servicio: $30,000/año con equipo completo disponible

---

### ¿Podemos reducir costos recurrentes?

**Opciones para reducir costos**:

1. **Infraestructura más pequeña**: $500-600/mes (vs $665/mes)
   - Riesgo: Más lento con muchos usuarios
   - Ahorro: ~$1,000/año

2. **Soporte básico**: $1,500/mes (vs $2,500/mes)
   - Incluye: Solo corrección de errores críticos
   - No incluye: Mejoras, optimizaciones, soporte proactivo
   - Ahorro: $12,000/año

3. **Autogestión técnica**: $0/mes
   - Requiere: Equipo técnico interno de Novo Nordisk
   - Riesgo: Sin soporte especializado
   - Ahorro: $30,000/año

**Recomendación**: Mantener soporte completo al menos el primer año.

---

### ¿Hay costos ocultos?

**NO**. La propuesta incluye TODO:
- ✅ Desarrollo completo
- ✅ Infraestructura inicial
- ✅ Capacitación
- ✅ Migración de datos
- ✅ Documentación
- ✅ 12 meses de soporte

**Únicos costos adicionales posibles**:
- Funcionalidades completamente nuevas no contempladas en el RFP
- Horas de desarrollo extra (más de 20 horas/mes incluidas)
- Crecimiento de infraestructura si usuarios aumentan significativamente

---

## 🔧 SOBRE LA TECNOLOGÍA

### ¿Por qué Laravel y no otra tecnología?

**Ventajas de Laravel**:
- ✅ Líder mundial en frameworks PHP (usado por millones)
- ✅ Comunidad enorme (fácil encontrar desarrolladores)
- ✅ Seguridad robusta incorporada
- ✅ Soporte hasta 2027 garantizado
- ✅ Su sistema actual ya es Laravel (conocemos el contexto)

**Alternativas consideradas**:
- .NET: Más caro, requiere licencias Microsoft
- Java: Más complejo, desarrollo más lento
- Node.js: Menos maduro para aplicaciones empresariales

---

### ¿Qué es FilamentPHP y por qué usarlo?

**FilamentPHP** es una herramienta especializada para crear paneles administrativos modernos.

**Ventajas**:
- ✅ Reduce 60% el tiempo de desarrollo de interfaces
- ✅ Interfaces modernas y responsivas (funcionan en móvil, tablet, PC)
- ✅ Componentes pre-construidos (tablas, formularios, gráficos)
- ✅ Fácil de personalizar según necesidades de Novo Nordisk

**Resultado**: Sistema más bonito, más rápido de desarrollar, más fácil de usar.

---

### ¿El sistema funcionará en celulares y tablets?

**SÍ**. FilamentPHP es **responsive**, significa que se adapta automáticamente a:
- 📱 Celulares (iOS y Android)
- 📱 Tablets
- 💻 Laptops
- 🖥️ Computadores de escritorio

**Ejemplo**: Un KAM puede consultar cotizaciones desde su celular mientras visita un cliente.

---

### ¿Qué pasa si Laravel 12 queda obsoleto en el futuro?

**Laravel 12 tiene soporte hasta 2027** (3 años desde ahora).

**Después de 2027**:
- Opción 1: Actualizar a Laravel 15 o 16 (proceso más simple que actualizar de 6 a 12)
- Opción 2: Mantener Laravel 12 con soporte extendido de la comunidad
- Opción 3: Migrar a otra tecnología (pero con 3+ años para planear)

**Ventaja**: Con código moderno y bien estructurado, futuras actualizaciones son mucho más fáciles.

---

## 🔐 SOBRE SEGURIDAD

### ¿Cómo garantizan la seguridad de nuestros datos?

**Múltiples capas de seguridad**:

1. **Autenticación**: SSO con Auth0 (credenciales corporativas)
2. **Autorización**: Permisos detallados por usuario
3. **Encriptación**: AES-256 para datos sensibles, HTTPS para comunicación
4. **Auditoría**: Registro de todas las acciones (quién, qué, cuándo)
5. **Respaldos**: Diarios, guardados por 30 días
6. **Firewall**: Solo accesos autorizados
7. **Monitoreo**: 24/7 para detectar actividad sospechosa

**Certificaciones**: Infraestructura AWS cumple con ISO 27001, SOC 2, PCI DSS.

---

### ¿Qué pasa si hay un hackeo?

**Prevención** (lo que hacemos para evitarlo):
- Actualizaciones de seguridad aplicadas inmediatamente
- Pruebas de penetración periódicas
- Monitoreo 24/7 de actividad sospechosa
- Firewall y protección DDoS

**Respuesta** (si llegara a pasar):
1. Detección inmediata (alertas automáticas)
2. Aislamiento del sistema afectado
3. Análisis forense para identificar la brecha
4. Restauración desde respaldo limpio
5. Parche de la vulnerabilidad
6. Reporte completo a Novo Nordisk

**Seguro**: Recomendamos contratar seguro de ciberseguridad (no incluido en propuesta).

---

### ¿Quién tiene acceso a nuestros datos?

**Acceso a datos de producción**:
- ❌ Equipo de desarrollo: NO (trabajan con datos de prueba)
- ✅ Administradores de Novo Nordisk: SÍ (control total)
- ✅ Equipo de soporte (solo con autorización): SÍ (para resolver incidentes)

**Controles**:
- Todo acceso queda registrado en auditoría
- Acceso de soporte requiere ticket aprobado
- Datos sensibles están encriptados (no se ven en texto plano)

---

## 🔗 SOBRE INTEGRACIÓN CON SAP

### ¿Cómo funciona la integración con SAP?

**Dos tipos de integración**:

1. **Importación de Ventas** (2 veces al mes):
   - SAP genera archivo de ventas
   - Sistema lo descarga automáticamente vía SFTP
   - Valida y procesa los datos
   - Listo para liquidar

2. **Exportación de Notas Crédito** (mensual):
   - Sistema genera archivos .txt con formato SAP
   - Los envía a SAP vía SFTP
   - SAP los procesa automáticamente

**Beneficio**: Cero digitación manual, cero errores de transcripción.

---

### ¿Qué pasa si SAP cambia su formato de archivos?

**Incluido en soporte**:
- Ajustes a formatos de archivos SAP sin costo extra
- Adaptación a cambios en APIs de SAP

**No incluido**:
- Integraciones completamente nuevas (se cotizarían aparte)

---

### ¿Necesitamos hacer algo en SAP?

**Mínimo**:
- Configurar usuario SFTP para transferencia de archivos
- Validar formatos de archivos de entrada/salida
- Pruebas de integración con ambiente de desarrollo SAP

**Coordinación**: Trabajaremos con su equipo de SAP para asegurar integración exitosa.

---

## 👥 SOBRE EL EQUIPO Y SOPORTE

### ¿Quiénes trabajarán en el proyecto?

**Equipo dedicado de 8 profesionales**:
- 1 Gerente de Proyecto (PM)
- 1 Arquitecto de Software
- 3 Desarrolladores Backend (Laravel)
- 2 Desarrolladores Frontend (FilamentPHP)
- 1 QA / Tester

**Disponibilidad**: 100% dedicados durante los 6 meses.

---

### ¿Qué pasa después del go-live?

**Primeros 30 días**:
- Soporte intensivo (respuesta en 1 hora para críticos)
- Monitoreo 24/7
- Ajustes según feedback de usuarios

**Después de 30 días**:
- Soporte según SLA (4 horas para críticos, 24 horas para normales)
- Mantenimiento preventivo mensual
- Hasta 20 horas/mes para mejoras incluidas

---

### ¿Qué pasa si el equipo cambia durante el proyecto?

**Garantía de continuidad**:
- Documentación completa de todo el código
- Repositorio Git con historial completo
- Sesiones de transferencia de conocimiento
- Mínimo 2 desarrolladores conocen cada módulo

**Si alguien sale**: Reemplazo en máximo 1 semana sin impacto en cronograma.

---

## 📚 SOBRE CAPACITACIÓN

### ¿Cómo será la capacitación?

**3 grupos diferentes**:

1. **Usuarios Finales** (KAMs, comerciales):
   - Cómo crear cotizaciones
   - Cómo consultar reportes
   - Cómo usar el sistema día a día
   - Duración: 4 horas

2. **Administradores** (gestores del sistema):
   - Cómo gestionar usuarios y permisos
   - Cómo configurar parámetros
   - Cómo generar reportes avanzados
   - Duración: 8 horas

3. **Equipo Técnico** (IT de Novo Nordisk):
   - Arquitectura del sistema
   - Cómo hacer respaldos
   - Cómo monitorear el sistema
   - Duración: 16 horas

**Materiales incluidos**:
- Manuales en PDF
- Videos tutoriales
- Guías de referencia rápida

---

### ¿Habrá capacitación de refuerzo?

**SÍ**. Incluimos:
- Sesiones de refuerzo 1 mes después del go-live
- Capacitación a nuevos usuarios (primeros 12 meses)
- Videos disponibles 24/7 para consulta

---

## 🚀 SOBRE LA PUESTA EN MARCHA

### ¿Cómo será el go-live?

**Plan de go-live** (Semana 24):

**Viernes noche**:
- 6:00 PM: Congelamiento del sistema actual
- 6:30 PM: Exportación de datos finales
- 7:00 PM: Migración de datos al nuevo sistema
- 9:00 PM: Validación de datos migrados
- 10:00 PM: Activación del nuevo sistema

**Sábado**:
- Pruebas finales con usuarios clave
- Ajustes si es necesario

**Lunes**:
- Sistema nuevo en producción
- Soporte intensivo todo el día

**Contingencia**: Si algo sale mal, podemos volver al sistema anterior en 2 horas.

---

### ¿Qué pasa con los datos históricos?

**Se migran todos**:
- Clientes y productos
- Cotizaciones históricas (últimos 2 años)
- Negociaciones vigentes
- Liquidaciones del año actual

**Validación**:
- Comparación automática de registros
- Validación manual de datos críticos
- Reporte de discrepancias

**Datos muy antiguos**: Se pueden exportar a Excel para consulta histórica.

---

## 📞 CONTACTO Y PRÓXIMOS PASOS

### ¿Cómo podemos hacer más preguntas?

**Canales disponibles**:
- 📧 Email: [email@proveedor.com]
- 📱 WhatsApp: [+57 XXX XXX XXXX]
- 📞 Teléfono: [+57 XXX XXX XXXX]
- 📅 Agendar reunión: [link a calendario]

**Tiempo de respuesta**: Máximo 24 horas hábiles.

---

### ¿Cuándo podemos ver una demostración?

**Demostración de FilamentPHP**:
- Fecha propuesta: Semana del 21-nov
- Duración: 1 hora
- Formato: Presencial o virtual
- Incluye: Demo en vivo, Q&A

**Agendar**: Contáctenos para coordinar fecha y hora.

---

**¿Tienen más preguntas?**

Este documento se actualiza continuamente. Si tienen una pregunta que no está aquí, con gusto la respondemos y la agregamos al FAQ.

---

**Última actualización**: Noviembre 2024  
**Versión**: 1.0

