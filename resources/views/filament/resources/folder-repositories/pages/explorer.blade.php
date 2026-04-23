<x-filament-panels::page>
    @php
        $trail = $this->breadcrumbsPath();
        $subfolders = $this->subfolders();
        $documents = $this->documents();
        $current = $this->currentFolder();
    @endphp

    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-1 text-sm text-gray-600 dark:text-gray-300">
        <button type="button" wire:click="goHome" class="flex items-center gap-1 hover:text-primary-600 dark:hover:text-primary-400">
            <x-filament::icon icon="heroicon-o-home" class="h-4 w-4" />
            <span>Raíz</span>
        </button>

        @foreach ($trail as $crumb)
            <span class="text-gray-400">/</span>
            @if ($loop->last)
                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $crumb->folder_name }}</span>
            @else
                <button type="button" wire:click="openFolder({{ $crumb->id_folder }})" class="hover:text-primary-600 dark:hover:text-primary-400">
                    {{ $crumb->folder_name }}
                </button>
            @endif
        @endforeach
    </nav>

    {{-- Folders section --}}
    <x-filament::section>
        <x-slot name="heading">Carpetas</x-slot>
        <x-slot name="description">
            {{ $subfolders->count() }} {{ $subfolders->count() === 1 ? 'subcarpeta' : 'subcarpetas' }}
        </x-slot>

        @if ($subfolders->isEmpty())
            <div class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                No hay carpetas en esta ubicación.
            </div>
        @else
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                @foreach ($subfolders as $folder)
                    <div class="group relative flex flex-col rounded-lg border border-gray-200 bg-white p-4 transition hover:border-primary-500 hover:shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:hover:border-primary-400">
                        <button type="button" wire:click="openFolder({{ $folder->id_folder }})" class="flex flex-col items-start gap-2 text-left">
                            <x-filament::icon icon="heroicon-o-folder" class="h-10 w-10 text-amber-500" />
                            <span class="line-clamp-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $folder->folder_name }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $folder->doc_repository_count }} {{ $folder->doc_repository_count === 1 ? 'documento' : 'documentos' }}
                            </span>
                        </button>

                        <button
                            type="button"
                            wire:click="deleteFolder({{ $folder->id_folder }})"
                            wire:confirm="¿Eliminar la carpeta '{{ $folder->folder_name }}' y todo su contenido?"
                            class="absolute right-2 top-2 hidden rounded p-1 text-gray-400 hover:bg-red-50 hover:text-red-600 group-hover:block dark:hover:bg-red-900/30"
                            title="Eliminar carpeta"
                        >
                            <x-filament::icon icon="heroicon-o-trash" class="h-4 w-4" />
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>

    {{-- Documents section --}}
    <x-filament::section>
        <x-slot name="heading">Documentos</x-slot>
        <x-slot name="description">
            {{ $documents->count() }} {{ $documents->count() === 1 ? 'archivo' : 'archivos' }}
        </x-slot>

        @if ($documents->isEmpty())
            <div class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                @if ($current)
                    No hay documentos en esta carpeta.
                @else
                    Selecciona una carpeta para ver sus documentos.
                @endif
            </div>
        @else
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($documents as $doc)
                    @php $url = $this->downloadDocUrl($doc); @endphp
                    <li class="flex items-center justify-between gap-3 py-3">
                        <div class="flex min-w-0 items-center gap-3">
                            <x-filament::icon icon="heroicon-o-document" class="h-6 w-6 shrink-0 text-gray-400" />
                            <div class="min-w-0">
                                <div class="truncate text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ basename($doc->doc_name) }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $doc->created_at?->format('Y-m-d H:i') }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if ($url)
                                <a href="{{ $url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 rounded-md border border-gray-200 px-2.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800">
                                    <x-filament::icon icon="heroicon-o-arrow-down-tray" class="h-4 w-4" />
                                    Descargar
                                </a>
                            @else
                                <span class="text-xs italic text-gray-400">Archivo no disponible</span>
                            @endif
                            <button
                                type="button"
                                wire:click="deleteDocument({{ $doc->id_doc }})"
                                wire:confirm="¿Eliminar '{{ basename($doc->doc_name) }}'?"
                                class="rounded p-1 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/30"
                                title="Eliminar"
                            >
                                <x-filament::icon icon="heroicon-o-trash" class="h-4 w-4" />
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-filament::section>
</x-filament-panels::page>
