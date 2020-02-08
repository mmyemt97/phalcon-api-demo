<?php
namespace Website\Controllers;

use SVCodebase\Validators\BaseValidate;
use Website\Repos\PlanMonthlyWorkloadRepo;

class PlanmonthlyworkloadController extends BaseController
{
    /**
     * @var $repo PlanMonthlyWorkloadRepo
     */
    protected $repo;

    public function onConstruct()
    {
        $this->repo = new PlanMonthlyWorkloadRepo();
    }

    /**
     * @SWG\Get(
     *     path="/planning/plan-monthly-workload {query}",
     *     description="khối lượng công việc danh sách thống kê theo team",
     *     summary="khối lượng công việc danh sách thống kê theo team",
     *     tags={"Plan Work Load"},
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
     *         required=false,
     *         type="integer"
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
     *                  type="array",
     *                  @SWG\Items(
     *                      type="object",
     *                          @SWG\Property(property="id", type="integer"),
     *                          @SWG\Property(property="channel_code", type="string"),
     *                          @SWG\Property(property="name", type="string"),
     *                          @SWG\Property(property="type", type="string"),
     *                          @SWG\Property(property="status", type="string"),
     *                          @SWG\Property(property="created_at", type="string"),
     *                          @SWG\Property(property="created_by", type="string"),
     *                          @SWG\Property(property="updated_at", type="string"),
     *                          @SWG\Property(property="updated_by", type="string"),
     *                          @SWG\Property(property="created_source", type="string"),
     *                          @SWG\Property(
     *                              property="result",
     *                              type="object",
     *                              @SWG\Property(property="team_id", type="integer"),
     *                              @SWG\Property(property="month", type="integer"),
     *                              @SWG\Property(property="year", type="integer"),
     *                              @SWG\Property(property="old_customer_quantity", type="integer"),
     *                              @SWG\Property(property="new_customer_quantity", type="integer"),
     *                              @SWG\Property(property="old_call_quantity", type="integer"),
     *                              @SWG\Property(property="new_call_quantity", type="integer"),
     *                              @SWG\Property(property="old_email_quantity", type="integer"),
     *                              @SWG\Property(property="new_email_quantity", type="integer"),
     *                              @SWG\Property(property="total_customer_quantity", type="integer"),
     *                              @SWG\Property(property="total_call_quantity", type="integer"),
     *                              @SWG\Property(property="total_email_quantity", type="integer"),
     *                      ),
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
    public function workloadTeamByMonthAction()
    {
        $rules = [
            'branch_code' => 'required',
            'month_year' => 'required|month_year',
            'team_id' => 'digit_min:1'
        ];

        BaseValidate::validator($this->request->getQuery(), $rules);

        $result = $this->repo->getWorkloadTeamByMonth();

        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Get(
     *     path="/planning/plan-monthly-workload/detail{query}",
     *     description="khối lượng công việc danh sách thống kê theo thành viên",
     *     summary="khối lượng công việc danh sách thống kê theo thành viên",
     *     tags={"Plan Work Load"},
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
     *         description="ID Staff | min : 1",
     *         name="staff_id",
     *         in="path",
     *         required=false,
     *         type="integer"
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
     *                  type="array",
     *                  @SWG\Items(
     *                      type="object",
     *                          @SWG\Property(property="id", type="integer"),
     *                          @SWG\Property(property="email", type="string"),
     *                          @SWG\Property(property="login_name", type="string"),
     *                          @SWG\Property(property="display_name", type="string"),
     *                          @SWG\Property(property="division_code", type="string"),
     *                          @SWG\Property(property="data_group_code", type="string"),
     *                          @SWG\Property(property="team_id", type="string"),
     *                          @SWG\Property(property="team_role", type="string"),
     *                          @SWG\Property(property="team_member_id", type="string"),
     *                          @SWG\Property(property="team_member_status", type="string"),
     *                          @SWG\Property(
     *                              property="result",
     *                              type="object",
     *                              @SWG\Property(property="staff_id", type="integer"),
     *                              @SWG\Property(property="plan_monthly_workload_id", type="integer"),
     *                              @SWG\Property(property="month", type="integer"),
     *                              @SWG\Property(property="year", type="integer"),
     *                              @SWG\Property(property="old_customer_quantity", type="integer"),
     *                              @SWG\Property(property="new_customer_quantity", type="integer"),
     *                              @SWG\Property(property="old_call_quantity", type="integer"),
     *                              @SWG\Property(property="new_call_quantity", type="integer"),
     *                              @SWG\Property(property="old_email_quantity", type="integer"),
     *                              @SWG\Property(property="new_email_quantity", type="integer"),
     *                              @SWG\Property(property="total_customer_quantity", type="integer"),
     *                              @SWG\Property(property="total_call_quantity", type="integer"),
     *                              @SWG\Property(property="total_email_quantity", type="integer"),
     *                      ),
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
    public function workloadTeamMemberOfTeamAction()
    {
        $rules = [
            'branch_code' => 'required',
            'month_year' => 'required|month_year',
            'team_id' => 'required|digit_min:1',
            //'staff_id' => ''
        ];

        BaseValidate::validator($this->request->getQuery(), $rules);

        $result = $this->repo->getWorkloadTeamMemberOfTeam();

        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Post(
     *     path="/planning/plan-monthly-workload/save",
     *     description="tạo + sửa khối lượng công việc theo thành viên, id = 0 là tạo",
     *     summary="tạo + sửa khối lượng công việc theo thành viên, id = 0 là tạo",
     *     tags={"Plan Work Load"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="  create = 0 | min : 0",
     *         name="plan_monthly_workload_id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
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
     *         description="ID Staff | min : 1",
     *         name="staff_id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="min : 0",
     *         name="old_customer_quantity",
     *         in="path",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="min : 0",
     *         name="new_customer_quantity",
     *         in="path",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="min : 0",
     *         name="old_call_quantity",
     *         in="path",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="min : 0",
     *         name="new_call_quantity",
     *         in="path",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="min : 0",
     *         name="old_email_quantity",
     *         in="path",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="min : 0",
     *         name="new_email_quantity",
     *         in="path",
     *         required=false,
     *         type="integer"
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
     *                          @SWG\Property(property="id", type="integer"),
     *                          @SWG\Property(property="channel_code", type="string"),
     *                          @SWG\Property(property="team_id", type="integer"),
     *                          @SWG\Property(property="staff_id", type="integer"),
     *                          @SWG\Property(property="month", type="integer"),
     *                          @SWG\Property(property="year", type="integer"),
     *                          @SWG\Property(property="old_customer_quantity", type="integer"),
     *                          @SWG\Property(property="new_customer_quantity", type="integer"),
     *                          @SWG\Property(property="old_call_quantity", type="integer"),
     *                          @SWG\Property(property="new_call_quantity", type="integer"),
     *                          @SWG\Property(property="old_email_quantity", type="string"),
     *                          @SWG\Property(property="new_email_quantity", type="string"),
     *                          @SWG\Property(property="created_source", type="string"),
     *                          @SWG\Property(property="created_by", type="string"),
     *                          @SWG\Property(property="created_at", type="string"),
     *                          @SWG\Property(property="updated_at", type="string"),
     *                          @SWG\Property(property="updated_by", type="string"),
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
    public function createOrUpdateWorkloadByMemberAction()
    {
        $request = (array)$this->request->getJsonRawBody(true);

        $rules = [
            'branch_code' => 'required',
            'plan_monthly_workload_id' => 'required|digit_min:0',
            'month_year' => 'required|month_year',
            'team_id' => 'required|digit_min:1',
            'staff_id' => 'required|digit_min:1',
            'old_customer_quantity' => 'digit_min:0',
            'new_customer_quantity' => 'digit_min:0',
            'old_call_quantity' => 'digit_min:0',
            'new_call_quantity' => 'digit_min:0',
            'old_email_quantity' => 'digit_min:0',
            'new_email_quantity' => 'digit_min:0'
        ];

        BaseValidate::validator($request, $rules);

        $result = $this->repo->createOrUpdateWorkloadByMember();

        return $this->outputSuccess($result);
    }
}