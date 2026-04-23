# ANÁLISIS DEL SISTEMA ACTUAL - MÓDULOS ADICIONALES

**Proyecto:** Migración Plataforma Comercial Novo Nordisk Colombia  
**Fecha:** Noviembre 2025  
**Versión:** 1.0

---

## 📋 RESUMEN EJECUTIVO

Este documento complementa la propuesta principal identificando **módulos adicionales** que existen en el sistema actual (Laravel 6) pero que **NO están mencionados en el RFP**. Estos módulos son críticos para el funcionamiento operativo y deben ser migrados.

### Hallazgos Principales

✅ **11 módulos totales** (8 del RFP + 3 adicionales)  
✅ **Sistema complejo de roles y permisos** con niveles de autorización  
✅ **Integración crítica con NovoUsers** (SSO corporativo)  
✅ **Módulo ARP** (Annual Revenue Planning) no documentado en RFP  
✅ **Sistema de ventas y liquidación** más complejo que lo especificado

---

## 🔍 MÓDULOS ADICIONALES IDENTIFICADOS

### **MÓDULO 9: ARP (Annual Revenue Planning) - Simulador de Ingresos**

**Estado:** ❌ NO mencionado en el RFP pero **CRÍTICO** en sistema actual

**¿Qué hace?**  
Sistema de simulación y planificación de ingresos anuales por marca, producto y cliente. Permite importar datos de forecast y generar escenarios de negocio.

**Modelos Identificados:**
- `Arp` - Configuración anual de ARP
- `ServiceArp` - Servicios asociados a ARP
- `ArpService` - Datos de volumen y valor por marca
- `BusinessArp` - Business case por marca (PBC)
- `ArpSimulations` - Simulaciones creadas
- `ArpSimulationDetail` - Detalles de cada simulación

**Funcionalidades Actuales:**
- ✅ Importación de archivos Excel con forecast
- ✅ Simulación por marca (Diabetes, Biofarmacéuticos, Obesidad)
- ✅ Cálculo de volúmenes y valores en COP y DKK
- ✅ Generación de reportes Excel multi-hoja por marca
- ✅ Integración con precios negociados
- ✅ Cálculo de descuentos por escalas
- ✅ Business case (PBC) por marca

**Campos Clave:**
```
ArpSimulationDetail:
- brand_id, product_id, client_id
- cal_year_month (año-mes)
- vol_type (tipo de volumen)
- forecast_vol (volumen forecast)
- sales_pack_vol (volumen packs vendidos)
- volume, asp_cop (precio promedio)
- amount_mcop, amount_dkk
- net_sales, version, versen
- year, quarter, month
- cam_id, cam_status
- consumption_data
- bu, group, cluster, region
```

**Controladores:**
- `ArpController` - CRUD de ARPs
- `ServiceArpController` - Gestión de servicios
- `BusinessArpController` - Business cases
- `ArpSimulationsController` - Simulaciones

**Exports:**
- `ArpSimulationsExport` - Excel multi-hoja
- `ArpSimulationsSheets` - Hoja por marca
- `ArpSpecialSheet` - Hoja especial
- `ArpVersionSheets` - Versiones

**Imports:**
- `ArpImport` - Importación de simulaciones

**Permisos Requeridos:**
- `reportes.index` (usado para acceso a ARP)

**Impacto de NO Migrar:**
- ❌ Pérdida de capacidad de planificación anual
- ❌ No se pueden generar forecasts
- ❌ Imposible simular escenarios de negocio
- ❌ Pérdida de reportes ejecutivos críticos

**Recomendación:** ✅ **MIGRAR COMPLETO** - Es un módulo crítico para planificación comercial

---

### **MÓDULO 10: Gestión de Ventas (Sales)**

**Estado:** ⚠️ Mencionado parcialmente en RFP (como fuente de datos para liquidación)

**¿Qué hace?**  
Importación, almacenamiento y procesamiento de datos de ventas desde SAP para el proceso de liquidación.

**Modelos Identificados:**
- `Sales` - Documento de ventas importado
- `SalesDetails` - Detalles de cada venta

**Funcionalidades Actuales:**
- ✅ Importación de archivos Excel desde SAP
- ✅ Validación de 17 campos obligatorios
- ✅ Procesamiento por chunks (1000 registros)
- ✅ Cruce con negociaciones para cálculo de descuentos
- ✅ Generación de notas crédito por escalas
- ✅ Agrupación por cliente, producto, marca
- ✅ Cálculo de totales (cantidad, precio, valor neto, volumen)

**Campos de SalesDetails:**
```
- id_sales (FK a documento)
- billT, bill_number, bill_ltm
- prod_sap_code, client_sap_code
- po_number, payterm_code
- brand, bill_doc
- bill_date, bill_quanty
- bill_price, bill_net_value
- bill_real_qty, unitxmaterial
- volume, value_mdkk
```

**Validaciones en Importación:**
- Todos los campos son requeridos
- Códigos SAP deben existir en maestros
- Fechas válidas
- Valores numéricos positivos

**Exports:**
- `InvoicesExport` - Exportación de facturas
- `NotesExport` - Notas crédito calculadas
- `SapExport` - Archivos para SAP

**Imports:**
- `SalesImport` - Importación desde SAP
- `GenSheetImport` - Hojas genéricas

**Recomendación:** ✅ **MIGRAR Y MEJORAR** - Agregar validaciones adicionales y automatización

---

### **MÓDULO 11: Gestión de Formatos de Documentos**

**Estado:** ⚠️ Mencionado parcialmente en RFP (como parte de repositorio)

**¿Qué hace?**  
Gestión de plantillas y formatos para documentos comerciales (cotizaciones, negociaciones, certificados).

**Modelos Identificados:**
- `DocFormat` - Formatos de documentos
- `DocFormatType` - Tipos de formatos
- `DocFormatCertificate` - Certificados especiales
- `DocStatus` - Estados de documentos

**Funcionalidades Actuales:**
- ✅ Plantillas personalizables para cotizaciones
- ✅ Formatos de certificados por país
- ✅ Gestión de firmas digitales
- ✅ Encabezados y pies de página configurables
- ✅ Términos y condiciones por tipo de documento
- ✅ Versionado de formatos

**Campos de DocFormat:**
```
- id_formattype (tipo)
- title, body
- conditions_time (plazo entrega)
- conditions_content (observaciones)
- conditions_special (condiciones especiales)
- terms_title, terms_content
- sign_name, sign_charge, sign_image
- footer
- active (activo/inactivo)
```

**Campos de DocFormatCertificate:**
```
- country, reference
- header_body, body, footer_body
- user_firm, user_name, user_position
- page_name
- footer_column1_1, footer_column1_2, footer_column1_3
- footer_column2_1, footer_column3_1
- active
```

**Controlador:**
- `DocFormatsController` - CRUD de formatos

**Recomendación:** ✅ **MIGRAR** - Necesario para generación de documentos

---

## 👥 SISTEMA DE ROLES Y PERMISOS

### Estructura Actual (Caffeinated Shinobi)

**Tablas:**
- `roles` - Roles del sistema
- `permissions` - Permisos granulares
- `role_user` - Asignación de roles a usuarios
- `permission_role` - Permisos por rol
- `permission_user` - Permisos directos a usuarios

**Campos de Role:**
```
- id, name, slug, description
- special (enum: 'all-access', 'no-access')
- timestamps
```

**Campos de Permission:**
```
- id, name, slug, description
- timestamps
```

### Niveles de Autorización

**Tabla:** `nvn_discount_levels`

**Modelo:** `DiscountLevels`

**¿Qué hace?**  
Define niveles jerárquicos de autorización para aprobar descuentos según porcentaje.

**Ejemplos de Niveles:**
- Nivel 1: Hasta 5% de descuento
- Nivel 2: Hasta 10% de descuento
- Nivel 3: Hasta 15% de descuento
- Nivel 4: Más de 15% de descuento

**Relación con Usuarios:**
```php
User:
- is_authorizer (0/1) - ¿Es autorizador?
- authlevel (FK a discount_levels) - Nivel de autorización
```

**Relación con Productos:**
```php
Product_AuthLevels:
- id_product
- id_dist_channel (canal)
- id_level_discount (nivel requerido)
- discount_value (% descuento)
- discount_price (precio con descuento)
```

### Roles Identificados en el Código

Basado en el análisis del código:

1. **Autorizador** - Aprueba cotizaciones/negociaciones
2. **admin_venta** - Administrador de ventas
3. **adminprecios** - Administrador de precios
4. **CAM/KAM** - Key Account Managers (creadores de cotizaciones)

### Permisos Identificados

**Usuarios:**
- `users.index` - Ver usuarios
- `users.create` - Crear usuarios
- `users.edit` - Editar usuarios
- `users.destroy` - Eliminar usuarios

**Roles:**
- `roles.index` - Ver roles
- `roles.create` - Crear roles
- `roles.edit` - Editar roles
- `roles.destroy` - Eliminar roles

**Otros Módulos:**
- `autorizaciones.index` - Ver autorizaciones
- `reportes.index` - Ver reportes (usado para ARP)
- `products.index` - Ver productos

### Migración a Spatie Laravel Permission

**Plan de Migración:**

1. **Mapeo de Roles:**
   - Shinobi `Role` → Spatie `Role`
   - Mantener `name`, `slug` como `name`
   - `description` → `guard_name` = 'web'

2. **Mapeo de Permisos:**
   - Shinobi `Permission` → Spatie `Permission`
   - Mantener estructura de slugs
   - Agrupar por módulo

3. **Niveles de Autorización:**
   - Mantener tabla `discount_levels`
   - Agregar campo `authlevel` a usuarios
   - Crear middleware personalizado

4. **Características Adicionales:**
   - Agregar permisos por equipo (teams)
   - Implementar permisos temporales
   - Cache de permisos con Redis

---

## 🔐 INTEGRACIÓN CON NOVOUSERS (SSO)

### Arquitectura Actual

**Flujo de Autenticación:**
```
Usuario → Auth0 (novonordiskco.auth0.com)
    ↓
Connection: NovoUsers
    ↓
Laravel App (validación JWT)
    ↓
Creación/Actualización Usuario Local
    ↓
Asignación de Roles
```

### Endpoints de Integración

**NovoUserController:**
- `GET /novo-users/app-data` - Info de la app y roles
- `POST /novo-users/create` - Crear usuario desde NovoUsers
- `PUT /novo-users/update` - Actualizar usuario
- `POST /novo-users/login` - Login vía NovoUsers

**Auth0EndPoints:**
- `GET /auth0/app-data` - Info para Auth0
- `POST /auth0/create-user` - Crear usuario desde Auth0

### Configuración Auth0

**Variables de Entorno:**
```
AUTH0_DOMAIN=novonordiskco.auth0.com
AUTH0_CLIENT_ID=n1cSgRaHn4VizfYvgyXy1pcWQuYByOHt
AUTH0_CLIENT_SECRET=[SECRETO]
```

**Connection:** `NovoUsers`

### Datos Sincronizados

**De NovoUsers a Laravel:**
```json
{
  "username": "usuario.apellido",
  "email": "usuario@novonordisk.com",
  "user_metadata": {
    "first_name": "Nombre",
    "last_name": "Apellido"
  },
  "role": "nombre_rol"
}
```

### Middleware JWT

**CheckJWT:**
- Valida token JWT en requests API
- Usa Auth0 SDK para decodificar
- Retorna 401 si no autorizado

### Migración a Laravel 12

**Cambios Necesarios:**

1. **Actualizar Auth0 SDK:**
   - `auth0/login` v7.x → v8.x
   - Compatibilidad con PHP 8.4

2. **Mantener Endpoints:**
   - NovoUsers debe seguir funcionando
   - Misma estructura de respuestas
   - Backward compatibility

3. **Mejorar Seguridad:**
   - Implementar MFA para roles críticos
   - Refresh tokens automáticos
   - Logging de accesos

4. **Testing:**
   - Probar integración en ambiente de pruebas
   - Validar sincronización de usuarios
   - Verificar asignación de roles

---

## 📊 RESUMEN DE IMPACTO

### Módulos a Migrar

| Módulo | RFP | Actual | Prioridad | Complejidad |
|--------|-----|--------|-----------|-------------|
| Parametrización | ✅ | ✅ | ALTA | Media |
| Cotizaciones | ✅ | ✅ | ALTA | Alta |
| Negociaciones | ✅ | ✅ | ALTA | Alta |
| Liquidación | ✅ | ✅ | ALTA | Alta |
| Notas Crédito | ✅ | ✅ | ALTA | Media |
| Repositorio Docs | ✅ | ✅ | MEDIA | Baja |
| Reportería | ✅ | ✅ | ALTA | Media |
| Seguimiento | ✅ | ✅ | MEDIA | Baja |
| **ARP Simulator** | ❌ | ✅ | **ALTA** | **Alta** |
| **Ventas** | ⚠️ | ✅ | **ALTA** | **Media** |
| **Formatos Docs** | ⚠️ | ✅ | **MEDIA** | **Baja** |

### Esfuerzo Adicional Estimado

**Módulo ARP:**
- Análisis: 1 semana
- Desarrollo: 2 semanas
- Testing: 1 semana
- **Total: 4 semanas**

**Módulo Ventas (mejoras):**
- Análisis: 3 días
- Desarrollo: 1 semana
- Testing: 2 días
- **Total: 2 semanas**

**Formatos Documentos:**
- Análisis: 2 días
- Desarrollo: 1 semana
- Testing: 2 días
- **Total: 1.5 semanas**

**Roles y Permisos:**
- Migración Shinobi → Spatie: 1 semana
- Testing: 3 días
- **Total: 1.5 semanas**

**Integración NovoUsers:**
- Actualización Auth0: 3 días
- Testing: 2 días
- **Total: 1 semana**

### **TOTAL ESFUERZO ADICIONAL: 10 semanas**

---

## 💰 IMPACTO EN COSTOS

**Nota:** Todos los valores están en **Pesos Colombianos (COP)**.

### Costos Adicionales por Módulos No Contemplados

**Desarrollo:**
- ARP Simulator: $33.600.000 COP
- Mejoras Ventas: $16.800.000 COP
- Formatos Docs: $12.600.000 COP
- Migración Roles: $12.600.000 COP
- NovoUsers: $8.400.000 COP

**Total Adicional: $84.000.000 COP**

### Cronograma Ajustado

**Propuesta Original:** 24 semanas (6 meses)
**Con Módulos Adicionales:** 34 semanas (8.5 meses)

**Opciones:**

1. **Opción A - Extender Cronograma:**
   - Duración: 34 semanas
   - Costo adicional: $84.000.000 COP
   - Entrega completa de todos los módulos

2. **Opción B - Fase 2:**
   - Fase 1: 24 semanas (módulos RFP)
   - Fase 2: 10 semanas (módulos adicionales)
   - Mismo costo total
   - Entrega escalonada

3. **Opción C - Paralelización:**
   - Aumentar equipo temporalmente
   - Duración: 28 semanas
   - Costo adicional: $105.000.000 COP
   - Entrega más rápida

---

## ✅ RECOMENDACIONES

1. **Incluir Módulo ARP:**
   - Es crítico para planificación comercial
   - Usado activamente por el equipo
   - Alto valor de negocio

2. **Mejorar Módulo de Ventas:**
   - Automatizar más validaciones
   - Agregar alertas proactivas
   - Mejorar performance de importación

3. **Migrar Formatos de Documentos:**
   - Necesario para generación de PDFs
   - Personalización por cliente
   - Cumplimiento normativo

4. **Actualizar Sistema de Roles:**
   - Spatie es más moderno y mantenido
   - Mejor integración con Laravel 12
   - Más flexible para futuro

5. **Mantener Integración NovoUsers:**
   - Es requisito corporativo
   - SSO es estándar de seguridad
   - Facilita gestión de usuarios

---

## 📞 PRÓXIMOS PASOS

1. **Validar con Stakeholders:**
   - Confirmar uso actual de módulo ARP
   - Priorizar funcionalidades
   - Definir alcance final

2. **Ajustar Propuesta:**
   - Actualizar cronograma
   - Recalcular costos
   - Definir fases de entrega

3. **Planificar Migración:**
   - Estrategia de datos
   - Plan de pruebas
   - Capacitación usuarios

---

**Documento preparado por:** Equipo de Análisis Técnico  
**Fecha:** Noviembre 2025  
**Versión:** 1.0

