<?php

namespace Nasirkhan\ModuleManager\Modules\Settings\Tests\Feature;

use App\Models\User;
use Nasirkhan\ModuleManager\Modules\Settings\Models\Setting;
use Tests\TestCase;

class SettingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $user = User::whereId(1)->first();
        $this->actingAs($user);
    }

    /**
     * Settings Test.
     */
    public function test_super_admin_user_can_view_settings_index(): void
    {
        $response = $this->get('/admin/settings');

        $response->assertStatus(200);
    }

    public function test_super_admin_user_can_update_settings(): void
    {
        $fields_data = [];

        foreach (config('settings.setting_fields') as $section => $fields) {
            foreach ($fields['elements'] as $field) {
                $name = $field['name'];
                $value = $field['value'];

                $fields_data[$name] = $value;
            }
        }

        $fields_data['app_name'] = 'Awesome Laravel Starter';

        $response = $this->postJson(route('backend.settings.store'), $fields_data);

        $response->assertStatus(302);
    }

    public function test_except_super_admin_user_can_not_update_settings(): void
    {
        $user = User::whereId(5)->first();

        $this->actingAs($user);

        $fields_data = [];

        foreach (config('settings.setting_fields') as $section => $fields) {
            foreach ($fields['elements'] as $field) {
                $name = $field['name'];
                $value = $field['value'];

                $fields_data[$name] = $value;
            }
        }

        $response = $this->postJson(route('backend.settings.store'), $fields_data);

        $response->assertStatus(403);
    }

    public function test_setting_model_can_add_settings(): void
    {
        $key = 'test_setting_key';
        $value = 'test_setting_value';
        $type = 'string';

        $result = Setting::add($key, $value, $type);

        $this->assertEquals($value, $result);
        $this->assertTrue(Setting::has($key));
    }

    public function test_setting_model_can_get_settings(): void
    {
        $key = 'test_get_key';
        $value = 'test_get_value';

        Setting::add($key, $value);

        $retrievedValue = Setting::get($key);

        $this->assertEquals($value, $retrievedValue);
    }

    public function test_setting_model_can_set_settings(): void
    {
        $key = 'test_set_key';
        $initialValue = 'initial_value';
        $newValue = 'new_value';

        Setting::add($key, $initialValue);
        Setting::set($key, $newValue);

        $retrievedValue = Setting::get($key);

        $this->assertEquals($newValue, $retrievedValue);
    }

    public function test_setting_model_can_remove_settings(): void
    {
        $key = 'test_remove_key';
        $value = 'test_remove_value';

        Setting::add($key, $value);
        $this->assertTrue(Setting::has($key));

        Setting::remove($key);
        $this->assertFalse(Setting::has($key));
    }
}
