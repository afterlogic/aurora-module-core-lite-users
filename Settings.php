<?php
/**
 * This code is licensed under AGPLv3 license or Afterlogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\CoreLiteUsers;

use Aurora\System\SettingsProperty;

/**
 * @property bool $Disabled
 * @property array $DisabledModulesForLiteUsers
 */

class Settings extends \Aurora\System\Module\Settings
{
    protected function initDefaults()
    {
        $this->aContainer = [
            "Disabled" => new SettingsProperty(
                false,
                "bool",
                null,
                "Setting to true disables the module",
            ),
            "DisabledModulesForLiteUsers" => new SettingsProperty(
                [
                    "Calendar", "Files", "CoreMobileWebclient"
                ],
                "array",
                null,
                "Defines a list of modules that will be disabled for the lite users",
            ),
        ];
    }
}
