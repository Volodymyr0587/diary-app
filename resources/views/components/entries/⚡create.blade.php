<?php

use App\Enums\Mood;
use App\Livewire\Forms\EntryForm;
use Flux\Flux;
use Livewire\Component;

new class extends Component {
    public EntryForm $form;

    public function save()
    {
        $this->form->save();

        Flux::toast(
            variant: 'success',
            text: __('Record created.')
        );

        return $this->redirectRoute('entries.index', navigate: true);
    }

};
?>

<div class="flex max-w-3xl mx-auto flex-1 flex-col gap-4 pt-6 rounded-xl">

    <h2>Write down your thoughts</h2>

    <form wire:submit.prevent="save" class="space-y-4">

        {{-- TITLE --}}
        <div>
            <flux:label>Title (optional)</flux:label>
            <input type="text" wire:model="form.title" placeholder="An unexpected pleasant meeting with a friend"
                class="w-full border rounded-lg p-2">
            @error('form.title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- CONTENT --}}
        <div>
            <flux:label>Content</flux:label>
            <textarea wire:model="form.content" rows="6" placeholder="Write your thoughts..."
                class="w-full border rounded-lg p-2"></textarea>
            @error('form.content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- MOOD --}}
        <div>
            <flux:label>Mood</flux:label>
            <flux:select wire:model="form.mood">
                <flux:select.option>Select mood</flux:select.option>
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
            <flux:button :href="route('entries.index')">
                Cancel
            </flux:button>
            <flux:button type="submit" variant="primary">
                Create
            </flux:button>
        </div>
    </form>
</div>