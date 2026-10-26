<?php

namespace Nasirkhan\ModuleManager\Modules\Post\Livewire\Backend;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Title;
use Nasirkhan\LaravelCube\Livewire\LwTable;
use Nasirkhan\ModuleManager\Modules\Post\Models\Post;

#[Title('Posts')]
class PostsIndex extends LwTable
{
    public string $sortCol = 'updated_at';

    public string $sortDir = 'desc';

    protected function baseQuery(): Builder
    {
        return Post::query()
            ->when(
                $this->search,
                fn (Builder $q) => $q->where(
                    fn (Builder $inner) => $inner
                        ->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('slug', 'like', '%'.$this->search.'%')
                )
            )
            ->orderBy($this->sortCol, $this->sortDir);
    }

    public function render()
    {
        return view('post::livewire.backend.posts-index', [
            'posts' => $this->rows(),
        ]);
    }
}
