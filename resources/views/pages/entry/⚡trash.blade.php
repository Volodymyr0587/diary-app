<?php

use App\Models\Entry;
use Flux\Flux;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;


new #[Title('Trashed Entries')] class extends Component {

    use WithPagination;

    public $date = null;
    public $search = null;

    #[Computed]
    public function entries()
    {
        return auth()->user()
            ->entries()
            ->onlyTrashed()
            ->filterByDeletedAt($this->date)
            ->searchDeleted($this->search)
            ->latest()->paginate(4);
    }

    public function restore($id)
    {
        $entry = Entry::withTrashed()->findOrFail($id);

        $this->authorize('workWith', $entry);

        $entry->restore();
    }

    public function forceDelete($id)
    {
        $entry = Entry::withTrashed()->findOrFail($id);

        $this->authorize('workWith', $entry);

        $entry->forceDelete();

        Flux::toast(variant: 'success', text: __('Record is permanently deleted'));

        // return $this->redirectRoute('entries.index', navigate: true);
    }

    public function forceDeleteAll()
    {
        $this->authorize('forceDeleteAll', Entry::class);

        $entries = Entry::onlyTrashed()
            ->where('user_id', auth()->id());

        if (!$entries->exists()) {
            Flux::toast(
                variant: 'info',
                text: __('Trash is already empty.')
            );

            return;
        }

        $entries->forceDelete();

        Flux::toast(variant: 'success', text: __('All records that were in the trash have been permanently deleted.'));
    }

    public function updatingDate(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['date', 'search']);
        $this->resetPage();
    }

    protected $queryString = [
        'date' => ['except' => ''],
        'search' => ['except' => ''],
    ];
};
?>

<div class="flex max-w-3xl mx-auto flex-1 flex-col gap-4 pt-6 rounded-xl">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Trashed Entries</h1>
        <div class="space-x-2">
            @if ($this->date || $this->search)
                <flux:button wire:click="resetFilters">
                    Reset filters
                </flux:button>
            @endif

            <flux:modal.trigger name="empty-trash">
                <flux:button variant="danger">Empty Trash</flux:button>
            </flux:modal.trigger>

            {{-- MODAL --}}
            <flux:modal name="empty-trash" class="min-w-[22rem]">
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">Empty trash?</flux:heading>

                        <flux:text class="mt-2">
                            You're about to delete all entries.<br>
                            This action cannot be reversed.
                        </flux:text>
                    </div>

                    <div class="flex gap-2">
                        <flux:spacer />

                        <flux:modal.close>
                            <flux:button variant="ghost">Cancel</flux:button>
                        </flux:modal.close>

                        <flux:button wire:click="forceDeleteAll" variant="danger">
                            Empty Trash
                        </flux:button>
                    </div>
                </div>
            </flux:modal>
        </div>
    </div>

    <flux:separator />

    {{-- Search and Filter by date--}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center gap-x-2">
            <flux:icon.magnifying-glass />
            <flux:separator vertical />
            <flux:input type="text" wire:model.live.debounce.400ms="search" placeholder="Search entries..." />
        </div>
        <div class="flex items-center gap-x-2">
            <flux:icon.calendar-days />
            <flux:separator vertical />
            <flux:input type="date" wire:model.live="date" max="2999-12-31" />
        </div>
    </div>

    {{-- LIST --}}
    <div class="space-y-4">

        @forelse($this->entries as $entry)
            <div class="block border rounded-xl p-4 dark:text-gray-50 transition">

                {{-- TITLE --}}
                <div class="font-semibold text-lg">
                    {{ $entry->title ?: 'No title' }}
                </div>

                {{-- CONTENT PREVIEW --}}
                <div class="text-sm mt-1">
                    {{ Str::limit($entry->content, 120) }}
                </div>

                {{-- FOOTER --}}
                <div class="flex justify-between items-center mt-3 text-sm text-gray-400">

                    {{-- MOOD --}}
                    <div>
                        @if($entry->mood)
                            {{ $entry->mood->label() }}
                        @endif
                    </div>

                    {{-- DATE --}}
                    <div>
                        {{ $entry->deleted_at->format('d M Y H:i:s') }}
                    </div>
                </div>

                <flux:button wire:click="restore({{ $entry->id }})">
                    Restore
                </flux:button>

                <flux:modal.trigger name="delete-forever-entry">
                    <flux:button variant="danger">Delete forever</flux:button>
                </flux:modal.trigger>

                {{-- MODAL --}}
                <flux:modal name="delete-forever-entry" class="min-w-[22rem]">
                    <div class="space-y-6">
                        <div>
                            <flux:heading size="lg">Delete entry forever?</flux:heading>

                            <flux:text class="mt-2">
                                You're about to delete this entry.<br>
                                This action cannot be reversed.
                            </flux:text>
                        </div>

                        <div class="flex gap-2">
                            <flux:spacer />

                            <flux:modal.close>
                                <flux:button variant="ghost">Cancel</flux:button>
                            </flux:modal.close>

                            <form method="POST" wire:submit.prevent="forceDelete({{ $entry->id }})">
                                <flux:button type="submit" variant="danger">Delete</flux:button>
                            </form>
                        </div>
                    </div>
                </flux:modal>

            </div>
        @empty
            <div class="text-center text-gray-500 py-10">
                No entries yet 😔
            </div>
        @endforelse

    </div>

    {{-- PAGINATION --}}
    <div>
        <flux:pagination :paginator="$this->entries" />
    </div>

</div>