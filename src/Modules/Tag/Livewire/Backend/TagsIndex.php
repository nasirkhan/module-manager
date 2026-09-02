<?php

namespace Nasirkhan\ModuleManager\Modules\Tag\Livewire\Backend;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Title;
use Nasirkhan\LaravelCube\Livewire\LwTable;
use Nasirkhan\ModuleManager\Modules\Tag\Models\Tag;

#[Title('Tags')]
class TagsIndex extends LwTable
{
    public string $sortCol = 'name';

    public string $sortDir = 'asc';

    protected function baseQuery(): Builder
    {
        return Tag::query()
            ->when(
                $this->search,
                fn (Builder $q) => $q->where('name', 'like', '%'.$this->search.'%')
            )
            ->orderBy($this->sortCol, $this->sortDir);
    }

    public function render()
    {
        return view('tag::livewire.backend.tags-index', [
            'tags' => $this->rows(),
        ]);
    }
}
