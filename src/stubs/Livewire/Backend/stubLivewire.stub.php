<?php

namespace {{namespace}}\{{moduleName}}\Livewire\Backend;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Title;
use Nasirkhan\LaravelCube\Livewire\LwTable;
use {{namespace}}\{{moduleName}}\Models\{{moduleName}};

#[Title('{{moduleNamePlural}}')]
class {{moduleNamePlural}}Index extends LwTable
{
    public string $sortCol = 'name';

    public string $sortDir = 'asc';

    protected function baseQuery(): Builder
    {
        return {{moduleName}}::query()
            ->when(
                $this->search,
                fn (Builder $q) => $q->where('name', 'like', '%'.$this->search.'%')
            )
            ->orderBy($this->sortCol, $this->sortDir);
    }

    public function render()
    {
        return view('{{moduleNameLower}}::livewire.backend.{{moduleNameLowerPlural}}-index', [
            '{{moduleNameLowerPlural}}' => $this->rows(),
        ]);
    }
}
