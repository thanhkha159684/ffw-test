/**
 * @file
 * Handles all the js for every forms in the MTL theme
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  // Start form behaviors
  Drupal.behaviors.forms = {
    attach: function (context, settings) {

      // ADD NEW USRER FORM

      // **ONLOAD
      //Hidden by default
      $('.temporary_access').hide();
      $('.show_if_inventory').hide();
      $('#edit-field-office-set').hide();

      //Show temporary access if branch is selected
      if ($("#edit-field-department").val() == 2) {
        $('.show_if_inventory').show(300);
        $('#edit-field-office-set').show(300);
        //hide admin if creating branch user
        $('.form-item-roles-administrator ').hide(300);
      }
      //if checked

      if ($("#edit-field-assign-temporary-on-off-value").is(':checked')) {
        $('.temporary_access').show(300);
      } else {
         $('.temporary_access').hide();
      }
      // ** END ONLOAD

      $("#edit-field-department").on('change',function(){
        if (this.value == 2) {
          $('.show_if_inventory').show(300);
          $('#edit-field-office-set').show(300);
          //hide admin if creating branch user
          $('.form-item-roles-administrator ').hide(300);
          $( "#edit-roles-administrator" ).prop( "checked", false);
        }
        else {
          $('.show_if_inventory').hide(300);
          $('#edit-field-office-set').hide(300);
          $('.form-item-roles-administrator ').show(300);

        }
      });

      $("#edit-field-assign-temporary-on-off-value").click(function(){
          this.checked ? $('.temporary_access').show(500) : $('.temporary_access').hide(500);
      });

      //Set select2 for all the dropdowns
      // $('.form-select').select2();


    }
  };
  // End form behaviors

})(jQuery, Drupal, drupalSettings);

