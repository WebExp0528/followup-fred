import { runtime } from "utils/Runtime.js";

//Base URL for Backend
export const BASE_URL = "http://app.followupfred.com";

//TODO: Add icon urls

//Main Menu Icons
export const MAIN_MENU_IC = runtime.api("runtime").getURL("icons/mailtag.svg");
export const DASHBOARD_IC = runtime
  .api("runtime")
  .getURL("icons/ic-dashboard.svg");
export const SCHEDULE_IC = runtime
  .api("runtime")
  .getURL("icons/ic-scheduled-emails.svg");
export const INBOX_IC = runtime.api("runtime").getURL("icons/ic-inbox.svg");
export const PINGS_IC = runtime.api("runtime").getURL("icons/ic-pings.svg");
export const SETTINGS_IC = runtime
  .api("runtime")
  .getURL("icons/ic-settings.svg");
export const REMINDER_IC = runtime
  .api("runtime")
  .getURL("icons/ic-reminder.svg");
export const SUPPORT_IC = runtime.api("runtime").getURL("icons/ic-support.svg");

//ComposeView Icons
export const SCHEDULE_CLOSED_IC = runtime
  .api("runtime")
  .getURL("icons/schedule-closed.svg");
export const SCHEDULE_OPENED_IC = runtime
  .api("runtime")
  .getURL("icons/schedule-opened.svg");
export const FOLLOWUP_OPENED_IC = runtime
  .api("runtime")
  .getURL("icons/ping-attached.svg");
export const FOLLOWUP_CLOSED_IC = runtime
  .api("runtime")
  .getURL("icons/ping-attached1.svg");
export const REMINDER_OPENED_IC = runtime
  .api("runtime")
  .getURL("icons/reminder-opened.svg");
export const REMINDER_CLOSED_IC = runtime
  .api("runtime")
  .getURL("icons/reminder-closed.svg");
export const FOLLOWUP_SETTING_IC = runtime
  .api("runtime")
  .getURL("icons/followup-setting.svg");
export const FOLLOWUP_INFO_IC = runtime
  .api("runtime")
  .getURL("icons/followup-info.svg");
export const TRACKING_ENABLED_IC = runtime
  .api("runtime")
  .getURL("icons/tracking-enabled.svg");
export const TRACKING_DISABLED_IC = runtime
  .api("runtime")
  .getURL("icons/tracking-disabled.svg");
export const RIGHT_ARROW_IC = runtime
  .api("runtime")
  .getURL("icons/right-arrow.svg");

//Tracking Icons
export const TRACK_OPENED_IC = runtime
  .api("runtime")
  .getURL("icons/read-16.svg");
export const TRACK_NOT_OPENED_IC = runtime
  .api("runtime")
  .getURL("icons/unread-16.svg");
export const TRACK_NOT_INSTALLED_IC = runtime
  .api("runtime")
  .getURL("icons/grey-16.svg");

//Side Panel Icons
export const SIDE_PANEL_BTN_IC = runtime
  .api("runtime")
  .getURL("icons/sidepanel-btn.svg");

//Popup Icon
export const GOOGLE_IC = runtime.api("runtime").getURL("icons/icon-google.svg");

//TODO: Add message IDs
export const MSG_AUTH = "auth";
export const MSG_FOLLOWUP = "followup";
export const MSG_GOOGLE_AUTH = "google_auth";
export const MSG_MAINMENU = "main_menu";

//TODO: Add API Endpoints
export const API_CHECK_AUTH = "/checkAuth";
export const API_GOOGLE_AUTH = "/google/redirect";
export const API_FOLLOWUP = "/followup";

export const URL_DASHBOARD = "/dashboard";
export const URL_FOLLOWUPS = "/followups";
export const URL_SCHEDULED = "/scheduled-emails";
export const URL_REMINDERS = "/reminders";
export const URL_SUPPORT = "/support";
