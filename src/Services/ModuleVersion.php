<?php

namespace Nasirkhan\ModuleManager\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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

        if (! is_array($data)) {
            Log::warning("module-manager: malformed module.json for [{$moduleName}] — ".json_last_error_msg());

            return [];
        }

        if (! isset($data['version'])) {
            Log::warning("module-manager: module.json for [{$moduleName}] is missing the required 'version' field.");
        }

        return $data;
    }

    /**
     * Get all modules with their versions.
     *
     * Merges published modules (Modules/) with vendor modules (src/Modules/),
     * giving published modules precedence when the same module name appears in both.
     * Only directories that contain a module.json file are included.
     */
    public function getAllVersions(): array
    {
        $paths = [
            __DIR__.'/../Modules',
            base_path('Modules'),
        ];

        $discovered = [];
        foreach ($paths as $path) {
            if (! File::exists($path)) {
                continue;
            }

            foreach (File::directories($path) as $directory) {
                $moduleName = basename($directory);
                if (File::exists($directory.'/module.json')) {
                    // Published path (second) overrides vendor path (first)
                    $discovered[$moduleName] = true;
                }
            }
        }

        $allModules = array_keys($discovered);
        $versions = [];

        foreach ($allModules as $module) {
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
     *
     * Returns a map with:
     *   - satisfied  – dependencies that are installed
     *   - missing    – dependencies that are not installed
     *   - circular   – dependencies that form a cycle back to $moduleName
     *   - all_satisfied – true only when missing and circular are both empty
     */
    public function dependenciesSatisfied(string $moduleName): array
    {
        $dependencies = $this->getDependencies($moduleName);
        $satisfied = [];
        $missing = [];
        $circular = [];

        foreach ($dependencies as $dependency) {
            $chain = $this->findCircularChain($dependency, $moduleName, [$moduleName, $dependency]);

            if ($chain !== null) {
                Log::warning("module-manager: circular dependency detected: {$chain}");
                $circular[] = [
                    'name' => $dependency,
                    'chain' => $chain,
                ];

                continue;
            }

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
            'circular' => $circular,
            'all_satisfied' => empty($missing) && empty($circular),
        ];
    }

    /**
     * Recursively walk the dependency graph to check whether $target appears
     * among the transitive dependencies of $current, indicating a cycle.
     *
     * @param  array<string>  $chain  Traversal path so far (used for reporting)
     * @return string|null Human-readable cycle chain (e.g. "A → B → A"), or null if no cycle
     */
    protected function findCircularChain(string $current, string $target, array $chain): ?string
    {
        foreach ($this->getDependencies($current) as $dep) {
            $newChain = [...$chain, $dep];

            if ($dep === $target) {
                return implode(' → ', $newChain);
            }

            // $dep is already in the traversal path but is not the target —
            // it forms a different cycle; skip to avoid infinite recursion.
            if (in_array($dep, $chain)) {
                continue;
            }

            $result = $this->findCircularChain($dep, $target, $newChain);

            if ($result !== null) {
                return $result;
            }
        }

        return null;
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
     *
     * Prefers the published module path (Modules/{module}) over the
     * vendor package path so that customized modules are resolved first.
     */
    protected function getModulePath(string $moduleName): string
    {
        $publishedPath = base_path('Modules/'.$moduleName);

        if (File::exists($publishedPath)) {
            return $publishedPath;
        }

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
