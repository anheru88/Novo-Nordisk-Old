<?php

namespace App\Filament\Resources\FolderRepositories\Pages;

use App\Filament\Resources\FolderRepositories\FolderRepositoryResource;
use App\Models\DocRepository;
use App\Models\FolderRepository;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;

class ListFolderRepositories extends Page
{
    protected static string $resource = FolderRepositoryResource::class;

    protected string $view = 'filament.resources.folder-repositories.pages.explorer';

    #[Url(as: 'folder')]
    public ?int $folderId = null;

    public function getTitle(): string
    {
        $folder = $this->currentFolder();

        return $folder ? $folder->folder_name : 'Repositorio de documentos';
    }

    public function currentFolder(): ?FolderRepository
    {
        return $this->folderId ? FolderRepository::find($this->folderId) : null;
    }

    public function subfolders(): Collection
    {
        $query = FolderRepository::query()->withCount('docRepository')->orderBy('folder_name');

        if ($this->folderId === null) {
            $existingIds = FolderRepository::query()->pluck('id_folder');
            $query->where(fn ($q) => $q->where('id_parent', 0)->orWhereNotIn('id_parent', $existingIds));
        } else {
            $query->where('id_parent', $this->folderId);
        }

        return $query->get();
    }

    public function documents(): Collection
    {
        return DocRepository::query()
            ->where('id_folder', $this->folderId ?? 0)
            ->orderBy('doc_name')
            ->get();
    }

    public function breadcrumbsPath(): array
    {
        $trail = [];
        $folder = $this->currentFolder();
        while ($folder) {
            array_unshift($trail, $folder);
            $folder = $folder->id_parent ? FolderRepository::find($folder->id_parent) : null;
        }

        return $trail;
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
                    DocRepository::create([
                        'doc_name' => $data['doc_name'],
                        'id_folder' => $this->folderId,
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
