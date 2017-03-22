<?php
include "ClientCredentials.php";

session_start();
$authorizationUrlBase = 'https://account.box.com/api/oauth2/authorize';

// URL parameters used to request an authorization token
$queryParams = array(
    "client_id" => $clientId,
    "response_type" => "code",
    "state" => "security_token%3DKnhMJatFipTAnM0nHlZA",
);
$goToUrl = $authorizationUrlBase . '?' . http_build_query($queryParams);
if (isset($_GET['session_name'])) {$_SESSION['session_name'] = $_GET['session_name'];}
?>
<html>
<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/sample/box_amg.css">

<body>    
   <div class="container">
        <h1 align='left' font-color='white'>Box Integration</h1>
            <div class="panel panel-body" style="background-color: rgba(0, 0, 0, 0.3);">   
                
            <form role="form" name="myform" action="index.php" method="POST" onsubmit="return myfn()">
                <div class="form-group col-md-5 spaceClass">
                    

                <label for="sel1">Task:<font color="red">*</font></label>
                <select class="form-control" name="state" id="div_session_write">
                        <option value="" name="empty">Select your Task</option>		
                        <option value="CP">Change Permission</option>
                </select>
                    <font color="red">
                        <span id="stateError" style="visibility:hidden">Please enter task</span> </font>
                    </div> 
                <div class="form-group col-md-offset-3 col-md-4 btnPadding" style="text-align:right">
                    <button type="button" class="btn btn-primary btn-md" name="search" id="search" value="Search" onclick="myfn()">
                        <span class="glyphicon glyphicon-user"></span> Authorize
                    </button>

                    <button type="button" class="btn btn-default btn-md" name="clear" value="Clear" id="clear">
                        <span class="glyphicon glyphicon-refresh"></span> Clear
                    </button>       
                </div>
                <div class="col-md-offset-9 col-md-3" style="text-align:right">

                <label style="font-weight:400">Powered by:</label>
                    <img src="/sample/boxbanner.png" width=70px height=70px/>
                </div>        
          </form>
        
 
</div>
</body>
<script>
   
    function myfn(){
        //document.getElementById('form2').submit();
        var state = document.getElementById("div_session_write").value;
        jQuery('#div_session_write').load('session_write.php?session_name='+state);
        return window.location='<?php echo $goToUrl;?>';
    }
    
</script>
    

</html>