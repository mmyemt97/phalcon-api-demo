<?php
namespace Website\Repos;

use SVCodebase\Library\UtilArr;
use Website\Models\PlanMonthlyTargetComment;
use Website\ErrorMessage;
use Website\StatusCode;
use Website\Constant;

class PlanMonthlyTargetCommentRepo extends BaseRepo
{
    protected $authService;

    public function __construct()
    {
        $this->authService = new \Website\Services\AuthService();
    }

    public function getCommentByMonthYear()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $monthYear = $this->request->getQuery('month_year');
        $dt = explode('/', $monthYear);
        $month = (int)$dt[0];
        $year = (int)$dt[1];

        $p = PlanMonthlyTargetComment::buildModel();
        $colums = $p->getAttributes();
        $colums[0] = 'id AS plan_monthly_target_commnent_id';
        $p->columns($colums);
        $p->wheres('month', '=', $month);
        $p->wheres('year', '=', $year);
        $comment = $p->findFirst();

        if (!$comment) {
            $comment = [
                'plan_monthly_target_commnent_id' => 0,
                'month' => $month,
                'year' => $year,
            ];
        }

        return $comment;
    }

    public function createOrUpdatePlanTargetComment()
    {
        $request = (array)$this->request->getJsonRawBody(true);

        $id = $request['plan_monthly_target_commnent_id'];

        return $id > 0 ? $this->updateTargetComment($id) : $this->createTargetComment();
    }

    public function createTargetComment()
    {
        $request = (array)$this->request->getJsonRawBody(true);
        $monthYear = $request['month_year'];
        $dt = explode('/', $monthYear);
        $request['month'] = (int)$dt[0];
        $request['year'] = (int)$dt[1];

        $request['team_id'] = $request['team_id'] ?? $this->auth->user()->team->id;

        $check = PlanMonthlyTargetComment::buildModel()
            ->wheres('team_id', '=', $request['team_id'])
            ->wheres('month', '=', $request['month'])
            ->wheres('year', '=', $request['year'])
            ->count();

        if ($check > 0) {
            throw new \InvalidArgumentException(ErrorMessage::INVALID_DATA_TO_UPDATE, StatusCode::ERROR_CODE_DEFAULT);
        }

        $null = [
            'explain_note' => '',
            'plan_note' => '',
            'channel_code' => $this->auth->userChannelCode(),
            'created_source' => Constant::CREATED_SOURCE_crm
        ];

        $onlys = array_merge(array_keys($null), ['team_id', 'month', 'year']);

        $params = UtilArr::only($request + $null, $onlys);

        $result = new PlanMonthlyTargetComment();
        $result->create($params);
        return $result;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function updateTargetComment($id)
    {
        $comment = PlanMonthlyTargetComment::buildModel()
            ->wheres('id', '=', $id)
            ->firstOrFail();

        $request = (array)$this->request->getJsonRawBody(true);
        $monthYear = $request['month_year'];
        $dt = explode('/', $monthYear);
        $request['month'] = (int)$dt[0];
        $request['year'] = (int)$dt[1];

        $check = $comment->month == $request['month'] &&
            $comment->year == $request['year'] &&
            $comment->team_id == $request['team_id'];

        if (!$check) {
            throw new \InvalidArgumentException(ErrorMessage::INVALID_DATA_TO_CREATE, StatusCode::ERROR_CODE_DEFAULT);
        }

        $update = UtilArr::only($request, ['explain_note', 'plan_note']);

        $comment->update($update);
        return $comment;
    }
}