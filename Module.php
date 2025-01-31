<?php
/**
 * This code is licensed under AGPLv3 license or Afterlogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\CoreLiteUsers;

/**
 * Allows to mark users as a lite-ones and disable modules for them.
 *
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 * @license https://afterlogic.com/products/common-licensing Afterlogic Software License
 * @copyright Copyright (c) 2023, Afterlogic Corp.
 *
 * @property Settings $oModuleSettings
 *
 * @package Modules
 */
class Module extends \Aurora\System\Module\AbstractModule
{
    public function init()
    {
        $this->subscribeEvent('System::IsAllowedModule', array($this, 'onIsAllowedModule'), 10);
        $this->subscribeEvent('System::GetAllowedModulesName', array($this, 'onGetAllowedModulesName'), 10);
    }

    /**
     * @return Module
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @return Module
     */
    public static function Decorator()
    {
        return parent::Decorator();
    }

    /**
     * @return Settings
     */
    public function getModuleSettings()
    {
        return $this->oModuleSettings;
    }

    public function onIsAllowedModule($aArgs, &$mResult)
    {
        $oUser = \Aurora\System\Api::getAuthenticatedUser();

        if ($oUser instanceof \Aurora\Modules\Core\Models\User && $oUser->getExtendedProp(self::GetName() . '::IsLite')) {
            if (in_array($aArgs['ModuleName'], $this->oModuleSettings->DisabledModulesForLiteUsers)) {
                $mResult = false;
            }
        }
    }

    public function onGetAllowedModulesName($aArgs, &$mRes)
    {
        $oUser = \Aurora\System\Api::getAuthenticatedUser();

        if ($oUser instanceof \Aurora\Modules\Core\Models\User && $oUser->getExtendedProp(self::GetName() . '::IsLite')) {
            $aDisabledModulesForLiteUsers = $this->oModuleSettings->DisabledModulesForLiteUsers;
            foreach ($mRes as $key => $sModuleName) {
                if (in_array($sModuleName, $aDisabledModulesForLiteUsers)) {
                    unset($mRes[$key]);
                }
            }
        }
    }

    public function GetPerUserSettings($UserId)
    {
        \Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::SuperAdmin);

        $oUser = \Aurora\Api::getUserById($UserId);
        if ($oUser) {
            return array(
                'IsLiteUser' => $oUser->getExtendedProp(self::GetName() . '::IsLite')
            );
        }

        return null;
    }

    public function UpdatePerUserSettings($UserId, $IsLiteUser)
    {
        $bResult = false;
        \Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::SuperAdmin);

        $oUser = \Aurora\Api::getUserById($UserId);

        if ($oUser) {
            $oUser->setExtendedProp(self::GetName() . '::IsLite', $IsLiteUser);
            $bResult = \Aurora\Modules\Core\Module::Decorator()->UpdateUserObject($oUser);
        }

        return $bResult;
    }
}
