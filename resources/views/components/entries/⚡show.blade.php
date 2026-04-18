<?php

use App\Models\Entry;
use Livewire\Component;
use Flux\Flux;

new class extends Component {
    public Entry $entry;

    public function mount(Entry $entry)
    {
        $this->entry = $entry;
    }

    public function delete()
    {
        $this->entry->delete();

        Flux::toast(variant: 'success', text: __('Record deleted.'));

        return $this->redirectRoute('entries.index', navigate: true);
    }

};
?>

<div class="max-w-3xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">
            {{ $entry->title ?: 'No title' }}
        </h1>

        <div class="flex gap-2">
            <flux:button wire:navigate href="{{ route('entries.edit', $entry) }}">Edit</flux:button>
            <!-- <form method="POST" wire:submit.prevent="delete">
                <flux:button type="submit" variant="danger">Delete</flux:button>
            </form> -->

            <flux:modal.trigger name="delete-entry">
                <flux:button variant="danger">Delete</flux:button>
            </flux:modal.trigger>

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
        </div>
    </div>

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

    {{-- DATE --}}
    <div class="text-sm text-gray-400">
        {{ $entry->created_at->format('d M Y H:i') }}
    </div>

</div>