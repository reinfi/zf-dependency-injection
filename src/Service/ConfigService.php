<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service;

use Reinfi\DependencyInjection\Exception\ConfigPathNotFoundException;

/**
 * @package Reinfi\DependencyInjection\Service
 */
class ConfigService
{
    public function __construct(
        private readonly array $config
    ) {
    }

    /**
     * @return mixed|null
     * @throws ConfigPathNotFoundException
     */
    public function resolve(string $configPath)
    {
        $nullAllowed = true;

        if (substr($configPath, -1) === '!') {
            $nullAllowed = false;
            $configPath = substr($configPath, 0, -1);
        }

        $configParts = explode('.', $configPath);

        return $this->resolveConfigPath($this->config, $configParts, $nullAllowed);
    }

    /**
     * @return mixed|null
     * @throws ConfigPathNotFoundException
     */
    private function resolveConfigPath(array $config, array $configParts, bool $nullAllowed)
    {
        $currentKey = array_shift($configParts);

        if (! is_string($currentKey)) {
            throw new ConfigPathNotFoundException('invalid key');
        }

        if (! array_key_exists($currentKey, $config)) {
            if ($nullAllowed) {
                return null;
            }

            throw new ConfigPathNotFoundException($currentKey);
        }

        if (count($configParts) === 0) {
            return $config[$currentKey];
        }

        $subConfig = $config[$currentKey];
        if (! is_array($subConfig)) {
            if ($nullAllowed) {
                return null;
            }
            throw new ConfigPathNotFoundException($currentKey);
        }

        return $this->resolveConfigPath($subConfig, $configParts, $nullAllowed);
    }
}
