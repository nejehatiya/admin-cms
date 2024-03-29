/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
//import './styles/dashbord-wordpress.css';
import  './styles/css/wp-admin.css';

import './styles/app.css';
// main function
//import * as main_function from './js/main-functions.js';
//window.main_function = main_function; 
// init select picker
if($('.selectpicker').length){
    $('.selectpicker').selectpicker();
}
// import main function 
//import './js/admin-post-modals.js';

