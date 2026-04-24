<?php

namespace App\Providers\Filament;

use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->navigationGroups([
                'Operaciones',
                'Configuración',
                'Datos del sistema',
                'Usuarios',
                'Informes',
                'Documentos',
            ])
            ->navigationItems($this->placeholderNavigationItems())
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentDeveloperLoginsPlugin::make()
                    ->enabled(app()->environment('local'))
                    ->users([
                        'Admin' => 'ajimenezescobar@gmail.com',
                    ]),
                AuthDesignerPlugin::make()
                    ->login(fn ($config) => $config
                        ->media($this->randomLoginBackground())
                        ->mediaPosition(MediaPosition::Right)
                        ->mediaSize('50%')
                    )
                    ->themeToggle(),
            ]);
    }

    protected function randomLoginBackground(): ?string
    {
        $dir = public_path('images/auth-backgrounds');
        $files = is_dir($dir) ? glob($dir.'/*.{jpg,jpeg,png,webp,gif,svg,avif,mp4,webm,mov,ogg}', GLOB_BRACE) : [];

        if (empty($files)) {
            return null;
        }

        return asset('images/auth-backgrounds/'.basename($files[array_rand($files)]));
    }

    /**
     * @return array<int, NavigationItem>
     */
    protected function placeholderNavigationItems(): array
    {
        // Tuple shape: [label, group, icon, permission, sort, done, blocked]
        $items = [
            // Operaciones
            ['Cotizaciones',               'Operaciones',        Heroicon::OutlinedDocumentText,       'quotations.index',   10, false, false],
            ['Negociaciones',              'Operaciones',        Heroicon::OutlinedBriefcase,          'negotiations.index', 20, false, false],
            ['Simulador ARP',              'Operaciones',        Heroicon::OutlinedCalculator,         'arp.simulations',    30, false, false],

            // Configuración
            ['Clientes',                   'Configuración',      Heroicon::OutlinedBuildingOffice2,    'clients.index',      10, false, false],
            ['Precios',                    'Configuración',      Heroicon::OutlinedBanknotes,          'prices.index',       30, false, false],
            ['Escalas',                    'Configuración',      Heroicon::OutlinedChartBar,           null,                 40, false, false],
            ['Formatos de documentos',     'Configuración',      Heroicon::OutlinedDocumentDuplicate,  null,                 50, false, true],
            ['ARP',                        'Configuración',      Heroicon::OutlinedCube,               'arp.index',          60, false, true],

            // Informes
            ['Reportes',                   'Informes',           Heroicon::OutlinedChartPie,           'reportes.index',     10, false, false],
            ['Notas',                      'Informes',           Heroicon::OutlinedDocumentMinus,      'reportes.creditnotes', 20, false, false],
            ['SAP',                        'Informes',           Heroicon::OutlinedArrowDownTray,      'reportes.export',    30, false, false],
        ];

        return array_map(function (array $i) {
            $label = match (true) {
                $i[6] ?? false => new HtmlString('<span style="color:#dc2626;">'.e($i[0]).' ✗</span>'),
                $i[5] => $i[0].' ✓',
                default => $i[0],
            };

            return NavigationItem::make($label)
                ->group($i[1])
                ->icon($i[2])
                ->url(fn () => '#')
                ->visible(fn () => $i[3] === null || auth()->user()?->can($i[3]))
                ->sort($i[4]);
        }, $items);
    }
}
