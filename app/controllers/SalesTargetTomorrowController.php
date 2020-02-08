<?php
namespace Website\Controllers;

use SVCodebase\Validators\BaseValidate;
use Website\Repos\SalesTargetTomorrowRepo;
use Website\Constant;

class SalestargettomorrowController extends BaseController
{
    /**
     * @var SalesTargetTomorrowRepo
     */
    protected $repo;

    public function onConstruct()
    {
        $this->repo = new SalesTargetTomorrowRepo();
    }

    /**
     * @SWG\Get(
     *     path="/planning/sales-target-tomorrow{query}",
     *     description="Mục tiêu ngày hôm sau(Test)",
     *     summary="Mục tiêu ngày hôm sau (Test)",
     *     tags={"Sales target tomorrow"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="ID Team",
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
     *                              property="target",
     *                              type="object",
     *                              @SWG\Property(property="total_quantity", type="integer"),
     *                              @SWG\Property(property="total_value", type="integer"),
     *                              @SWG\Property(property="absolute_quantity", type="integer"),
     *                              @SWG\Property(property="potential_quantity", type="integer"),
     *                              @SWG\Property(property="potential_value", type="integer"),
     *                              @SWG\Property(property="full_attempt_quantity", type="integer"),
     *                              @SWG\Property(property="full_attempt_value", type="integer"),
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
    public function listsAction()
    {
        $rules = [
            'branch_code' => 'required',
            'team_id' => 'digit_min:1',
        ];

        BaseValidate::validator($this->request->getQuery(), $rules);

        $results = $this->repo->getLists();

        return $this->outputSuccess($results);
    }

    /**
     * @SWG\Get(
     *     path="/planning/sales-target-tomorrow/detail{query}",
     *     description="Chi tiết mục tiêu ngày hôm sau (Test)",
     *     summary="Chi tiết mục tiêu ngày hôm sau  (Test)",
     *     tags={"Sales target tomorrow"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="ID Team",
     *         name="team_id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="ID Staff",
     *         name="staff_id[]",
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
     *                              property="target",
     *                              type="object",
     *                                  @SWG\Property(
     *                                      property="total",
     *                                      type="object",
     *                                      @SWG\Property(property="quantity", type="integer"),
     *                                      @SWG\Property(property="value", type="integer"),
     *                                  ),
     *                                  @SWG\Property(
     *                                      property="absolute",
     *                                      type="object",
     *                                      @SWG\Property(property="sale_target_id", type="integer"),
     *                                      @SWG\Property(property="quantity", type="integer"),
     *                                      @SWG\Property(property="value", type="integer"),
     *                                      @SWG\Property(property="master_kpi_code", type="string"),
     *                                      @SWG\Property(property="team_id", type="integer"),
     *                                  ),
     *                                  @SWG\Property(
     *                                      property="potential",
     *                                      type="object",
     *                                      @SWG\Property(property="sale_target_id", type="integer"),
     *                                      @SWG\Property(property="quantity", type="integer"),
     *                                      @SWG\Property(property="value", type="integer"),
     *                                      @SWG\Property(property="master_kpi_code", type="string"),
     *                                      @SWG\Property(property="team_id", type="integer"),
     *                                  ),
     *                                  @SWG\Property(
     *                                      property="full_attempt",
     *                                      type="object",
     *                                      @SWG\Property(property="sale_target_id", type="integer"),
     *                                      @SWG\Property(property="quantity", type="integer"),
     *                                      @SWG\Property(property="value", type="integer"),
     *                                      @SWG\Property(property="master_kpi_code", type="string"),
     *                                      @SWG\Property(property="team_id", type="integer"),
     *                                  ),
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
    public function detailAction()
    {
        $rules = [
            'branch_code' => 'required',
            'team_id' => 'required|digit_min:1',
            //'staff_id' => 'isArray',
        ];

        BaseValidate::validator($this->request->getQuery(), $rules);

        $results = $this->repo->getDetail();
        return $this->outputSuccess($results);
    }

    private function validSave()
    {
        $request = (array) $this->request->getJsonRawBody(true);

        $codes = implode(',', Constant::listKpiCode());

        $rules = [
            'sale_target_id' => 'required|digit_min:0',
            'branch_code' => 'required',
            'team_id' => 'required|digit_min:1',
            'staff_id' => 'required|digit_min:1',
            'master_kpi_code' => "required|inclusionIn:$codes",
            'date' => 'required|timestamp',
            'quantity' => 'digit_min:0',
            'value' => 'digit_min:1000'
        ];

        if (empty($request['quantity'])) {
            $rules['value'] .= '|required';
        }

        BaseValidate::validator($request, $rules);
    }



    /**
     * @SWG\Post(
     *     path="/planning/sales-target-tomorrow-save",
     *     description="Thêm mới hoặc sửa mục tiêu ngày hôm sau",
     *     summary="Thêm mới hoặc sửa mục tiêu ngày hôm sau",
     *     tags={"Sales target tomorrow"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="sale_target_id | min : 0",
     *         name="sale_target_id",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="master_kpi_code",
     *         name="master_kpi_code",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="ID Team | min : 0",
     *         name="team_id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="ID Staff | min : 0",
     *         name="staff_id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="timestamp",
     *         name="date",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="min : 0",
     *         name="quantity",
     *         in="path",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="min : 1000",
     *         name="value",
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
     *                          @SWG\Property(property="master_kpi_code", type="string"),
     *                          @SWG\Property(property="date", type="string"),
     *                          @SWG\Property(property="quantity", type="integer"),
     *                          @SWG\Property(property="value", type="integer"),
     *                          @SWG\Property(property="week", type="integer"),
     *                          @SWG\Property(property="month", type="integer"),
     *                          @SWG\Property(property="year", type="integer"),
     *                          @SWG\Property(property="created_at", type="string"),
     *                          @SWG\Property(property="created_by", type="string"),
     *                          @SWG\Property(property="updated_at", type="string"),
     *                          @SWG\Property(property="updated_by", type="string"),
     *                          @SWG\Property(property="created_source", type="string"),
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

    /**
     * @throws \Exception
     */

    public function createOrUpdateAction()
    {
        $this->validSave();

        $results = $this->repo->createOrUpdate();

        return $this->outputSuccess($results);
    }
}