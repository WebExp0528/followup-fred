import { selector } from "utils/Selector.js";
import { runtime } from "utils/Runtime.js";
import {
  MAIN_MENU_IC,
  DASHBOARD_IC,
  INBOX_IC,
  SCHEDULE_IC,
  PINGS_IC,
  SETTINGS_IC,
  SUPPORT_IC,
  GOOGLE_IC
} from "core/constants.js";
import { dropdown } from "utils/dropdown.js";
import { createDOM } from "utils/helper.js";

import "styles/popup.scss";
import { MSG_AUTH, API_GOOGLE_AUTH } from "./core/constants";

class Popup {
  constructor() {
    this._port;
    selector(document).ready(this.bind());
  }

  /**
   * Document Ready
   */
  bind() {
    this.init();
  }

  /**
   * Initialize Function
   */
  init() {
    this._port = runtime.api("extension").connect({
      name: "Sample Communication"
    });
    this._port.onMessage.addListener(msg => this.onMessage(msg));

    //Add Header in Popup html
    selector(".header-title").element.innerHTML = runtime
      .api("i18n")
      .getMessage("extName");
    selector(".header-img").element.setAttribute(
      "style",
      "background: url(" + MAIN_MENU_IC + ") no-repeat center;"
    );

    //Check Auth
    this._port.postMessage(MSG_AUTH);
  }

  onMessage(msg) {
    console.log("message recieved" + msg);
  }

  /**
   * If Authenticated, render DashBoad in Popup html
   */
  renderDashBoard() {
    console.log("Render Dashboard");
    var dropdownViewDescriber = {
      class: "main-menu-dropdown",
      title: "MAILTAG",
      iconURL: MAIN_MENU_IC,
      dropdownItems: [
        {
          iconURL: DASHBOARD_IC,
          title: "Dashboard",
          callback: function() {
            alert("clicked Dashboard");
          }
        },
        {
          iconURL: INBOX_IC,
          title: "Inbox",
          callback: function() {
            alert("clicked Inbox");
          }
        },
        {
          iconURL: SCHEDULE_IC,
          title: "Scheduled Emails",
          callback: function() {
            alert("clicked Scheduled Emails");
          }
        },
        {
          iconURL: PINGS_IC,
          title: "Pings",
          callback: function() {
            alert("clicked Pings");
          }
        },
        {
          iconURL: SETTINGS_IC,
          title: "Settings",
          callback: function() {
            alert("clicked Settings");
          }
        },
        {
          iconURL: SUPPORT_IC,
          title: "Support",
          callback: function() {
            alert("clicked Support");
          }
        }
      ]
    };
    selector(".detail-container").element.appendChild(
      dropdown(dropdownViewDescriber).render()
    );
  }

  /**
   * If unauthenticated, render Signup in Popup html
   */
  renderSignup() {
    selector(".detail-container").element.appendChild(
      createDOM(this.templateSignUp())
    );
    selector(".img-google").element.src = GOOGLE_IC;
    selector(".btn-signup").click(() => {
      window.open(API_GOOGLE_AUTH);
    });
  }

  /**
   * HTML Template for Signup
   */
  templateSignUp() {
    return '<div><div class = "detail-signup">Continue with Google to use this exetnsion!</div><div class="signup"><span class="btn-signup"><span class = "btn-text">Continue with <img class="img-google"></img></span></span></div></div>';
  }
}

export const popup = new Popup();
