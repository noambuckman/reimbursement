<?php 

if(isset($_POST['submit_reimbursement'])){
    $to = "aepi-exchequer@mit.edu"; // this is your Email address
    $from = $_SERVER['SSL_CLIENT_S_DN_Email']; // this is the sender's Email address
    $first_name = $_SERVER['SSL_CLIENT_S_DN_CN'];    
    $message = "Payment to " . $first_name . "\n\n";    
    $headers = "From: " . $from . "\r\n";
    $headers .= "CC: " . $from . "\r\n";
    $send_date = date_create();
    $subject = "6_1_3 AEPi Reimbursement: " . $first_name . "_" . date_format($send_date, 'Y-m-d H:i:s');
    define("UPLOAD_DIR", "receipts/");
    $csv_data = array();
    for ($i=1; $i<=5; $i++){
        upload_receipt($i);

    }
    $message = wordwrap($message, 70);
    mail($to,$subject,$message,$headers);
    echo "Thank you for submitting a reimbursement.  You will receive a confirmation email at your MIT email.";

    ////Make a record online
    $fp = fopen(UPLOAD_DIR . '/reimbursements.csv', 'a+');

    foreach ($csv_data as $fields) {
        fputcsv($fp, $fields);
    }

    fclose($fp);

     $file = fopen("reimbursements.html","a+");
     fwrite($file, $message);
     fclose($file);


}    


    /*$message = "Cost #1: $" . $cost_1_value . " and it was for" . $description_1_value . "\n\n"
    		 . "Cost #2: $" . $cost_2_value . "  Description:" . $description_2_value;
    */
    // You can also use header('Location: thank_you.php'); to redirect to another page.

function upload_receipt($num){
    if ($_FILES["receipt".$num]) {
        $file = $_FILES["receipt".$num];
        $cost_value = $_POST["cost" . $num];
        $description_value = $_POST["description" . $num];
        $date_value = $_POST["date_reimbursement" . $num];

        if ($file["error"] !== UPLOAD_ERR_OK) {
            return;
        }

        if (($file["type"] !== "image/png") &&
            ($file["type"] !== "image/jpeg") &&
            ($file["type"] !== "image/jpg") &&
            ($file["type"] !== "application/pdf") ){
            return;
        }

        if ($file["size"] / 1024 / 1024 > 2){
            return;
        }

        // ensure a safe filename
        $name = preg_replace("/[^A-Z0-9._-]/i", "_", $file["name"]);
     
        // don't overwrite an existing file
        $i = 0;
        $parts = pathinfo($name);
        while (file_exists(UPLOAD_DIR . $name)) {
            $i++;
            $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
        }
     
        // preserve file from temporary directory
        $success = move_uploaded_file($file["tmp_name"],
            UPLOAD_DIR . $name);
        if (!$success) { 
            return;
        }
        // set proper permissions on the new file
        chmod(UPLOAD_DIR . $name, 0644);

        //Create Message to Be Sent In EMAIL
        global $message;
        $message .= "Date #" . $num . ":  " . $date_value . "\n";
        $message .= "Cost #" . $num . " :  $" . $cost_value . "\n";
        $message .= "Description #" . $num . ":  " . $description_value . "\n";
        $message .= "Receipt #" . $num . ":  https://nbuckman.scripts.mit.edu:444/" . UPLOAD_DIR . $name . "\n\n";

        global $csv_data;
        global $first_name;
        global $send_date;
        array_push($csv_data, array($send_date,$first_name,$description_value,$cost_value,$date_value));
    }

}

if(isset($_POST['submit_dinner'])){
    $to = "aepi-exchequer@mit.edu"; // this is your Email address
    $from = $_SERVER['SSL_CLIENT_S_DN_Email']; // this is the sender's Email address
    $first_name = $_SERVER['SSL_CLIENT_S_DN_CN'];    
    $message = "Sent from" . $first_name . "\n\n";    
    $headers = "From: " . $from . "\r\n";
    $headers .= "CC: " . $from . "\r\n";
    define("UPLOAD_DIR", "thursday_pics/");
    $send_date = date_create();
    upload_pic();
    if ($_POST['uno'] == "uno"){
        $message.= "Pay all of the reimbursement to:" . $first_name ;
    }
    $subject = "1_8 AEPi Thursday Dinner: " . $first_name . "_" . date_format($send_date, 'Y-m-d H:i:s');
    mail($to,$subject,$message,$headers);
    echo "Thank you for submitting the Thursday night dinner reimbursement. You will receive a confirmation email shortly";
}


function upload_pic(){
    if ($_FILES["pic"]) {
        $file = $_FILES["pic"];
        $date_value = $_POST["date_dinner"];

        if ($file["error"] !== UPLOAD_ERR_OK) {
            return;
        }

        if (($file["type"] !== "image/png") &&
            ($file["type"] !== "image/jpeg") &&
            ($file["type"] !== "image/jpg") &&
            ($file["type"] !== "application/pdf") ){
            return;
        }

        if ($file["size"] / 1024 / 1024 > 3){
            return;
        }

        // ensure a safe filename
        $name = preg_replace("/[^A-Z0-9._-]/i", "_", $file["name"]);
     
        // don't overwrite an existing file
        $i = 0;
        $parts = pathinfo($name);
        while (file_exists(UPLOAD_DIR . $name)) {
            $i++;
            $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
        }
     
        // preserve file from temporary directory
        $success = move_uploaded_file($file["tmp_name"],
            UPLOAD_DIR . $name);
        if (!$success) { 
            return;
        }
        // set proper permissions on the new file
        chmod(UPLOAD_DIR . $name, 0644);

        //Create Message to Be Sent In EMAIL
        
        global $message;
        
        $message .= "Date:  " . $date_value . "\n\n";
        $message .= "Brothers At Dinner: \n\n";
        for ($i=0; $i<=13; $i++){
                if (!empty($_POST["person" . $i])){
                    $message .= $_POST["person" . $i] . "\n";
                }
            }
        $message .= "\n";    
        $message .= "Picture:  https://nbuckman.scripts.mit.edu:444/" . UPLOAD_DIR . $name . "\n\n";  

    }
}

?>
