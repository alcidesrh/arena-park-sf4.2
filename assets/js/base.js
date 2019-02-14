/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
// require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// var $ = require('jquery');
// import $ from "cash-dom";
// import "materialize-css/dist/js/materialize.min";
import "materialize-css/js/cash";
import "materialize-css/js/global";
import "materialize-css/js/anime.min";
import "materialize-css/js/sidenav";
import "materialize-css/js/cards";

import {TweenMax} from "gsap/TweenMax";

document.addEventListener('DOMContentLoaded', function() {
    M.Sidenav.init(cash('.sidenav'));

    let logo = new TimelineMax({paused: true, repeat: -1});
    // logo.add(TweenMax.to('#logo', 3, {rotationX:"-360_cw"}));
    logo.add(TweenMax.to('#logo', 5, { rotationY:"+=360_cw"}));
    logo.add(TweenMax.to('#logo', 5, { rotationY:"-=360_cw"}));
    logo.play();
});


