<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ClientFiles\ClientFileResource;
use App\Filament\Resources\FolderRepositories\FolderRepositoryResource;
use App\Models\Client;
use App\Models\ClientFile;
use App\Models\DocRepository;
use App\Models\FolderRepository;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class DocumentsIndex extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderOpen;

    protected static string|UnitEnum|null $navigationGroup = 'Documentos';

    protected static ?string $navigationLabel = 'Documentos';

    protected static ?int $navigationSort = 10;

    protected static ?string $slug = 'documentos';

    protected static ?string $title = 'Documentos';

    public static function getNavigationBadge(): ?string
    {
        return (string) (DocRepository::count() + ClientFile::count());
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                Section::make('Documentos generales')
                    ->description('Repositorio general de carpetas y archivos compartidos para toda la organización.')
                    ->icon(Heroicon::OutlinedFolderOpen)
                    ->iconColor('warning')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('folders_count')
                                ->label('Carpetas')
                                ->state(FolderRepository::count())
                                ->size(TextSize::Large)
                                ->weight(FontWeight::Bold)
                                ->color('warning'),
                            TextEntry::make('docs_count')
                                ->label('Archivos')
                                ->state(DocRepository::count())
                                ->size(TextSize::Large)
                                ->weight(FontWeight::Bold)
                                ->color('warning'),
                        ]),
                    ])
                    ->footerActions([
                        Action::make('open_generic')
                            ->label('Abrir repositorio')
                            ->icon(Heroicon::ArrowRight)
                            ->iconPosition(IconPosition::After)
                            ->color('warning')
                            ->url(fn (): string => FolderRepositoryResource::getUrl('index')),
                    ]),

                Section::make('Clientes')
                    ->description('Documentos y fichas asociadas a cada cliente.')
                    ->icon(Heroicon::OutlinedBuildingOffice2)
                    ->iconColor('info')
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('clients_count')
                                ->label('Clientes')
                                ->state(Client::whereHas('clientsFiles')->count())
                                ->size(TextSize::Large)
                                ->weight(FontWeight::Bold)
                                ->color('info'),
                            TextEntry::make('files_count')
                                ->label('Archivos')
                                ->state(ClientFile::count())
                                ->size(TextSize::Large)
                                ->weight(FontWeight::Bold)
                                ->color('info'),
                        ]),
                    ])
                    ->footerActions([
                        Action::make('open_clients')
                            ->label('Abrir clientes')
                            ->icon(Heroicon::ArrowRight)
                            ->iconPosition(IconPosition::After)
                            ->color('info')
                            ->url(fn (): string => ClientFileResource::getUrl('index')),
                    ]),
            ]),
        ]);
    }
}
