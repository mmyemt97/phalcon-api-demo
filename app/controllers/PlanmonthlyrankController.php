<?php
namespace Website\Controllers;

use SVCodebase\Validators\BaseValidate;
use Website\Constant;
use Website\Repos\PlanMonthlyRankRepo;

class PlanmonthlyrankController extends BaseController
{
    /**
     * @var $repo PlanMonthlyRankRepo
     */
    protected $repo;

    public function onConstruct()
    {
        $this->repo = new PlanMonthlyRankRepo();
    }

    /**
     * @SWG\Get(
     *     path="/planning/plan-monthly-rank{query}",
     *     description="Thống kê thứ hạng danh sách thứ hạng",
     *     summary="Thống kê thứ hạng danh sách thứ hạng",
     *     tags={"Plan Rank"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Tháng/Năm",
     *         name="month_year",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="ID team min: 1",
     *         name="month_year",
     *         in="path",
     *         required=false,
     *         type="string"
     *     ),
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Thành công",
     *         @SWG\Schema(
     *              @SWG\Property(type="integer",property="code"),
     *              @SWG\Property(property="message",type="string"),
     *              @SWG\Property(
     *                  property="data",
     *                  type="object",
     *                  @SWG\Property(
     *                      type="object",
     *                      property="total",
     *                          @SWG\Property(property="count_team_all", type="integer"),
     *                          @SWG\Property(property="count_team_channel", type="integer"),
     *                          @SWG\Property(property="count_team_branch", type="integer"),
     *                   ),
     *                  @SWG\Property(
     *                      type="array",
     *                      property="result",
     *                          @SWG\Items(
     *                              type="object",
     *                              @SWG\Property(property="rank_type", type="string"),
     *                              @SWG\Property(property="plan_monthly_rank_id", type="integer"),
     *                              @SWG\Property(property="team_id", type="integer"),
     *                              @SWG\Property(property="rank", type="integer"),
     *                              @SWG\Property(property="month", type="integer"),
     *                              @SWG\Property(property="year", type="integer"),
     *                   ),
     *                   ),
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Bad request",
     *         @SWG\Schema(
     *             @SWG\Property(
     *                type="integer",
     *                property="code"
     *             ),
     *             @SWG\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *      )
     *  ))
     */
    public function listRankAction()
    {
        $rules = [
            'branch_code' => 'required',
            'month_year' => 'required|month_year',
            //'team_id' => 'digit_min:1'
        ];

        BaseValidate::validator($this->request->getQuery(), $rules);

        $result = $this->repo->getListRank();

        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Post(
     *     path="/planning/plan-monthly-rank/save",
     *     description="tạo + sửa thứ hạng",
     *     summary="cần post đồng thời nguyên result 3 array như yêu cầu mới qua validate, plan_monthly_rank_id = 0 là tạo",
     *     tags={"Plan Rank"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Tháng/Năm",
     *         name="month_year",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="ID Team | min : 1",
     *         name="team_id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="array cần post đồng thời nguyên result 3 array như yêu cầu mới qua validate",
     *         name="result",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=400,
     *         description="Bad request",
     *         @SWG\Schema(
     *             @SWG\Property(
     *                type="integer",
     *                property="code"
     *             ),
     *             @SWG\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *      )
     *  ))
     */

    public function createOrUpdateRankByTeamAction()
    {
        $request = (array)$this->request->getJsonRawBody(true);

        $rules = [
            'branch_code' => 'required',
            'month_year' => 'required|month_year',
            //'team_id' => 'required|digit_min:1',
            'result' => 'required|isArray'
        ];

        $listRankType = implode(',', Constant::listRankType());

        for ($i = 0; $i < 3; $i++) {
            $rules['result.' . $i . '.plan_monthly_rank_id'] = "required|digit_min:0";
            $rules['result.' . $i . '.rank_type'] = "required|inclusionIn:$listRankType";
            $rules['result.' . $i . '.rank'] = 'required|digit_min:0';
        }

        BaseValidate::validator($request, $rules);

        $result = $this->repo->createOrUpdateRankByTeam();

        return $this->outputSuccess($result);
    }
}