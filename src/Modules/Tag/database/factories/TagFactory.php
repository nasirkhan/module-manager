<?php

namespace Nasirkhan\ModuleManager\Modules\Tag\database\factories;

use App\Models\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Nasirkhan\ModuleManager\Modules\Tag\Enums\TagStatus;
use Nasirkhan\ModuleManager\Modules\Tag\Models\Tag;

/**
 * @extends Factory<Model>
 */
class TagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'slug' => '',
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(TagStatus::cases()),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
