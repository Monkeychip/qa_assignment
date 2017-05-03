////////////////////////////////////////////////////////////////////////////////
/*Script to show and hide buttons depending on what form fields are filled out*/
////////////////////////////////////////////////////////////////////////////////

jQuery(document).ready(function($){
  $('.trigger_show').on("change keyup click", showButtons);
    function showButtons(){
      if($("#form_id").val().length > 5){
        $('.button.hide').css("display","block");
      }
      if($("#number_test").val() > 0){
        $('.button.test').css("display","block");
      }
    }
});
