$(document).ready(function() {
  $('#search').click(function(){
      
    var check=true;
	var state = document.getElementById("state").value;

  if($('#state').val().trim()!=""){
      
      $('#stateError').css('visibility','hidden');
  }
    else
    {
        check=false;
        $('#stateError').css('visibility','visible');
    }        
});
    
 $('#clear').click(function(){ 
     $('#stateError').css('visibility','hidden');
     $('#state').val("");
     
  });
    
 $('#state').bind("keyup focusout change", function(){   
 
  if($('#state').val().trim()!=""){
      $('#stateError').css('visibility','hidden');
  }
    else
    {
        $('#stateError').css('visibility','visible');
    }
 if(check){
     myfn();
 }
});    
});
