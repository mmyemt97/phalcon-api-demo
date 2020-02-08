<?php
namespace Website\Controllers;

use SVCodebase\Validators\BaseValidate;
use Website\Repos\SalesTargetWeekRepo;

class SalestargetweekController extends BaseController
{
    /**
     * @var SalesTargetWeekRepo
     */
    protected $repo;

    public function onConstruct()
    {
        $this->repo = new SalesTargetWeekRepo();
    }

    /**
     * @SWG\Get(
     *     path="/planning/sales-target-week{query}",
     *     description="Mục tiêu hàng tuần (Test)",
     *     summary="Mục tiêu hàng tuần (Test)",
     *     tags={"Sales target week"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Tuần",
     *         name="week",
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
            'week' => 'required|digit_min:1'
        ];

        BaseValidate::validator($this->request->getQuery(), $rules);

        $results = $this->repo->getLists();

        return $this->outputSuccess($results);
    }

    private function validateDetail()
    {
        $rules = [
            'team_id' => 'required|digit_min:1',
            'branch_code' => 'required',
            'week' => 'required|digit_min:1'
        ];

        BaseValidate::validator($this->request->getQuery(), $rules);
    }

    /**
     * @throws \Exception
     */

    /**
     * @SWG\Get(
     *     path="/planning/sales-target-week/detail{query}",
     *     description="Chi tiết mục tiêu hàng tuần (Test)",
     *     summary="Chi tiết mục tiêu hàng tuần (Test)",
     *     tags={"Sales target week"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Tuần",
     *         name="week",
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
     *                          @SWG\Property(property="total_quantity", type="integer"),
     *                          @SWG\Property(property="total_value", type="integer"),
     *                          @SWG\Property(property="absolute_quantity", type="integer"),
     *                          @SWG\Property(property="potential_quantity", type="integer"),
     *                          @SWG\Property(property="potential_value", type="integer"),
     *                          @SWG\Property(property="full_attempt_quantity", type="integer"),
     *                          @SWG\Property(property="full_attempt_value", type="integer"),
     *                          @SWG\Property(property="date", type="integer"),
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
        $this->validateDetail();

        $results = $this->repo->getDetail();

        $results = array_map(function ($item) {
            $item['date'] = strtotime($item['date']);
            return $item;
        }, array_values($results));

        return $this->outputSuccess($results);
    }
}