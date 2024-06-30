import './permession.css'
// require main function
import * as main_function from '../../js/main-functions.js';
$(document).ready(function () {
    let role_init = $("select#role-permession").val();
    $("select#role-permession").trigger('change');
    /**
     * gestion role
     */
    $(document).on('change', "select#role-permession", function (e) {
      role_init = $(this).val();
      chargerRoutesRoles($(this));
    });
    /**
     * charger Routes Roles
     */
    let charger_routes_role = null;
    function chargerRoutesRoles(ele) {
      /**
       * save routes for role
       */
      if (charger_routes_role != null) {
        charger_routes_role.abort();
      }
      ele.siblings('.lds-ellipsis').addClass('show');
      charger_routes_role = main_function.ajaxOperation('/api/permession/charger-role-routes',{role_id:role_init},'POST').done((data)=>{
        if (data.success) {
            $(".permession-role tbody").html(data.html);
        }
        setTimeout(() => { $('.alert.result-response').remove() }, 1000);
        ele.siblings('.lds-ellipsis').removeClass('show');
      }).fail((error)=>{
        ele.siblings('.lds-ellipsis').removeClass('show');
      })
    }
    /***
     * save checked routes
     */
  
    $(document).on('click', ".permession-role #save", function (e) {
      e.preventDefault();
      saveRouteRole($(this));
    });
    /**
     * function save route for role
     */
    let save_routes_role = null;
    function saveRouteRole(ele) {
      /**
       * colect checked list
       */
      let checked_role_collection = [];
      $.each($(".permession-role tbody input[type=checkbox]:checked"), function (index) {
        let routes = $(".permession-role tbody input[type=checkbox]:checked").eq(index).siblings('input[type=hidden]').val();
        console.log('oki',routes);
        routes = routes.length ? routes.split(',') : [];
        if (routes.length) {
          for (let i = 0; i < routes.length; i++) {
            checked_role_collection.push(routes[i]);
          }
        }
      });
      /**
       * save routes for role
       */
      if (save_routes_role != null) {
        save_routes_role.abort();
      }
      ele.find('.lds-ellipsis').addClass('show');

      save_routes_role = main_function.ajaxOperation('/api/permession/save-role-routes',{role_id:role_init,checked_role_collection: JSON.stringify(checked_role_collection),},'POST').done((data)=>{
        if (data.success) {
            $(".permession-role tbody").html(data.html);
        }
        setTimeout(() => { $('.alert.result-response').remove() }, 1000);
        ele.find('.lds-ellipsis').removeClass('show');
      }).fail((error)=>{
            console.error('error', error);
          ele.find('.lds-ellipsis').removeClass('show');
      })
      console.log('checked_role_collection', checked_role_collection);
    }
  
});