<?php
namespace WwtcCoinSdk;
class wwtc{

    const API_URL = "https://wwtcapps.com/api/dev0.1/";
    protected $client;
    protected $secret;
    protected $apikey;
    protected $walletuser;
    protected $walletpass;
    protected $accesstoken;

    public function __construct()
    {
	
        $this->client = new \GuzzleHttp\Client();
    }

    public function setsecret($secret,$apikey){

        $this->secret = $secret;
        $this->apikey = $apikey;

    }

    protected function prepareAccessToken()
    {
        try{
            $url = "https://wwtcapps.com/api/dev0.1/auth";
            $value = ['grant_type' => "client_credentials"
            ];
	    
            $header = array('Authorization'=>'Basic ' .base64_encode($this->secret.":".$this->apikey),
            "Content-Type"=>"application/x-www-form-urlencoded;charset=UTF-8");
            $response = $this->client->post($url, ['query' => $value,'headers' => $header]);
            $result = json_decode($response->getBody()->getContents());
	    if($result->status==400){

		throw new Exception('Error!' .$result->result);

		}
            $this->accesstoken = $result->result;
	    
        }
        catch (Exception $e) {
	    
            $response = $this->statusCodeHandling($e);
            return $response;
        }
    }

    protected function callApi($method,$request,$post = [])
    {
        try{
            $this->prepareAccessToken();
            $url = self::API_URL . $request;
            
            $header = array('Authorization'=>'Bearer ' . $this->accesstoken, "Content-Type"=>"application/x-www-form-urlencoded;charset=UTF-8");
	    $post['token']=$this->accesstoken;
            $response = $this->client->request($method,$url, array('query' => $post,'headers' => $header));
	    $result = json_decode($response->getBody()->getContents());
	    if($result->status==400){

		throw new Exception('Error!' .$result->result);
		}
            
            return $result;
        } catch (Exception $e) {
            $response = $this->StatusCodeHandling($e);
            return $response;
        }
    }


    protected function statusCodeHandling($e)
    {
        $response = array("statuscode" => $e->getResponse()->getStatusCode(),
        "error" => json_decode($e->getResponse()->getBody(true)->getContents()));
        return $response;
    }


   public function getnewaddress(){

	return $this->callApi('post','getnewaddress');
   }

    public function listtransactions($limit=null, $offset=null){
	
	return $this->callApi('post','listtransactions',array('limit'=>$limit, 'offset'=>$offset));
    }

    public function sendcoin($address, $amount){
	
	return $this->callApi('post','sendcoin',array('address'=>$address, 'amount'=>$amount));
    }








}
