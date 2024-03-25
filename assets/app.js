/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
// get api prefix value
let api_prefix = document.getElementById('prefix-admin').value;
document.getElementById('prefix-admin').remove();
console.log('api_prefixapi_prefix',api_prefix)
window.api_prefix = api_prefix;
// import main function 
import './js/admin-post-modals.js';

