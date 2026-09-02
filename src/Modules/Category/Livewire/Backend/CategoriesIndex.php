<?php

namespace Nasirkhan\ModuleManager\Modules\Category\Livewire\Backend;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Title;
use Nasirkhan\LaravelCube\Livewire\LwTable;
use Nasirkhan\ModuleManager\Modules\Category\Models\Category;

#[Title('Categories')]
class CategoriesIndex extends LwTable
{
    public string $sortCol = 'name';

    public string $sortDir = 'asc';

    protected function baseQuery(): Builder
    {
        return Category::query()
            ->when(
                $this->search,
                fn (Builder $q) => $q->where('name', 'like', '%'.$this->search.'%')
            )
            ->orderBy($this->sortCol, $this->sortDir);
    }

    public function render()
    {
        return view('category::livewire.backend.categories-index', [
            'categories' => $this->rows(),
        ]);
    }
}
