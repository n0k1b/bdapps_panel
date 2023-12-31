<?php
namespace App\Classes;
use App\Classes\Core;
use App\Classes\SubscriptionException;
use Log;

class Subscription extends core{
    var $server;
    var $applicationId;
    var $password;
    
    
       public function getStatus($address){
		 
		 $this->server = 'https://developer.bdapps.com/subscription/getstatus';

        $arrayField = array("applicationId" => $this->applicationId,
            "password" => $this->password,
            "subscriberId" => $address
            );

        $jsonObjectFields = json_encode($arrayField);
        return $this->handleResponse2(json_decode($this->sendRequest($jsonObjectFields,$this->server)));
    }

			

    public function __construct($server,$applicationId,$password){
     
        $this->server = $server;
        $this->applicationId = $applicationId;
        $this->password = $password;
        
    }
	

    public function subscribe($subscriberId){
        
        $arrayField = array(
				        	"applicationId" => $this->applicationId, 
				            "password" => $this->password,
				            "version" => "1.0",
				            "subscriberId" => $subscriberId,
				            "action" => "1"
				        );
        $jsonObjectFields = json_encode($arrayField); 
        
        
        return $this->handleResponse(json_decode($this->sendRequest($jsonObjectFields,$this->server)));
    }
	
	public function unsubscribe($subscriberId){
        $arrayField = array(
				        	"applicationId" => $this->applicationId, 
				            "password" => $this->password,
				            "version" => "1.0",
				            "subscriberId" => $subscriberId,
				            "action" => "0"
				        );
        $jsonObjectFields = json_encode($arrayField); 
        return $this->handleResponse(json_decode($this->sendRequest($jsonObjectFields,$this->server)));
    }
    
    	private function handleResponse2($jsonResponse){
	    //file_put_contents('test2.txt',$jsonResponse); 
    
        if(empty($jsonResponse))
            throw new CassException('Invalid server URL', '500');
        
        $statusCode = $jsonResponse->statusCode;
        $statusDetail = $jsonResponse->statusDetail;
        //$requestId = $jsonResponse->requestId;
        // $subscriptionStatus = $jsonResponse->requestId;
        
        if(!(empty($jsonResponse->subscriptionStatus)))
            return $jsonResponse->subscriptionStatus;
        
        if(strcmp($statusCode, 'S1000')==0)
            return 'ok';
        else
            throw new CassException($statusDetail, $statusCode);
    }
	
	private function handleResponse($jsonResponse){
	    date_default_timezone_set("Asia/Dhaka");
	    $myfile = fopen("subscription_response_check.txt", "a+") or die("Unable to open file!");
        fwrite($myfile,json_encode($jsonResponse)." ".date("d/m/Y h:i:s")."\n");
	    //file_put_contents('test2.txt',json_encode($jsonResponse)); 
    
        if(empty($jsonResponse))
            throw new CassException('Invalid server URL', '500');
        
        $statusCode = $jsonResponse->statusCode;
        $statusDetail = $jsonResponse->statusDetail;
        
        
        
        if(!(empty($jsonResponse->subscriptionStatus)))
            return $jsonResponse->statusCode;
        
        if(strcmp($statusCode, 'S1000')==0)
            return $statusCode;
        else
            throw new CassException($statusDetail, $statusCode);
    }
}