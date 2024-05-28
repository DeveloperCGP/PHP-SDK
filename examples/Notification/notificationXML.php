<?php
include __DIR__ . '/../../vendor/autoload.php';
use AddonPaymentsSDK\NotificationModel\Transaction;

$xmlNotification = file_get_contents(__DIR__ . '/notification_samples/notification.xml');



// passing the XML Notification string as a parameter into notification parser
$notifiction = new Transaction($xmlNotification);
$status = $notifiction->getOperations()->getPaymentSolutionOperation()->getStatus();
if($status == 'SUCCESS'){
   echo "Transaction Completed Successfully";
}else {
   echo "Transaction Not Completed";
}



