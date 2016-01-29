$(document).ready(function(){
  $('#search').submit(function(event){
    //event.preventDefault();
    $('#errors').empty();
    var zipReg = /^[0-9]{5}$/gm;
    var fields = /^$/gm;
    var brand = $("#brand").val();
    var zip = $('#usr_zip_code').val();
    var errorsCollection = Array();
    if(!zipReg.test(zip)){
      errorsCollection.push('Enter 5 digit zip code.');
    }
    if(fields.test(brand)){
      errorsCollection.push('Select Brand.');
    }
    /*if($('#brew')){
      var brew = $('#brew').val();
      if(fields.test(brew)){
        errorsCollection.push('Select Brew');
      }
    }*/
    if(errorsCollection.length>0){
      var errorMsg="";
      for (var i = 0; i < errorsCollection.length; i++)
      {
          errorMsg+=errorsCollection[i]+"<br />";//add errors to alert message
      }
      $('#errors').append("<div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a><strong>Warning!</strong><br />"+errorMsg+"</div>");
      return false
    }
    else return true;
  });
});
