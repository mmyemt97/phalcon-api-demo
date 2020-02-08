<?php
namespace Website\Repos;

use SVCodebase\Library\UtilArr;
use Website\Models\PlanMonthlyExpectedRevenue;
use Website\ErrorMessage;
use Website\Constant;
use Website\StatusCode;

class PlanMonthlyExpectedRevenueRepo extends BaseRepo
{
    protected $authService;

    public function __construct()
    {
        $this->authService = new \Website\Services\AuthService();
    }

    public function getPlanTeamByMonth()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $monthYear = $this->request->getQuery('month_year');

        $team_id = $this->request->getQuery('team_id');
        $teams = $this->authService->filterTeams($team_id);

        $dates = $this->listMonth($monthYear);

        $alias = 'PlanMonthlyExpectedRevenue';
        $columns = [$alias . '.staff_id', $alias . '.month'];
        $sumColumns = ['realizable_revenue','realized_revenue','old_revenue','new_revenue'];
        foreach ($sumColumns as $collumn){
            $columns[] = "SUM(COALESCE({$alias}.{$collumn},0)) AS total_{$collumn}";
        }

        foreach ($teams as $indexTeam => $team) {

            foreach ($dates as $keyDate => $date) {
                $month = (int)$date->format('m');
                $year = (int)$date->format('Y');

                $p = PlanMonthlyExpectedRevenue::buildModel();
                $p->columns($columns);
                $p->whereIf($alias . '.team_id', '=', $team['id']);
                //$p->whereArray($aliasJoin . '.staff_id', $staff_id);
                $p->wheres($alias . '.month', '=', $month);
                $p->wheres($alias . '.year', '=', $year);
                $p->groupBy($alias . ".team_id");
                $p->groupBy($alias . ".month");
                $p->orderBy($alias . ".month ASC");
                $data = $p->findFirst();

                if (!$data) {
                    $null = [
                        'team_id' => $team['id'],
                        'month' => $month,
                        'total_realizable_revenue' => 0,
                        'total_realized_revenue' => 0,
                        'total_old_revenue' => 0,
                        'total_new_revenue' => 0,
                        'total_revenue' => 0,
                    ];
                    $result[$keyDate] = $null;
                } else {
                    $result[$keyDate] = $data->toArray();
                    $result[$keyDate]['total_revenue'] = $result[$keyDate]['total_old_revenue'] + $result[$keyDate]['total_new_revenue'];
                }
            }

            $teams[$indexTeam]['result'] = $result;
        }

        return $teams;
    }

    public function getPlanTeamMemberOfTeam()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $monthYear = $this->request->getQuery('month_year');

        $team_id = $this->request->getQuery('team_id');
        $staff_id = $this->request->getQuery('staff_id'); // string or array
        $members = $this->authService->filterMemberByTeamId($team_id, $staff_id);

        $dates = $this->listMonth($monthYear);

        $alias = 'PlanMonthlyExpectedRevenue';
        $columns = [$alias . '.id as plan_monthly_expected_revenue_id', $alias . '.staff_id', $alias . '.month', $alias . '.note'];
        $sumCollumns = ['realizable_revenue','realized_revenue','old_revenue','new_revenue'];
        foreach ($sumCollumns as $collumn){
            $columns[] = "SUM(COALESCE({$alias}.{$collumn},0)) AS total_{$collumn}";
        }

        foreach ($members as $indexMember => $member) {

            foreach ($dates as $keyDate => $date) {
                $month = (int)$date->format('m');
                $year = (int)$date->format('Y');

                $p = PlanMonthlyExpectedRevenue::buildModel();
                $p->columns($columns);
                $p->wheres($alias . '.team_id', '=', $team_id);
                $p->wheres($alias . '.staff_id', '=', $member['id']);
                $p->wheres($alias . '.month', '=', $month);
                $p->wheres($alias . '.year', '=', $year);
                $p->groupBy($alias . '.team_id');
                $p->groupBy($alias . '.month');
                $p->orderBy($alias . '.month ASC');
                $data = $p->findFirst();

                if (!$data) {
                    $null = [
                        'plan_monthly_expected_revenue_id' => 0,
                        'staff_id' => $member['id'],
                        'month' => $month,
                        'note' => '',
                        'total_realizable_revenue' => 0,
                        'total_realized_revenue' => 0,
                        'total_old_revenue' => 0,
                        'total_new_revenue' => 0,
                        'total_revenue' => 0
                    ];
                    $result[$keyDate] = $null;
                } else {
                    $result[$keyDate] = $data->toArray();
                    $result[$keyDate]['total_revenue'] = $result[$keyDate]['total_old_revenue'] + $result[$keyDate]['total_new_revenue'];
                }
            }

            $members[$indexMember]['result'] = $result;
        }

        return $members;
    }

    private function listMonth($monthYear)
    {
        $dt = explode('/', $monthYear);
        $month = (int)$dt[0];
        $year = (int)$dt[1];

        $current = new \DateTimeImmutable($year.'-'.$month);

        $months = [
            'current' => $current,
            'back_1' =>  $current->modify('-1 months'),
            //'back_2' =>  $current->modify('-2 months'),
            //'back_3' =>  $current->modify('-3 months'),
            'back_4' =>  $current->modify('-4 months'),
        ];

        return $months;
    }

    public function createOrUpdateRevenueByMember()
    {
        $request = (array)$this->request->getJsonRawBody(true);

        $id = $request['plan_monthly_expected_revenue_id'];

        return $id > 0 ? $this->updateRevenueByMember($id) : $this->createRevenueByMember();
    }

    public function createRevenueByMember()
    {
        $request = (array)$this->request->getJsonRawBody(true);
        $monthYear = $request['month_year'];
        $dt = explode('/', $monthYear);
        $request['month'] = (int)$dt[0];
        $request['year'] = (int)$dt[1];

        $check = PlanMonthlyExpectedRevenue::buildModel()
            ->wheres('staff_id', '=', $request['staff_id'])
            ->wheres('team_id', '=', $request['team_id'])
            ->wheres('month', '=', $request['month'])
            ->wheres('year', '=', $request['year'])
            ->count();

        if ($check > 0) {
            throw new \InvalidArgumentException(ErrorMessage::INVALID_DATA_TO_CREATE, StatusCode::ERROR_CODE_DEFAULT);
        }

        $null = [
            'old_revenue' => 0,
            'new_revenue' => 0,
            'note' => '',
            'channel_code' => $this->auth->userChannelCode(),
            'created_source' => Constant::CREATED_SOURCE_crm,
        ];

        $onlys = array_merge(array_keys($null), ['staff_id', 'team_id', 'month', 'year']);

        $params = UtilArr::only($request + $null, $onlys);

        $result = new PlanMonthlyExpectedRevenue();
        $result->create($params);
        return $result;
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public function updateRevenueByMember($id)
    {
        $revenue = PlanMonthlyExpectedRevenue::buildModel()
            ->wheres('id', '=', $id)
            ->firstOrFail();

        $request = (array)$this->request->getJsonRawBody(true);
        $monthYear = $request['month_year'];
        $dt = explode('/', $monthYear);
        $request['month'] = (int)$dt[0];
        $request['year'] = (int)$dt[1];

        $check = $revenue->month == $request['month'] &&
            $revenue->year == $request['year'] &&
            $revenue->staff_id == $request['staff_id'] &&
            $revenue->team_id == $request['team_id'];

        if (!$check) {
            throw new \InvalidArgumentException(ErrorMessage::INVALID_DATA_TO_UPDATE, StatusCode::ERROR_CODE_DEFAULT);
        }

        $update = UtilArr::only($request, ['old_revenue', 'new_revenue', 'note']);

        $revenue->update($update);
        return $revenue;
    }

    public function reportPlanMonthlyRevenue()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $monthYear = $this->request->getQuery('month_year');

        $team_id = $this->auth->user()->team->id;
        $team_id = 1;
        $members = $this->authService->getMemberByTeamId($team_id);

        $dates = $this->listMonth($monthYear);

        $alias = 'PlanMonthlyExpectedRevenue';
        $columns = [$alias . '.id as plan_monthly_expected_revenue_id', $alias . '.staff_id', $alias . '.month', $alias . '.note'];
        $sumCollumns = ['realizable_revenue','realized_revenue','old_revenue','new_revenue'];
        foreach ($sumCollumns as $collumn){
            $columns[] = "SUM(COALESCE({$alias}.{$collumn},0)) AS total_{$collumn}";
        }

        foreach ($members as $indexMember => $member) {

            foreach ($dates as $keyDate => $date) {
                $month = (int)$date->format('m');
                $year = (int)$date->format('Y');

                $p = PlanMonthlyExpectedRevenue::buildModel();
                $p->columns($columns);
                $p->wheres($alias . '.team_id', '=', $team_id);
                $p->wheres($alias . '.staff_id', '=', $member['id']);
                $p->wheres($alias . '.month', '=', $month);
                $p->wheres($alias . '.year', '=', $year);
                $p->groupBy($alias . '.team_id');
                $p->groupBy($alias . '.month');
                $p->orderBy($alias . '.month ASC');
                $data = $p->findFirst();

                if (!$data) {
                    $null = [
                        'plan_monthly_expected_revenue_id' => 0,
                        'staff_id' => $member['id'],
                        'month' => $month,
                        'note' => '',
                        'total_realizable_revenue' => 0,
                        'total_realized_revenue' => 0,
                        'total_old_revenue' => 0,
                        'total_new_revenue' => 0,
                        'total_revenue' => 0
                    ];
                    $result[$keyDate] = $null;
                } else {
                    $result[$keyDate] = $data->toArray();
                    $result[$keyDate]['total_revenue'] = $result[$keyDate]['total_old_revenue'] + $result[$keyDate]['total_new_revenue'];
                }
            }

            $members[$indexMember]['result'] = $result;
        }

        return $members;
    }
}