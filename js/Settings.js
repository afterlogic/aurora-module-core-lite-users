'use strict';

var
	_ = require('underscore'),

	Types = require('%PathToCoreWebclientModule%/js/utils/Types.js')
;

module.exports = {
	ServerModuleName: '%ModuleName%',
	HashModuleName: 'core-lite-users',

	EnableModuleForUser: false,


	/**
	 * Initializes settings from AppData object sections.
	 *
	 * @param {Object} oAppData Object contained modules settings.
	 */
	init: function (oAppData)
	{
		var
			oAppDataSection = oAppData['%ModuleName%']
		;

		if (!_.isEmpty(oAppDataSection))
		{
			this.EnableModuleForUser = Types.pBool(oAppDataSection.EnableModuleForUser, this.EnableModuleForUser);
		}
	},

	updateAdmin: function (sEnableModule, sEnableForNewUsers, sServer, sLinkToManual)
	{
		this.EnableForNewUsers = sEnableForNewUsers;
	}
};
