<?php

namespace App;

use SoapClient;

class Cybozu extends SoapClient
{
    /**
     * specify cybozu wsdl
     */
    public function __construct() {
        $wsdl = 'http://192.168.56.201/cgi-bin/mfham/grn.cgi?WSDL';
        $opts = array(
#            'trace' => 1,
            'soap_version' => SOAP_1_2
        );
        return parent::__construct($wsdl, $opts);
    }

    /**
     * need to replace to call cybozu api
     */
    public function __doRequest($request, $location, $action, $version, $one_way = 0) {
        $request = preg_replace('/<env:Action/', '<Action', $request);
        $request = preg_replace('/<\/env:Action/', '</Action', $request);
        $request = preg_replace('/<env:Timestamp/', '<Timestamp', $request);
        $request = preg_replace('/<\/env:Timestamp/', '</Timestamp', $request);

        return parent::__doRequest($request, $location, $action, $version, $one_way);
    }

    public function setHeader($apiName) {
       $expiresHeader = new \stdClass();
       $expiresHeader->Created = date("c");
       $expiresHeader->Expires = date("c", strtotime("+7 day"));
       $headers = array();
       $ns = 'http://www.w3.org/2003/05/soap-envelope';
       $headers[] = new \SOAPHeader($ns, 'Action', $apiName, true);
       $headers[] = new \SOAPHeader($ns, 'Security', '', true);
       $headers[] = new \SOAPHeader($ns, 'Timestamp', $expiresHeader, true);
       parent::__setSoapHeaders($headers);
    }
    /**
     * Login
     */
    public function UtilLogin($loginName, $password) {
        $this->setHeader('UtilLogin');

        $params = array();
        # ToDo: validation
        $params[] = new \SoapVar($loginName, XSD_STRING, null, null, 'login_name');
        $params[] = new \SoapVar($password, XSD_STRING, null, null, 'password');
        $p = new \SoapVar($params, SOAP_ENC_OBJECT);
        try {
            $result = parent::UtilLogin($p);
#            # set cookie
#            # $session_id = explode('=', explode(';', $result->cookie)[0])[1];
        } catch (\SoapFault $e){
#            var_dump($e);
            return false;
        }

        return true;
    }
}