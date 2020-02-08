<?php
namespace Website\Repos;

use SVCodebase\Library\UtilArr;
use Website\Models\PlanMonthlyIndividualEarningTarget;
use Website\ErrorMessage;
use Website\Services\AuthService;
use Website\StatusCode;
use Website\Constant;

class PlanMonthlyIndividualEarningTargetRepo extends BaseRepo
{
    protected $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function getEarningTargetByTeam()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $monthYear = $this->request->getQuery('month_year');
        //$dt = explode('/', $monthYear);
        //$month = (int)$dt[0];
        //$year = (int)$dt[1];

        $team_id = $this->request->getQuery('team_id');
        $teams = $this->authService->filterTeams($team_id);

        return $teams;
    }

    public function getEarningTargetTeamMemberOfTeam()
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

        $w = PlanMonthlyIndividualEarningTarget::buildModel();
        $columns = [
            'id AS plan_monthly_earning_target_id',
            'team_id',
            'staff_id',
            'month',
            'year',
            'experience_month',
            'expected_revenue',
            'total_salary'
        ];
        $w->columns($columns);
        $w->wheres('month', '=', $month);
        $w->wheres('year', '=', $year);
        $w->wheres('team_id', '=', $team_id);
        $w->whereArray('staff_id', $staff_id);
        $result = $w->get()->toArray();

        $memberGroups = UtilArr::keyBy('staff_id', $result);

        $null = [
            'plan_monthly_earning_target_id' => 0,
            'team_id' => $team_id,
            'month' => $month,
            'year' => $year,
            'experience_month' => 0,
            'expected_revenue' => 0,
            'total_salary' => 0
        ];

        foreach ($members as $index => $member) {
            $members[$index]['result'] = isset($memberGroups[$member['id']]) ?
                $memberGroups[$member['id']] :
                array_merge(['staff_id' => $member['id']], $null);
        }

        return $members;
    }

    public function createOrUpdateEarningTargetByMember()
    {
        $request = (array) $this->request->getJsonRawBody(true);

        $id = $request['plan_monthly_earning_target_id'];

        return $id > 0 ? $this->updateEarningTargetByMember($id) : $this->createEarningTargetByMember();
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function updateEarningTargetByMember($id)
    {
        $earning = PlanMonthlyIndividualEarningTarget::buildModel()
            ->wheres('id', '=', $id)
            ->firstOrFail();

        $request = (array)$this->request->getJsonRawBody(true);
        $monthYear = $request['month_year'];
        $dt = explode('/', $monthYear);
        $request['month'] = (int)$dt[0];
        $request['year'] = (int)$dt[1];

        $check = $earning->month == $request['month'] &&
            $earning->year == $request['year'] &&
            $earning->staff_id == $request['staff_id'] &&
            $earning->team_id == $request['team_id'];

        if (!$check) {
            throw new \InvalidArgumentException(ErrorMessage::INVALID_DATA_TO_UPDATE, StatusCode::ERROR_CODE_DEFAULT);
        }

        $update = UtilArr::only($request, ['experience_month', 'expected_revenue', 'total_salary']);

        $earning->update($update);
        return $earning;
    }

    public function createEarningTargetByMember()
    {
        $request = (array) $this->request->getJsonRawBody(true);
        $monthYear = $request['month_year'];
        $dt = explode('/', $monthYear);
        $request['month'] = (int)$dt[0];
        $request['year'] = (int)$dt[1];

        $check = PlanMonthlyIndividualEarningTarget::buildModel()
            ->wheres('staff_id', '=', $request['staff_id'])
            ->wheres('team_id', '=', $request['team_id'])
            ->wheres('month', '=', $request['month'])
            ->wheres('year', '=', $request['year'])
            ->count();

        if ($check > 0) {
            throw new \InvalidArgumentException(ErrorMessage::INVALID_DATA_TO_CREATE, StatusCode::ERROR_CODE_DEFAULT);
        }

        $null = [
            'experience_month' => 0,
            'expected_revenue' => 0,
            'total_salary' => 0,
            'branch_code' => $request['branch_code'],
            'created_source' => Constant::CREATED_SOURCE_crm
        ];

        $only = array_merge(array_keys($null), ['staff_id', 'team_id', 'month', 'year']);

        $params = UtilArr::only($request + $null, $only);

        $result = new PlanMonthlyIndividualEarningTarget();
        $result->create($params);
        return $result;
    }
}