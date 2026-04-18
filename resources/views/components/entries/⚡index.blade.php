<?php

use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;


new class extends Component {

    use WithPagination;

    #[Computed]
    public function entries()
    {
        return auth()->user()->entries()->latest()->paginate(4);
    }
};
?>

<div class="max-w-3xl mx-auto p-6 space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">My Entries</h1>

        <flux:button href="{{ route('entries.create') }}" icon:trailing="pencil-square">
            + New Entry
        </flux:button>
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