<?php
namespace App\Contract\Repository;

use Illuminate\Http\Request;

interface User
{
    /**
     * Get user list
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request);

    /**
     * Find a user
     *
     * @param int $id
     * @return \App\Models\User
     */
    public function find($id);

    /**
     * 根据第三方平台的信息，获取用户
     *
     * @param integer $id
     * @param integer $type
     * @return \App\Models\User
     */
    public function findByVendorInfo(int $foreign_id, int $type);

    /**
     * 统计用户的来源qq/wechat/github
     *
     * @return array
     */
    public function statisticBySource();

    /**
     * Retrieve user by user email
     *
     * @param string $email
     * @return App\Models\User
     */
    public function findByEmail($email);
}