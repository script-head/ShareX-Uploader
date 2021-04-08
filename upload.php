<?php
$secret_key = "fail";
$domain_url = "https://".$_SERVER["HTTP_HOST"];
$folder_destination = "";
$length_of_string = 10;

function RandomString($length)
{
    $string = "";
    $characters = array_merge(range(0,9), range("a", "z"), range("A", "Z"));
 
    for ($i=0; $i < $length; $i++)
	{
        $string .= $characters[mt_rand(0, count($characters) - 1)];
    }
    return $string;
}
 
if (isset($_POST["secret"]))
{
    if ($_POST["secret"] == $secret_key)
    {
        $filename = RandomString($length_of_string);
        $target_file = $_FILES["sharex"]["name"];
        $fileType = pathinfo($target_file, PATHINFO_EXTENSION);
 
        if (move_uploaded_file($_FILES["sharex"]["tmp_name"], $folder_destination.$filename.".".$fileType))
        {
            echo $domain_url."/".$filename.".".$fileType;
        }
        else
        {
           echo "File upload failed - The maximum file size limit is ".ini_get("upload_max_filesize")."B, and the maximum post size is ".ini_get("post_max_size")."B. Ensure that your file is not bigger than either of these limits. If your file is under these limits, then there is possibly permission issues.";
        }  
    }
    else
    {
        echo "Invalid secret key recieved.";
    }
}
else
{
    echo "No secret key specified.";
}
?>