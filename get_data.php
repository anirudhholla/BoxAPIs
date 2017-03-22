<html>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-csv/0.71/jquery.csv-0.71.min.js"></script>
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="box_amg.css">
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<?php
session_start();

$accessToken = $_SESSION['access_token'];
$task_val = $_SESSION['session_name'];
  //  echo "<script type='text/javascript'>alert('$task_val');</script>";

?>
    
    <body>
   <div class="container">
        <h1 align='left' font-color='white'>Box Integration</h1>
            <div class="panel panel-body" style="background-color: rgba(0, 0, 0, 0.3);">   
                
            <form action="action.php" method="post" enctype="multipart/form-data" id="form2">
                <div class="form-group col-md-4 spaceClass" id="formpage_1">
                    
                <!--<label for="sel1">Upload CSV:<font color="red">*</font></label>-->
                <label class="btn btn-success btn-file">
                    Upload CSV:<font color="red">*</font>
                <input type="file" name="fileToUpload" id="fileToUpload" style="display: none;" >
                </label>
                    
                <label class="btn btn-primary btn-file">
                    Submit
                <input type="submit" name="submit" id="submit" style="display: none;" >
                </label>
                    
                <!--<input type="file" name="fileToUpload" id="fileToUpload" > -->
                </div>
                 
                
                <div class="col-md-offset-9 col-md-3" style="text-align:right">

                <label style="font-weight:400">Powered by:</label>
                    <img src="/sample/boxbanner.png" width=70px height=70px/>
                </div>        
          </form>
       </div>  </div> 
</body>
<script type="text/javascript">
   var access_token = "<?php echo $accessToken; ?>";
   var task_val = "<?php echo $task_val; ?>";
   var allText = "";
   var main_array = "";
   var i,j;
   var email_id = [];
   var owner_mail = "";
   var colab_id =[];
   var f_id ="";
   var colab_email = [];
   var item_name = [];
   var owner_login = [];
   var owner_user_id = [];
    if(task_val == "CP"){
        document.getElementById('formpage_1').style.display="block";
    }
    else{
        document.getElementById('formpage_1').style.display="none";
    }
       
    function readTextFile(file)
    {
        var rawFile = new XMLHttpRequest();
        rawFile.open("GET", file, true);
        rawFile.onreadystatechange = function ()
        {
            if(rawFile.readyState === 4)
            {
                if(rawFile.status === 200 || rawFile.status == 0)
                {
                    allText = rawFile.responseText;
                    //console.log(allText)
                    main_array = $.csv.toArrays(allText);
                     //debugger;
                   
                     main(main_array);
                }
            }
        }
        rawFile.send(null);
   
    }
    function main(email_id_arr){
        console.log(email_id_arr)
        //var user_id  = getUserId(email_id);
        var i;
        //console.log(email_id_arr)
        //console.log(email_id_arr.length)
        var chk_mail = "amgen.com";
        for(i=1;i<= email_id_arr.length-1;i++){
            colab_email[i-1] = email_id_arr[i][0];
            item_name[i-1] = email_id_arr[i][1];
            owner_login[i-1] = email_id_arr[i][2];
            owner_user_id[i-1] = getUserId(email_id_arr[i][2]);
            
            if(owner_user_id[i-1]!=null && owner_user_id[i-1]!='' && item_name[i-1]!='' && colab_email[i-1]!=''){
                //alert(item_name[i-1])
                //alert(owner_user_id[i-1])
                //alert(colab_email[i-1])
                f_id = fetchfolderid(item_name[i-1],owner_user_id[i-1],owner_login[i-1])
                
            }
            else{
                f_id = 0;
                alert("Some field value is blank");
            }
            if(!(colab_email[i-1].indexOf(chk_mail)> -1)){
                //owner not null
                //alert("inside if")
                if(f_id !="" && f_id!=null){
                    //alert("inside if if")
                    change_perm = changePermission(colab_email[i-1],f_id);
                }
            }
            
        
        }
         
        
        /*
        owner_mail = main_array[1][2];
        folder_name = main_array[1][1];
        user_id = getUserId(owner_mail);
        
        for(i=1;i<main_array.length;i++){         
            email_id[i-1] =main_array[i][0];
        }
        if (owner_mail != "" && user_id !="" && user_id!=null && folder_name!=null){
            f_id= fetchfolderid(folder_name,user_id,owner_mail);
        }
        for(i=0;i<email_id.length;i++){
            var str = "amgen.com";
            //Non Amgen ID
            if(!(email_id[i].indexOf(str)> -1)){
               //owner not null
                if(f_id !="" && f_id!=null){
                    change_perm = changePermission(email_id[i],f_id);
                }
            }
        } */
              
    }
    function getUserId(owner_mail){
        
            var data = null;
            var url = 'https://api.box.com/2.0/users?filter_term='+owner_mail;
            //alert(url);
            var httpRequest = new XMLHttpRequest();
            var token = "Bearer "+access_token;
            httpRequest.open('GET', url, false);
            httpRequest.setRequestHeader("authorization",token);
            httpRequest.setRequestHeader("cache-control", "no-cache");
            httpRequest.send();
            if (httpRequest.status === 200) {// That's HTTP for 'ok'
                obj = JSON.parse(httpRequest.responseText);
                if(obj.total_count == 0){
                    u_id = null;
                }
                else{
                   u_id = obj.entries[0].id; 
                }
                return u_id;
            }
       
    }
    function fetchfolderid(folder_name,user_id,owner_login){
            
            var up_folder_name = folder_name.replace(/ /g,"%20");
            var url = 'https://api.box.com/2.0/search?query='+up_folder_name+'&owner_user_ids='+user_id+'&type=folder';
            
            var httpRequest = new XMLHttpRequest();
            var token = "Bearer "+access_token;
            httpRequest.open('GET', url, false);
            httpRequest.setRequestHeader("authorization",token);
            httpRequest.setRequestHeader("cache-control", "no-cache");
            httpRequest.send();
            if (httpRequest.status === 200) {// That's HTTP for 'ok'
                obj = JSON.parse(httpRequest.responseText);
                if(obj.total_count == 0){
                    f_id = null;
                }
                else{
                    //alert(obj.entries[0].owned_by.login)
                    //alert(owner_login)
                    if (obj.entries[0].owned_by.login == owner_login){
                    //alert("Inside")
                    f_id = obj.entries[0].id;
                    console.log(f_id)
                    }
                    else{
                        f_id = null;
                    }
                }
                return f_id;
            }
        
    }
    
    function changePermission(email_id,f_id){
            var i,j=0,k;
            var url = 'https://api.box.com/2.0/folders/'+f_id+'/collaborations';
            //alert(url);
            var httpRequest = new XMLHttpRequest();
            var token = "Bearer "+access_token;
            httpRequest.open('GET', url, false);
            httpRequest.setRequestHeader("authorization",token);
            httpRequest.setRequestHeader("cache-control", "no-cache");
            httpRequest.send();
            if (httpRequest.status === 200) {// That's HTTP for 'ok'
                obj = JSON.parse(httpRequest.responseText);
                
                for(i=0;i<obj.total_count;i++){
                    if(obj.entries[i].accessible_by != null){
                        if(obj.entries[i].accessible_by.login == email_id){
                            if(obj.entries[i].role == "co-owner"){
                                colab_id = obj.entries[i].id;
                                //j++;
                                var url1 = 'https://api.box.com/2.0/collaborations/'+colab_id;
                                var data = JSON.stringify({
                                              'role': 'editor' 
                                            });
                                var httpRequest1 = new XMLHttpRequest();
                                var token1 = "Bearer "+access_token;
                                httpRequest1.open('PUT', url1,false);
                                httpRequest1.setRequestHeader("authorization",token1);
                                httpRequest1.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                                httpRequest1.setRequestHeader("cache-control", "no-cache");
                                httpRequest1.send(data);
                                //alert(httpRequest1.responseText);

                                if (httpRequest1.status === 200) {// That's HTTP for 'ok'
                                    obj = JSON.parse(httpRequest1.responseText);
                                   
                                    alert("User "+email_id+" changed from co-owner to Editor!! Thank you")
                                    //console.log(obj);
                                }
                                else{
                                    alert("Error")
                                }
                            }
                        }   
                    }  
                }
                
            }
          
    }

   
   
</script>

<!--<input type="button" onClick='readTextFile("http://localhost/sample/test2.csv")' value="Permission" /> -->
</html>