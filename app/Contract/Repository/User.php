<?php
namespace App\Contract\Repository;
use App\Model\User as Model;
use App\Model\SocialAccount;
use Illuminate\Http\Request;

interface User
{
    /**
     * Find a user
     *
     * @param int $id
     * @return \App\Model\User
     */
    public function find($id);

    /**
     * 根据第三方平台的信息，获取用户
     *
     * @param integer $id
     * @param integer $type
     * @return \App\Model\User
     */
    public function findByVendorInfo(int $foreign_id, int $type);

    /**
     * 统计用户的来源qq/wechat/github
     *
     * @return array
     */
    public function statisticBySource();

    /**
     * 获取所有管理员的信息
     *
     * @return array
     */
    public function getManagers(Request $request) : array;

    /**
     * Retrieve user by user email
     *
     * @param string $email
     * @return App\Model\User
     */
    public function findByEmail($email);
}