<?php

namespace Modules\Core\Repositories;

use Modules\Core\Models\Permission;
use Modules\Core\Presenters\PermissionPresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class PermissionRepositoryEloquent
 * @package namespace Modules\Core\Repositories;
 */
class PermissionRepositoryEloquent extends BaseRepository implements PermissionRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Permission::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function presenter()
    {
        return PermissionPresenter::class; 
    }
}
