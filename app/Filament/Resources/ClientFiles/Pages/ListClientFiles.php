<?php

namespace App\Filament\Resources\ClientFiles\Pages;

use App\Filament\Resources\ClientFiles\ClientFileResource;
use App\Models\Client;
use App\Models\ClientFile;
use App\Support\FileIconResolver;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Actions as SchemaActions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;

class ListClientFiles extends Page
{
    protected static string $resource = ClientFileResource::class;

    #[Url(as: 'client')]
    public ?int $clientId = null;

    public ?string $search = null;

    public function getTitle(): string
    {
        $client = $this->currentClient();

        return $client ? $client->name : 'Docs de Clientes';
    }

    public function currentClient(): ?Client
    {
        return $this->clientId ? Client::find($this->clientId) : null;
    }

    public function clients(): Collection
    {
        return Client::query()
            ->when($this->search, fn ($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', '%'.$this->search.'%')
                ->orWhere('nit', 'like', '%'.$this->search.'%')))
            ->withCount('clientsFiles')
            ->orderBy('name')
            ->get();
    }

    public function files(): Collection
    {
        if (! $this->clientId) {
            return collect();
        }

        return ClientFile::query()
            ->where('client_id', $this->clientId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(fn (): string => $this->clientId === null ? 'Clientes' : 'Documentos')
                ->icon(Heroicon::OutlinedDocumentDuplicate)
                ->iconColor('gray')
                ->description(fn (): string => $this->clientId === null
                    ? $this->clientsSummaryLabel()
                    : $this->fileCountLabel())
                ->schema($this->mainSchema()),
        ]);
    }

    /** @return array<Component> */
    protected function mainSchema(): array
    {
        if ($this->clientId === null) {
            return [
                TextInput::make('search')
                    ->hiddenLabel()
                    ->placeholder('Buscar por cliente o NIT...')
                    ->prefixIcon(Heroicon::OutlinedMagnifyingGlass)
                    ->live(debounce: 300),

                RepeatableEntry::make('clientRows')
                    ->hiddenLabel()
                    ->state(fn (): Collection => $this->clients())
                    ->placeholder(fn (): string => $this->search
                        ? 'Sin coincidencias para "'.$this->search.'".'
                        : 'No hay clientes registrados.')
                    ->table([
                        TableColumn::make('Cliente'),
                        TableColumn::make('NIT')->width('180px'),
                        TableColumn::make('Archivos')->alignment(Alignment::End)->width('120px'),
                    ])
                    ->schema([
                        TextEntry::make('name')
                            ->hiddenLabel()
                            ->icon(Heroicon::OutlinedBuildingOffice2)
                            ->iconColor('info')
                            ->weight(FontWeight::Medium)
                            ->url(fn (Model $record): string => $this->clientUrl($record->id)),
                        TextEntry::make('nit')
                            ->hiddenLabel()
                            ->placeholder('—')
                            ->size(TextSize::Small),
                        TextEntry::make('clients_files_count')
                            ->hiddenLabel()
                            ->badge()
                            ->color('gray')
                            ->icon(Heroicon::OutlinedDocument)
                            ->formatStateUsing(fn ($state): string => (int) $state.' '.((int) $state === 1 ? 'archivo' : 'archivos')),
                    ]),
            ];
        }

        return [
            TextEntry::make('sectionBreadcrumb')
                ->hiddenLabel()
                ->state(fn (): string => $this->sectionBreadcrumbHtml())
                ->visible(fn (): bool => $this->clientId !== null)
                ->html(),

            Grid::make(['default' => 1, 'sm' => 2, 'lg' => 4])
                ->schema([
                    TextEntry::make('nit')->label('NIT')
                        ->state(fn (): ?string => $this->currentClient()?->nit)
                        ->placeholder('—'),
                    TextEntry::make('contact')->label('Contacto')
                        ->state(fn (): ?string => $this->currentClient()?->contact)
                        ->placeholder('—'),
                    TextEntry::make('phone')->label('Teléfono')
                        ->state(fn (): ?string => $this->currentClient()?->phone)
                        ->placeholder('—'),
                    TextEntry::make('email')->label('Email')
                        ->state(fn (): ?string => $this->currentClient()?->email)
                        ->placeholder('—'),
                ]),

            RepeatableEntry::make('fileRows')
                ->hiddenLabel()
                ->state(fn (): Collection => $this->files())
                ->placeholder('No hay documentos para este cliente.')
                ->table([
                    TableColumn::make('Nombre'),
                    TableColumn::make('Creado el')->width('180px'),
                    TableColumn::make('Acciones')->alignment(Alignment::End)->width('160px'),
                ])
                ->schema([
                    TextEntry::make('name')
                        ->hiddenLabel()
                        ->icon(fn (Model $record): string|Heroicon => $this->downloadUrl($record) !== null
                            ? $this->iconFor($record->name)['icon']
                            : Heroicon::OutlinedExclamationTriangle)
                        ->iconColor(fn (Model $record): string => $this->downloadUrl($record) !== null ? 'gray' : 'danger')
                        ->color(fn (Model $record): ?string => $this->downloadUrl($record) !== null ? null : 'danger')
                        ->tooltip(fn (Model $record): ?string => $this->downloadUrl($record) !== null ? null : 'Archivo no encontrado en el almacenamiento')
                        ->url(fn (Model $record): ?string => $this->downloadUrl($record), shouldOpenInNewTab: true),
                    TextEntry::make('created_at')
                        ->hiddenLabel()
                        ->icon(Heroicon::OutlinedCalendar)
                        ->iconColor('gray')
                        ->size(TextSize::Small)
                        ->date('d-m-Y'),
                    SchemaActions::make([
                        Action::make('deleteFile')
                            ->iconButton()
                            ->icon(Heroicon::OutlinedTrash)
                            ->color('danger')
                            ->requiresConfirmation()
                            ->modalHeading('Eliminar documento')
                            ->modalDescription(fn (Model $record): string => "¿Eliminar '{$record->name}'?")
                            ->action(fn (Model $record) => $this->deleteFile($record->id)),
                    ])->alignment(Alignment::End),
                ]),
        ];
    }

    protected function sectionBreadcrumbHtml(): string
    {
        if ($this->clientId === null) {
            return '';
        }

        $base = static::getResource()::getUrl('index');
        $parts = ['<a href="'.e($base).'" class="fi-link fi-link-color-primary">Clientes</a>'];

        if ($client = $this->currentClient()) {
            $parts[] = '<span class="fi-color-gray">'.e($client->name).'</span>';
        }

        return '<nav class="fi-breadcrumbs"><ol class="fi-breadcrumbs-list">'
            .implode('<li class="fi-breadcrumbs-separator" aria-hidden="true">/</li>', array_map(
                fn (string $p): string => '<li class="fi-breadcrumbs-item">'.$p.'</li>',
                $parts
            ))
            .'</ol></nav>';
    }

    protected function clientUrl(int $id): string
    {
        return static::getResource()::getUrl('index').'?client='.$id;
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function fileCountLabel(): string
    {
        $n = $this->files()->count();

        return $n.' '.($n === 1 ? 'archivo' : 'archivos');
    }

    protected function clientsSummaryLabel(): string
    {
        $clients = $this->clients();
        $c = $clients->count();
        $f = (int) $clients->sum('clients_files_count');

        return $c.' '.($c === 1 ? 'cliente' : 'clientes').' · '.$f.' '.($f === 1 ? 'archivo' : 'archivos');
    }

    public function openClient(int $id): void
    {
        $this->clientId = $id;
    }

    public function goHome(): void
    {
        $this->clientId = null;
        $this->search = null;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Volver')
                ->icon(Heroicon::OutlinedArrowLeft)
                ->color('gray')
                ->url(fn (): string => $this->clientId !== null
                    ? static::getResource()::getUrl('index')
                    : route('filament.admin.pages.documentos')),

            Action::make('uploadFile')
                ->label('Subir documento')
                ->icon('heroicon-o-arrow-up-tray')
                ->visible(fn (): bool => $this->clientId !== null)
                ->schema([
                    FileUpload::make('name')
                        ->label('Archivo')
                        ->disk('public')
                        ->directory(fn (): string => 'uploads/'.$this->clientFolder())
                        ->preserveFilenames()
                        ->required()
                        ->maxSize(51200),
                ])
                ->action(function (array $data): void {
                    $path = (string) $data['name'];
                    $size = Storage::disk('public')->exists($path)
                        ? Storage::disk('public')->size($path)
                        : null;

                    ClientFile::create([
                        'client_id' => $this->clientId,
                        'folder' => dirname($path),
                        'name' => basename($path),
                        'size' => $size,
                    ]);

                    Notification::make()->title('Documento subido')->success()->send();
                }),
        ];
    }

    protected function clientFolder(): string
    {
        return 'clientes/'.$this->clientId;
    }

    public function deleteFile(int $id): void
    {
        $file = ClientFile::find($id);
        if (! $file) {
            return;
        }

        $path = trim((string) $file->folder, '/').'/'.$file->name;
        if ($file->name && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $file->delete();
        Notification::make()->title('Documento eliminado')->success()->send();
    }

    public function downloadUrl(ClientFile $file): ?string
    {
        $path = $this->filePath($file);

        return $file->name && Storage::disk('public')->exists($path)
            ? Storage::disk('public')->url($path)
            : null;
    }

    public function fileSize(ClientFile $file): ?string
    {
        if ($file->size !== null) {
            return FileIconResolver::formatBytes((int) $file->size);
        }

        return FileIconResolver::humanSize('public', $this->filePath($file));
    }

    /** @return array{icon: string, color: string} */
    public function iconFor(?string $filename): array
    {
        return FileIconResolver::resolve($filename);
    }

    protected function filePath(ClientFile $file): string
    {
        return trim((string) $file->folder, '/').'/'.$file->name;
    }
}
