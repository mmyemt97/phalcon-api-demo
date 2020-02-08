<?php
namespace Website\Repos;

use SVCodebase\Library\Utils;
use Website\Constant;
use Website\Models\PlanWeeklyExpectedRevenue;
use Website\Models\PlanWeeklyTargetComment;
use Website\Models\SalesRevenueAchievement;

class PlanWeeklyExpectedRevenueRepo extends BaseRepo
{
    protected $authService;

    public function __construct()
    {
        $this->authService = new \Website\Services\AuthService();
    }

    public function getPlanTeamByWeek()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $staff_id = $this->request->getQuery('staff_id'); // string or array

        $team_id = $this->request->getQuery('team_id',['int']);
        $teams = $this->authService->filterTeams($team_id);

        $weekYear = $this->request->getQuery('week_year');
        $dates = $this->listWeek($weekYear);

        foreach ($teams as $indexTeam => $team) {

            foreach ($dates as $keyDate => $date) {
                $week = (int)$date->format('W');
                $year = (int)$date->format('Y');
                $date_start = $date->format('d/m');
                $date_end = $date->modify('+6 days')->format('d/m');

                $p = PlanWeeklyExpectedRevenue::buildModel();
                $p->columns(['SUM(revenue) AS total_revenue_target']);
                $p->wheres('team_id', '=', $team['id']);
                $p->wheres('week', '=', $week);
                $p->wheres('year', '=', $year);
                $p->groupBy("team_id,week");
                $p->orderBy( "week ASC");
                $revenueWeek = $p->findFirst();
                $total_revenue_target = $revenueWeek ? $revenueWeek->total_revenue_target : 0;

                $s = SalesRevenueAchievement::buildModel();
                $s->columns(['SUM(quantity*revenue) AS total_revenue_real']);
                $s->wheres('team_id', '=', $team['id']);
                $s->wheres('week', '=', $week);
                $s->wheres('year', '=', $year);
                $s->groupBy("team_id,week,year");
                $s->orderBy( "week ASC");
                $revenueSale = $s->findFirst();
                $total_revenue_real = $revenueSale ? $revenueSale->total_revenue_real : 0;

                $result[$keyDate]['team_id'] = $team['id'];
                $result[$keyDate]['week'] = $week;
                $result[$keyDate]['total_revenue_target'] = $total_revenue_target;
                $result[$keyDate]['total_revenue_real'] = $total_revenue_real;

                $result[$keyDate]['date_start'] = $date_start;
                $result[$keyDate]['date_end'] = $date_end;
            }

            //calculator
            $temp = $result;
            $currentRevenueReal = $result['current']['total_revenue_real'];

            $resultBack = array_splice($temp, -4);
            $totalRevenue4Week = array_sum(array_column($resultBack, 'total_revenue_real'));

            $calc['avarage_4_week'] = round($totalRevenue4Week / 4, 0);
            $calc['compare_target_current'] = Utils::percent($currentRevenueReal, $result['current']['total_revenue_target']);
            $calc['compare_target_back_1'] = Utils::percent($currentRevenueReal, $result['back_1']['total_revenue_real']);
            $calc['compare_target_4_week'] = Utils::percent($currentRevenueReal, $calc['avarage_4_week']);

            $teams[$indexTeam]['result'] = $result;
            $teams[$indexTeam]['calc'] = $calc;
        }

        return $teams;
    }

    public function getPlanTeamMemberOfTeam()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $team_id = $this->request->getQuery('team_id', ['int']);
        $staff_id = $this->request->getQuery('staff_id'); // string or array
        $members = $this->authService->filterMemberByTeamId($team_id, $staff_id);

        $weekYear = $this->request->getQuery('week_year');
        $dates = $this->listWeek($weekYear);

        foreach ($members as $index => $member) {

            foreach ($dates as $keyDate => $date) {
                $week = (int)$date->format('W');
                $year = (int)$date->format('Y');
                $date_start = $date->format('d/m');
                $date_end = $date->modify('+6 days')->format('d/m');

                $p = PlanWeeklyExpectedRevenue::buildModel();
                $p->columns(['SUM(revenue) AS total_revenue_target']);
                $p->wheres('team_id', '=', $team_id);
                $p->wheres('staff_id', '=', $member['id']);
                $p->wheres('week', '=', $week);
                $p->wheres('year', '=', $year);
                $p->groupBy("week,year,staff_id");
                $p->orderBy( "week ASC");
                $revenueWeek = $p->findFirst();
                $total_revenue_target = $revenueWeek ? $revenueWeek->total_revenue_target : 0;

                $s = SalesRevenueAchievement::buildModel();
                $s->columns(['SUM(quantity*revenue) AS total_revenue_real']);
                $s->wheres('team_id', '=', $team_id);
                $s->wheres('staff_id', '=', $member['id']);
                $s->wheres('week', '=', $week);
                $s->wheres('year', '=', $year);
                $s->groupBy("week,year,staff_id");
                $s->orderBy( "week ASC");
                $revenueSale = $s->findFirst();

                $total_revenue_real = (!empty($revenueSale)) ? $revenueSale->total_revenue_real : 0;

                $result[$keyDate]['team_id'] = $team_id;
                $result[$keyDate]['staff_id'] = $member['id'];
                $result[$keyDate]['week'] = $week;
                $result[$keyDate]['total_revenue_target'] = $total_revenue_target;
                $result[$keyDate]['total_revenue_real'] = $total_revenue_real;

                $result[$keyDate]['date_start'] = $date_start;
                $result[$keyDate]['date_end'] = $date_end;
            }

            //calculator
            $temp = $result;
            $currentRevenueReal = $result['current']['total_revenue_real'];

            $resultBack = array_splice($temp, -4);
            $totalRevenue4Week = array_sum(array_column($resultBack, 'total_revenue_real'));

            $calc['avarage_4_week'] = round($totalRevenue4Week / 4, 0);
            $calc['compare_target_current'] = Utils::percent($currentRevenueReal, $result['current']['total_revenue_target']);
            $calc['compare_target_back_1'] = Utils::percent($currentRevenueReal, $result['back_1']['total_revenue_real']);
            $calc['compare_target_4_week'] = Utils::percent($currentRevenueReal, $calc['avarage_4_week']);

            $members[$index]['result'] = $result;
            $members[$index]['calc'] = $calc;
        }

        return $members;
    }

    private function listWeek($weekYear)
    {
        $dt = explode('/', $weekYear);
        $week = $dt[0];
        $year = $dt[1];

        $current = (new \DateTimeImmutable())->setISODate($year, $week);

        $weeks = [
            'current' => $current,
            'next' => $current->modify('+1 weeks'),
            'back_1' => $current->modify('-1 weeks'),
            'back_2' => $current->modify('-2 weeks'),
            'back_3' => $current->modify('-3 weeks'),
            'back_4' => $current->modify('-4 weeks'),
        ];

        return $weeks;
    }

    public function reportPlanWeeklyRevenue()
    {
        $branch_code = $this->request->getQuery('branch_code');

        $auth = $this->auth->user();
        $team_id = $auth->team->id;
        $members = $this->authService->getMemberByTeamId($team_id);

        $weekYear = $this->request->getQuery('week_year');
        $dates = $this->listWeek($weekYear);

        foreach ($members as $index => $member) {

            foreach ($dates as $keyDate => $date) {
                $week = (int)$date->format('W');
                $year = (int)$date->format('Y');
                $date_start = $date->format('d/m');
                $date_end = $date->modify('+6 days')->format('d/m');

                $p = PlanWeeklyExpectedRevenue::buildModel();
                $p->columns(['SUM(revenue) AS total_revenue_target']);
                $p->wheres('team_id', '=', $team_id);
                $p->wheres('staff_id', '=', $member['id']);
                $p->wheres('week', '=', $week);
                $p->wheres('year', '=', $year);
                $p->groupBy("week,year,staff_id");
                $p->orderBy( "week ASC");
                $revenueWeek = $p->findFirst();
                $total_revenue_target = $revenueWeek ? $revenueWeek->total_revenue_target : 0;

                $s = SalesRevenueAchievement::buildModel();
                $s->columns(['SUM(quantity*revenue) AS total_revenue_real']);
                $s->wheres('team_id', '=', $team_id);
                $s->wheres('staff_id', '=', $member['id']);
                $s->wheres('week', '=', $week);
                $s->wheres('year', '=', $year);
                $s->groupBy("week,year,staff_id");
                $s->orderBy( "week ASC");
                $revenueSale = $s->findFirst();

                $total_revenue_real = (!empty($revenueSale)) ? $revenueSale->total_revenue_real : 0;

                $result[$keyDate]['team_id'] = $team_id;
                $result[$keyDate]['staff_id'] = $member['id'];
                $result[$keyDate]['week'] = $week;
                $result[$keyDate]['year'] = $year;
                $result[$keyDate]['total_revenue_target'] = $total_revenue_target;
                $result[$keyDate]['total_revenue_real'] = $total_revenue_real;

                $result[$keyDate]['date_start'] = $date_start;
                $result[$keyDate]['date_end'] = $date_end;
            }

            //calculator
            $temp = $result;
            $currentRevenueReal = $result['current']['total_revenue_real'];

            $resultBack = array_splice($temp, -4);
            $totalRevenue4Week = array_sum(array_column($resultBack, 'total_revenue_real'));

            $calc['avarage_4_week'] = round($totalRevenue4Week / 4, 0);
            $calc['compare_target_current'] = Utils::percent($currentRevenueReal, $result['current']['total_revenue_target']);
            $calc['compare_target_back_1'] = Utils::percent($currentRevenueReal, $result['back_1']['total_revenue_real']);
            $calc['compare_target_4_week'] = Utils::percent($currentRevenueReal, $calc['avarage_4_week']);

            $members[$index]['result'] = $result;
            $members[$index]['calc'] = $calc;
        }

        return $members;
    }

    public function createOrUpdateWeeklyRevenueByMember()
    {
        $request = (array)$this->request->getJsonRawBody(true);
        $weekYear = $request['week_year'];
        $dt = explode('/', $weekYear);
        $request['week'] = (int)$dt[0];
        $request['year'] = (int)$dt[1];

        $weeklyRevenue = PlanWeeklyExpectedRevenue::buildModel()
            ->wheres('staff_id', '=', $request['staff_id'])
            ->wheres('team_id', '=', $request['team_id'])
            ->wheres('week', '=', $request['week'])
            ->wheres('year', '=', $request['year'])
            ->findFirst();

        if($weeklyRevenue){
            $params['revenue'] = $request['revenue'];
            $weeklyRevenue->update($params);
            return $weeklyRevenue;
        }

        $more = [
            'channel_code' => $this->auth->userChannelCode(),
            'created_source' => Constant::CREATED_SOURCE_crm,
        ];

        $params = array_merge($request, $more);
        $result = new PlanWeeklyExpectedRevenue();
        $result->create($params);
        return $result;
    }

    public function getCommentInWeek()
    {
        $request = $this->request->getQuery();
        $branch_code = $request['branch_code'];
        //cần sẽ gọi bên service để lấy channel_code hoặc lấy từ jwt user để lấy channel_code
        $channel_code = explode('.', $branch_code)[0];
        $dt = explode('/', $request['week_year']);
        $week = $dt[0];
        $year = $dt[1];

        $comment = PlanWeeklyTargetComment::buildModel()
            ->wheres('channel_code', '=', $channel_code)
            ->wheres('week', '=', $week)
            ->wheres('year', '=', $year)
            ->findFirst();

        return $comment;
    }

    public function createOrUpdateCommentInWeek()
    {
        $request = (array)$this->request->getJsonRawBody(true);

        $branch_code = $request['branch_code'];
        $channel_code = explode('.',$branch_code)[0];
        $comment = $request['comment'];
        $dt = explode('/', $request['week_year']);
        $week = $dt[0];
        $year = $dt[1];

        $auth = $this->auth->user();

        $result = PlanWeeklyTargetComment::buildModel()
            ->wheres('channel_code', '=', $channel_code)
            ->wheres('week', '=', $week)
            ->wheres('year', '=', $year)
            ->findFirst();

        $params = [
            'channel_code' => $auth->channel_code,
            'team_id' => $auth->team->id,
            'staff_id' => $auth->id,
            'week' => $week,
            'year' => $year,
            'comment' => $comment,
            'created_source' => Constant::CREATED_SOURCE_crm
        ];

        if (!empty($result)) {
            $result->update($params);
            return $result;
        }

        $comment = new PlanWeeklyTargetComment();
        $comment->create($params);
        return $comment;
    }
}