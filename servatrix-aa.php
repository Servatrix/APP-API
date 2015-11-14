<?php
/**
* A PHP class that acts as wrapper for the Servatrix APP API
*
* The API documentation is located at https://servatrix.pw/apidoc/
*
* Copyright 2015 Servatrix
* Licensed under the GNU GENERAL PUBLIC LICENSE
*
* Author: Razor [me.rzr.re]
* Version: 1.0.0
*/
 
 class ServatrixAA
 {
    private $timeout = 10;
    private $debug = false;
    
    private $appKey;
    private $endpointUrl;
    private $connectUrl;
    
    /**
    * Class constructor.
    *
    * @param string $appKey String containing the application key.
    *                       
    */
    public function __construct($appKey)
    {
        $this->appKey = $appKey;

        if (!preg_match('/^[a-f0-9]{32}$/', $this->appKey)){
            throw new Exception('You must provide a valid application key');
        }

        $this->endpointUrl = 'https://servatrix.pw/app.php';
        $this->connectUrl = 'https://servatrix.pw/?action=connect';
    }


    /**
    * **********************************
    * Connect functions
    * **********************************
    */
    /**
    * Returns the full connect URL for your application
    * Call it with an unique identifier for the user
    * The identifier will be added to the connect callback
    *
    * @param string $identifier An unique identifier for the user
    */
    public function buildConnectUrl($identifier)
    {
        return sprintf('%s&app=%s&identifier=%s', $this->connectUrl, $this->appKey, urlencode($identifier));
    }


    /**
    * **********************************
    * User functions
    * **********************************
    */
    /**
    * Returns an array of programs the user owns or null
    * Call it with the users token that you retreived from
    * the connect callback
    *
    * @param string $userToken
    */
    public function listPrograms($userToken)
    {
        $url = sprintf('%s?action=list_programs&app=%s&token=%s', $this->endpointUrl, $this->appKey, $userToken);
        $response = json_decode($this->get($url));
        if($response->error) throw new Exception($response->error_message);
        return $response->response;
    }

    /**
    * Creates and return a new serial
    * Call it with the users token that you retreived from
    * the connect callback
    *
    * @param string $userToken
    * @param string $productId The ID of the product you want to create the serial for
    * @param int $points The amount of points you want to asign to the serial
    * @param int $duration The duration of the serial in seconds
    * @param string $custom A custom field you can store tracking data in (Invoice ID?)
    */
    public function serialCreate($userToken, $productId, $points, $duration, $custom)
    {
        $url = sprintf('%s?action=serial_create&app=%s&token=%s&product=%s&points=%s&duration=%s&custom=%s', 
            $this->endpointUrl, $this->appKey, $userToken, $productId, $points, $duration, urldecode($custom));
        $response = json_decode($this->get($url));
        if($response->error) throw new Exception($response->error_message);
        return $response->response;
    }


    /**
    * This function performs a simple GET request using CURL
    * You can also use POST for requests if you want to
    *
    * @param string $url
    */
    private function get($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'ServatrixAA'
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
 }
