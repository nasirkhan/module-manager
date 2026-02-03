<?php

namespace Nasirkhan\ModuleManager\Services;

use Illuminate\Support\Facades\File;

class ModuleVersion
{
    /**
     * Get version information for a module.
     */
    public function getVersion(string $moduleName): ?string
    {
        $moduleData = $this->getModuleData($moduleName);

        return $moduleData['version'] ?? null;
    }

    /**
     * Get all module data from module.json.
     */
    public function getModuleData(string $moduleName): array
    {
        $modulePath = $this->getModulePath($moduleName);
        $jsonPath = $modulePath.'/module.json';

        if (! File::exists($jsonPath)) {
            return [];
        }

        $content = File::get($jsonPath);
        $data = json_decode($content, true);

        return $data ?: [];
    }

    /**
     * Get all modules with their versions.
     */
    public function getAllVersions(): array
    {
        $modules = ['Post', 'Category', 'Tag', 'Menu'];
        $versions = [];

        foreach ($modules as $module) {
            $data = $this->getModuleData($module);
            $versions[$module] = [
                'version' => $data['version'] ?? 'unknown',
                'description' => $data['description'] ?? '',
                'keywords' => $data['keywords'] ?? [],
                'priority' => $data['priority'] ?? 0,
                'requires' => $data['requires'] ?? [],
            ];
        }

        return $versions;
    }

    /**
     * Check if module version matches.
     */
    public function versionMatches(string $moduleName, string $version): bool
    {
        $currentVersion = $this->getVersion($moduleName);

        if (! $currentVersion) {
            return false;
        }

        return version_compare($currentVersion, $version, '=');
    }

    /**
     * Check if module version is greater than or equal to required version.
     */
    public function versionSatisfies(string $moduleName, string $requiredVersion): bool
    {
        $currentVersion = $this->getVersion($moduleName);

        if (! $currentVersion) {
            return false;
        }

        return version_compare($currentVersion, $requiredVersion, '>=');
    }

    /**
     * Get module dependencies.
     */
    public function getDependencies(string $moduleName): array
    {
        $moduleData = $this->getModuleData($moduleName);

        return $moduleData['requires'] ?? [];
    }

    /**
     * Check if all dependencies are satisfied.
     */
    public function dependenciesSatisfied(string $moduleName): array
    {
        $dependencies = $this->getDependencies($moduleName);
        $satisfied = [];
        $missing = [];

        foreach ($dependencies as $dependency) {
            $depVersion = $this->getVersion($dependency);

            if ($depVersion) {
                $satisfied[] = [
                    'name' => $dependency,
                    'version' => $depVersion,
                    'satisfied' => true,
                ];
            } else {
                $missing[] = [
                    'name' => $dependency,
                    'version' => null,
                    'satisfied' => false,
                ];
            }
        }

        return [
            'satisfied' => $satisfied,
            'missing' => $missing,
            'all_satisfied' => empty($missing),
        ];
    }

    /**
     * Get modules sorted by priority (higher priority first).
     */
    public function getModulesByPriority(): array
    {
        $versions = $this->getAllVersions();

        uasort($versions, function ($a, $b) {
            return $b['priority'] <=> $a['priority'];
        });

        return $versions;
    }

    /**
     * Get module path.
     */
    protected function getModulePath(string $moduleName): string
    {
        return __DIR__.'/../Modules/'.$moduleName;
    }

    /**
     * Compare two versions.
     *
     * @return int Returns -1 if $version1 < $version2, 0 if equal, 1 if $version1 > $version2
     */
    public function compareVersions(string $version1, string $version2): int
    {
        return version_compare($version1, $version2);
    }

    /**
     * Get module changelog if it exists.
     */
    public function getChangelog(string $moduleName): ?string
    {
        $modulePath = $this->getModulePath($moduleName);
        $changelogPath = $modulePath.'/CHANGELOG.md';

        if (! File::exists($changelogPath)) {
            return null;
        }

        return File::get($changelogPath);
    }
}
