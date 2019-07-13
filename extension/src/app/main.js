import {
    selector
} from 'utils/Selector.js'
import {
    features
} from "core/features.js"

import 'styles/main.scss'
/**
 * Define content script functions
 * @type {class}
 */
class Main {
    constructor() {
        selector(document).ready(this.bind())
    }

    /**
     * Document Ready
     * @returns {void}
     */
    bind() {

        //Loading Features
        console.log("Loadking Features")
        features()
    }

}

export const main = new Main()