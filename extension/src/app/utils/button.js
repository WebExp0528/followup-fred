import {
    createDOM
} from './helper.js'
import {
    selector
} from './Selector.js'

/**
 * Define Button functions.
 * @type Class
 */
export class Button {
    /**
     * Get current provided Button.
     * 
     * @param {htmlstring or object - { title: string(required), tooltip: string(optional), iconUrl: url(optional) } } button
     */
    constructor(button) {
        if (typeof button === "object") {
            this._btnDes = button
            this._element = createDOM(this.templateButton())
        } else {
            this._element = createDOM(button)
        }
    }


    /**
     * Add click event on button
     * @param { Function } callback
     * @returns { button }
     */
    click(callback) {
        selector(this._element).click(callback)
        return this._element
    }

    /**
     * HTML Template for button
     * @returns { string }
     */
    templateButton() {
        return '<div class="followupfred-btn" id = "followupfred_btn_' + this._btnDes.title + '" role="button"  data-tooltip="' + ((this._btnDes.tooltip) ? this._btnDes.tooltip : this._btnDes.title) + '" aria-label="' + this._btnDes.title + '" style="width: 20px;user-select: none;"><div class="asa"><div class="followupfred-btn-icon" style="background: url(' + ((this._btnDes.iconUrl) ? this._btnDes.iconUrl : "") + ') no-repeat center;">' + ((!this._btnDes.iconUrl) ? this._btnDes.title : "") + '</div></div></div>'
    }
}

export const button = (button) => {
    return new Button(button)
}