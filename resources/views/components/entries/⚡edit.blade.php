<?php

use App\Enums\Mood;
use App\Livewire\Forms\EntryForm;
use App\Models\Entry;
use Flux\Flux;
use Livewire\Component;

new class extends Component {

    public EntryForm $form;
    public Entry $entry;

    public function mount(Entry $entry)
    {
        $this->authorize('workWith', $entry);

        $this->entry = $entry;

        $this->form->setEntry($entry);
    }

    public function update()
    {
        $this->authorize('workWith', $this->entry);

        $this->form->save();

        Flux::toast(
            variant: 'success',
            text: __('Record updated.')
        );

        return $this->redirectRoute(
            'entries.show',
            $this->entry,
            navigate: true
        );
    }

    public function render()
    {
        $title = $this->entry->title ?: 'No title';

        return $this->view()
            ->title('Edit: ' . $title);
    }
};
?>

<div class="flex max-w-3xl mx-auto flex-1 flex-col gap-4 pt-6 rounded-xl">

    <h2>Edit entry</h2>

    <form wire:submit.prevent="update" class="space-y-4">

        {{-- TITLE --}}
        <div>
            <flux:label>Title</flux:label>
            <input type="text" wire:model="form.title" class="w-full border rounded-lg p-2">
            @error('form.title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- CONTENT --}}
        <div>
            <flux:label>Content</flux:label>
            <textarea wire:model="form.content" rows="6" class="w-full border rounded-lg p-2"></textarea>
            @error('form.content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- MOOD --}}
        <div>
            <flux:label>Mood</flux:label>
            <flux:select wire:model="form.mood">
                <flux:select.option value="">Select mood</flux:select.option>
                @foreach(Mood::cases() as $m)
                    <flux:select.option value="{{ $m->value }}">
                        {{ $m->label() }}
                    </flux:select.option>
                @endforeach
            </flux:select>
            @error('form.mood') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- BUTTON --}}
        <div class="flex justify-end gap-x-2">
            <flux:button href="{{ route('entries.show', $entry) }}" wire:navigate>Cancel</flux:button>
            <flux:button type="submit" variant="primary">
                Update
            </flux:button>
        </div>

    </form>
</div>