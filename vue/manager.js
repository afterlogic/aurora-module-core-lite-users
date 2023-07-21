import store from 'src/store'

import CoreLiteUsersAdminSettingsPerUser from './components/CoreLiteUsersAdminSettingsPerUser'

export default {
  moduleName: 'CoreLiteUsers',

  requiredModules: [],

  init (appData) {
  },

  getAdminUserTabs () {
    const isUserSuperAdmin = store.getters['user/isUserSuperAdmin']
    if (isUserSuperAdmin) {
      return [
        {
          tabName: 'core-lite-users-user',
          tabTitle: 'CORELITEUSERS.LABEL_SETTINGS_TAB',
          tabRouteChildren: [
            { path: 'id/:id/core-lite-users-user', component: CoreLiteUsersAdminSettingsPerUser },
            { path: 'search/:search/id/:id/core-lite-users-user', component: CoreLiteUsersAdminSettingsPerUser },
            { path: 'page/:page/id/:id/core-lite-users-user', component: CoreLiteUsersAdminSettingsPerUser },
            { path: 'search/:search/page/:page/id/:id/core-lite-users-user', component: CoreLiteUsersAdminSettingsPerUser },
          ],
        }
      ]
    } else {
      return []
    }
  },
}
