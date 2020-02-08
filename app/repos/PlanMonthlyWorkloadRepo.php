<?php
namespace Website\Repos;

use SVCodebase\Library\UtilArr;
use Website\Models\PlanMonthlyWorkload;
use Website\ErrorMessage;
use Website\Services\AuthService;
use Website\StatusCode;
use Website\Constant;

class PlanMonthlyWorkloadRepo extends BaseRepo
{
    protected $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function getWorkloadTeamByMonth()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $monthYear = $this->request->getQuery('month_year');
        $dt = explode('/', $monthYear);
        $month = (int)$dt[0];
        $year = (int)$dt[1];
        //$current = new \DateTimeImmutable($year . '-' . $month);

        $team_id = $this->request->getQuery('team_id',['int']);
        $teams = $this->authService->filterTeams($team_id);

        $w = PlanMonthlyWorkload::buildModel();
        $columns = [
            'team_id',
            'month',
            'year',
            'SUM(old_customer_quantity) AS old_customer_quantity',
            'SUM(new_customer_quantity) AS new_customer_quantity',
            'SUM(old_call_quantity) AS old_call_quantity',
            'SUM(new_call_quantity) AS new_call_quantity',
            'SUM(old_email_quantity) AS old_email_quantity',
            'SUM(new_email_quantity) AS new_email_quantity',
        ];
        $totalColumns = [
            'SUM(old_customer_quantity) + SUM(new_customer_quantity) AS total_customer_quantity',
            'SUM(old_call_quantity) + SUM(new_call_quantity) AS total_call_quantity',
            'SUM(old_email_quantity) + SUM(new_email_quantity) AS total_email_quantity'
        ];

        $w->columns(array_merge($columns, $totalColumns));
        $w->whereIf('team_id', '=', $team_id);
        $w->wheres('month', '=', $month);
        $w->wheres('year', '=', $year);
        $w->groupBy('team_id');
        $result = $w->get()->toArray();
        $result = UtilArr::keyBy('team_id', $result);

        $null = [
            'month' => $month,
            'year' => $year,
            'old_customer_quantity' => 0,
            'new_customer_quantity' => 0,
            'old_call_quantity' => 0,
            'new_call_quantity' => 0,
            'old_email_quantity' => 0,
            'new_email_quantity' => 0,
            'total_customer_quantity' => 0,
            'total_call_quantity' => 0,
            'total_email_quantity' => 0,
        ];

        foreach ($teams as $index => $team) {
            $teams[$index]['result'] = isset($result[$team['id']]) ?
                $result[$team['id']] :
                array_merge(['team_id' => $team['id']], $null);
        }


        return $teams;
    }

    public function getWorkloadTeamMemberOfTeam()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $monthYear = $this->request->getQuery('month_year');
        $dt = explode('/', $monthYear);
        $month = (int)$dt[0];
        $year = (int)$dt[1];
        //$current = new \DateTimeImmutable($year . '-' . $month);

        $team_id = $this->request->getQuery('team_id');
        $staff_id = $this->request->getQuery('staff_id'); // string or array
        $members = $this->authService->filterMemberByTeamId($team_id, $staff_id);

        $w = PlanMonthlyWorkload::buildModel();
        $columns = [
            'id AS plan_monthly_workload_id',
            'staff_id',
            'team_id',
            'month',
            'year',
            'old_customer_quantity',
            'new_customer_quantity',
            'old_call_quantity',
            'new_call_quantity',
            'old_email_quantity',
            'new_email_quantity',
        ];
        $totalColumns = [
            'old_customer_quantity + new_customer_quantity AS total_customer_quantity',
            'old_call_quantity + new_call_quantity AS total_call_quantity',
            'old_email_quantity + new_email_quantity AS total_email_quantity'
        ];

        $w->columns(array_merge($columns, $totalColumns));
        $w->wheres('month', '=', $month);
        $w->wheres('year', '=', $year);
        $w->wheres('team_id', '=', $team_id);
        $w->whereArray('staff_id', $staff_id);
        $result = $w->get()->toArray();
        $result = UtilArr::keyBy('staff_id', $result);

        $null = [
            'plan_monthly_workload_id' => 0,
            'month' => $month,
            'year' => $year,
            'old_customer_quantity' => 0,
            'new_customer_quantity' => 0,
            'old_call_quantity' => 0,
            'new_call_quantity' => 0,
            'old_email_quantity' => 0,
            'new_email_quantity' => 0,
            'total_customer_quantity' => 0,
            'total_call_quantity' => 0,
            'total_email_quantity' => 0,
        ];

        foreach ($members as $index => $member) {
            $members[$index]['result'] = isset($result[$member['id']]) ?
                $result[$member['id']] :
                array_merge(['staff_id' => $member['id']], $null);
        }

        return $members;
    }

    public function createOrUpdateWorkloadByMember()
    {
        $request = (array)$this->request->getJsonRawBody(true);

        $id = $request['plan_monthly_workload_id'];

        return $id > 0 ? $this->updateWorkloadByMember($id) : $this->createWorkloadByMember();
    }

    public function createWorkloadByMember()
    {
        $request = (array)$this->request->getJsonRawBody(true);
        $monthYear = $request['month_year'];
        $dt = explode('/', $monthYear);
        $request['month'] = (int)$dt[0];
        $request['year'] = (int)$dt[1];

        $check = PlanMonthlyWorkload::buildModel()
            ->wheres('staff_id', '=', $request['staff_id'])
            ->wheres('team_id', '=', $request['team_id'])
            ->wheres('month', '=', $request['month'])
            ->wheres('year', '=', $request['year'])
            ->count();

        if ($check > 0) {
            throw new \InvalidArgumentException(ErrorMessage::INVALID_DATA_TO_CREATE, StatusCode::ERROR_CODE_DEFAULT);
        }

        $null = [
            'old_customer_quantity' => 0,
            'new_customer_quantity' => 0,
            'old_call_quantity' => 0,
            'new_call_quantity' => 0,
            'old_email_quantity' => 0,
            'new_email_quantity' => 0,
            'channel_code' => $this->auth->userChannelCode(),
            'created_source' => Constant::CREATED_SOURCE_crm,
        ];

        $onlys = array_merge(array_keys($null), ['staff_id', 'team_id', 'month', 'year']);

        $params = UtilArr::only($request + $null, $onlys);

        $result = new PlanMonthlyWorkload();
        $result->create($params);
        return $result;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function updateWorkloadByMember($id)
    {
        $workload = PlanMonthlyWorkload::buildModel()
            ->wheres('id', '=', $id)
            ->firstOrFail();

        $request = (array)$this->request->getJsonRawBody(true);
        $monthYear = $request['month_year'];
        $dt = explode('/', $monthYear);
        $request['month'] = (int)$dt[0];
        $request['year'] = (int)$dt[1];

        $check = $workload->month == $request['month'] &&
            $workload->year == $request['year'] &&
            $workload->staff_id == $request['staff_id'] &&
            $workload->team_id == $request['team_id'];

        if (!$check) {
            throw new \InvalidArgumentException(ErrorMessage::INVALID_DATA_TO_UPDATE, StatusCode::ERROR_CODE_DEFAULT);
        }

        $onlys = [
            'old_customer_quantity',
            'new_customer_quantity',
            'old_call_quantity',
            'new_call_quantity',
            'old_email_quantity',
            'new_email_quantity'
        ];

        $update = UtilArr::only($request, $onlys);

        $workload->update($update);
        return $workload;
    }
}