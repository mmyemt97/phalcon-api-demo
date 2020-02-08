<?php

namespace Website\Repos;

use SVCodebase\Library\UtilArr;
use SVCodebase\Library\Utils;
use Website\Models\SalesRevenueAchievement;
use Website\Models\PlanWeeklyExpectedRevenue;

class ProgressMonthlyRepo extends BaseRepo
{
    protected $authService;

    public function __construct()
    {
        $this->authService = new \Website\Services\AuthService();
    }

    public function getProcessMonthListTeam()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $team_id = $this->request->getQuery('team_id');
        $teams = UtilArr::keyBy('id', $this->authService->filterTeams($team_id));

        $monthYear = $this->request->getQuery('month_year');
        $dt = explode('/', $monthYear);
        $year = (int)$dt[1];
        $getDates = $this->listDayInMonth($monthYear);
        $weeks = $getDates['weekArray'];
        $arWeek = array_keys($weeks);

        $plan_target = PlanWeeklyExpectedRevenue::buildModel()
            ->columns([
                'SUM(revenue) AS total_target',
                'team_id'
            ])
            ->whereArray('week', $arWeek)
            ->wheres('year', '=', $year)
            ->groupBy('team_id')
            ->get()->toArray();
        $target = UtilArr::keyBy('team_id', $plan_target);
        $columnsSales = [
            'SUM(revenue * quantity) AS total_progress_week',
            'week',
            'team_id'
        ];
        foreach ($weeks as $keyWeek => $week) {
            $s = SalesRevenueAchievement::buildModel();
            $s->columns($columnsSales);
            $s->wheres('week', '=', $keyWeek);
            $s->wheres('year', '=', $year);
            $s->groupBy('team_id');
            $data = $s->get()->toArray();
            $progress[$keyWeek] = UtilArr::keyBy('team_id', $data);
        }

        foreach ($teams as $keyTeam => $team) {
            foreach ($progress as $keyProgress => $valueProgress) {
                $result['weeks'][$keyProgress] = array_key_exists($keyTeam, $valueProgress) ?
                    (int)$valueProgress[$keyTeam]['total_progress_week'] : 0;
            }
            $result['total_progress'] = array_sum($result['weeks']);
            $result['target'] = isset($target[$keyTeam]) ? $target[$keyTeam]['total_target'] : 0;
            $result['percent_completed'] = Utils::percent($result['total_progress'], $result['target']);
            $teams[$keyTeam]['result'] = $result;
        }

        return $teams;
    }

    public function getProcessMonthlyListStaff()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $monthYear = $this->request->getQuery('month_year');
        $team_id = $this->request->getQuery('team_id');
        $staff_id = $this->request->getQuery('staff_id');
        $getMembers = $this->authService->filterMemberByTeamId($team_id, $staff_id);
        $members = UtilArr::keyBy('id', $getMembers);
        $getDates = $this->listDayInMonth($monthYear);
        $weeks = $getDates['weekArray'];
        $dt = explode('/', $monthYear);
        $year = (int)$dt[1];
        $arWeek = array_keys($weeks);
        $columns = [
            'SUM(revenue * quantity) AS total_progress_week_of_member',
            'staff_id'
        ];
        $p = PlanWeeklyExpectedRevenue::buildModel();
        $p->columns([
            'SUM(revenue) AS total_target_of_member',
            'staff_id'
        ]);
        $p->whereArray('week', $arWeek);
        $p->wheres('year', '=', $year);
        $p->wheres('team_id', '=', $team_id);
        $p->groupBy('staff_id');
        $target = $p->get()->toArray();
        $target = UtilArr::keyBy('staff_id', $target);

        foreach ($weeks as $keyWeek => $week) {
            $s = SalesRevenueAchievement::buildModel();
            $s->columns($columns);
            $s->wheres('team_id', '=', $team_id);
            $s->wheres('week', '=', $keyWeek);
            $s->wheres('year', '=', $year);
            $s->groupBy('staff_id');
            $data = $s->get()->toArray();
            $progress[$keyWeek] = UtilArr::keyBy('staff_id', $data);
        }
        foreach ($members as $keyMember => $member) {
            foreach ($progress as $keyProgress => $valueProgress) {
                $result['weeks'][$keyProgress] = array_key_exists($keyMember, $valueProgress) ?
                    (int)$valueProgress[$keyMember]['total_progress_week_of_member'] : 0;
            }
            $result['total_progress'] = array_sum($result['weeks']);

            $result['target'] = array_key_exists($keyMember, $target) ?
                (int)$target[$keyMember]['total_target_of_member'] : 0;

            $result['percent_completed'] = Utils::percent($result['total_progress'], $result['target']);
            $members[$keyMember]['result'] = $result;
        }

        return $members;
    }

    public function getProcessWeekListTeam()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $monthYear = $this->request->getQuery('month_year');
        $team_id = $this->request->getQuery('team_id');
        $getDates = $this->listDayInMonth($monthYear);
        $dates = $getDates['listDay'];
        $arWeek = array_keys($getDates['weekArray']);
        $teams = UtilArr::keyBy('id', $this->authService->filterTeams($team_id));
        $columns = [
            'SUM(revenue * quantity) AS total_progress_day',
            'team_id'
        ];
        $dates = UtilArr::groupBy('key', $dates);
        $dt = explode('/', $monthYear);
        $year = $dt[1];
        $p = PlanWeeklyExpectedRevenue::buildModel();
        $p->whereArray('week', $arWeek);
        $p->wheres('year', '=', $year);
        $p->columns([
            'SUM(revenue) AS total_target',
            'team_id'
        ]);
        $p->groupBy('team_id');
        $target = $p->get()->toArray();
        $target = UtilArr::keyBy('team_id', $target);
        foreach ($dates as $keyDate => $date) {
            $arDate = array_column($date, 'date');
            $s = SalesRevenueAchievement::buildModel();
            $s->columns($columns);
            $s->whereArray('date', $arDate);
            $s->wheres('year', '=', $year);
            $s->groupBy('team_id');
            $data = $s->get()->toArray();
            $dataProgress[$keyDate] = UtilArr::keyBy('team_id', $data);
        }

        foreach ($teams as $keyTeam => $team) {
            foreach ($dataProgress as $keyProgress => $value) {
                $result['days'][$keyProgress] = array_key_exists($keyTeam, $value) ?
                    (int)$value[$keyTeam]['total_progress_day'] : 0;
            }

            $result['total_progress'] = array_sum($result['days']);

            $result['total_target'] = isset($target[$keyTeam]['total_target']) ?
                $target[$keyTeam]['total_target'] : 0;

            $result['percent_completed'] = Utils::percent($result['total_progress'], $result['total_target']);
            $teams[$keyTeam]['result'] = $result;
        }
        return $teams;
    }

    public function getProcessWeekListWeek()
    {
        $listNull = [
            'Mon' => null,
            'Tue' => null,
            'Wed' => null,
            'Thu' => null,
            'Fri' => null,
            'Sat' => null,
            'Sun' => null
        ];
        $result = array();
        $branch_code = $this->request->getQuery('branch_code');
        $monthYear = $this->request->getQuery('month_year');
        $team_id = $this->request->getQuery('team_id');
        $getDates = $this->listDayInMonth($monthYear);
        $listDay = $getDates['listDay'];

        $week = $this->request->getQuery('week_year');
        if ($week) {
            $d = explode('/', $week);
            $arWeek = [(int)$d[0]];
        } else {
            $arWeek = array_keys($getDates['weekArray']);
        }

        $dt = explode('/', $monthYear);
        $year = $dt[1];
        $p = PlanWeeklyExpectedRevenue::buildModel();
        $p->columns([
            'SUM(revenue) AS total_target',
            'week'
        ]);
        $p->whereArray('week', $arWeek);
        $p->wheres('year', '=', $year);
        $p->wheres('team_id', '=', $team_id);
        $p->groupBy('week');
        $target = $p->get()->toArray();
        $target = UtilArr::keyBy('week', $target);

        $columns = [
            'SUM(revenue * quantity) AS total_progress_day',
            'date'
        ];
        $s = SalesRevenueAchievement::buildModel();
        $s->columns($columns);
        $s->whereArray('week', $arWeek);
        $s->wheres('year', '=', $year);
        $s->wheres('team_id', '=', $team_id);
        $s->groupBy('date');
        $progress = $s->get()->toArray();
        $progress = UtilArr::keyBy('date', $progress);

        foreach ($listDay as $keyDay => $valueDay) {
            $listWeek[$valueDay['week']][$valueDay['key']] = array_key_exists($valueDay['date'], $progress) ?
                (int)$progress[$valueDay['date']]['total_progress_day'] : 0;
        }

        foreach ($listWeek as $keyWeek => $valueWeek) {
            if (in_array($keyWeek, $arWeek)) {
                $resultWeek['days'] = array_merge($listNull, $valueWeek);
                $resultWeek['target'] = array_key_exists($keyWeek, $target) ? (int)$target[$keyWeek]['total_target'] : 0;
                $resultWeek['total_progress'] = array_sum($valueWeek);
                $resultWeek['percent_completed'] = Utils::percent($resultWeek['total_progress'], $resultWeek['target']);
                $resultWeek['week'] = $keyWeek;
                $result[] = $resultWeek;
            }
        }
        return $result;
    }

    /**
     * @param $monthYear
     * @return array
     * @throws \Exception
     */
    private function listDayInMonth($monthYear)
    {
        $week = array();
        $result = array();
        $dt = explode('/', $monthYear);
        $month = (int)$dt[0];
        $year = (int)$dt[1];
        $date = new \DateTime();
        $date->setDate($year, $month, 1);
        $weekStart = (int)$date->format('W');
        $current = (new \DateTimeImmutable())->setISODate($year, $weekStart);

        if ($month == 12) {
            $yearAfter = $year + 1;
            $monthAfter = 0;
        } else {
            $yearAfter = $year;
            $monthAfter = $month;
        }
        for ($i = 0; $i < 6; $i++) {
            $dateStart = $current->modify('+' . $i . 'week');

            if ($dateStart >= $date->setDate($yearAfter, $monthAfter + 1, 1)) {
                break;
            }
            $week[(int)$dateStart->format('W')] = [
                'date_start' => $dateStart,
                'date_end' => $dateStart->modify('+6 day'),
            ];
            for ($j = 0; $j < 7; $j++) {
                $datesWeek = $dateStart->modify('+' . $j . 'day');
                if ((int)$datesWeek->format('Y') == $year) {
                    if ((int)$datesWeek->format('W') == 1 && (int)$datesWeek->format('m') == 12) {
                        $valWeek = 53;
                    } else {
                        $valWeek = (int)$datesWeek->format('W');
                    }
                    $result[] = [
                        'key' => $datesWeek->format('D'),
                        'date' => $datesWeek->format('Y-m-d'),
                        'week' => $valWeek,
                        'datetime' => $datesWeek
                    ];
                }
            }
        }
//         đổi start-end tuần 1,53 của tháng;
        if (isset($week[1]) && isset($week[2])) {
            $dateStart = $week[2]['date_start'];
            $week[1]['date_start'] = $dateStart->modify('first day of this month');
        }

        if (isset($week[53])) {
            $week[53]['date_end'] = $week[53]['date_start']->modify('last day of this month');
        }

        if (isset($week[1]) && isset($week[52])) {
            $dateStart = $week[1]['date_start'];
            $week[53] = $week[1];
            unset($week[1]);
            $week[53]['date_end'] = $dateStart->modify('last day of this month');
        }
        return $date = [
            'weekArray' => $week,
            'listDay' => $result
        ];
    }

    private function listDayInWeek($week, $year)
    {
        $current = (new \DateTimeImmutable())->setISODate($year, $week);
        for ($j = 0; $j < 7; $j++) {
            $day = $current->modify('+' . $j . 'day');
            $result[$day->format('D')] = $day;
//            $result[$day->format('D')] = $day->format('Y-m-d');
        }
        return $result;
    }

    public function getProcessWeekListStaff()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $weekYear = $this->request->getQuery('week_year');
        $team_id = $this->request->getQuery('team_id');
        $staff_id = $this->request->getQuery('staff_id');
        $dt = explode('/', $weekYear);
        $week = $dt[0];
        $year = $dt[1];
        $dates = $this->listDayInWeek($week, $year);
        $members = UtilArr::keyBy('id', $this->authService->filterMemberByTeamId($team_id, $staff_id));


        $p = PlanWeeklyExpectedRevenue::buildModel();
        $p->columns([
            'SUM(revenue) AS total_target_of_member',
            'staff_id'
        ]);
        $p->wheres('week', '=', $week);
        $p->wheres('year', '=', $year);
        $p->wheres('team_id', '=', $team_id);
        $p->groupBy('staff_id');
        $target = $p->get()->toArray();
        $target = UtilArr::keyBy('staff_id', $target);
        $columns = [
            'staff_id',
            'SUM(revenue * quantity) AS total_progress',
        ];
        foreach ($dates as $keyDate => $date) {
            if ((int)$date->format('Y') == $year) {
                $s = SalesRevenueAchievement::buildModel();
                $s->columns($columns);
                $s->wheres('team_id', '=', $team_id);
                $s->wheres('year', '=', $year);
                $s->wheres('week', '=', $week);
                $s->wheres('date', '=', $date->format('Y-m-d'));
                $s->groupBy('staff_id');
                $progress = $s->get()->toArray();
                $resultProgress[$keyDate] = UtilArr::keyBy('staff_id', $progress);
            } else {
                $resultProgress[$keyDate] = null; // ngày ko thuộc năm
            }

        }
        foreach ($members as $keyMember => $member) {

            foreach ($resultProgress as $keyProgress => $progress) {
                if (isset($progress)) {
                    $resultWeek[$keyProgress] = array_key_exists($keyMember, $progress) ?
                        (int)$progress[$keyMember]['total_progress'] :
                        $resultWeek[$keyProgress] = 0;
                } else {
                    $resultWeek[$keyProgress] = null;
                }
                $result['week'] = $resultWeek;
            }

            $result['target'] = array_key_exists($keyMember, $target) ?
                (int)$target[$keyMember]['total_target_of_member'] :
                $result['target'] = 0;

            $result['total_progress'] = array_sum($result['week']);
            $result['percent_completed'] = Utils::percent($result['total_progress'], $result['target']);
            $members[$keyMember]['result'] = $result;


        }

        return $members;
    }
}
