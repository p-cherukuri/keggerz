//add jquery submit listener for button
//return validation functiion that will return true if all is correct or false
//add jquery click listener to modal agree button to enable the terms check box.
//validation function keeps track of errors in an arra
$(document).ready(function(){
    var msg ="";
       if(msg.length !=""){
           alert('test');
       }
   $('#application').submit(function(){
       
       return validation();
   }); 
   $('#agreeButton').click(function(){
       //$('#checkbox').removeAttr("disabled");// this would enable the check box so the user would still have to check after agreeing to terms in modal dialog
       $('#checkbox').prop( "checked", true );//automatically checks agreements
   });
});

function validation(){
    var errors = true;
    var errorsCollection= new Array();
    
    //regex
    //var nameReg = /^[a-zA-Z]+$/m;//fname lname city
    var telReg = /^([0-9]{3}(-)?)?(\([0-9]{3}\)(-)?)? ?([0-9]{3})(-)? ?([0-9]{4})$/gm //covers all number formats
    var nameReg = /^[a-z '-]+$/mi;
    var emailReg = /^[a-z0-9_-]+@[a-z0-9]+\.[a-z]+$/im;
    var zipReg = /^[0-9]{5}(-[0-9]{4})?$/gm
    //var telReg = /^([0-9]{3}(-)?)?([0-9]{3})(-)?([0-9]{4})$/m;
    
    //elements
    var fname = document.getElementById('fname');
    var lname = document.getElementById('lname');
    var email = document.getElementById('email');
    var tel = document.getElementById('tel');
    var city = document.getElementById('city');
    var state = document.getElementById('state');
    var zip = document.getElementById('zip');
    var checkbox = document.getElementById('checkbox');
    
    //error check
    if(!nameReg.test(fname.value.trim()))
    {
        errorsCollection.push("Invalid First Name!");
    }
    if(!nameReg.test(lname.value.trim()))
    {
        errorsCollection.push("Invalid Last Name!");    
    }
    if(!emailReg.test(email.value.trim()))
    {
        errorsCollection.push("Invalid Email!");  
    }
    if(!telReg.test(tel.value.trim()))
    {
        errorsCollection.push("Invalid Phone Number!");
    }
    if(!nameReg.test(city.value.trim()))
    {
        errorsCollection.push("Invalid City!");
    }
    if(!nameReg.test(state.value))
    {
        errorsCollection.push("Select A State!");
    }
    if(!zipReg.test(zip.value.trim()))
    {
        errorsCollection.push("Invalid Zip Code");
    }
    if(!checkbox.checked)
    {
        errorsCollection.push("Agree to the Terms!");
    }
    
    //check if any errors have been collected
    if(errorsCollection.length>0)
    {
        var errorMsg="";
        for (var i = 0; i < errorsCollection.length; i++) 
        {
            errorMsg+=errorsCollection[i]+"<br />";//add errors to alert message
        }
        //alert(errorMsg);
        var errorDiv = document.getElementById('error');
        //errorDiv.innerText = errorMsg;
        errorDiv.innerHTML = "<div class=\"alert alert-warning\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a><strong>Warning!</strong><br />"+errorMsg+"</div>"
        return false;
    }
    else
    {
        return true;//submit form to php file
    }
}