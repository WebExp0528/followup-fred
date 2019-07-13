import Base from "core/base.js"
import {
    composePanel
} from "features/compose_panel.js"
import {
    mainMenu
} from "features/main_menu.js"
import {
    router
} from "features/router.js"
import {
    threadRow
} from "features/threadrow.js";
import {
    createDOM
} from "../utils/helper";

/**
 * Define Features functions
 * @type { class }
 */

export class Features {
    constructor() {

        InboxSDK.load(1, "sdk_Gmail-Extension_290e96f7ea").then((sdk) => this.loadInboxSDK(sdk))
        console.log("Loaded Inbox SDK", InboxSDK)
    }

    /**
     * Load functions in InboxSDK
     * @param {object} sdk
     * @returns {void}
     */
    loadInboxSDK(sdk) {

        Base._iSDK = sdk
        Base.checkAuth().then(() => this.successAuth(), () => this.failedAuth())

    }


    successAuth() {

        console.log("Authenticated!!!")
        //Add main Menu in Gamil
        mainMenu()

        //Register new Router
        Base._iSDK.Router.handleAllRoutes((routerView) => {
            router(routerView)
        });

        //Register ThreadRows
        Base._iSDK.Lists.registerThreadRowViewHandler((threadRowView) => {
            threadRow(threadRowView)
        })

        // Register New composeviews
        Base._iSDK.Compose.registerComposeViewHandler((composeView) => {
            composePanel(composeView)
        })

    }

    failedAuth() {
        console.log("Failed Auth")
        // Base.googleAuth()
        var auth_modal = Base._iSDK.Widgets.showModalView({
            el: "Please authenticate with Google Account to use this exetnsion!",
            title: "Followup-Fred",
            buttons: [{
                text: "Continue with Google",
                title: "Go to Google OAuth!",
                onClick: function () {
                    Base.googleAuth()
                    auth_modal.close()
                },
                type: "PRIMARY_ACTION"
            }, {
                text: "Cancel",
                title: "Cancel",
                onClick: function () {
                    auth_modal.close()
                },
                type: "SECONDARY"
            }]
        })
    }

}

export const features = () => {
    return new Features()
}