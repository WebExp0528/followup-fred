import moment from 'moment-timezone';
import {
    runtime
} from "utils/Runtime.js";
import {
    MSG_AUTH,
    MSG_GOOGLE_AUTH
} from "core/constants.js";

/**
 * Define Base functions
 * @type { class }
 */
class Base {

    constructor() {}

    //Global Variables
    static _iSDK = null
    static _auth = false
    static _route = null

    //Check Auth
    static checkAuth() {
        return new Promise(function (resolve, reject) {

            //Call BackgroundScript for checking Auth
            runtime.api("runtime").sendMessage({
                type: MSG_AUTH,
                options: {
                    email: Base._iSDK.User.getEmailAddress(),
                    timezone: moment.tz.guess(true)
                    // timezone: "1212312312"
                }
            }, function (response) {
                console.log("Recevied Response from Background: " + response.isAuth)
                if (response.isAuth) {
                    Base._auth = true
                    resolve()
                } else {
                    Base._auth = false
                    reject()
                }

            });
        })
    }

    static googleAuth() {
        runtime.api("runtime").sendMessage({
            type: MSG_GOOGLE_AUTH
        })
    }
}
export default Base