<?php
use PHPUnit\Framework\TestCase;
include __DIR__ . '/../vendor/autoload.php';
use AddonPaymentsSDK\NotificationModel\Transaction;
use AddonPaymentsSDK\QuixNotificationModel\QuixTransaction;

class NotificationTest extends TestCase
{
    private $xml_inside_json;

    private $notification_4907271141151723;
    private $notification_4907270002222227;
    private $notification_4907271141151707;

    private $notification_4012000000150084;
    private $notification_4907271141151715;
    private $notification_4012000000010080;
    private $notification_4012000000160083;
    private $notification_4012000000000081;

    

    protected function setUp(): void
    {
               
        
        $this->notification_4907270002222227 = file_get_contents(__DIR__ . '/notifications/4907270002222227.xml');
        $this->notification_4907270002222227 = file_get_contents(__DIR__ . '/notifications/4907270002222227.xml');
        $this->notification_4907271141151707 = file_get_contents(__DIR__ . '/notifications/4907271141151707.xml');
        $this->notification_4012000000150084 = file_get_contents(__DIR__ . '/notifications/4012000000150084.xml');
        $this->notification_4907271141151715 = file_get_contents(__DIR__ . '/notifications/4907271141151715.xml');
        $this->notification_4012000000010080 = file_get_contents(__DIR__ . '/notifications/4012000000010080.xml');
        $this->notification_4012000000160083 = file_get_contents(__DIR__ . '/notifications/4012000000160083.xml');
        $this->notification_4012000000000081 = file_get_contents(__DIR__ . '/notifications/4012000000000081.xml');
        $this->notification_4907271141151723 = file_get_contents(__DIR__ . '/notifications/4907271141151723.xml');

        $this->xml_inside_json = file_get_contents(__DIR__ . '/notifications/xml_inside_json.json');
        
        $this->notification_optional_transaction_paramters = file_get_contents(__DIR__ . '/notifications/optional_transactions_paramters.xml');
        $this->notification_optional_transaction_paramters_json = file_get_contents(__DIR__ . '/response/charge.json');
    }

    public function testCard4907270002222227()
    {
      

        $notification = new Transaction($this->notification_4907270002222227);
        $this->assertEquals(3, $notification->getOperations()->getOperationSize(), 'Operation Size should be "3"');
        $this->assertEquals('TRA', $notification->getOperations()->getTRAOperation()->getService(), 'Service Name should be "TRA"');
        $this->assertEquals('SUCCESS', $notification->getOperations()->getTRAOperation()->getStatus(), 'TRA Status should be "SUCCESS"');
        
        $this->assertEquals('3DSv2', $notification->getOperations()->getThreeDsOperation()->getService(), 'Service Name should be "3DSv2"');
        $this->assertEquals('SUCCESS3DS', $notification->getOperations()->getThreeDsOperation()->getStatus() , ' ThreeDsService should be "SUCCESS3DS"');
                
        $this->assertEquals('SUCCESS', $notification->getOperations()->getPaymentSolutionOperation()->getStatus(), 'PaymentSolution should be "SUCCESS"');
        $this->assertEquals('SUCCESS', $notification->getStatus(), 'Transaction should be "SUCCESS"');
    }

    public function testCard4907271141151707()
    {
       

        $notification = new Transaction($this->notification_4907271141151707);
        $this->assertEquals(3, $notification->getOperations()->getOperationSize(), 'Operation Size should be "3"');
        $this->assertEquals('TRA', $notification->getOperations()->getTRAOperation()->getService(), 'Service Name should be "TRA"');
        $this->assertEquals('SUCCESS', $notification->getOperations()->getTRAOperation()->getStatus(), 'TRA Status should be "SUCCESS"');
        
        $this->assertEquals('3DSv2', $notification->getOperations()->getThreeDsOperation()->getService(), 'Service Name should be "3DSv2"');
        $this->assertEquals('SUCCESS3DS', $notification->getOperations()->getThreeDsOperation()->getStatus() , ' ThreeDsService should be "SUCCESS3DS"');
        
        $this->assertEquals('ERROR', $notification->getOperations()->getPaymentSolutionOperation()->getStatus(), 'PaymentSolution should be "ERROR"');
        $this->assertEquals('SUCCESS', $notification->getStatus(), 'Transaction should be "SUCCESS"');
    }

   
    public function testCard4907271141151715()
    {
       

        $notification = new Transaction($this->notification_4907271141151715);
        $this->assertEquals(3, $notification->getOperations()->getOperationSize(), 'Operation Size should be "3"');
        $this->assertEquals('TRA', $notification->getOperations()->getTRAOperation()->getService(), 'Service Name should be "TRA"');
        $this->assertEquals('SUCCESS', $notification->getOperations()->getTRAOperation()->getStatus(), 'TRA Status should be "SUCCESS"');
        
        $this->assertEquals('3DSv2', $notification->getOperations()->getThreeDsOperation()->getService(), 'Service Name should be "3DSv2"');
        $this->assertEquals('SUCCESS3DS', $notification->getOperations()->getThreeDsOperation()->getStatus() , ' ThreeDsService should be "SUCCESS3DS"');
       
        $this->assertEquals('ERROR', $notification->getOperations()->getPaymentSolutionOperation()->getStatus(), 'PaymentSolution should be "ERROR"');
        $this->assertEquals('SUCCESS', $notification->getStatus(), 'Transaction should be "SUCCESS"');
    }

    public function testCard4012000000010080()
    {
       

        $notification = new Transaction($this->notification_4012000000010080);
        $this->assertEquals(2, $notification->getOperations()->getOperationSize(), 'Operation Size should be "2"');
        $this->assertEquals('TRA', $notification->getOperations()->getTRAOperation()->getService(), 'Service Name should be "TRA"');
        $this->assertEquals('SUCCESS', $notification->getOperations()->getTRAOperation()->getStatus(), 'TRA Status should be "SUCCESS"');
               
        $this->assertEquals('3DSv2', $notification->getOperations()->getThreeDsOperation()->getService(), 'Service Name should be "3DSv2"');
        $this->assertEquals('ERROR3DS', $notification->getOperations()->getThreeDsOperation()->getStatus() , ' ThreeDsService should be "ERROR3DS"');
        $this->assertEquals('Not authenticated', $notification->getOperations()->getThreeDsOperation()->getPaymentMessage() , ' Payment message shoud be "Not authenticated"');

        $this->assertEquals(null, $notification->getOperations()->getPaymentSolutionOperation()->getStatus(), 'PaymentSolution should be null');
        $this->assertEquals('ERROR', $notification->getStatus(), 'Transaction should be "ERROR"');
    }

    public function testCard4012000000160083()
    {
       

        $notification = new Transaction($this->notification_4012000000160083);
        $this->assertEquals(2, $notification->getOperations()->getOperationSize(), 'Operation Size should be "2"');
        $this->assertEquals('TRA', $notification->getOperations()->getTRAOperation()->getService(), 'Service Name should be "TRA"');
        $this->assertEquals('SUCCESS', $notification->getOperations()->getTRAOperation()->getStatus(), 'TRA Status should be "SUCCESS"');

        $this->assertEquals('3DSv2', $notification->getOperations()->getThreeDsOperation()->getService(), 'Service Name should be "3DSv2"');
        $this->assertEquals('ERROR3DS', $notification->getOperations()->getThreeDsOperation()->getStatus() , ' ThreeDsService should be "ERROR3DS"');
        $this->assertEquals('Not authenticated because the issuer is rejecting authentication', $notification->getOperations()->getThreeDsOperation()->getPaymentMessage() , ' Payment message shoud be "Not authenticated because the issuer is rejecting authentication"');
        
        $this->assertEquals(null, $notification->getOperations()->getPaymentSolutionOperation()->getStatus(), 'PaymentSolution should be null');
        $this->assertEquals('ERROR', $notification->getStatus(), 'Transaction should be "ERROR"');
    }

    public function testCard4012000000000081()
    {
       

        $notification = new Transaction($this->notification_4012000000000081);
        $this->assertEquals(2, $notification->getOperations()->getOperationSize(), 'Operation Size should be "2"');
        $this->assertEquals('TRA', $notification->getOperations()->getTRAOperation()->getService(), 'Service Name should be "TRA"');
        $this->assertEquals('SUCCESS', $notification->getOperations()->getTRAOperation()->getStatus(), 'TRA Status should be "SUCCESS"');

        $this->assertEquals('3DSv2', $notification->getOperations()->getThreeDsOperation()->getService(), 'Service Name should be "3DSv2"');
        $this->assertEquals('ERROR3DS', $notification->getOperations()->getThreeDsOperation()->getStatus() , ' ThreeDsService should be "ERROR3DS"');
        $this->assertEquals('Not authenticated due to technical or other issue', $notification->getOperations()->getThreeDsOperation()->getPaymentMessage() , ' Payment message shoud be "Not authenticated due to technical or other issue"');
        
        $this->assertEquals(null, $notification->getOperations()->getPaymentSolutionOperation()->getStatus(), 'PaymentSolution should be null');
        $this->assertEquals('ERROR', $notification->getStatus(), 'Transaction should be "ERROR"');
    }

    public function testCard4012000000150084()
    {
       

        $notification = new Transaction($this->notification_4012000000150084);
        $this->assertEquals(3, $notification->getOperations()->getOperationSize(), 'Operation Size should be "3"');
        $this->assertEquals('TRA', $notification->getOperations()->getTRAOperation()->getService(), 'Service Name should be "TRA"');
        $this->assertEquals('SUCCESS', $notification->getOperations()->getTRAOperation()->getStatus(), 'TRA Status should be "SUCCESS"');
       
        $this->assertEquals('3DSv2', $notification->getOperations()->getThreeDsOperation()->getService(), 'Service Name should be "3DSv2"');
        $this->assertEquals('SUCCESS3DS', $notification->getOperations()->getThreeDsOperation()->getStatus() , ' ThreeDsService should be "SUCCESS3DS"');
        $this->assertEquals('Challenge: Authenticated successfully', $notification->getOperations()->getThreeDsOperation()->getMessage() , ' ThreeDs Message should be "Challenge: Authenticated successfully"');
        
        $this->assertEquals('SUCCESS', $notification->getOperations()->getPaymentSolutionOperation()->getStatus(), 'PaymentSolution should be "SUCCESS"');
        $this->assertEquals('SUCCESS', $notification->getStatus(), 'Transaction should be "SUCCESS"');
    }
    public function testCard4907271141151723()
    {
       

        $notification = new Transaction($this->notification_4907271141151723);
        $this->assertEquals(3, $notification->getOperations()->getOperationSize(), 'Operation Size should be "3"');
        $this->assertEquals('TRA', $notification->getOperations()->getTRAOperation()->getService(), 'Service Name should be "TRA"');
        $this->assertEquals('SUCCESS', $notification->getOperations()->getTRAOperation()->getStatus(), 'TRA Status should be "SUCCESS"');

        $this->assertEquals('3DSv2', $notification->getOperations()->getThreeDsOperation()->getService(), 'Service Name should be "3DSv2"');
        $this->assertEquals('SUCCESS3DS', $notification->getOperations()->getThreeDsOperation()->getStatus() , ' ThreeDsService should be "SUCCESS3DS"');

        $this->assertEquals('ERROR', $notification->getOperations()->getPaymentSolutionOperation()->getStatus(), 'PaymentSolution should be "ERROR"');
        $this->assertEquals("Denied 'Settle' operation with code: 180 message: Tarjeta ajena al servicio o no compatible.", $notification->getOperations()->getPaymentSolutionOperation()->getMessage() , ' ThreeDs Message should be "Denied \'Settle\' operation with code: 180 message: Tarjeta ajena al servicio o no compatible."');
        $this->assertEquals('SUCCESS', $notification->getStatus(), 'Transaction should be "SUCCESS"');
    }


    public function testXML_Inside_JSON()
    {
       
      
        $notification = new QuixTransaction($this->xml_inside_json);
        
        $this->assertEquals('898a0370-249b-43db-b604-e4ce5e7f120f', $notification->getOperation()?->getPaymentDetails()?->getExtraDetails()?->getNemuruCartHash() , ' NemuruCartHash should be "898a0370-249b-43db-b604-e4ce5e7f120f"');
        $this->assertEquals('LHb76UKXmwW78LUI9VCWnwP9NKv5Qljt', $notification->getOperation()?->getPaymentDetails()?->getExtraDetails()?->getNemuruAuthToken(), 'NemuruAuthToken should be "LHb76UKXmwW78LUI9VCWnwP9NKv5Qljt"');
    }

    public function testCardOptionalTransactionParamterJson()
    {
       

        $notification = new Transaction($this->notification_optional_transaction_paramters_json);
        
        $this->assertEquals('ValorN', $notification->getOptionalTransactionParams()['ClaveN'] , 'First entry should be "ecommerce"');
        $this->assertEquals('Valor1', $notification->getOptionalTransactionParams()['Clave1'] , 'Second entry should be "postman"');
    }
    public function testCardOptionalTransactionParamter()
    {
        $notification = new Transaction($this->notification_optional_transaction_paramters);
        $this->assertEquals('ecommerce', $notification->getOptionalTransactionParams()['product'] , 'First entry should be "ecommerce"');
        $this->assertEquals('postman', $notification->getOptionalTransactionParams()['platform'] , 'Second entry should be "postman"');
        $this->assertEquals('ecommerce', $notification->getOperations()->getPaymentSolutionOperation()->getOptionalTransactionParams()['product'] , 'First entry should be "ecommerce"');
        $this->assertEquals('postman', $notification->getOperations()->getPaymentSolutionOperation()->getOptionalTransactionParams()['platform'] , 'Second entry should be "postman"');
    }
}