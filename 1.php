<?php
if($_FILES["zip_file"]["name"]) {
	$filename = $_FILES["zip_file"]["name"];
	$source = $_FILES["zip_file"]["tmp_name"];
	$type = $_FILES["zip_file"]["type"];
	
	$name = explode(".", $filename);
	$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
	foreach($accepted_types as $mime_type) {
		if($mime_type == $type) {
			$okay = true;
			break;
		} 
	}
	
	$continue = strtolower($name[1]) == 'zip' ? true : false;
	if(!$continue) {
		$message = "The file you are trying to upload is not a .zip file. Please try again.";
	}

	$target_path = "C:/xampp/htdocs/useless".$filename;  // change this to the correct site path
    $target_file = $target_path . basename($_FILES["fileToUpload"]["name"]);
    $file_name = basename($target_file);
    $whole_path = "http://localhost/sample/$file_name";
	if(move_uploaded_file($source, $target_path)) {
		$zip = new ZipArchive();
		$x = $zip->open($target_path);
		if ($x === true) {
			$zip->extractTo("C:/xampp/htdocs/useless"); // change this to the correct site path
			$zip->close();
	
			unlink($target_path);
		}
		//$message = "Your .zip file was uploaded and unpacked.";
        include 'get_data.php';
        ?>
        <script>
            var myvar = "<?php echo $whole_path; ?>";
            readTextFile(myvar);
        </script>
        <?php
	} else {	
		$message = "There was a problem with the upload. Please try again.";
	}
}
?>
