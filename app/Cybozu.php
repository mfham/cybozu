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
        $request = preg_replace('/<ns1:/', '<', $request);
        $request = preg_replace('/<\/ns1:/', '</', $request);
        $request = preg_replace('/<ns2:/', '<', $request);
        $request = preg_replace('/<\/ns2:/', '</', $request);

        return parent::__doRequest($request, $location, $action, $version, $one_way);
    }

    /**
     * Set Soap Header
     */
    public function setHeader($apiName) {
        # reset soap header
        parent::__setSoapHeaders();

        # set new soap header
        $expiresHeader = new \stdClass();
        $expiresHeader->Created = date("c");
        $expiresHeader->Expires = date("c", strtotime("+7 day"));
        $headers = array();
        $headers[] = new \SOAPHeader($this->ns, 'Action', $apiName, true);
        $headers[] = new \SOAPHeader($this->ns, 'Security', '', true);
        $headers[] = new \SOAPHeader($this->ns, 'Timestamp', $expiresHeader, true);

        return parent::__setSoapHeaders($headers);
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

        $params = new \StdClass();
        # ToDo: validation
        $params->login_name = $loginName;
        $params->password = $password;

        try {
            $result = parent::UtilLogin($params);
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

    /**
     * Get free schedule
     *
     * $searchTime minutes
     */
    public function ScheduleSearchFreeTimes($memberIds, $startDate, $endDate, $searchTime, $searchCondition = 'and') {
        $this->setHeader('ScheduleSearchFreeTimes');

        $params = new \StdClass();
        $params->search_time = date('H:i:s', $searchTime * 60);
        $params->search_condition = $searchCondition;
        foreach ($memberIds as $v) {
            $params->member[] = array('user' => array('id' => $v));
        }
        # ex. 'start' => '2016-08-02T00:00:00' (UTC)
        $params->candidate = array(
            'start' => $startDate,
            'end' => $endDate
        );
        try {
            return parent::ScheduleSearchFreeTimes($params);
        } catch (\SoapFault $e) {
            return false;
        }
    }

    /**
     * Get user information by id
     */
    public function BaseGetUsersById($userId) {
        $this->setHeader('BaseGetUsersById');

        $params = new \StdClass();
        $params->user_id = $userId;

        try {
            return parent::BaseGetUsersById($params);
        } catch (\SoapFault $e) {
            return false;
        }
    }

    /**
     * Get schedule by id
     * return information if someone use it
     * return empty if someone doesn't use it
     */
    public function ScheduleGetEventsByTarget($startDate, $endDate, $facilityId) {
        $this->setHeader('ScheduleGetEventsByTarget');

        $params = new \StdClass();
        # ex. 'start' => '2016-08-02T00:00:00' (UTC)
        $params->start = $startDate;
        $params->end = $endDate;
        $params->facility = array('id' => $facilityId);

        try {
            return parent::ScheduleGetEventsByTarget($params);
        } catch (\SoapFault $e) {
            return false;
        }
    }

    /**
     * Get facility information
     */
    public function ScheduleGetFacilitiesById($id) {
        $this->setHeader('ScheduleGetFacilitiesById');

        $params = new \StdClass();
        $params->facility_id = $id;

        return parent::ScheduleGetFacilitiesById($params);
    }
}
