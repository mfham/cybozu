<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Cybozu;

class IndexController extends Controller
{
    public function login(Request $request) {
        # ToDo: validation
        $cybozuSessionId = $request->cookie('CBSESSID');
        $cybozu = new Cybozu($cybozuSessionId);

        if ($cybozu->isLogin()) {
            # logged in
            return redirect()->route('home');
        } else {
            # not login
            # ToDo: show error message
            return view('login');
        }
    }
    public function home(Request $request) {
        $cybozu = new Cybozu();

        if ($request->isMethod('get')) {
            # ToDo: validation
            $cybozuSessionId = $request->cookie('CBSESSID');
            $cybozu->setCybozuCookie($cybozuSessionId);

            if ($cybozu->isLogin()) {
                # logged in
                return view('home');
            } else {
                # not login
                return redirect()->route('login');
            }

        } else if ($request->isMethod('post')) {
            $name = $request->input('username');
            $password = $request->input('password');

            if ($cybozuValue = $cybozu->UtilLogin($name, $password)) {
                # success
                return response()->view('home')->withCookie('CBSESSID', $cybozuValue, 5);
            } else {
                # error
                # ToDo: show error message
                return redirect()->route('login');
            }

        }
    }

    public function search(Request $request) {
        $cybozu = new Cybozu();
        $cybozuSessionId = $request->cookie('CBSESSID');
        $cybozu->setCybozuCookie($cybozuSessionId);

        if (!$cybozu->isLogin()) {
            return redirect()->route('login');
        }

        $weeks = $request->input('weeks');
        $minutes = $request->input('minutes');
        $userIds = $request->input('user_ids');
        $facilityIds = $request->input('facility_ids');

        # get ok schedule
        $schedule = $cybozu->getEmptySchedule($weeks, $minutes, $userIds, $facilityIds);

#        $schedule = $cybozu->ScheduleSearchFreeTimes(array(6,67), '2016-08-02T00:00:00', '2016-08-06T20:00:00', 60, $searchCondition = 'and');
#        var_dump(json_decode(json_encode($schedule), true));

        try {
#            $r = $cybozu->ScheduleGetEventsByTarget('2016-08-02T00:00:00', '2016-08-06T20:00:00', '15');
#            $r = $cybozu->ScheduleGetFacilitiesById('15');
            var_dump($schedule);
#            var_dump($cybozu->__getLastRequest());
#            var_dump($cybozu->__getLastRequestHeaders());
#            var_dump($cybozu->__getLastResponse());
#            var_dump($cybozu->__getLastResponseHeaders());
            exit;
        } catch (\SoapFault $e) {
#            var_dump($cybozu->__getLastRequest());
#            var_dump($cybozu->__getLastRequestHeaders());
#            var_dump($cybozu->__getLastResponse());
#            var_dump($cybozu->__getLastResponseHeaders());
        }

        return view('result', ['schedule' => $schedule]);
    }
}
