<?php
namespace AddonPaymentsSDK\Config\Utils;

use Composer\InstalledVersions;

class Helpers {

    /**
     * Get the package version from extra in composer.json
     *
     * @param string $packageName The package name as defined in composer.json
     * @param string $defaultVersion Default version to return if package version cannot be determined
     * @return string The package version
     */
    public static function getPackageVersion(string $defaultVersion = '1.0.0'): string
    {
        $composerFile = __DIR__ . '/../../../composer.json';
        
        if (file_exists($composerFile)) {
            $composerData = json_decode(file_get_contents($composerFile), true);
            if (isset($composerData['extra']['sdk-version'])) {
                return $composerData['extra']['sdk-version'];
            }
        }
        
        return $defaultVersion;
    }

}