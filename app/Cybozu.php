<?php

namespace App;

use App\Common;
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

        return Common::convertToArray(parent::UtilGetLoginUserId());
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
            return Common::convertToArray(parent::ScheduleSearchFreeTimes($params));
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
            return Common::convertToArray(parent::BaseGetUsersById($params));
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
            # 変なidを指定しても結果が帰ってきてしまう
            return Common::convertToArray(parent::ScheduleGetEventsByTarget($params));
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

        try {
            return Common::convertToArray(parent::ScheduleGetFacilitiesById($params));
        } catch (\SoapFault $e) {
            return false;
        }
    }

    /**
     * Get OK schedule
     */
    public function getEmptySchedule($weeks, $minutes, $userIds, $facilityIds) {
        # UTC
        # 現在日時を繰り上げた時間を開始時間
        # ToDo: フォームで開始時間を指定させる
        $currentDate = date("Y-m-d\TH:00:00");
        $endDate = date('c', strtotime("$weeks week"));

        $emptySchedule = array();

        # 1. user全員の共有空き時間取得
        # 2. 1の時間をループ。その時間に指定施設が空いているか確認。
        #    空いていたら同じ時間帯の他の施設はチェックしない。

        # 全員が空いている時間帯
        $freeSchedule = $this->ScheduleSearchFreeTimes(array($userIds), $currentDate, $endDate, $minutes, 'and');

        # 直近の時間帯からチェック
        foreach ($freeSchedule['candidate'] as $date) {
            # ひとつでも空いていたら、その時間帯はそれでチェック終了
            foreach ($facilityIds as $facilityId) {
                $result = $this->ScheduleGetEventsByTarget($date['start'], $date['end'], $facilityId);
                if (empty($result)) {
                    $emptySchedule[] = array(
                        'start' => $date['start'], # string
                        'end' => $date['end'],
                        'facilityId' => $facilityId,
                        'start_jst' => date("Y-m-d\TH:00:00", strtotime('+9 hour', strtotime($date['start']))),
                        'end_jst' => date("Y-m-d\TH:00:00", strtotime('+9 hour', strtotime($date['end'])))
                    );
                    break;
                }
            }
        }

        return $emptySchedule;
    }
}
