<?php
include __DIR__ . '/../../vendor/autoload.php';
use AddonPaymentsSDK\NotificationModel\Transaction;

$jsonNotification = file_get_contents(__DIR__ . '/notification_samples/notification.json');

// passing the JSON Notification string as a parameter into notification parser
$notifiction = new Transaction($jsonNotification);


$status = $notifiction->getOperations()->getPaymentSolutionOperation()->getStatus();
if($status == 'SUCCESS'){
   echo "Transaction Completed Successfully";
}else {
   echo "Transaction Not Completed";
}


