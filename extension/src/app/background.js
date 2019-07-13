import "babel-polyfill";
import { runtime } from "utils/Runtime.js";

import {
  MSG_AUTH,
  MSG_FOLLOWUP,
  MSG_GOOGLE_AUTH,
  API_CHECK_AUTH,
  API_FOLLOWUP,
  API_GOOGLE_AUTH,
  BASE_URL,
  MSG_MAINMENU,
  URL_DASHBOARD,
  URL_FOLLOWUPS,
  URL_SCHEDULED,
  URL_REMINDERS,
  URL_SUPPORT
} from "core/constants.js";
import axios from "axios";

const axiosInstance = axios.create({
  baseURL: BASE_URL,
  headers: {
    "Content-Type": "application/json"
  },
  transformRequest: [
    (data, headers) => {
      console.log("===== request header from client =====", headers);
      console.log("===== request data from client=====", data);
      return data;
    }
  ],
  transformResponse: [
    data => {
      let resp;

      try {
        resp = JSON.parse(data);
      } catch (error) {
        throw Error(
          `[requestClient] Error parsing response JSON data - ${JSON.stringify(
            error
          )}`
        );
      }
      console.log("===== response data from server =====", resp);
      return resp;
    }
  ],
  timeout: 5000,
  validateStatus: status => {
    console.log("===== response status code from server =====", status);
    return status >= 200 && status < 300; // default
  }
});

/**
 * Define content script functions
 * @type {class}
 */
class Background {
  constructor() {
    this._port;
    this._AuthTabID;
    this._mainTab;
    this.init();
  }

  /**
   * Document Ready
   * @returns {void}
   */
  init() {
    console.log("loaded Background Scripts");

    //Redirect to google OAuth when extension installed
    runtime.api("runtime").onInstalled.addListener(() => this.onInstalled());

    //Add message listener in Browser.
    runtime
      .api("runtime")
      .onMessage.addListener((message, sender, reply) =>
        this.onMessage(message, sender, reply)
      );

    //Add message listener from Extension
    runtime
      .api("extension")
      .onConnect.addListener(port => this.onConnect(port));

    //Add Update listener for tab
    runtime
      .api("tabs")
      .onUpdated.addListener((tabId, changeInfo, tab) =>
        this.onUpdatedTab(tabId, changeInfo, tab)
      );
  }

  //TODO: Listeners
  /**
   * Extension Installed
   */
  onInstalled() {
    console.log("Installed Followup-Fred Extension!");
    console.log("Redirect into Google OAuth!");
    // Base.googleAuth()
  }

  /**
   * Message Handler Function
   *
   * @param { object } message
   * @param { object } sender
   * @param { object } reply
   */
  onMessage(message, sender, reply) {
    console.log("Received Message:", message);
    if (message.type == MSG_AUTH) {
      //Check Auth
      this.checkAuth(message.options).then(function(res) {
        reply({
          isAuth: res.isAuth
        });
      });
    } else if (message.type == MSG_FOLLOWUP) {
      //Followup
      this.addFollowup(message.options);
    } else if (message.type == MSG_GOOGLE_AUTH) {
      this.googleAuth();
    } else if (message.type == MSG_MAINMENU) {
      this.gotoURLs(message.options);
    }

    return true;
  }

  /**
   * Connect with Extension
   *
   * @param {*} port
   */
  onConnect(port) {
    this._port = port;
    console.log("Connected .....");
    this._port.onMessage.addListener(msg => this.onMessageFromExtension(msg));
  }

  /**
   * Message from Extension
   *
   * @param {*} msg
   */
  onMessageFromExtension(msg) {
    console.log("message recieved:" + msg);
    // if(msg==MSG_AUTH){
    //     this.checkAuth(message.options).then(function (res) {
    //         reply({
    //             isAuth: res.isAuth
    //         })
    //     })
    // }
  }

  /**
   *
   * @param {*} tabId
   * @param {*} changeInfo
   * @param {*} tab
   */
  onUpdatedTab(tabId, changeInfo, tab) {
    //Close Auth tab
    if (
      this._AuthTabID == tabId &&
      changeInfo.url == "https://mail.google.com/mail/u/0/"
    ) {
      // alert("dddddddddd")
      runtime.api("tabs").remove(tabId, function() {
        console.log("Closed Google OAuth Tab!");
      });
      runtime.api("tabs").reload(this._mainTab, function() {
        console.log("Reloaded Main Tab!");
      });
      runtime.api("tabs").update(this._mainTab, {
        highlighted: true
      });
    }
  }

  //TODO: API Call
  /**
   * API Call for checking Auth
   *
   * @param { object | email: string, timezone: string } param
   */
  async checkAuth(param) {
    console.log("*****Checking Auth: ", API_CHECK_AUTH);
    try {
      const res = await axiosInstance.get(API_CHECK_AUTH, {
        params: param
      });
      return {
        isAuth: true
      };
    } catch (error) {
      return {
        isAuth: false
      };
    }
  }

  /**
   * API Call for Followup
   *
   * @param { object | email: string, threadID: string, messageID: string } param
   */
  addFollowup(param) {
    console.log("*****Sending Followup Request : " + API_FOLLOWUP);
    axiosInstance
      .post(API_FOLLOWUP, JSON.stringify(param))
      .then(function(response) {
        // console.log(response)
      })
      .catch(function(error) {
        // console.log(error);
      });
  }

  /**
   *
   */
  googleAuth() {
    var _this = this;
    runtime.api("tabs").query(
      {
        active: true,
        currentWindow: true
      },
      function(tabs) {
        _this._mainTab = tabs[0].id;
      }
    );
    runtime.api("tabs").create(
      {
        url: BASE_URL + API_GOOGLE_AUTH
      },
      function(newTab) {
        _this._AuthTabID = newTab.id;
      }
    );
  }

  gotoURLs(param) {
    runtime.api("tabs").create(
      {
        url: BASE_URL + param.url + "?email=" + param.email
      },
      function(newTab) {
        console.log("opened new tab:" + param.url);
      }
    );
  }
}

export const background = new Background();
