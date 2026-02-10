<?php

namespace Nasirkhan\ModuleManager\Modules\Post\Models;

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Nasirkhan\ModuleManager\Modules\Post\Enums\PostStatus;
use Nasirkhan\ModuleManager\Modules\Post\Models\Presenters\PostPresenter;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Post extends BaseModel
{
    use HasFactory;
    use LogsActivity;
    use Notifiable;
    use PostPresenter;
    use SoftDeletes;

    protected $table = 'posts';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName($this->table);
    }

    public function category()
    {
        return $this->belongsTo('Nasirkhan\ModuleManager\Modules\Category\Models\Category');
    }

    public function tags()
    {
        return $this->morphToMany('Nasirkhan\ModuleManager\Modules\Tag\Models\Tag', 'taggable');
    }

    public function scopePublishedAndScheduled($query)
    {
        return $query->where('status', '=', PostStatus::Published->value);
    }

    /**
     * Get the list of Published Articles.
     *
     * @param [type] $query [description]
     * @return [type] [description]
     */
    public function scopePublished($query)
    {
        return $query->publishedAndScheduled()->where('published_at', '<=', Carbon::now());
    }

    public function scopeFeatured($query)
    {
        return $query->publishedAndScheduled()->where('is_featured', '=', 'Yes');
    }

    /**
     * Get the list of Recently Published Articles.
     *
     * @param [type] $query [description]
     * @return [type] [description]
     */
    public function scopeRecentlyPublished($query)
    {
        return $query->publishedAndScheduled()->orderBy('published_at', 'desc');
    }

    /**
     * Increment the hits/views count for the post.
     */
    public function incrementHits(): bool
    {
        return $this->increment('hits');
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Nasirkhan\ModuleManager\Modules\Post\database\factories\PostFactory::new();
    }
}
