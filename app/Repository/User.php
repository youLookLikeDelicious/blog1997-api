<?php
namespace App\Repository;

use App\Contract\Repository\Role as RepositoryRole;
use App\Facades\Page;
use App\Model\User as Model;
use App\Model\SocialAccount;
use App\Contract\Repository\User as Contract;
use App\Model\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class User implements Contract
{
    /**
     * User eloquent
     *
     * @var App\Model\User
     */
    protected $model;

    /**
     * Social account with belongs to user
     *
     * @var App\Model\socialAccounts
     */
    protected $socialAccount;

    /**
     * Create Instance
     *
     * @param \App\Model\User $model
     */
    public function __construct(Model $model, SocialAccount $socialAccount)
    {
        $this->model = $model;
        $this->socialAccount = $socialAccount;
    }

    /**
     * Get specified user
     *
     * @param int $id
     * @return \App\Model\User
     */
    public function find($id)
    {
        $user = $this->model->find($id);

        return $user;
    }

    /**
     * 根据第三方平台的信息，获取用户
     *
     * @param integer $foreign_id 第三方平台的id
     * @param integer $type 第三方平台的类型
     * @return App\Model\user|null
     */
    public function findByVendorInfo($foreign_id, int $type)
    {
        $socialAccount = $this->socialAccount->select(['user_id', 'id', 'type', 'foreign_id'])
            ->with('user:id,name,avatar,article_sum')
            ->where('type', $type)
            ->where('foreign_id', $foreign_id)
            ->first();

        return $socialAccount
            ? $socialAccount->user
            : null;
    }

    /**
     * 统计用户的来源
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function statisticBySource ()
    {
        $result = $this->socialAccount
            ->selectRaw('count(id) as count, type')
            ->groupBy('type')
            ->get();
        
        return $result;
    }

    /**
     * 获取管理员的信息
     * 有role的用户
     *
     * @param Request $request
     * @return array
     */
    public function getManagers(Request $request) : array
    {
        $this->validateGetManagersRequest($request);

        $query = $this->buildQueryToGetManagers($request);

        $managers = Page::paginate($query);
        
        $roles = app()->make(RepositoryRole::class)->flatted()->toArray();

        array_unshift($roles, ['id' => 0, 'name' => '--所有角色--']);

        $managers['roles'] = $roles;

        return $managers;
    }

    /**
     * Validate incoming request that to get manager list
     *
     * @param Request $request
     * @return void
     * @throws ValidationException
     */
    protected function validateGetManagersRequest(Request $request)
    {
        $request->validate([
            'email' => 'sometimes|required',
            'role_id' => 'sometimes|required|integer|min:1'
        ]);
    }

    /**
     * Build query to get manager list
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function buildQueryToGetManagers(Request $request)
    {
        $query = $this->model
            ->whereHas('roles')
            ->with('roles:role.id,name')
            ->selectRaw('id, name, avatar, email, deleted_at, created_at, email_verified_at, true as editAble')
            ->where('deleted_at', '');

        if ($request->input('email')) {
            $query->where('email', 'like', $request->input('email') . '%');
        }

        if ($roleId = $request->input('role_id')) {
            $query->whereHas('roles', function (Builder $query) use ($roleId) {
                $query->where('role.id', $roleId + 0);
            });
        } else {
            $query->whereHas('roles');
        }

        return $query;
    }

    /**
     * Retrieve user by user email
     * with roles
     * 
     * @param string $email
     * @return App\Model\User
     */
    public function findByEmail($email)
    {
        return $this->model->where('email', $email)
            ->with('roles:role.id,role.name')
            ->select(['id', 'name', 'avatar'])
            ->first();
    }
}