# Estructura del Sidebar - CAM Tool

## Árbol de Navegación con Permisos

```
📂 OPERACIONES
├── 🏠 Inicio                          → (Sin permiso requerido)
├── 📄 Cotizaciones                    → cotizaciones.index
├── 💼 Negociaciones                   → negociaciones.index
└── 🧮 Simulador ARP                   → negociaciones.index

📂 CONFIGURACIÓN
│   (Visible si: clients.index OR products.index OR precios.index OR levels.index OR scales.index)
├── 🏢 Clientes                        → clients.index
├── 💉 Productos                       → products.index
├── 💵 Precios                         → precios.index
├── 📊 Escalas                         → scales.index
├── 📄 Formatos de documentos          → docs.edit
└── 📦 ARP                             → (Sin permiso requerido)

📂 DATOS DEL SISTEMA
│   (Visible si: clientstype.index OR paymethods.index OR productlines.index OR productunits.index OR productuses.index)
├── ♟️ Tipos de cliente                → clientstype.index
├── 💰 Métodos de pago                 → paymethods.index
├── 🧪 Líneas de producto              → productlines.index
├── 💊 Unidades de venta               → productunits.index
├── 🔗 Usos adicionales                → productuses.index
├── ☂️ Marcas                          → clientstype.index
└── 📎 Conceptos de negociación        → concept.index

📂 USUARIOS
│   (Visible si: users.index OR roles.index)
├── 👥 Usuarios                        → users.index
└── 🗂️ Roles                           → roles.index

📂 INFORMES
│   (Visible si: reportes.index)
├── 📈 Reportes                        → reportes.index
├── 📝 Notas                           → reportes.index
└── 📝 SAP                             → reportes.index

📂 DOCUMENTOS
└── 📚 Repositorio de Documentos       → (Sin permiso requerido)
```

---

## Detalle de Permisos por Sección

### OPERACIONES
| Elemento | Permiso | Slug |
|----------|---------|------|
| Inicio | Ninguno | - |
| Cotizaciones | Ver cotizaciones | `cotizaciones.index` |
| Negociaciones | Ver negociaciones | `negociaciones.index` |
| Simulador ARP | Ver negociaciones | `negociaciones.index` |

### CONFIGURACIÓN
| Elemento | Permiso | Slug |
|----------|---------|------|
| Clientes | Ver clientes | `clients.index` |
| Productos | Ver productos | `products.index` |
| Precios | Ver precios | `precios.index` |
| Escalas | Ver escalas | `scales.index` |
| Formatos de documentos | Editar documentos | `docs.edit` |
| ARP | Ninguno | - |

### DATOS DEL SISTEMA
| Elemento | Permiso | Slug |
|----------|---------|------|
| Tipos de cliente | Ver tipos cliente | `clientstype.index` |
| Métodos de pago | Ver métodos pago | `paymethods.index` |
| Líneas de producto | Ver líneas producto | `productlines.index` |
| Unidades de venta | Ver unidades producto | `productunits.index` |
| Usos adicionales | Ver usos producto | `productuses.index` |
| Marcas | Ver tipos cliente | `clientstype.index` |
| Conceptos de negociación | Ver conceptos | `concept.index` |

### USUARIOS
| Elemento | Permiso | Slug |
|----------|---------|------|
| Usuarios | Ver usuarios | `users.index` |
| Roles | Ver roles | `roles.index` |

### INFORMES
| Elemento | Permiso | Slug |
|----------|---------|------|
| Reportes | Ver reportes | `reportes.index` |
| Notas | Ver reportes | `reportes.index` |
| SAP | Ver reportes | `reportes.index` |

### DOCUMENTOS
| Elemento | Permiso | Slug |
|----------|---------|------|
| Repositorio de Documentos | Ninguno | - |

---

## Lista Completa de Permisos del Sistema

### Usuarios
- `users.index` - Ver usuarios
- `users.create` - Crear usuarios
- `users.edit` - Editar usuarios
- `users.delete` - Eliminar usuarios

### Roles
- `roles.index` - Ver roles
- `roles.create` - Crear roles
- `roles.edit` - Editar roles
- `roles.delete` - Eliminar roles

### Productos
- `products.index` - Ver productos
- `products.show` - Ver detalle producto
- `products.create` - Crear productos
- `products.edit` - Editar productos
- `products.delete` - Eliminar productos
- `products.masive` - Carga masiva productos

### Clientes
- `clients.index` - Ver clientes
- `clients.show` - Ver detalle cliente
- `clients.create` - Crear clientes
- `clients.edit` - Editar clientes
- `clients.delete` - Eliminar clientes
- `clients.masive` - Carga masiva clientes

### Cotizaciones
- `cotizaciones.index` - Ver cotizaciones
- `cotizaciones.show` - Ver detalle cotización
- `cotizaciones.create` - Crear cotizaciones
- `cotizaciones.edit` - Editar cotizaciones
- `cotizaciones.delete` - Eliminar cotizaciones

### Negociaciones
- `negociaciones.index` - Ver negociaciones
- `negociaciones.show` - Ver detalle negociación
- `negociaciones.create` - Crear negociaciones
- `negociaciones.edit` - Editar negociaciones
- `negociaciones.delete` - Eliminar negociaciones



### Autorizaciones
- `authlevel.index` - Ver niveles autorización
- `authlevel.store` - Crear niveles autorización
- `autorizaciones.index` - Ver autorizaciones
- `autorizaciones.show` - Ver detalle autorización
- `preautorizacion` - Preautorización nivel 1
- `preautorizacion.2` - Preautorización nivel 2

### Precios y Escalas
- `precios.index` - Ver precios
- `precios.show` - Ver detalle precios
- `precios.edit` - Editar precios
- `prices.masive` - Carga masiva precios
- `levels.index` - Ver niveles
- `levels.edit` - Editar niveles
- `scales.index` - Ver escalas
- `scales.edit` - Editar escalas
- `scales.masive` - Carga masiva escalas

### Datos del Sistema
- `clientstype.index` - Ver tipos de cliente
- `paymethods.index` - Ver métodos de pago
- `productlines.index` - Ver líneas de producto
- `productunits.index` - Ver unidades de venta
- `productuses.index` - Ver usos adicionales

### Otros
- `financiero` - Acceso módulo financiero
- `docs.edit` - Editar formatos documentos
- `concept.index` - Ver conceptos negociación
- `concept.edit` - Editar conceptos negociación
- `reportes.index` - Ver reportes

---

> **Archivo fuente:** `resources/views/admin/layouts/mainmenu.blade.php`
