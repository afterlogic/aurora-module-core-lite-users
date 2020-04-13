<?php
namespace Aurora\Modules\CoreLiteUsers;

class Module extends \Aurora\System\Module\AbstractModule
{
	public function init()
	{
		$this->subscribeEvent('System::IsAllowedModule', array($this, 'onIsAllowedModule'), 10);
		$this->subscribeEvent('System::GetAllowedModulesName', array($this, 'onGetAllowedModulesName'), 10);

		\Aurora\Modules\Core\Classes\User::extend(
			self::GetName(),
			[
				'IsLite'	=> ['bool', false],
			]
		);
	}

	public function onIsAllowedModule($aArgs, &$mRes)
	{
		$oUser = \Aurora\System\Api::getAuthenticatedUser();

		if ($oUser instanceof \Aurora\Modules\Core\Classes\User && $oUser->{self::GetName() . '::IsLite'})
		{
			$aDisabledModulesForLiteUsers = $this->getConfig('DisabledModulesForLiteUsers', []);
			if (in_array($aArgs['ModuleName'], $aDisabledModulesForLiteUsers))
			{
				$mRes = false;
			}
		}
	}

	public function onGetAllowedModulesName($aArgs, &$mRes)
	{
		$oUser = \Aurora\System\Api::getAuthenticatedUser();

		if ($oUser instanceof \Aurora\Modules\Core\Classes\User && $oUser->{self::GetName() . '::IsLite'})
		{
			$aDisabledModulesForLiteUsers = $this->getConfig('DisabledModulesForLiteUsers', []);
			foreach ($mRes as $key => $sModuleName)
			{
				if (in_array($sModuleName, $aDisabledModulesForLiteUsers))
				{
					unset($mRes[$key]);
				}
			}
		}
	}

	public function GetPerUserSettings($UserId)
	{
		\Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::SuperAdmin);

		$oUser = \Aurora\Modules\Core\Module::Decorator()->GetUserUnchecked($UserId);
		if ($oUser)
		{
			return array(
				'IsLiteUser' => $oUser->{self::GetName() . '::IsLite'}
			);
		}

		return null;
	}

	public function UpdatePerUserSettings($UserId, $IsLiteUser)
	{
		$bResult = false;
		\Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::SuperAdmin);

		$oUser = \Aurora\Modules\Core\Module::Decorator()->GetUserUnchecked($UserId);

		if ($oUser)
		{
			$oUser->{self::GetName() . '::IsLite'} = $IsLiteUser;
			$bResult = \Aurora\Modules\Core\Module::Decorator()->UpdateUserObject($oUser);
		}

		return $bResult;
	}
}
