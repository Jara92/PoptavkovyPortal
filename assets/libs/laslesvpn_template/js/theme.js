import is from "./is.min";
import navbarInit from './bootstrap-navbar';
import detectorInit from './detector';
import utils from "./utils";

// /* -------------------------------------------------------------------------- */
// /*                            Theme Initialization                            */
// /* -------------------------------------------------------------------------- */

// Import is script.
window.is = is;

utils.docReady(navbarInit);
utils.docReady(detectorInit);
