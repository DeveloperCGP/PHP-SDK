<?php
include __DIR__ . '/../../vendor/autoload.php';

use AddonPaymentsSDK\NotificationModel\Transaction;

$quixNotification = file_get_contents(__DIR__ . '/notification_samples/quix_notification.xml');

// passing the QuixNotification string as a parameter into notification parser
$notifiction = new Transaction($quixNotification);
$status =  $notifiction->getOperations()->getPaymentSolutionOperation()->getStatus();
echo "Status: " . $status . "</br>";
if($status == 'SUCCESS'){
   echo "Transaction Completed Successfully";
}else {
   echo "Transaction Not Completed";
}