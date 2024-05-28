<?php
include __DIR__ . '/../../vendor/autoload.php';
use AddonPaymentsSDK\NotificationHandler;
use AddonPaymentsSDK\NotificationModel\Transaction;

// Usage
$handler = new NotificationHandler();
$handler->setNotificationCallback(function($xml) {
    $transaction = new Transaction($xml);
    $status = $transaction->getOperations()->getPaymentSolutionOperation()->getStatus();
    if($status == 'SUCCESS'){
       echo "Transaction Completed Successfully";    
    }else {
       echo "Transaction Not Completed";
    }
});
$handler->handleNotification();