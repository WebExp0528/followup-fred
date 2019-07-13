import {
    button
} from './button.js'
import {
    createDOM
} from "./helper";
/**
 * Define DropdownView.
 * @type { class }
 */
class Dropdown {

    /**
     * @param { object = { class: the class name of dropdownView, dropdownItems: [{ iconURL: url, title: title of button, callback: the callback function },...] }} props
     * @returns { void }
     */
    constructor(props) {
        this.props = props;
    }

    /**
     * render Dorpdown menu and button
     * @returns { void }
     */
    render() {
        this._dropdownView = createDOM(this.template())
        for (var value of this.props.dropdownItems) {
            var menuButton
            if (value.iconURL) {
                menuButton = button(this.templateDropdownBtnImage(value.iconURL, value.title)).click(value.callback)
            } else {
                menuButton = button(this.templateDropdownBtn(value.title)).click(value.callback)
            }
            this._dropdownView.appendChild(menuButton)
        }
        return this._dropdownView
    }

    /**
     * Return a html template of Dropdown Menu
     * @returns {string}
     */
    template() {
        var templateDropdown = '<ul class="' + this.props.class + ' dd-menu" style="padding-inline-start: 0px">'
        if (this.props.iconURL || this.props.title) {
            templateDropdown += '<li class="dd-menu-item dd-menu-header" style="user-select: none;">'
        }
        if (this.props.iconURL) {
            templateDropdown += '<span class="dd-menu-item-img" style="user-select: none; background:url(' + this.props.iconURL + ') no-repeat center"></span>'
        }
        if (this.props.title) {
            templateDropdown += '<span class="dd-menu-item-title" style="user-select: none;">' + this.props.title + '</span>'
        }
        if (this.props.iconURL || this.props.title) {
            templateDropdown += '</li><div class="d-Na-axR" style="user-select: none;"></div>'
        }
        return templateDropdown + "</ul>"
    }

    /**
     * Return a html template of Dropdown Menu Button
     * @returns {string}
     */
    templateDropdownBtnImage(iconURL, title) {
        return '<li class="dd-menu-item" style="user-select: none;"><span class="dd-menu-item-img" style="user-select: none; background:url(' + iconURL + ') no-repeat center"></span><span class="dd-menu-item-title" style="user-select: none;">' + title + '</span></li>'
    }
    templateDropdownBtn(title) {
        return '<li class="dd-menu-item" style="user-select: none;"><span class="dd-menu-item-title" style="user-select: none;">' + title + '</span></li>'
    }
}


export const dropdown = (dropdown) => {
    return new Dropdown(dropdown)
}