<?php

namespace Nasirkhan\ModuleManager\Modules\Category\database\factories;

use App\Models\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Nasirkhan\ModuleManager\Modules\Category\Enums\CategoryStatus;
use Nasirkhan\ModuleManager\Modules\Category\Models\Category;

/**
 * @extends Factory<Model>
 */
class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->unique()->words(3, true);

        return [
            'name' => $name,
            'slug' => '',
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(CategoryStatus::cases()),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
