<?php

namespace Modules\Core\Criteria;

use Portal\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\CriteriaInterface;

/**
 * Class PermissionCriteria
 * @package namespace Portal\Criteria;
 */
class PermissionCriteria extends BaseCriteria implements CriteriaInterface
{
    protected $filterCriteria = [
        'permissions.id'        =>'=',
        'permissions.name'      =>'ilike',
        'permissions.created_at'=>'between',
        'permissions.updated_at'=>'between',
    ];
}
