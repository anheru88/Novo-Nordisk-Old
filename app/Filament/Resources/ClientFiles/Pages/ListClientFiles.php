<?php

namespace App\Filament\Resources\ClientFiles\Pages;

use App\Filament\Resources\ClientFiles\ClientFileResource;
use App\Models\Client;
use App\Models\ClientFile;
use App\Support\FileIconResolver;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
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

        return $client ? $client->client_name : 'Docs de Clientes';
    }

    public function currentClient(): ?Client
    {
        return $this->clientId ? Client::find($this->clientId) : null;
    }

    public function clients(): Collection
    {
        return Client::query()
            ->when($this->search, fn ($q) => $q->where('client_name', 'like', '%'.$this->search.'%'))
            ->withCount('clientsFiles')
            ->orderBy('client_name')
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
            Grid::make(12)
                ->schema([
                    Section::make('Clientes')
                        ->icon(Heroicon::OutlinedBuildingOffice2)
                        ->iconColor('info')
                        ->description(fn (): string => $this->clientCountLabel())
                        ->columnSpan(['default' => 12, 'sm' => 3])
                        ->schema($this->sidebarSchema()),

                    Section::make(fn (): string => $this->clientId === null ? 'Clientes' : 'Documentos')
                        ->icon(Heroicon::OutlinedDocumentDuplicate)
                        ->iconColor('gray')
                        ->description(fn (): string => $this->fileCountLabel())
                        ->columnSpan(['default' => 12, 'sm' => 9])
                        ->schema($this->mainSchema()),
                ]),
        ]);
    }

    /** @return array<Component> */
    protected function sidebarSchema(): array
    {
        return [
            TextEntry::make('clientsRoot')
                ->hiddenLabel()
                ->state('Clientes')
                ->icon(Heroicon::OutlinedBuildingOffice2)
                ->weight(FontWeight::Medium)
                ->url(fn (): string => static::getResource()::getUrl('index')),

            RepeatableEntry::make('sidebarClients')
                ->hiddenLabel()
                ->state(fn (): Collection => $this->clients())
                ->placeholder('Sin clientes.')
                ->schema([
                    TextEntry::make('client_name')
                        ->hiddenLabel()
                        ->icon(Heroicon::OutlinedBuildingOffice2)
                        ->iconColor('info')
                        ->url(fn (Model $record): string => $this->clientUrl($record->id)),
                ]),
        ];
    }

    /** @return array<Component> */
    protected function mainSchema(): array
    {
        if ($this->clientId === null) {
            return [
                RepeatableEntry::make('clientRows')
                    ->hiddenLabel()
                    ->state(fn (): Collection => $this->clients())
                    ->placeholder('No hay clientes registrados.')
                    ->table([
                        TableColumn::make('Cliente'),
                        TableColumn::make('NIT')->width('180px'),
                        TableColumn::make('Archivos')->alignment(Alignment::End)->width('120px'),
                    ])
                    ->schema([
                        TextEntry::make('client_name')
                            ->hiddenLabel()
                            ->icon(Heroicon::OutlinedBuildingOffice2)
                            ->iconColor('info')
                            ->weight(FontWeight::Medium)
                            ->url(fn (Model $record): string => $this->clientUrl($record->id)),
                        TextEntry::make('client_nit')
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
            Grid::make(['default' => 1, 'sm' => 2, 'lg' => 4])
                ->schema([
                    TextEntry::make('nit')->label('NIT')
                        ->state(fn (): ?string => $this->currentClient()?->client_nit)
                        ->placeholder('—'),
                    TextEntry::make('contact')->label('Contacto')
                        ->state(fn (): ?string => $this->currentClient()?->client_contact)
                        ->placeholder('—'),
                    TextEntry::make('phone')->label('Teléfono')
                        ->state(fn (): ?string => $this->currentClient()?->client_phone)
                        ->placeholder('—'),
                    TextEntry::make('email')->label('Email')
                        ->state(fn (): ?string => $this->currentClient()?->client_email)
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
                    TextEntry::make('file_name')
                        ->hiddenLabel()
                        ->icon(fn (Model $record): string => $this->iconFor($record->file_name)['icon']),
                    TextEntry::make('created_at')
                        ->hiddenLabel()
                        ->icon(Heroicon::OutlinedCalendar)
                        ->iconColor('gray')
                        ->size(TextSize::Small)
                        ->date('d-m-Y'),
                    SchemaActions::make([
                        Action::make('downloadFile')
                            ->iconButton()
                            ->icon(Heroicon::OutlinedArrowDownTray)
                            ->color('primary')
                            ->visible(fn (Model $record): bool => $this->downloadUrl($record) !== null)
                            ->url(fn (Model $record): ?string => $this->downloadUrl($record), shouldOpenInNewTab: true),
                        Action::make('deleteFile')
                            ->iconButton()
                            ->icon(Heroicon::OutlinedTrash)
                            ->color('danger')
                            ->requiresConfirmation()
                            ->modalHeading('Eliminar documento')
                            ->modalDescription(fn (Model $record): string => "¿Eliminar '{$record->file_name}'?")
                            ->action(fn (Model $record) => $this->deleteFile($record->id)),
                    ])->alignment(Alignment::End),
                ]),
        ];
    }

    protected function clientUrl(int $id): string
    {
        return static::getResource()::getUrl('index').'?client='.$id;
    }

    public function getBreadcrumbs(): array
    {
        return [static::getResource()::getUrl('index') => 'Clientes'];
    }

    protected function clientCountLabel(): string
    {
        $n = $this->clients()->count();

        return $n.' '.($n === 1 ? 'cliente' : 'clientes');
    }

    protected function fileCountLabel(): string
    {
        $n = $this->files()->count();

        return $n.' '.($n === 1 ? 'archivo' : 'archivos');
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
                ->visible(fn (): bool => $this->clientId !== null)
                ->url(fn (): string => static::getResource()::getUrl('index')),

            Action::make('uploadFile')
                ->label('Subir documento')
                ->icon('heroicon-o-arrow-up-tray')
                ->visible(fn (): bool => $this->clientId !== null)
                ->schema([
                    FileUpload::make('file_name')
                        ->label('Archivo')
                        ->disk('public')
                        ->directory(fn (): string => 'uploads/'.$this->clientFolder())
                        ->preserveFilenames()
                        ->required()
                        ->maxSize(51200),
                ])
                ->action(function (array $data): void {
                    $path = (string) $data['file_name'];
                    $size = Storage::disk('public')->exists($path)
                        ? Storage::disk('public')->size($path)
                        : null;

                    ClientFile::create([
                        'client_id' => $this->clientId,
                        'file_folder' => $this->clientFolder(),
                        'file_name' => basename($path),
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

        $path = trim((string) $file->file_folder, '/').'/'.$file->file_name;
        if ($file->file_name && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $file->delete();
        Notification::make()->title('Documento eliminado')->success()->send();
    }

    public function downloadUrl(ClientFile $file): ?string
    {
        $path = $this->filePath($file);

        return $file->file_name && Storage::disk('public')->exists($path)
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
        return trim((string) $file->file_folder, '/').'/'.$file->file_name;
    }
}
