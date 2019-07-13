import Base from "core/base.js"
import {
    button
} from "utils/button.js"
import {
    selector
} from "utils/Selector.js"
import {
    SIDE_PANEL_BTN_IC
} from "core/constants.js";
import {
    createDOM
} from "utils/helper.js";

/**
 * Define SidePanel functions
 * @type { class }
 */

class SidePanel {
    constructor() {
        this._settingBar

        this.addSidePanelBtn()
    }

    /**
     * add SidePanel Button in Setting Bar
     */
    addSidePanelBtn() {

        this._settingBar = selector('.G-atb .Cr.aqJ', 'querySelectorAll').element
        for (var i = 0; i < this._settingBar.length; i++) {
            var sideBtn = {
                title: "SidePanel",
                iconUrl: SIDE_PANEL_BTN_IC,
                tooltip: "Go To SidePanel"
            }
            if (!this.isAddedSidePanelBtn(i)) {
                this._settingBar[i].appendChild(button(sideBtn).click(() => this.onClickedSidePanelBtn()))
            }
        }
    }

    /**
     * Check whether added or not SidePanel button
     * 
     * @param { number } index
     * @returns { boolean }
     */
    isAddedSidePanelBtn(index) {
        if (selector(this._settingBar[index]).findChildBySelector("#followupfred_btn_SidePanel").length == 0) {
            return false
        } else {
            return true
        }
    }

    //TODO: Show Side Panel 
    /**
     * Clicked Side Panel Button, 
     */
    onClickedSidePanelBtn() {
        console.log("Clicked Side Panel Button!!!")
        Base._iSDK.Global.addSidebarContentPanel({
            el: createDOM("<div>asdfasdfasdfa</div>"),
            title: "sdfasdfasd",
            iconUrl: SIDE_PANEL_BTN_IC
        })
    }

}
export const sidePanel = () => {
    return new SidePanel()
}