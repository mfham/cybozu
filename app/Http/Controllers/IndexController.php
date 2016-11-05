<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Cybozu;

class IndexController extends Controller
{
    public function login(Request $request) {
        return view('login');
    }
    public function home(Request $request) {
        return view('home');
    }

    public function search(Request $request) {
        $cybozu = session('cybozu');
        $weeks = $request->input('weeks');
        $minutes = $request->input('minutes');
        $userIds = $request->input('user_ids');
        $facilityIds = $request->input('facility');

        $schedule = $cybozu->getEmptySchedule($weeks, $minutes, $userIds, $facilityIds);
#        $schedule = $cybozu->ScheduleSearchFreeTimes(array(6,67), '2016-08-02T00:00:00', '2016-08-06T20:00:00', 60, $searchCondition = 'and');
#        var_dump(json_decode(json_encode($schedule), true));

        try {
#            $r = $cybozu->ScheduleGetEventsByTarget('2016-08-02T00:00:00', '2016-08-06T20:00:00', '15');
#            $r = $cybozu->ScheduleGetFacilitiesById('15');
#            var_dump($schedule);exit;
#            var_dump($cybozu->__getLastRequest());
#            var_dump($cybozu->__getLastRequestHeaders());
#            var_dump($cybozu->__getLastResponse());
#            var_dump($cybozu->__getLastResponseHeaders());
        } catch (\SoapFault $e) {
#            var_dump($cybozu->__getLastRequest());
#            var_dump($cybozu->__getLastRequestHeaders());
#            var_dump($cybozu->__getLastResponse());
#            var_dump($cybozu->__getLastResponseHeaders());
        }

        return view('result', ['schedule' => $schedule]);
    }
}
