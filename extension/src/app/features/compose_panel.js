import Base from "core/base.js";
import {
  SCHEDULE_CLOSED_IC,
  SCHEDULE_OPENED_IC,
  FOLLOWUP_CLOSED_IC,
  FOLLOWUP_OPENED_IC,
  REMINDER_CLOSED_IC,
  REMINDER_OPENED_IC,
  MSG_FOLLOWUP,
  TRACKING_DISABLED_IC,
  TRACKING_ENABLED_IC
} from "core/constants.js";
import { selector } from "utils/Selector.js";
import { followup } from "./followup.js";
import { runtime } from "utils/Runtime.js";

/**
 * Define composeViewPanel functions
 * @type { class }
 */
class ComposePanel {
  constructor(composeView) {
    this._composeView = composeView;
    this._followup_Seq_Value = 100;
    this._isTracking = true;
    console.log(
      "Loaded ComposeView: " +
        (this._composeView.getThreadID()
          ? this._composeView.getThreadID()
          : "New Compose")
    );

    this.addFeature();

    this.addEvent();
  }

  /**
   * Add Features in ComposeView
   * @return { void }
   */
  addFeature() {
    //add Tracking Enable amd Disable in ComposeView
    this._composeView.addButton({
      title: "Tracking Enable/Disable",
      iconUrl: TRACKING_ENABLED_IC,
      iconClass: "compose-panel-tracking",
      hasDropdown: false,
      onClick: e => this.onClickTrackingBtn(e)
    });

    //add FollowUp Button in ComposeView
    this._composeView.addButton({
      title: "FollowUp Email",
      iconUrl: FOLLOWUP_CLOSED_IC,
      iconClass: "compose-panel-followup",
      hasDropdown: true,
      onClick: e => this.onClickFollowupBtn(e)
    });

    //add Reminder Button in ComposeView
    this._composeView.addButton({
      title: "Reminder Email",
      iconUrl: REMINDER_CLOSED_IC,
      iconClass: "compose-panel-reminder",
      hasDropdown: true,
      onClick: e => this.onClickReminderBtn(e)
    });

    //add Schedule Button in ComposeView
    this._composeView.addButton({
      title: "Schedule Email",
      iconUrl: SCHEDULE_CLOSED_IC,
      iconClass: "compose-panel-schedule",
      hasDropdown: true,
      onClick: e => this.onClickScheduleBtn(e)
    });
  }

  //TODO: Listeners
  /**
   * Action for Schedule Button
   * @param { objec | {dropdown: dropdownView, composeView: composeView }} e
   * @returns { void }
   */
  onClickScheduleBtn(e) {
    var activeBtnImg = selector(
      ".inboxsdk__composeButton_active .compose-panel-schedule img"
    );
    activeBtnImg.element.src = SCHEDULE_OPENED_IC;
    e.dropdown.on("destroy", function() {
      activeBtnImg.element.src = SCHEDULE_CLOSED_IC;
    });
    // TODO: Add Schedule Dropdow Menu.
    e.dropdown.el.append("coming soon");
  }

  /**
   * Action for FollowUp Button
   * @param { objec | { dropdown: dropdownView, composeView: composeView }} e
   * @returns { void }
   */
  onClickTrackingBtn(e) {
    //Change followup button icon, once click
    var activeBtnImg = selector(
      ".inboxsdk__button_hover .compose-panel-tracking img"
    );
    console.log(activeBtnImg);
    this._isTracking = !this._isTracking;
    activeBtnImg.element.src = this._isTracking
      ? TRACKING_ENABLED_IC
      : TRACKING_DISABLED_IC;
  }

  /**
   * Action for FollowUp Button
   * @param { objec | { dropdown: dropdownView, composeView: composeView }} e
   * @returns { void }
   */
  onClickFollowupBtn(e) {
    //Change followup button icon, once click
    var activeBtnImg = selector(
      ".inboxsdk__composeButton_active .compose-panel-followup img"
    );
    activeBtnImg.element.src = FOLLOWUP_OPENED_IC;
    e.dropdown.on("destroy", () =>
      this.onDestroyFollowupDropdown(activeBtnImg.element)
    );

    //Show dropdown menu
    e.dropdown.el.append(followup(e, this).render());
  }

  /**
   * Destroy Event for followup dropdown view
   */
  onDestroyFollowupDropdown(el) {
    if (this.getFollowUpSeqValue() == 100) {
      el.src = FOLLOWUP_CLOSED_IC;
    }
    // activeBtnImg.element.src = FOLLOWUP_CLOSED_IC;
  }

  /**
   * Action for Pings Button
   * @param { objec | {dropdown: dropdownView, composeView: composeView }} e
   * @returns { void }
   */
  onClickReminderBtn(e) {
    var activeBtnImg = selector(
      ".inboxsdk__composeButton_active .compose-panel-reminder img"
    );
    activeBtnImg.element.src = REMINDER_OPENED_IC;
    e.dropdown.on("destroy", function() {
      activeBtnImg.element.src = REMINDER_CLOSED_IC;
    });
    // TODO: Add Reminder Dropdow Menu.
    e.dropdown.el.append("coming soon");
  }

  /**
   * Add Event for ComposeView
   */
  addEvent() {
    //Add Destroy Event
    this._composeView.on("destroy", event => this.onDestroy(event));

    //Add Presending Event
    this._composeView.on("presending", event => this.onPresending(event));

    //Add Sent Event
    this._composeView.on("sent", event => this.onSent(event));
  }

  /**
   *
   * @param { object | { messageID: string, closedByInboxSDK: boolean } } event
   */
  onDestroy(event) {
    console.log(event);
    console.log(this._followup_Seq_Value);
  }

  /**
   * Presending Event for ComposeView
   *
   * @param { cancel() | function } event
   */
  onPresending(event) {
    //Send message can cancel
    // event.cancel();
  }

  /**
   *
   * @param { object | { getThreadID(), getMessageID() } } event
   */
  onSent(event) {
    //Send followup
    var seq_value = this._followup_Seq_Value;
    if (seq_value != 100) {
      Promise.all([event.getThreadID(), event.getMessageID()]).then(function(
        values
      ) {
        console.log("Sending followup request");
        runtime.api("runtime").sendMessage({
          type: MSG_FOLLOWUP,
          options: {
            email: Base._iSDK.User.getEmailAddress(),
            // threadID: values[0],
            followup_type: seq_value,
            // message_id: BigInt("0x" + values[1]).toString(10)
            message_id: values[1]
          }
        });
      });
    }
  }

  //TODO: Setters
  /**
   * Set followup sequence value
   *
   * @param { number } value
   */
  setFollowUpSeqValue(value) {
    this._followup_Seq_Value = value;
  }

  //TODO: Getters
  /**
   * Get followup sequence value
   */
  getFollowUpSeqValue() {
    return this._followup_Seq_Value;
  }
}

export const composePanel = composeView => {
  return new ComposePanel(composeView);
};
