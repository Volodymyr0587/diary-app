<?php

use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;


new #[Title('Entries')] class extends Component {

    use WithPagination;

    public $date = null;
    public $search = null;

    #[Computed]
    public function entries()
    {
        return auth()->user()
            ->entries()
            ->filterByDate($this->date)
            ->search($this->search)
            ->latest()->paginate(4);
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
        <h1 class="text-2xl font-bold">My Entries</h1>
        <div class="space-x-2">
            @if ($this->date || $this->search)
                <flux:button wire:click="resetFilters">
                    Reset filters
                </flux:button>
            @endif

            <flux:button href="{{ route('entries.create') }}" wire:navigate variant="primary"
                icon:trailing="pencil-square">
                + New Entry
            </flux:button>
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
            <a href="{{ route('entries.show', $entry) }}" wire:navigate
                class="block border rounded-xl p-4 hover:bg-gray-50 hover:text-gray-600 dark:text-gray-50 transition">

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
                        {{ $entry->created_at->format('d M Y H:i:s') }}
                    </div>
                </div>

            </a>
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