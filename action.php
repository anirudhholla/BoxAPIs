<?php
$target_dir = "C:/xampp/htdocs/sample/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$file_name = basename($target_file);
$whole_path = "http://localhost/sample/$file_name";

$uploadOk = 1;
$excelFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.openxmlformats-officedocument.spreadsheetml.template','application/vnd.ms-excel.sheet.macroEnabled.12');
if(isset($_POST["submit"])) {
    /*
	$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    } */
	
	if(in_array($_FILES['fileToUpload']['type'],$mimes)){
	  // do something
	   $uploadOk = 1;
	} else {
	  die("Sorry, mime type not allowed");
	   $uploadOk = 0;
	}
}
// Check if file already exists
if (file_exists($target_file)) {
    $uploadOk = 1;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($excelFileType != "csv" && $excelFileType != "xlsx" && $excelFileType != "xls") {
    echo "Sorry, only CSV files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
         include 'get_data.php';
        ?>
        <script>
            var myvar = "<?php echo $whole_path; ?>";
            readTextFile(myvar);
        </script>
        <?php
       
        
        //header('Location: get_data.php');
		//echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
