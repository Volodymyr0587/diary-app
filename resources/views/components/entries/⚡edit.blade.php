<?php

use App\Models\Entry;
use App\Enums\Mood;
use Flux\Flux;
use Livewire\Component;

new class extends Component {


    public Entry $entry;

    public $title;
    public $content;
    public $mood;

    public function mount(Entry $entry)
    {
        $this->entry = $entry;

        // preload
        $this->title = $entry->title;
        $this->content = $entry->content;
        $this->mood = $entry->mood?->value;
    }

    public function update()
    {
        $validated = $this->validate([
            'title' => 'nullable|min:3',
            'content' => 'required|min:3',
            'mood' => ['nullable', 'in:' . collect(Mood::cases())->pluck('value')->join(',')],
        ]);

        $this->entry->update($validated);

        Flux::toast(variant: 'success', text: __('Record updated.'));

        return $this->redirectRoute('entries.show', $this->entry, navigate: true);
    }

};
?>

<div class="flex max-w-3xl mx-auto flex-1 flex-col gap-4">

    <h2>Edit entry</h2>

    <form wire:submit.prevent="update" class="space-y-4">

        {{-- TITLE --}}
        <div>
            <flux:label>Title</flux:label>
            <input type="text" wire:model="title" class="w-full border rounded-lg p-2">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- CONTENT --}}
        <div>
            <flux:label>Content</flux:label>
            <textarea wire:model="content" rows="6" class="w-full border rounded-lg p-2"></textarea>
            @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- MOOD --}}
        <div>
            <flux:label>Mood</flux:label>
            <flux:select wire:model="mood">
                <flux:select.option value="">Select mood</flux:select.option>
                @foreach(Mood::cases() as $m)
                    <flux:select.option value="{{ $m->value }}">
                        {{ $m->label() }}
                    </flux:select.option>
                @endforeach
            </flux:select>
            @error('mood') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- BUTTON --}}
        <div class="flex gap-2">
            <flux:button type="submit">
                Update
            </flux:button>

            <flux:button href="{{ route('entries.show', $entry) }}" wire:navigate variant="ghost">Cancel</flux:button>
        </div>

    </form>
</div>