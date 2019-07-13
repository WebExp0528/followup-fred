import Base from "core/base.js";
import {
  MAIN_MENU_IC,
  DASHBOARD_IC,
  SCHEDULE_IC,
  PINGS_IC,
  REMINDER_IC,
  SUPPORT_IC,
  MSG_MAINMENU,
  URL_DASHBOARD,
  URL_FOLLOWUPS,
  URL_SCHEDULED,
  URL_REMINDERS,
  URL_SUPPORT
} from "core/constants.js";
import { dropdown } from "utils/dropdown.js";
import { runtime } from "utils/Runtime.js";

/**
 * Define composeViewPanel functions
 * @type { class }
 */

class MainMenu {
  constructor() {
    console.log("loaded MainMenu");
    //Add Main Menu in Gmail
    this.addMainMenu();
    this._ddMainMenu; // AppToolBarButtonView
  }

  /**
   * Add Main Menu in Gmail
   * @return { void }
   */
  addMainMenu() {
    console.log(Base._iSDK);
    //Add main menu button.
    Base._iSDK.Toolbars.addToolbarButtonForApp({
      title: Base._iSDK.User.isUsingGmailMaterialUI() ? "" : "Dashboard",
      iconUrl: MAIN_MENU_IC,
      titleClass: "fred-toolbar-button-title",
      iconClass: "fred-toolbar-button-class",
      onClick: btn => this.onClickMainMenu(btn)
    });
  }

  /**
   * Action for Main Menu Button Click
   *
   * @param { AppToolbarButtonView } btn
   * @return { void }
   */
  onClickMainMenu(btn) {
    this._ddMainMenu = btn.dropdown;
    var dropdownViewDescriber = {
      class: "main-menu-dropdown",
      title: "Follow Up Fred",
      iconURL: MAIN_MENU_IC,
      dropdownItems: [
        {
          iconURL: DASHBOARD_IC,
          title: "Dashboard",
          callback: () => this.onClickDashBoard()
        },
        {
          iconURL: PINGS_IC,
          title: "Follow-ups",
          callback: () => this.onClickFollowups()
        },
        {
          iconURL: SCHEDULE_IC,
          title: "Scheduled Emails",
          callback: () => this.onClickScheduledEmails()
        },
        {
          iconURL: REMINDER_IC,
          title: "Reminders",
          callback: () => this.onClickReminders()
        },
        {
          iconURL: SUPPORT_IC,
          title: "Support",
          callback: () => this.onClickSupport()
        }
      ]
    };
    btn.dropdown.el.appendChild(dropdown(dropdownViewDescriber).render());
  }

  onClickDashBoard() {
    console.log("clicked dashboard");
    runtime.api("runtime").sendMessage({
      type: MSG_MAINMENU,
      options: {
        email: Base._iSDK.User.getEmailAddress(),
        url: URL_DASHBOARD
      }
    });
    this._ddMainMenu.close();
  }

  onClickFollowups() {
    console.log("clicked followups");
    runtime.api("runtime").sendMessage({
      type: MSG_MAINMENU,
      options: {
        email: Base._iSDK.User.getEmailAddress(),
        url: URL_FOLLOWUPS
      }
    });
    this._ddMainMenu.close();
  }

  onClickScheduledEmails() {
    console.log("clicked Scheduled emails");
    runtime.api("runtime").sendMessage({
      type: MSG_MAINMENU,
      options: {
        email: Base._iSDK.User.getEmailAddress(),
        url: URL_SCHEDULED
      }
    });
    this._ddMainMenu.close();
  }

  onClickReminders() {
    console.log("clicked reminders");
    runtime.api("runtime").sendMessage({
      type: MSG_MAINMENU,
      options: {
        email: Base._iSDK.User.getEmailAddress(),
        url: URL_REMINDERS
      }
    });
    this._ddMainMenu.close();
  }

  onClickDashBoard() {
    console.log("clicked dashboard");
    runtime.api("runtime").sendMessage({
      type: MSG_MAINMENU,
      options: {
        email: Base._iSDK.User.getEmailAddress(),
        url: URL_DASHBOARD
      }
    });
    this._ddMainMenu.close();
  }

  onClickSupport() {
    console.log("clicked support");
    runtime.api("runtime").sendMessage({
      type: MSG_MAINMENU,
      options: {
        email: Base._iSDK.User.getEmailAddress(),
        url: URL_SUPPORT
      }
    });
    this._ddMainMenu.close();
  }
}

export const mainMenu = () => {
  return new MainMenu();
};
