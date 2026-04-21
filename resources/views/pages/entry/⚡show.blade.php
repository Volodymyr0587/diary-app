<?php

use App\Models\Entry;
use Livewire\Component;
use Flux\Flux;

new class extends Component {
    public Entry $entry;

    public function mount(Entry $entry)
    {
        $this->authorize('workWith', $this->entry);

        $this->entry = $entry;
    }

    public function delete()
    {
        $this->authorize('workWith', $this->entry);

        $this->entry->delete();

        Flux::toast(variant: 'success', text: __('Record deleted.'));

        return $this->redirectRoute('entries.index', navigate: true);
    }

    public function render()
    {
        $title = $this->entry->title ? $this->entry->title : 'No title';
        return $this->view()
            ->title($title);
    }

};
?>

<div class="flex max-w-3xl mx-auto flex-1 flex-col gap-4 pt-6 rounded-xl">
    {{-- HEADER ACTIONS --}}
    <div class="flex items-center justify-between">
        {{-- Back --}}
        <flux:button variant="filled" icon="arrow-uturn-left" wire:navigate href="{{ route('entries.index') }}">
            back to all entries
        </flux:button>
        {{-- Actions --}}
        <div class="flex flex-wrap gap-2">
            <flux:button wire:navigate href="{{ route('entries.edit', $entry) }}" variant="primary">
                Edit
            </flux:button>

            <flux:modal.trigger name="delete-entry">
                <flux:button variant="danger">Delete</flux:button>
            </flux:modal.trigger>
        </div>
    </div>
    {{-- DATE --}}
    <div class="text-sm text-gray-400">
        {{ $entry->created_at->format('d M Y H:i') }}
    </div>
    {{-- TITLE --}}
    <h1 class="text-2xl font-bold wrap-break-words">
        {{ $entry->title ?: 'No title' }}
    </h1>
    {{-- MODAL --}}
    <flux:modal name="delete-entry" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete entry?</flux:heading>

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

                <form method="POST" wire:submit.prevent="delete">
                    <flux:button type="submit" variant="danger">Delete</flux:button>
                </form>
            </div>
        </div>
    </flux:modal>
    {{-- MOOD --}}
    @if($entry->mood)
        <div class="text-sm text-gray-500">
            {{ $entry->mood->label() }}
        </div>
    @endif
    {{-- CONTENT --}}
    <div class="whitespace-pre-line text-lg">
        {{ $entry->content }}
    </div>
</div>