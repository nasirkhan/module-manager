<?php

namespace Nasirkhan\ModuleManager;

use Illuminate\Support\Facades\File;

class ModuleManager
{
    /**
     * Get all registered modules and their statuses.
     */
    public function getModules(): array
    {
        $statusFile = base_path('modules_statuses.json');

        if (! File::exists($statusFile)) {
            return [];
        }

        return json_decode(File::get($statusFile), true) ?? [];
    }

    /**
     * Get the names of all enabled modules.
     */
    public function getEnabledModules(): array
    {
        return array_keys(array_filter($this->getModules(), function ($status) {
            return $status === true || (is_array($status) && ($status['enabled'] ?? true) === true);
        }));
    }

    /**
     * Check if a specific module is enabled.
     */
    public function isEnabled(string $module): bool
    {
        $modules = $this->getModules();

        if (! isset($modules[$module])) {
            return false;
        }

        $status = $modules[$module];

        return $status === true || (is_array($status) && ($status['enabled'] ?? true) === true);
    }
}
