<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * RBAC seed based on docs/Matriz-Roles-Permisos-Sistema.md.
 *
 * Uses Spatie Laravel Permission. Role/permission names = module.action slugs.
 * - `admin` bypasses checks via Gate::before in AppServiceProvider (no perms synced).
 * - `inactivo` blocked at User::canAccessPanel (no perms synced).
 */
class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            // Usuarios y accesos
            'users.index', 'users.create', 'users.edit', 'users.destroy',
            'roles.index', 'roles.create', 'roles.edit', 'roles.destroy',

            // Parametrización
            'clients.index', 'clients.create', 'clients.edit', 'clients.destroy', 'clients.import', 'clients.export',
            'products.index', 'products.create', 'products.edit', 'products.destroy', 'products.import', 'products.export',
            'prices.index', 'prices.create', 'prices.edit', 'prices.destroy', 'prices.approve',

            // Cotizaciones
            'quotations.index', 'quotations.create', 'quotations.edit', 'quotations.destroy',
            'quotations.approve', 'quotations.export', 'quotations.send', 'quotations.view_all',

            // Negociaciones
            'negotiations.index', 'negotiations.create', 'negotiations.edit', 'negotiations.destroy',
            'negotiations.approve', 'negotiations.export', 'negotiations.view_all',

            // Liquidación y notas crédito
            'sales.index', 'sales.import', 'sales.export',
            'liquidation.index', 'liquidation.calculate', 'liquidation.approve',
            'creditnotes.index', 'creditnotes.generate', 'creditnotes.export', 'creditnotes.destroy',

            // Documentos
            'documents.index', 'documents.create', 'documents.download', 'documents.destroy',
            'folders.create', 'folders.edit', 'folders.destroy',

            // Reportería
            'reportes.index', 'reportes.quotations', 'reportes.negotiations',
            'reportes.sales', 'reportes.creditnotes', 'reportes.export',

            // Seguimiento y notificaciones
            'tracking.index', 'tracking.quotations', 'tracking.negotiations', 'tracking.alerts',
            'notifications.index', 'notifications.send',

            // ARP
            'arp.index', 'arp.create', 'arp.edit', 'arp.destroy',
            'arp.simulations', 'arp.import', 'arp.export', 'arp.business_case',

            // Autorizaciones
            'autorizaciones.index', 'autorizaciones.approve', 'autorizaciones.reject', 'autorizaciones.comment',
        ];

        foreach ($permissions as $name) {
            Permission::findOrCreate($name);
        }

        $matrix = [
            'admin'              => [],
            'admin_venta'        => [
                'users.index', 'users.create', 'users.edit',
                'roles.index',
                'clients.index', 'clients.create', 'clients.edit', 'clients.destroy', 'clients.import', 'clients.export',
                'products.index', 'products.create', 'products.edit', 'products.destroy', 'products.import', 'products.export',
                'prices.index', 'prices.create', 'prices.edit', 'prices.destroy', 'prices.approve',
                'quotations.index', 'quotations.create', 'quotations.edit', 'quotations.destroy',
                'quotations.approve', 'quotations.export', 'quotations.send', 'quotations.view_all',
                'negotiations.index', 'negotiations.create', 'negotiations.edit', 'negotiations.destroy',
                'negotiations.approve', 'negotiations.export', 'negotiations.view_all',
                'sales.index', 'sales.import', 'sales.export',
                'liquidation.index', 'liquidation.calculate', 'liquidation.approve',
                'creditnotes.index', 'creditnotes.generate', 'creditnotes.export', 'creditnotes.destroy',
                'documents.index', 'documents.create', 'documents.download', 'documents.destroy',
                'reportes.index', 'reportes.export',
                'arp.index', 'arp.create', 'arp.simulations', 'arp.import', 'arp.export',
                'autorizaciones.index', 'autorizaciones.approve', 'autorizaciones.reject',
            ],
            'adminprecios'       => [
                'clients.index', 'clients.export',
                'products.index', 'products.create', 'products.edit', 'products.destroy', 'products.import', 'products.export',
                'prices.index', 'prices.create', 'prices.edit', 'prices.destroy', 'prices.approve',
                'quotations.index', 'quotations.export', 'quotations.view_all',
                'negotiations.index', 'negotiations.export', 'negotiations.view_all',
                'sales.index', 'sales.export',
                'liquidation.index',
                'creditnotes.index', 'creditnotes.export',
                'documents.index', 'documents.create', 'documents.download',
                'reportes.index', 'reportes.export',
                'arp.index', 'arp.simulations', 'arp.export',
                'autorizaciones.index',
            ],
            'autorizador'        => [
                'clients.index', 'clients.export',
                'products.index', 'products.export',
                'prices.index', 'prices.approve',
                'quotations.index', 'quotations.approve', 'quotations.export', 'quotations.view_all',
                'negotiations.index', 'negotiations.approve', 'negotiations.export', 'negotiations.view_all',
                'sales.index', 'sales.export',
                'liquidation.index', 'liquidation.approve',
                'creditnotes.index', 'creditnotes.export',
                'documents.index', 'documents.download',
                'reportes.index', 'reportes.export',
                'arp.index', 'arp.simulations', 'arp.export',
                'autorizaciones.index', 'autorizaciones.approve', 'autorizaciones.reject',
            ],
            'cam'                => [
                'clients.index', 'clients.create', 'clients.edit', 'clients.export',
                'products.index', 'products.export',
                'prices.index',
                'quotations.index', 'quotations.create', 'quotations.edit', 'quotations.destroy', 'quotations.export', 'quotations.send',
                'negotiations.index', 'negotiations.create', 'negotiations.edit', 'negotiations.destroy', 'negotiations.export',
                'documents.index', 'documents.create', 'documents.download',
                'reportes.index', 'reportes.export',
            ],
            'analista_comercial' => [
                'clients.index', 'clients.export',
                'products.index', 'products.export',
                'prices.index',
                'quotations.index', 'quotations.export', 'quotations.view_all',
                'negotiations.index', 'negotiations.export', 'negotiations.view_all',
                'sales.index', 'sales.export',
                'liquidation.index',
                'creditnotes.index', 'creditnotes.export',
                'documents.index', 'documents.download',
                'reportes.index', 'reportes.export',
                'arp.index', 'arp.simulations', 'arp.export',
            ],
            'consulta'           => [
                'clients.index', 'clients.export',
                'products.index', 'products.export',
                'prices.index',
                'quotations.index', 'quotations.export', 'quotations.view_all',
                'negotiations.index', 'negotiations.export', 'negotiations.view_all',
                'sales.index', 'sales.export',
                'liquidation.index',
                'creditnotes.index', 'creditnotes.export',
                'documents.index', 'documents.download',
                'reportes.index', 'reportes.export',
                'arp.index', 'arp.simulations', 'arp.export',
            ],
            'inactivo'           => [],
        ];

        foreach ($matrix as $roleName => $perms) {
            Role::findOrCreate($roleName)->syncPermissions($perms);
        }
    }
}
