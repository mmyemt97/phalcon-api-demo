<?php
namespace Website\Controllers;

use SVCodebase\Validators\BaseValidate;
use Website\Repos\PlanMonthlyIndividualEarningTargetRepo;

class PlanmonthlyearningtargetController extends BaseController
{
    /**
     * @var $repo PlanMonthlyIndividualEarningTargetRepo
     */
    protected $repo;

    public function onConstruct()
    {
        $this->repo = new PlanMonthlyIndividualEarningTargetRepo();
    }

    /**
     * @SWG\Get(
     *     path="/planning/plan-monthly-individual-earning-target{query}",
     *     description="Mục tiêu thu nhập từng cá nhân danh sách thống kê theo team",
     *     summary="Mục tiêu thu nhập từng cá nhân danh sách thống kê theo team",
     *     tags={"Plan Earning Target"},
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
     *         description="ID Team min:1",
     *         name="team_id",
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
    public function earningTargetByTeamAction()
    {
        $rules = [
            'branch_code' => 'required',
            'month_year' => 'required|month_year',
            'team_id' => 'digit_min:1'
        ];

        BaseValidate::validator($this->request->getQuery(), $rules);

        $result = $this->repo->getEarningTargetByTeam();

        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Get(
     *     path="/planning/plan-monthly-individual-earning-target/detail{query}",
     *     description="Mục tiêu thu nhập từng cá nhân danh sách thống kê theo thành viên",
     *     summary="Mục tiêu thu nhập từng cá nhân danh sách thống kê theo thành viên",
     *     tags={"Plan Earning Target"},
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
     *         description="ID Team | Min :1",
     *         name="team_id",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Min : 1",
     *         name="staff_id[]",
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
    public function earningTargetTeamMemberOfTeamAction()
    {
        $rules = [
            'branch_code' => 'required',
            'month_year' => 'required|month_year',
            'team_id' => 'required|digit_min:1',
            //'staff_id' => ''
        ];

        BaseValidate::validator($this->request->getQuery(), $rules);

        $result = $this->repo->getEarningTargetTeamMemberOfTeam();

        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Post(
     *     path="/planning/plan-monthly-individual-earning-target/save",
     *     description="Tạo + sửa mục tiêu thu nhập từng cá nhân ",
     *     summary="Tạo + sửa mục tiêu thu nhập từng cá nhân",
     *     tags={"Plan Earning Target"},
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
     *         description="ID Team | Min :1",
     *         name="team_id",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Min : 1",
     *         name="staff_id",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Min : 1000",
     *         name="expected_revenue",
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
    public function createOrUpdateEarningTargetByMemberAction()
    {
        $request = (array) $this->request->getJsonRawBody(true);

        $rules = [
            'branch_code' => 'required',
            'month_year' => 'required|month_year',
            'team_id' => 'required|digit_min:1',
            'staff_id' => 'required|digit_min:0',
            'expected_revenue' => 'required|digit_min:1000'
        ];

        BaseValidate::validator($request, $rules);

        $result = $this->repo->createOrUpdateEarningTargetByMember();

        return $this->outputSuccess($result);
    }
}