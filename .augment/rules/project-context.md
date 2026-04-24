---
type: always
description: Stack, convenciones y contexto de migración del proyecto Novo-Nordisk.
---

# Novo-Nordisk — Contexto del proyecto

## Qué es este repo

Migración de una aplicación legacy (PHP/MySQL, AdminLTE, Blade clásico) a un stack moderno Laravel + Filament. El objetivo es preservar datos históricos (se seedean desde dumps PostgreSQL/TSV) y reimplementar la UI/UX con los patrones nativos de Filament 5.

Dominios principales migrados o en migración:
- **Documentos**: hub `/admin/documentos` con dos exploradores:
  - *Documentos generales* — árbol de carpetas + archivos (`FolderRepository`, `DocRepository`).
  - *Clientes* — archivos asociados a cada cliente (`Client`, `ClientFile`).
- **ARP** (modelos `Arp*`): business cases, proyecciones, simulaciones, ventas, descuentos.
- **Brands / Clients / Users / Roles / Permissions**: catálogos base.

La data legacy se reimporta vía `database/seeders/LocalSeeders/` usando archivos en `database/seeders/LocalSeeders/data/` (TSV y SQL). Los seeders normalizan nombres de columnas al estándar Laravel (`id`, `client_id`, etc.) aunque el dump origen use otras convenciones.

**Estado de normalización de columnas (migraciones ya aplicadas):**
- PKs y FKs: renombradas a `id` / `{table}_id` (ej. `brand_id`, `folder_id`, `client_id`).
- Atributos con prefijo redundante: renombrados a su forma corta (`brand_name`→`name`, `client_nit`→`nit`, `folder_name`→`name`, `folder_url`→`url`, `doc_name`→`name`, `file_folder`→`folder`, etc.). Ver `database/migrations/2026_04_24_120000_normalize_attribute_column_names.php` para el listado completo.
- Cualquier seeder, modelo, form o resource nuevo debe usar los nombres **normalizados**; no reintroducir prefijos legacy.

## Stack

- **PHP** 8.4 (composer requiere `^8.3`)
- **Laravel** 13.6
- **Filament** 5.6 (panel admin en `App\Providers\Filament\AdminPanelProvider`, path `/admin`)
- **Laravel Boost** 2.4 (guidelines en `CLAUDE.md`)
- **Pint** para formato (`./vendor/bin/pint`)
- **Pest / PHPUnit** 12 para tests
- **Storage**: disk `public`, rutas relativas a `uploads/`

## Convenciones Filament 5

Usar **siempre** APIs nativas de Filament 5. No inventar Blade custom si existe componente equivalente.

- **Páginas custom**: implementar `public function content(Schema $schema): Schema` en vez de definir `$view`. El default `filament-panels::pages.page` renderiza el schema.
- **Schemas**: `Filament\Schemas\Components\{Section, Grid, Tabs, Fieldset, Split, Group}` + entries de `Filament\Infolists\Components\*` + fields de `Filament\Forms\Components\*`.
- **TextEntry sin record**: usar `->state(valor)` o `->state(fn () => ...)` para stats/derivados.
- **Enums tipados obligatorios** (no strings mágicos):
  - `Filament\Support\Enums\TextSize` (`ExtraSmall`, `Small`, `Medium`, `Large`)
  - `Filament\Support\Enums\FontWeight` (`Thin`..`Black`)
  - `Filament\Support\Enums\IconPosition` (`Before`, `After`)
  - `Filament\Support\Enums\{Alignment, Size, IconSize, Width, VerticalAlignment}`
  - `Filament\Support\Icons\Heroicon` (ej. `Heroicon::OutlinedFolderOpen`, `Heroicon::ArrowRight`)
- **Acciones en secciones**: `Section::make()->footerActions([...])` / `->headerActions([...])` con `Filament\Actions\Action`.
- **Navegación**: propiedades estáticas tipadas (`$navigationIcon` como `BackedEnum`, `$navigationGroup` como `UnitEnum|string`).
- **Search/inputs reactivos en páginas custom**: preferir `Forms\Components\TextInput::make('propiedad')->live(debounce: 300)->prefixIcon(Heroicon::OutlinedMagnifyingGlass)` bindeado a una public property de la página (schema sin `statePath`) sobre HTML crudo con `Html::make()`. Filament genera el `wire:model.live.debounce.300` automáticamente.
- **Archivos y paths de storage**:
  - `FileUpload::make()->disk('public')->directory('uploads/...')` retorna el path completo incluyendo el prefijo. Al persistir, guardar `dirname($path)` en la columna `folder` para que coincida con la ruta real en disco.
  - Validación visual de archivos ausentes: `IconAction` con `icon(fn () => exists ? DocumentArrowDown : ExclamationTriangle)`, `color('danger')` y `tooltip('Archivo no encontrado ...')` cuando `Storage::disk('public')->exists($path) === false`. Click-to-download sobre el nombre del archivo.
- **Breadcrumbs in-section**: construir el trail con un `TextEntry` / `RepeatableEntry` en el schema de la propia `Section` (no apoyarse en el breadcrumb del page header cuando la vista tiene navegación interna por query params).

## Convenciones de código

- Respetar estructura existente (`app/Filament/Resources/{Module}/{Resource}Resource.php` + `Pages/`).
- Modelos Eloquent con relaciones explícitas; nombres de relaciones en camelCase plural (`clientsFiles`).
- Los helpers de documentos/archivos viven en `app/Support/` (ej. `FileIconResolver`).
- Tamaños de archivos se **persisten** en DB (columna `size`) para evitar `Storage::size()` en render.
- Breadcrumbs de árbol: cargar toda la colección una vez y recorrer con `keyBy('id')` en memoria (no N+1).

## Flujo de trabajo

- Formatear con `./vendor/bin/pint <path>` tras cualquier edición PHP.
- Limpiar caches tras cambios de Filament/vistas: `php artisan view:clear` (y `filament:optimize-clear` si hace falta).
- Verificar rutas con `php artisan route:list | grep <slug>`.
- Seeders locales: `php artisan db:seed --class=LocalSeeders\\XxxSeeder`.

## Qué NO hacer

- No crear Blade custom si Filament ya provee el componente (Section, Grid, TextEntry, Action, etc.).
- No usar strings donde existe un enum (`'bold'` → `FontWeight::Bold`, `'lg'` → `TextSize::Large`).
- No agregar queries en loops de render; precargar con `with()` / `keyBy()`.
- No renombrar columnas ya seedeadas desde el dump legacy sin actualizar el seeder correspondiente.
- No reintroducir los prefijos `brand_`, `client_`, `folder_`, `doc_`, `file_`, `loc_`, etc. en columnas de atributo — ya fueron normalizados.
- No usar `Html::make()` con inputs/svgs crudos cuando Filament provee el componente nativo (`TextInput`, `prefixIcon`).
- No tocar `vendor/` ni editar `composer.json` a mano — usar `composer require/remove`.
