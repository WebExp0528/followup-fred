import Base from "core/base.js"
import {
    sidePanel
} from "features/side_panel.js"
/**
 * Define Router functions
 * @type { class }
 */

class Router {
    constructor(routerView) {
        this._routerView = routerView
        this.onLoadRouter()
    }

    /**
     * Handle Router change.
     */
    onLoadRouter() {

        console.log("Router:" + this._routerView.getRouteID())
        Base._route = this._routerView.getRouteID()

        //Add Side Panel Button, when router changes
        sidePanel()
    }
}
export const router = (routerView) => {
    return new Router(routerView)
}