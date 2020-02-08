<?php
namespace Website\Controllers;

use SVCodebase\Validators\BaseValidate;
use Website\Repos\PlanMonthlyTargetCommentRepo;

class PlanmonthlytargetcommentController extends BaseController
{
    /**
     * @var $repo PlanMonthlyTargetCommentRepo
     */
    protected $repo;

    public function onConstruct()
    {
        $this->repo = new PlanMonthlyTargetCommentRepo();
    }

    /**
     * @SWG\Get(
     *     path="/planning/plan-monthly-target-comment{query}",
     *     description="Lý giải doanh thu tháng sau chi tiết bình luận theo week_year",
     *     summary="Lý giải doanh thu tháng sau chi tiết bình luận theo week_year",
     *     tags={"Plan Comment Target"},
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
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Thành công",
     *         @SWG\Schema(
     *              @SWG\Property(type="integer",property="code"),
     *              @SWG\Property(property="message",type="string"),
     *              @SWG\Property(property="data",type="object"),
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

    public function getCommentByMonthYearAction()
    {
        $rules = [
            'branch_code' => 'required',
            'month_year' => 'required|month_year'
        ];

        BaseValidate::validator($this->request->getQuery(), $rules);

        $result = $this->repo->getCommentByMonthYear();

        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Post(
     *     path="/planning/plan-monthly-target-comment/save",
     *     description="Tạo + sửa lý giải doanh thu tháng sau ",
     *     summary="Tạo + sửa lý giải doanh thu tháng sau",
     *     tags={"Plan Comment Target"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Min : 0 , 0 = create",
     *         name="plan_monthly_target_commnent_id",
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
     *         description="Min : 1",
     *         name="team_id",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Max : 255",
     *         name="explain_note",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Max : 255",
     *         name="plan_note",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Thành công",
     *         @SWG\Schema(
     *              @SWG\Property(type="integer",property="code"),
     *              @SWG\Property(property="message",type="string"),
     *              @SWG\Property(property="data",type="object"),
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

    public function createOrUpdatePlanTargetCommentAction()
    {
        $request = (array) $this->request->getJsonRawBody(true);

        $rules = [
            'branch_code' => 'required',
            'plan_monthly_target_commnent_id' => 'required|digit_min:0',
            'month_year' => 'required|month_year',
            'team_id' => 'digit_min:1',
            'explain_note' => 'max:255',
            'plan_note' => 'max:255',
        ];

        BaseValidate::validator($request, $rules);

        $result = $this->repo->createOrUpdatePlanTargetComment();

        return $this->outputSuccess($result);
    }
}