import {
  FOLLOWUP_SETTING_IC,
  FOLLOWUP_INFO_IC,
  RIGHT_ARROW_IC
} from "core/constants.js";
import { selector } from "utils/Selector.js";
import { createDOM } from "utils/helper.js";
import { button } from "utils/button.js";

/**
 * Define FollowUp functions
 * @type { class }
 */

class FollowUp {
  constructor(props, delegate) {
    // console.log(props);
    this._composeView = props.composeView;
    this._dropdownView = props.dropdown;
    this._delegate = delegate;

    this.props = {
      dropdownItems: [
        {
          title: "Very aggressive",
          description: "2x a day",
          tooltiptext:
            "Send follow-up emails every day at 8 am and 4 pm in your time zone starting tomorrow at 8 am!"
        },
        {
          title: "Aggressive",
          description: "1x a day",
          tooltiptext:
            "Send follow-up emails every day starting 24 hours from when it was sent!"
        },
        {
          title: "Mild",
          description: "every other day",
          tooltiptext:
            "Send follow-up emails every other day starting 24 hours from when it was sent!"
        },
        {
          title: "Lite",
          description: "every two days",
          tooltiptext:
            "Send follow-up emails every three days starting 24 hours from when it was sent!"
        },
        {
          title: "Very lite",
          description: "every three days",
          tooltiptext:
            "Send follow-up emails every four days starting 24 hours from when it was sent!"
        },
        {
          title: "Custom Sequence",
          description: "",
          tooltiptext: "Create custom sequence!"
        }
      ]
    };
  }

  render() {
    this._ddView = createDOM(this.template());

    //Add Setting Button in Followup Dropdown
    this._ddView.appendChild(
      button(this.templateSetting()).click(function() {
        console.log("clicked followup setting button");
      })
    );
    //Add underline in setting
    // this._ddView.appendChild(createDOM(this.templateLine()));

    //Add Options in Followup Dropdown
    var i = 0;
    for (var el of this.props.dropdownItems) {
      var menuButton = createDOM(this.templateOption(i));
      var option = selector(menuButton).findChildBySelector("label")[0];
      if (i == 5) {
        selector(option).click(function() {
          selector(".followup-custom-sequence").addClass("show");
        });
      } else {
        selector(option).click(function() {
          selector(".followup-custom-sequence").removeClass("show");
        });
      }

      this._ddView.appendChild(menuButton);
      i++;
    }
    this._ddView.appendChild(createDOM(this.templateLine()));

    //Add custom sequence
    this._ddView.appendChild(createDOM(this.templateCustomSequence()));

    //Add Attach Button in Followup Dropdown
    this._ddView.appendChild(
      button(this.templateAttachBtn()).click(() =>
        this.onClickCreateSequenceBtn()
      )
    );

    return this._ddView;
  }

  /**
   * Return a html template of Dropdown Menu
   * @returns {string}
   */
  template() {
    return '<ul class="composeview-followup-dd dd-menu" style="padding-inline-start: 0px"></ul>';
  }

  /**
   * Return a html template of Dropdown Menu Select option
   * @returns {string}
   */
  templateOption(value) {
    const ddItem = this.props.dropdownItems[value == 100 ? 0 : value];
    return (
      '<li class="dd-menu-item" style="user-select: none;"><span class="dd-menu-item-title" style="user-select: none;"><label><input class="followup_type" type="radio" name="gender" value="' +
      value +
      '" ' +
      (value == this._delegate.getFollowUpSeqValue() ||
      (value == 0 && this._delegate.getFollowUpSeqValue() == 100)
        ? "checked"
        : "") +
      "><span>" +
      ddItem.title +
      '</span><span class ="dd-menu-des">  ' +
      ddItem.description +
      '</span></label></span><span class="dd-menu-item-img dd-menu-tooltip" style="user-select: none; background:url(' +
      FOLLOWUP_INFO_IC +
      ') no-repeat center; margin-right:5px"><span class="dd-menu-tooltiptext">' +
      ddItem.tooltiptext +
      "</span></span></li>"
    );
  }

  /**
   * Return a html template of Dropdown Menu Attach Button
   * @returns {string}
   */
  templateAttachBtn() {
    return (
      '<li class="dd-menu-item dd-menu-header dd-menu-followup-attach ' +
      (this._delegate.getFollowUpSeqValue() == 100 ? "on" : "off") +
      '" style="user-select: none;"><span class="dd-menu-item-title" style="user-select: none;">' +
      (this._delegate.getFollowUpSeqValue() == 100 ? "Create" : "Cancel") +
      " Sequence</span></li>"
    );
  }

  /**
   * Return a html template of Dropdown Menu Setting Button
   * @returns {string}
   */
  templateSetting() {
    return (
      '<li class="dd-menu-item dd-menu-header dd-menu-followup-setting" style="user-select: none;"></span><span class="dd-menu-item-title" style="user-select: none;">Select FollowUp Sequence</span><span class="dd-menu-item-img" style="display:none; user-select: none; background:url(' +
      FOLLOWUP_SETTING_IC +
      ') no-repeat center"></li>'
    );
  }

  /**
   * Return a html template of line
   * @returns {string}
   */
  templateLine() {
    return '<div class="d-Na-axR" style="user-select: none;"></div>';
  }

  /**
   * Return a html template of Custom sequence
   * @returns {string}
   */
  templateCustomSequence() {
    var options = "";
    for (var i = 1; i <= 100; i++) {
      options += '<option value="' + i + '">' + i + "</option>";
    }
    return (
      `<div class="followup-custom-sequence ` +
      (this._delegate.getFollowUpSeqValue() == 5 ? `show` : `hide`) +
      `">
              <div style="font-size: 17px;">Send follow-up emails every:</div>
              <div class="followup-custom-row">
                <img  class="img-right-arrow" src="` +
      RIGHT_ARROW_IC +
      `">
                <div class="followup-select-div">
                  <input type="number" list="days" class=""/>
                  <datalist id="day(s)">
                    ` +
      options +
      `</datalist>
                </div>
                <div>days</div>
              </div>
              <div class="followup-custom-row">
                <img  class="img-right-arrow" src="` +
      RIGHT_ARROW_IC +
      `">
                <div>for next</div>
                <div class="followup-select-div">
                  <input type="number" list="days" class=""/>
                  <datalist id="days">
                    ` +
      options +
      `</datalist>
                </div>
                <div>day(s)</div>
              </div>
              <div class="followup-custom-row">
                <img  class="img-right-arrow" src="` +
      RIGHT_ARROW_IC +
      `">
                <div>at</div>
                <div class="followup-select-div">
                  <input type="time" class=""/>
                </div>
              </div>
              <div class="followup-custom-row">
                <img  class="img-right-arrow" src="` +
      RIGHT_ARROW_IC +
      `">
                <div>and at</div>
                <div class="followup-select-div">
                  <input type="time" class=""/>
                </div>
                <div style="font-size:14px; color:grey;">optional</div>
              </div>
            </div>`
    );
  }

  /**
   * Action for Create Sequence Button
   */
  onClickCreateSequenceBtn() {
    if (this._delegate.getFollowUpSeqValue() == 100) {
      var value = selector(
        "input[type=radio]:checked.followup_type",
        "querySelectorAll"
      ).element[0].value;
      this._delegate.setFollowUpSeqValue(value);
      console.log(
        "create sequence button clicked :" +
          this.props.dropdownItems[value].title
      );
    } else {
      this._delegate.setFollowUpSeqValue(100);
      console.log("cancel sequence");
    }

    this._dropdownView.close();
  }
}

export const followup = (props, delegate) => {
  return new FollowUp(props, delegate);
};
