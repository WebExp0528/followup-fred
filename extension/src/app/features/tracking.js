import Base from "core/base.js";
import {
    TRACK_OPENED_IC,
    TRACK_NOT_OPENED_IC,
    TRACK_NOT_INSTALLED_IC
} from "core/constants.js"
import {
    runtime
} from "utils/Runtime.js"
import {
    selector
} from "utils/Selector.js"
import {
    createDOM
} from "utils/helper.js"



/**
 * Define composeView functions
 * @type { class }
 */

class Tracking {
    constructor(threadRowView) {
        console.log("loaded Tracking.js")
        this._threadRowView = threadRowView
        this._trackState = {
            threadID: "",
            state: 0, //number of tracked state : 0 - not opened, 1 - opened, 2 - Not installed
            des: "", //description for track state
        }
        this.init()
    }

    /**
     * Load ThreadRowView
     * 
     */
    init() {

        if (Base._route == Base._iSDK.Router.NativeRouteIDs.SENT) {
            console.log(this._threadRowView)
            //Get Thread ID for sent message.
            var threadID = this._threadRowView.getThreadID();
            var elementTreadView = selector("span[data-legacy-thread-id='" + threadID + "']").findParentBySelector('tr')

            if (!elementTreadView.querySelector(".inboxsdk__thread_row_button"))
                elementTreadView.children[2].appendChild(createDOM(this.templateTracked(threadID)))
            else {
                this.updateTrackState()
            }

        }
        // threadRowView.addImage({
        //     imageUrl: TRACK_OPENED_IC
        // })
    }

    /**
     * update Tracking State
     * @param
     */
    updateTrackState(trackState) {
        this._trackState = trackState

    }

    /**
     * Return HTML Template for inserting Tracking Icon 
     * 
     * @param { string } threadID 
     * @requires { string }
     */
    templateTracked(threadID) {
        this.checkTrackedEmail(threadID)
        return '<div class="inboxsdk__thread_row_button" tabindex="-1" data-order-hint="0" style="padding-left:5px;"><div class="track-button-' + this._trackState.threadID + '" data="' + this._trackState.threadID + '"><img class="inboxsdk__button_iconImg" src="' + this.getTrackIconUrl() + '"><div class="followupfred-tooltip">' + this._trackState.des + '</div></div></div>'
    }

    /**
     * check Tracked Emails
     * 
     * @param { threadID } threadID
     * @returns { Object | { state : 0 - not opened, 1 - opened, 2 - Not installed , description: string } } 
     */
    checkTrackedEmail(threadID) {
        //CheckTracked Email
        // runtime.api('runtime').sendMessage({""})
        this._trackState = {
            threadID: threadID,
            state: 1,
            des: ""
        }
    }

    /**
     * Get Track Icon Url for Tracking state
     * 
     * @returns {Icon Url}
     */
    getTrackIconUrl() {
        switch (this._trackState.state) {
            case 0:
                return TRACK_OPENED_IC
                break;
            case 1:
                return TRACK_NOT_OPENED_IC
                break;
            case 2:
                return TRACK_NOT_INSTALLED_IC
                break;
            default:
                break;
        }
    }
}

export const tracking = (threadRowView) => {
    return new Tracking(threadRowView)
}