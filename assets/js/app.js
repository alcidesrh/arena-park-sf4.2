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
// import "materialize-css/js/component";
import "materialize-css/js/global";
import "materialize-css/js/anime.min";
import "materialize-css/js/buttons";
import "materialize-css/js/sidenav";
import "materialize-css/js/forms";
import "materialize-css/js/cards";
import "materialize-css/js/dropdown";
import "materialize-css/js/modal";
import "materialize-css/js/select";
import "materialize-css/js/datepicker";


document.addEventListener('DOMContentLoaded', function() {
    M.Sidenav.init(cash('.sidenav'));
    M.Datepicker.init(cash('.datepicker'), {autoClose: true, format: 'dd/mm/yyyy', minDate: new Date()});
});


