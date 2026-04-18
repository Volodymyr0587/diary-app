<?php

use App\Enums\Mood;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component {
    public $title = null;
    public $content = '';
    public $mood = null;

    public function save()
    {
        $validated = $this->validate([
            'title' => 'nullable|min:3',
            'content' => 'required|min:3',
            'mood' => ['nullable', 'in:' . collect(Mood::cases())->pluck('value')->join(',')],
        ]);

        $user = Auth::user();

        $user->entries()->create($validated);

        $this->reset(['title', 'content', 'mood']);

        Flux::toast(variant: 'success', text: __('Record created.'));

        return $this->redirectRoute('entries.index', navigate: true);
    }

};
?>

<div class="flex max-w-3xl mx-auto flex-1 flex-col gap-4 rounded-xl">

    <h2>Write down your thoughts</h2>

    <form wire:submit.prevent="save" class="space-y-4">

        {{-- TITLE --}}
        <div>
            <flux:label>Title (optional)</flux:label>
            <input type="text" wire:model="title" placeholder="An unexpected pleasant meeting with a friend"
                class="w-full border rounded-lg p-2">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- CONTENT --}}
        <div>
            <flux:label>Content</flux:label>
            <textarea wire:model="content" rows="6" placeholder="Write your thoughts..."
                class="w-full border rounded-lg p-2"></textarea>
            @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- MOOD --}}
        <div>
            <flux:label>Mood</flux:label>
            <flux:select wire:model="mood">
                <flux:select.option>Select mood</flux:select.option>
                @foreach(Mood::cases() as $m)
                    <flux:select.option value="{{ $m->value }}">
                        {{ $m->label() }}
                    </flux:select.option>
                @endforeach
            </flux:select>
            @error('mood') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- BUTTON --}}
        <div>
            <flux:button type="submit">
                Create
            </flux:button>
        </div>
    </form>
</div>