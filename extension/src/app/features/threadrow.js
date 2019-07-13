import {
    tracking
} from "./tracking.js"

/**
 * Define Thread Row functions
 * @type { class }
 */

class ThreadRow {
    constructor(threadRowView) {
        this._threadRowView = threadRowView
        console.log("Threadrowview: " + threadRowView.getThreadID())
        tracking(threadRowView)
    }

}
export const threadRow = (threadRowView) => {
    return new ThreadRow(threadRowView)
}