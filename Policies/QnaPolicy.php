<?php

namespace Modules\Qna\Policies;

use App\Models\Config;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Modules\Qna\Entities\Qna;

/**
 * Q&A 정책
 * 
 */
class QnaPolicy
{
    use HandlesAuthorization;

    protected $config;

    public function __construct()
    {
        $this->config = Config::getConfig();
    }

    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Qna  $qna
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Qna $qna)
    {
        return $user->mb_id === $qna->mb_id
                    ? Response::allow()
                    : Response::deny('본인의 문의글만 볼 수 있습니다.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Qna  $qna
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Qna $qna)
    {
        if ($qna->status > 0) {
            return Response::deny('답변이 등록되거나 진행 중인 문의글은 수정할 수 없습니다.');
        }

        return $user->mb_id === $qna->mb_id
                    ? Response::allow()
                    : Response::deny('You do not own this QnA.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Qna  $qna
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Qna $qna)
    {
        return $user->mb_id === $qna->mb_id
                    ? Response::allow()
                    : Response::deny('You do not own this QnA.');
    }
}
