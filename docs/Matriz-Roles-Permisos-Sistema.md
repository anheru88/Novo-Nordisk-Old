# MATRIZ DE ROLES Y PERMISOS - SISTEMA COMERCIAL NOVO NORDISK

**Proyecto:** Migración Plataforma Comercial Novo Nordisk Colombia  
**Fecha:** Noviembre 2025  
**Versión:** 1.0

---

## 📋 RESUMEN EJECUTIVO

Este documento detalla el **sistema completo de roles, permisos y niveles de autorización** identificado en el sistema actual y propuesto para la migración a Laravel 12 + Filament.

---

## 🎭 ROLES DEL SISTEMA

### Roles Identificados en Sistema Actual

| # | Rol | Slug | Descripción | Usuarios Típicos |
|---|-----|------|-------------|------------------|
| 1 | **Administrador** | `admin` | Acceso total al sistema | Gerente TI, Administrador Sistema |
| 2 | **Administrador de Ventas** | `admin_venta` | Gestión completa de ventas | Gerente Comercial |
| 3 | **Administrador de Precios** | `adminprecios` | Gestión de precios y listas | Gerente de Pricing |
| 4 | **Autorizador** | `autorizador` | Aprobación de cotizaciones/negociaciones | Directores, Gerentes |
| 5 | **CAM/KAM** | `cam` | Creación de cotizaciones y negociaciones | Key Account Managers |
| 6 | **Analista Comercial** | `analista_comercial` | Consulta y reportes | Analistas |
| 7 | **Consulta** | `consulta` | Solo lectura | Auditores, Consultores |

### Características Especiales de Roles

**Rol Autorizador:**
- Requiere campo adicional: `is_authorizer = 1`
- Requiere nivel de autorización: `authlevel` (FK a `discount_levels`)
- Puede aprobar según su nivel jerárquico

**Niveles de Autorización:**

| Nivel | Nombre | Descuento Máximo | Descripción |
|-------|--------|------------------|-------------|
| 1 | Nivel 1 | Hasta 5% | Supervisores |
| 2 | Nivel 2 | Hasta 10% | Gerentes de Área |
| 3 | Nivel 3 | Hasta 15% | Gerentes Regionales |
| 4 | Nivel 4 | Más de 15% | Directores |

---

## 🔐 PERMISOS DEL SISTEMA

### Estructura de Permisos

**Formato:** `modulo.accion`

**Acciones Estándar:**
- `index` - Ver listado
- `create` - Crear nuevo
- `edit` - Editar existente
- `destroy` - Eliminar
- `show` - Ver detalle

### Permisos por Módulo

#### 1. Usuarios y Accesos

| Permiso | Nombre | Descripción |
|---------|--------|-------------|
| `users.index` | Ver usuarios | Listar usuarios del sistema |
| `users.create` | Crear usuarios | Registrar nuevos usuarios |
| `users.edit` | Editar usuarios | Modificar datos de usuarios |
| `users.destroy` | Eliminar usuarios | Eliminar usuarios del sistema |
| `roles.index` | Ver roles | Listar roles disponibles |
| `roles.create` | Crear roles | Crear nuevos roles |
| `roles.edit` | Editar roles | Modificar roles existentes |
| `roles.destroy` | Eliminar roles | Eliminar roles del sistema |

#### 2. Parametrización

| Permiso | Nombre | Descripción |
|---------|--------|-------------|
| `clients.index` | Ver clientes | Listar clientes |
| `clients.create` | Crear clientes | Registrar nuevos clientes |
| `clients.edit` | Editar clientes | Modificar datos de clientes |
| `clients.destroy` | Eliminar clientes | Eliminar clientes |
| `clients.import` | Importar clientes | Carga masiva desde Excel |
| `clients.export` | Exportar clientes | Descarga masiva a Excel |
| `products.index` | Ver productos | Listar productos |
| `products.create` | Crear productos | Registrar nuevos productos |
| `products.edit` | Editar productos | Modificar datos de productos |
| `products.destroy` | Eliminar productos | Eliminar productos |
| `products.import` | Importar productos | Carga masiva desde Excel |
| `products.export` | Exportar productos | Descarga masiva a Excel |
| `prices.index` | Ver precios | Listar listas de precios |
| `prices.create` | Crear precios | Crear listas de precios |
| `prices.edit` | Editar precios | Modificar precios |
| `prices.destroy` | Eliminar precios | Eliminar listas de precios |
| `prices.approve` | Aprobar precios | Aprobar listas de precios |

#### 3. Cotizaciones

| Permiso | Nombre | Descripción |
|---------|--------|-------------|
| `quotations.index` | Ver cotizaciones | Listar cotizaciones |
| `quotations.create` | Crear cotizaciones | Generar nuevas cotizaciones |
| `quotations.edit` | Editar cotizaciones | Modificar cotizaciones |
| `quotations.destroy` | Eliminar cotizaciones | Eliminar cotizaciones |
| `quotations.approve` | Aprobar cotizaciones | Aprobar/rechazar cotizaciones |
| `quotations.export` | Exportar cotizaciones | Generar PDF/Excel |
| `quotations.send` | Enviar cotizaciones | Enviar por email |
| `quotations.view_all` | Ver todas | Ver cotizaciones de todos los CAMs |

#### 4. Negociaciones

| Permiso | Nombre | Descripción |
|---------|--------|-------------|
| `negotiations.index` | Ver negociaciones | Listar negociaciones |
| `negotiations.create` | Crear negociaciones | Generar nuevas negociaciones |
| `negotiations.edit` | Editar negociaciones | Modificar negociaciones |
| `negotiations.destroy` | Eliminar negociaciones | Eliminar negociaciones |
| `negotiations.approve` | Aprobar negociaciones | Aprobar/rechazar negociaciones |
| `negotiations.export` | Exportar negociaciones | Generar PDF/Excel |
| `negotiations.view_all` | Ver todas | Ver negociaciones de todos los CAMs |

#### 5. Liquidación y Notas Crédito

| Permiso | Nombre | Descripción |
|---------|--------|-------------|
| `sales.index` | Ver ventas | Listar archivos de ventas |
| `sales.import` | Importar ventas | Cargar archivos SAP |
| `sales.export` | Exportar ventas | Descargar datos de ventas |
| `liquidation.index` | Ver liquidaciones | Listar liquidaciones |
| `liquidation.calculate` | Calcular liquidaciones | Ejecutar cálculo mensual |
| `liquidation.approve` | Aprobar liquidaciones | Aprobar liquidaciones |
| `creditnotes.index` | Ver notas crédito | Listar notas crédito |
| `creditnotes.generate` | Generar notas | Generar archivos TXT |
| `creditnotes.export` | Exportar notas | Descargar archivos SAP |
| `creditnotes.destroy` | Eliminar notas | Eliminar notas crédito |

#### 6. Repositorio de Documentos

| Permiso | Nombre | Descripción |
|---------|--------|-------------|
| `documents.index` | Ver documentos | Listar documentos |
| `documents.create` | Subir documentos | Cargar nuevos documentos |
| `documents.download` | Descargar documentos | Descargar archivos |
| `documents.destroy` | Eliminar documentos | Eliminar documentos |
| `folders.create` | Crear carpetas | Crear nuevas carpetas |
| `folders.edit` | Editar carpetas | Renombrar carpetas |
| `folders.destroy` | Eliminar carpetas | Eliminar carpetas |

#### 7. Reportería

| Permiso | Nombre | Descripción |
|---------|--------|-------------|
| `reportes.index` | Ver reportes | Acceso a módulo de reportes |
| `reportes.quotations` | Reporte cotizaciones | Generar reporte de cotizaciones |
| `reportes.negotiations` | Reporte negociaciones | Generar reporte de negociaciones |
| `reportes.sales` | Reporte ventas | Generar reporte de ventas |
| `reportes.creditnotes` | Reporte notas | Generar reporte de notas crédito |
| `reportes.export` | Exportar reportes | Descargar reportes |

#### 8. Seguimiento y Control

| Permiso | Nombre | Descripción |
|---------|--------|-------------|
| `tracking.index` | Ver seguimiento | Acceso a seguimiento |
| `tracking.quotations` | Seguir cotizaciones | Seguimiento de cotizaciones |
| `tracking.negotiations` | Seguir negociaciones | Seguimiento de negociaciones |
| `tracking.alerts` | Ver alertas | Ver alertas de vencimiento |
| `notifications.index` | Ver notificaciones | Listar notificaciones |
| `notifications.send` | Enviar notificaciones | Enviar notificaciones manuales |

#### 9. ARP (Annual Revenue Planning)

| Permiso | Nombre | Descripción |
|---------|--------|-------------|
| `arp.index` | Ver ARPs | Listar ARPs |
| `arp.create` | Crear ARPs | Crear nuevo ARP |
| `arp.edit` | Editar ARPs | Modificar ARPs |
| `arp.destroy` | Eliminar ARPs | Eliminar ARPs |
| `arp.simulations` | Ver simulaciones | Acceso a simulador |
| `arp.import` | Importar simulaciones | Cargar archivos Excel |
| `arp.export` | Exportar simulaciones | Generar reportes Excel |
| `arp.business_case` | Gestionar PBC | Gestionar business cases |

#### 10. Autorizaciones

| Permiso | Nombre | Descripción |
|---------|--------|-------------|
| `autorizaciones.index` | Ver autorizaciones | Acceso a módulo de autorizaciones |
| `autorizaciones.approve` | Aprobar | Aprobar documentos pendientes |
| `autorizaciones.reject` | Rechazar | Rechazar documentos |
| `autorizaciones.comment` | Comentar | Agregar comentarios |

---

## 📊 MATRIZ DE PERMISOS POR ROL

### Leyenda
- ✅ = Permiso otorgado
- ❌ = Permiso denegado
- 🔒 = Requiere nivel de autorización adicional

### Usuarios y Accesos

| Permiso | Admin | Admin Ventas | Admin Precios | Autorizador | CAM | Analista | Consulta |
|---------|-------|--------------|---------------|-------------|-----|----------|----------|
| users.index | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| users.create | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| users.edit | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| users.destroy | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| roles.index | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| roles.create | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| roles.edit | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| roles.destroy | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |

### Parametrización

| Permiso | Admin | Admin Ventas | Admin Precios | Autorizador | CAM | Analista | Consulta |
|---------|-------|--------------|---------------|-------------|-----|----------|----------|
| clients.index | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| clients.create | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| clients.edit | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| clients.destroy | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| clients.import | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| clients.export | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| products.index | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| products.create | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| products.edit | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| products.destroy | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| products.import | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| products.export | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| prices.index | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| prices.create | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| prices.edit | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| prices.destroy | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| prices.approve | ✅ | ✅ | ✅ | 🔒 | ❌ | ❌ | ❌ |

### Cotizaciones

| Permiso | Admin | Admin Ventas | Admin Precios | Autorizador | CAM | Analista | Consulta |
|---------|-------|--------------|---------------|-------------|-----|----------|----------|
| quotations.index | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| quotations.create | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| quotations.edit | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| quotations.destroy | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| quotations.approve | ✅ | ✅ | ❌ | 🔒 | ❌ | ❌ | ❌ |
| quotations.export | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| quotations.send | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| quotations.view_all | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ |

### Negociaciones

| Permiso | Admin | Admin Ventas | Admin Precios | Autorizador | CAM | Analista | Consulta |
|---------|-------|--------------|---------------|-------------|-----|----------|----------|
| negotiations.index | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| negotiations.create | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| negotiations.edit | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| negotiations.destroy | ✅ | ✅ | ❌ | ❌ | ✅ | ❌ | ❌ |
| negotiations.approve | ✅ | ✅ | ❌ | 🔒 | ❌ | ❌ | ❌ |
| negotiations.export | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| negotiations.view_all | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ |

### Liquidación y Notas Crédito

| Permiso | Admin | Admin Ventas | Admin Precios | Autorizador | CAM | Analista | Consulta |
|---------|-------|--------------|---------------|-------------|-----|----------|----------|
| sales.index | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ |
| sales.import | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| sales.export | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ |
| liquidation.index | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ |
| liquidation.calculate | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| liquidation.approve | ✅ | ✅ | ❌ | 🔒 | ❌ | ❌ | ❌ |
| creditnotes.index | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ |
| creditnotes.generate | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| creditnotes.export | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ |
| creditnotes.destroy | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

### Repositorio y Reportes

| Permiso | Admin | Admin Ventas | Admin Precios | Autorizador | CAM | Analista | Consulta |
|---------|-------|--------------|---------------|-------------|-----|----------|----------|
| documents.index | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| documents.create | ✅ | ✅ | ✅ | ❌ | ✅ | ❌ | ❌ |
| documents.download | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| documents.destroy | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| reportes.index | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| reportes.export | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |

### ARP y Autorizaciones

| Permiso | Admin | Admin Ventas | Admin Precios | Autorizador | CAM | Analista | Consulta |
|---------|-------|--------------|---------------|-------------|-----|----------|----------|
| arp.index | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ |
| arp.create | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| arp.simulations | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ |
| arp.import | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| arp.export | ✅ | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ |
| autorizaciones.index | ✅ | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ |
| autorizaciones.approve | ✅ | ✅ | ❌ | 🔒 | ❌ | ❌ | ❌ |
| autorizaciones.reject | ✅ | ✅ | ❌ | 🔒 | ❌ | ❌ | ❌ |

---

## 🔄 MIGRACIÓN DE SHINOBI A SPATIE

### Comparación de Paquetes

| Característica | Caffeinated Shinobi | Spatie Laravel Permission |
|----------------|---------------------|---------------------------|
| Última actualización | 2019 | 2024 (activo) |
| Laravel 12 | ❌ No compatible | ✅ Compatible |
| PHP 8.4 | ❌ No compatible | ✅ Compatible |
| Documentación | Limitada | Excelente |
| Comunidad | Inactiva | Muy activa |
| Caché | Básico | Redis/Memcached |
| Teams | ❌ No | ✅ Sí |
| Wildcards | ❌ No | ✅ Sí |

### Plan de Migración

**Paso 1: Instalación**
```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

**Paso 2: Migración de Datos**
```sql
-- Migrar roles
INSERT INTO spatie_roles (name, guard_name, created_at, updated_at)
SELECT slug, 'web', created_at, updated_at
FROM roles;

-- Migrar permisos
INSERT INTO spatie_permissions (name, guard_name, created_at, updated_at)
SELECT slug, 'web', created_at, updated_at
FROM permissions;

-- Migrar asignaciones
INSERT INTO model_has_roles (role_id, model_type, model_id)
SELECT role_id, 'App\Models\User', user_id
FROM role_user;
```

**Paso 3: Actualizar Modelos**
```php
// Antes (Shinobi)
use Caffeinated\Shinobi\Concerns\HasRolesAndPermissions;

// Después (Spatie)
use Spatie\Permission\Traits\HasRoles;
```

**Paso 4: Actualizar Middleware**
```php
// Antes
'has.role' => \Caffeinated\Shinobi\Middleware\UserHasRole::class,

// Después
'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
```

---

## 📝 NOTAS FINALES

### Campos Adicionales en Usuarios

```php
User Model:
- is_authorizer (boolean) - ¿Es autorizador?
- authlevel (FK) - Nivel de autorización (1-4)
- firm (string) - Firma digital (imagen)
- uuid_firm (uuid) - UUID de firma
```

### Validaciones de Autorización

```php
// Verificar si usuario puede aprobar descuento
if ($user->is_authorizer && $user->authlevel >= $required_level) {
    // Puede aprobar
}
```

---

**Documento preparado por:** Equipo de Análisis Técnico  
**Fecha:** Noviembre 2025  
**Versión:** 1.0

