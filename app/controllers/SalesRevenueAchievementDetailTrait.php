<?php
namespace Website\Controllers;

use SVCodebase\Validators\BaseValidate;

trait SalesrevenueachievementdetailTrait
{
    /**
     * @SWG\Get(
     *     path="/planning/sales-revenue-achievement/team-member-detail{query}",
     *     description="Chi tiết doanh thu hằng ngày của staff",
     *     summary="Chi tiết doanh thu hằng ngày của staff",
     *     tags={"Sales Revenue"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Timestamp",
     *         name="date",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="min:1",
     *         name="team_id",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="min : 1",
     *         name="staff_id",
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
     *              @SWG\Property(
     *                  property="data",
     *                  type="object",
     *                  @SWG\Property(
     *                      property="items",
     *                      type="array",
     *                      @SWG\Items(
     *                          type="object",
     *                              @SWG\Property(property="id",type="integer"),
     *                              @SWG\Property(property="employer_name",type="string"),
     *                              @SWG\Property(property="quantity",type="integer"),
     *                              @SWG\Property(property="total_revenue",type="integer"),
     *                          ),
     *                  ),
     *                  @SWG\Property(property="first",type="integer"),
     *                  @SWG\Property(property="before",type="integer"),
     *                  @SWG\Property(property="previous",type="integer"),
     *                  @SWG\Property(property="current",type="integer"),
     *                  @SWG\Property(property="last",type="integer"),
     *                  @SWG\Property(property="next",type="integer"),
     *                  @SWG\Property(property="total_pages",type="integer"),
     *                  @SWG\Property(property="total_items",type="integer"),
     *                  @SWG\Property(property="limit",type="integer"),
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
    public function detailOfMemberAction()
    {
        $request = $this->request->getQuery();

        $rules = [
            'branch_code' => 'required',
            'team_id' => 'digit_min:1',
            'staff_id' => 'required|digit_min:1',
            'date' => 'required|timestamp'
        ];

        BaseValidate::validator($request, $rules);

        $result = $this->repo->getDetailOfMember();

        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Post(
     *     path="/planning/sales-revenue-achievement/achievement-create",
     *     description="Thêm mới doanh thu",
     *     summary="Thêm mới doanh thu",
     *     tags={"Sales Revenue"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description=" max:  200",
     *         name="employer_name",
     *         in="path",
     *         required=true,
     *         type="string"
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
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="min : 1000",
     *         name="revenue",
     *         in="path",
     *         required=true,
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
     *                          @SWG\Property(property="date", type="string"),
     *                          @SWG\Property(property="quantity", type="integer"),
     *                          @SWG\Property(property="revenue", type="integer"),
     *                          @SWG\Property(property="employer_name", type="string"),
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
    public function createAchievementOfMemberAction()
    {
        $request = (array) $this->request->getJsonRawBody(true);

        $rules = [
            'branch_code' => 'required',
            'team_id' => 'required|digit_min:1',
            'staff_id' => 'required|digit_min:1',
            'date' => 'required|timestamp',
            'employer_name' => 'required|max:200',
            'quantity' => 'required|digit_min:1',
            'revenue' => 'required|digit_min:1000',
        ];

        BaseValidate::validator($request, $rules);

        $result = $this->repo->createAchievementOfMember();

        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Post(
     *     path="/planning/sales-revenue-achievement/achievement-update",
     *     description="Cập nhật danh thu",
     *     summary="Cập nhật danh thu",
     *     tags={"Sales Revenue"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="nummeric min:1",
     *         name="achievement_id",
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
     *         description=" max:  200",
     *         name="employer_name",
     *         in="path",
     *         required=false,
     *         type="string"
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
     *         name="revenue",
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
     *                          @SWG\Property(property="date", type="string"),
     *                          @SWG\Property(property="quantity", type="integer"),
     *                          @SWG\Property(property="revenue", type="integer"),
     *                          @SWG\Property(property="employer_name", type="string"),
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
    public function updateAchievementOfMemberAction()
    {
        $request = (array) $this->request->getJsonRawBody(true);

        $rules = [
            'employer_name' => 'max:200',
            'quantity' => 'digit_min:1',
            'revenue' => 'digit_min:1000',
            'achievement_id' => 'required|digit_min:1'
        ];

        BaseValidate::validator($request, $rules);

        $result = $this->repo->updateAchievementOfMember();

        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Post(
     *     path="/planning/sales-revenue-achievement/achievement-delete",
     *     description="Xóa danh thu",
     *     summary="Xóa danh thu",
     *     tags={"Sales Revenue"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="nummeric min:1",
     *         name="achievement_id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Thành công",
     *         @SWG\Schema(
     *              @SWG\Property(type="integer",property="code"),
     *              @SWG\Property(property="message",type="string"),
     *              @SWG\Property(property="data",type="boolean"),
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
    public function deleteAchievementOfMemberAction()
    {
        $request = (array) $this->request->getJsonRawBody(true);

        $rules = [
            'achievement_id' => 'required|digit_min:1'
        ];

        BaseValidate::validator($request, $rules);

        $result = $this->repo->deleteAchievementOfMember();

        return $this->outputSuccess($result);
    }
}