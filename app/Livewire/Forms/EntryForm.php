<?php

namespace App\Livewire\Forms;

use App\Enums\Mood;
use App\Models\Entry;
use Illuminate\Support\Facades\Auth;
use Livewire\Form;

class EntryForm extends Form
{
    public ?Entry $entry = null;
    public $title = null;
    public $content = '';
    public $mood = null;

    public function rules()
    {
        return [
            'title' => 'nullable|min:3|max:255',
            'content' => 'required|min:3',
            'mood' => ['nullable', 'in:' . collect(Mood::cases())->pluck('value')->join(',')],
        ];
    }

    public function setEntry(Entry $entry)
    {
        $this->entry = $entry;

        $this->title = $entry->title;
        $this->content = $entry->content;
        $this->mood = $entry->mood?->value;
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->entry) {
            $this->entry->update($validated);
        } else {
            $this->entry = Auth::user()
                ->entries()
                ->create($validated);
        }
    }
}