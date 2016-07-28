<?php

namespace App;

use SoapClient;

class Cybozu extends SoapClient
{
    /**
     * specify cybozu wsdl
     */
    public function __construct($cybozuSessionId = '') {
        $this->wsdl = 'http://192.168.56.201/cgi-bin/mfham/grn.cgi?WSDL';
        $this->ns = 'http://www.w3.org/2003/05/soap-envelope';
        $opts = array(
            'trace' => 1,
            'soap_version' => SOAP_1_2
        );
        if ($cybozuSessionId) {
            parent::__setCookie('CBSESSID', $cybozuSessionId);
        }
        return parent::__construct($this->wsdl, $opts);
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

    /**
     * Set Soap Header
     */
    public function setHeader($apiName) {
       $expiresHeader = new \stdClass();
       $expiresHeader->Created = date("c");
       $expiresHeader->Expires = date("c", strtotime("+7 day"));
       $headers = array();
       $headers[] = new \SOAPHeader($this->ns, 'Action', $apiName, true);
       $headers[] = new \SOAPHeader($this->ns, 'Security', '', true);
       $headers[] = new \SOAPHeader($this->ns, 'Timestamp', $expiresHeader, true);
       parent::__setSoapHeaders($headers);
    }

    /**
     *
     */
    public function setCybozuCookie($cybozuSessionId) {
        parent::__setCookie('CBSESSID', $cybozuSessionId);
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
            $cybozuSessionId = explode('=', explode(';', $result->cookie)[0])[1];
        } catch (\SoapFault $e){
            return false;
        }

        return $cybozuSessionId;
    }

    /**
     * Get User ID of user who execute API
     */
    public function UtilGetLoginUserId() {
        $this->setHeader('UtilGetLoginUserId');

        return parent::UtilGetLoginUserId();
    }

    /**
     * Check if user login
     */
    public function isLogin() {
        try {
            return $this->UtilGetLoginUserId();
        } catch (\SoapFault $e) {
            return false;
        }
    }
}