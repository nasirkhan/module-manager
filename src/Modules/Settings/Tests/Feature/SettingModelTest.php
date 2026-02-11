<?php

namespace Nasirkhan\ModuleManager\Modules\Settings\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Nasirkhan\ModuleManager\Modules\Settings\Models\Setting;
use Tests\TestCase;

class SettingModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_add_setting(): void
    {
        $result = Setting::add('test_key', 'test_value', 'string');

        $this->assertEquals('test_value', $result);
        $this->assertTrue(Setting::has('test_key'));
    }

    public function test_can_get_setting(): void
    {
        Setting::add('app_name', 'Test App', 'string');

        $value = Setting::get('app_name');

        $this->assertEquals('Test App', $value);
    }

    public function test_can_update_setting(): void
    {
        Setting::add('test_key', 'old_value', 'string');
        Setting::set('test_key', 'new_value', 'string');

        $value = Setting::get('test_key');

        $this->assertEquals('new_value', $value);
    }

    public function test_can_remove_setting(): void
    {
        Setting::add('test_key', 'test_value', 'string');
        $this->assertTrue(Setting::has('test_key'));

        $result = Setting::remove('test_key');

        // Clear cache to ensure fresh check
        Setting::flushCache();
        
        $this->assertNotFalse($result);
        $this->assertFalse(Setting::has('test_key'));
    }

    public function test_returns_default_value_when_setting_not_found(): void
    {
        $value = Setting::get('non_existent_key', 'default_value');

        $this->assertEquals('default_value', $value);
    }
}
