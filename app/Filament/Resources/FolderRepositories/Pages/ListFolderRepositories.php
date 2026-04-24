<?php

namespace App\Filament\Resources\FolderRepositories\Pages;

use App\Filament\Resources\FolderRepositories\FolderRepositoryResource;
use App\Models\DocRepository;
use App\Models\FolderRepository;
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

class ListFolderRepositories extends Page
{
    protected static string $resource = FolderRepositoryResource::class;

    #[Url(as: 'folder')]
    public ?int $folderId = null;

    public ?string $search = null;

    public function getTitle(): string
    {
        $folder = $this->currentFolder();

        return $folder ? $folder->folder_name : 'Docs Genéricos';
    }

    public function getSubheading(): ?string
    {
        $folder = $this->currentFolder();

        return $folder?->folder_url ?: null;
    }

    public function currentFolder(): ?FolderRepository
    {
        return $this->folderId ? FolderRepository::find($this->folderId) : null;
    }

    public function subfolders(): Collection
    {
        $query = FolderRepository::query()->withCount('docRepository')->orderBy('folder_name');

        if ($this->folderId === null) {
            $existingIds = FolderRepository::query()->pluck('id');
            $query->where(fn ($q) => $q->where('id_parent', 0)->orWhereNotIn('id_parent', $existingIds));
        } else {
            $query->where('id_parent', $this->folderId);
        }

        if ($this->search) {
            $query->where('folder_name', 'like', '%'.$this->search.'%');
        }

        return $query->get();
    }

    public function documents(): Collection
    {
        return DocRepository::query()
            ->where('folder_id', $this->folderId ?? 0)
            ->when($this->search, fn ($q) => $q->where('doc_name', 'like', '%'.$this->search.'%'))
            ->orderBy('doc_name')
            ->get();
    }

    /** @return array{icon: string, color: string} */
    public function iconFor(?string $filename): array
    {
        return FileIconResolver::resolve($filename);
    }

    public function fileSize(DocRepository $doc): ?string
    {
        if ($doc->size !== null) {
            return FileIconResolver::formatBytes((int) $doc->size);
        }

        return FileIconResolver::humanSize('public', $doc->doc_name);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(12)
                ->schema([
                    Section::make('Carpetas')
                        ->icon(Heroicon::OutlinedFolder)
                        ->iconColor('warning')
                        ->description(fn (): string => $this->folderCountLabel())
                        ->columnSpan(['default' => 12, 'sm' => 3])
                        ->schema($this->sidebarSchema()),

                    Section::make('Documentos')
                        ->icon(Heroicon::OutlinedDocumentDuplicate)
                        ->iconColor('gray')
                        ->description(fn (): string => $this->documentCountLabel())
                        ->columnSpan(['default' => 12, 'sm' => 9])
                        ->schema($this->mainSchema()),
                ]),
        ]);
    }

    /** @return array<Component> */
    protected function sidebarSchema(): array
    {
        return [
            TextEntry::make('rootLink')
                ->hiddenLabel()
                ->state('Repositorio')
                ->icon(Heroicon::OutlinedFolder)
                ->iconColor(fn (): string => $this->folderId === null ? 'warning' : 'gray')
                ->weight(FontWeight::Medium)
                ->url(fn (): string => static::getResource()::getUrl('index')),

            RepeatableEntry::make('sidebarFolders')
                ->hiddenLabel()
                ->visible(fn (): bool => $this->folderId !== null)
                ->state(fn (): Collection => $this->sidebarTreeNodes())
                ->schema([
                    TextEntry::make('folder_name')
                        ->hiddenLabel()
                        ->icon(Heroicon::OutlinedFolder)
                        ->iconColor(fn (?Model $record): string => $record?->is_active ? 'warning' : 'gray')
                        ->weight(fn (?Model $record): FontWeight => $record?->is_active ? FontWeight::SemiBold : FontWeight::Normal)
                        ->extraAttributes(fn (?Model $record): array => [
                            'style' => 'padding-left: '.((int) ($record?->depth ?? 0) * 16).'px;',
                        ])
                        ->url(fn (?Model $record): ?string => $record ? $this->folderUrl($record->id) : null),
                ]),
        ];
    }

    public function sidebarTreeNodes(): Collection
    {
        if ($this->folderId === null) {
            return collect();
        }

        /** @var Collection<int, FolderRepository> $all */
        $all = FolderRepository::query()
            ->get(['id', 'id_parent', 'folder_name'])
            ->keyBy('id');

        $existingIds = $all->keys()->all();

        $trail = [];
        $cursor = $all->get($this->folderId);
        while ($cursor) {
            array_unshift($trail, $cursor);
            $parentId = in_array((int) $cursor->id_parent, $existingIds, true) ? (int) $cursor->id_parent : 0;
            $cursor = $parentId ? $all->get($parentId) : null;
        }

        $out = collect();
        foreach ($trail as $depth => $folder) {
            $folder->setAttribute('depth', $depth + 1);
            $folder->setAttribute('is_active', $this->folderId === $folder->id);
            $out->push($folder);
        }

        return $out;
    }

    /** @return array<Component> */
    protected function mainSchema(): array
    {
        return [
            RepeatableEntry::make('folderRows')
                ->hiddenLabel()
                ->state(fn (): Collection => $this->subfolders())
                ->placeholder('Sin carpetas en esta ubicación.')
                ->table([
                    TableColumn::make('Nombre'),
                    TableColumn::make('Creado el')->width('180px'),
                    TableColumn::make('Acciones')->alignment(Alignment::End)->width('120px'),
                ])
                ->schema([
                    TextEntry::make('folder_name')
                        ->hiddenLabel()
                        ->icon(Heroicon::Folder)
                        ->iconColor('warning')
                        ->weight(FontWeight::Medium)
                        ->url(fn (Model $record): string => $this->folderUrl($record->id)),
                    TextEntry::make('created_at')
                        ->hiddenLabel()
                        ->icon(Heroicon::OutlinedCalendar)
                        ->iconColor('gray')
                        ->size(TextSize::Small)
                        ->date('d-m-Y'),
                    SchemaActions::make([
                        Action::make('editFolder')
                            ->iconButton()
                            ->icon(Heroicon::OutlinedPencilSquare)
                            ->color('warning')
                            ->fillForm(fn (Model $record): array => ['folder_name' => $record->folder_name])
                            ->schema([
                                TextInput::make('folder_name')->label('Nombre')->required()->maxLength(191),
                            ])
                            ->action(fn (array $data, Model $record) => $this->renameFolder($record->id, $data['folder_name'])),
                        Action::make('deleteFolder')
                            ->iconButton()
                            ->icon(Heroicon::OutlinedTrash)
                            ->color('danger')
                            ->requiresConfirmation()
                            ->modalHeading('Eliminar carpeta')
                            ->modalDescription(fn (Model $record): string => "¿Eliminar la carpeta '{$record->folder_name}' y todo su contenido?")
                            ->action(fn (Model $record) => $this->deleteFolder($record->id)),
                    ])->alignment(Alignment::End),
                ]),

            RepeatableEntry::make('documentRows')
                ->hiddenLabel()
                ->state(fn (): Collection => $this->documents())
                ->placeholder(fn (): string => $this->folderId ? 'Sin documentos en esta carpeta.' : 'Selecciona una carpeta para ver sus documentos.')
                ->table([
                    TableColumn::make('Nombre'),
                    TableColumn::make('Creado el')->width('180px'),
                    TableColumn::make('Acciones')->alignment(Alignment::End)->width('160px'),
                ])
                ->schema([
                    TextEntry::make('doc_name')
                        ->hiddenLabel()
                        ->icon(fn (Model $record): string => $this->iconFor($record->doc_name)['icon'])
                        ->formatStateUsing(fn (string $state): string => basename($state)),
                    TextEntry::make('created_at')
                        ->hiddenLabel()
                        ->icon(Heroicon::OutlinedCalendar)
                        ->iconColor('gray')
                        ->size(TextSize::Small)
                        ->date('d-m-Y'),
                    SchemaActions::make([
                        Action::make('downloadDocument')
                            ->iconButton()
                            ->icon(Heroicon::OutlinedArrowDownTray)
                            ->color('primary')
                            ->visible(fn (Model $record): bool => $this->downloadDocUrl($record) !== null)
                            ->url(fn (Model $record): ?string => $this->downloadDocUrl($record), shouldOpenInNewTab: true),
                        Action::make('deleteDocument')
                            ->iconButton()
                            ->icon(Heroicon::OutlinedTrash)
                            ->color('danger')
                            ->requiresConfirmation()
                            ->modalHeading('Eliminar documento')
                            ->modalDescription(fn (Model $record): string => "¿Eliminar '".basename((string) $record->doc_name)."'?")
                            ->action(fn (Model $record) => $this->deleteDocument($record->id)),
                    ])->alignment(Alignment::End),
                ]),
        ];
    }

    protected function folderUrl(?int $id): string
    {
        $base = static::getResource()::getUrl('index');

        return $id ? $base.'?folder='.$id : $base;
    }

    protected function parentFolderId(): ?int
    {
        $current = $this->currentFolder();
        if (! $current) {
            return null;
        }

        $parentId = (int) $current->id_parent;
        if ($parentId <= 0) {
            return null;
        }

        return FolderRepository::whereKey($parentId)->exists() ? $parentId : null;
    }

    public function renameFolder(int $id, string $name): void
    {
        FolderRepository::find($id)?->update(['folder_name' => $name]);
        Notification::make()->title('Carpeta actualizada')->success()->send();
    }

    public function getBreadcrumbs(): array
    {
        $crumbs = [static::getResource()::getUrl('index') => 'Raíz'];

        if ($this->folderId === null) {
            return $crumbs;
        }

        $map = FolderRepository::query()
            ->get(['id', 'id_parent', 'folder_name'])
            ->keyBy('id');

        $trail = [];
        $current = $map->get($this->folderId);
        while ($current) {
            array_unshift($trail, $current);
            $current = $current->id_parent ? $map->get($current->id_parent) : null;
        }

        array_pop($trail);

        $base = static::getResource()::getUrl('index');
        foreach ($trail as $crumb) {
            $crumbs[$base.'?folder='.$crumb->id] = $crumb->folder_name;
        }

        return $crumbs;
    }

    protected function folderCountLabel(): string
    {
        $n = $this->subfolders()->count();

        return $n.' '.($n === 1 ? 'carpeta' : 'carpetas');
    }

    protected function documentCountLabel(): string
    {
        $n = $this->documents()->count();

        return $n.' '.($n === 1 ? 'archivo' : 'archivos');
    }

    public function openFolder(int $id): void
    {
        $this->folderId = $id;
    }

    public function goHome(): void
    {
        $this->folderId = null;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Volver')
                ->icon(Heroicon::OutlinedArrowLeft)
                ->color('gray')
                ->visible(fn (): bool => $this->folderId !== null)
                ->url(fn (): string => $this->folderUrl($this->parentFolderId())),

            Action::make('createFolder')
                ->label('Nueva carpeta')
                ->icon('heroicon-o-folder-plus')
                ->schema([
                    TextInput::make('folder_name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(191),
                ])
                ->action(function (array $data): void {
                    $parent = $this->currentFolder();
                    $parentUrl = $parent ? $parent->folder_url : '';
                    $url = rtrim($parentUrl, '/').'/'.$data['folder_name'];

                    FolderRepository::create([
                        'folder_name' => $data['folder_name'],
                        'folder_url' => $url,
                        'id_parent' => $this->folderId ?? 0,
                    ]);

                    Notification::make()->title('Carpeta creada')->success()->send();
                }),

            Action::make('uploadDocument')
                ->label('Subir documento')
                ->icon('heroicon-o-arrow-up-tray')
                ->visible(fn (): bool => $this->folderId !== null)
                ->schema([
                    FileUpload::make('doc_name')
                        ->label('Archivo')
                        ->disk('public')
                        ->directory(fn (): string => 'uploads/'.ltrim($this->currentFolder()?->folder_url ?? '', '/'))
                        ->preserveFilenames()
                        ->required()
                        ->maxSize(51200),
                ])
                ->action(function (array $data): void {
                    $path = (string) $data['doc_name'];
                    $size = Storage::disk('public')->exists($path)
                        ? Storage::disk('public')->size($path)
                        : null;

                    DocRepository::create([
                        'doc_name' => $path,
                        'folder_id' => $this->folderId,
                        'size' => $size,
                    ]);

                    Notification::make()->title('Documento subido')->success()->send();
                }),
        ];
    }

    public function deleteFolder(int $id): void
    {
        FolderRepository::find($id)?->delete();
        Notification::make()->title('Carpeta eliminada')->success()->send();
    }

    public function deleteDocument(int $id): void
    {
        $doc = DocRepository::find($id);
        if (! $doc) {
            return;
        }

        if ($doc->doc_name) {
            Storage::disk('public')->delete($doc->doc_name);
        }

        $doc->delete();
        Notification::make()->title('Documento eliminado')->success()->send();
    }

    public function downloadDocUrl(DocRepository $doc): ?string
    {
        return $doc->doc_name && Storage::disk('public')->exists($doc->doc_name)
            ? Storage::disk('public')->url($doc->doc_name)
            : null;
    }
}
