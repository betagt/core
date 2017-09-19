<?php

namespace Modules\Core\Criteria;

use Portal\Criteria\BaseCriteria;
use Prettus\Repository\Contracts\CriteriaInterface;

/**
 * Class RoleCriteria
 * @package namespace Portal\Criteria;
 */
class RoleCriteria extends BaseCriteria implements CriteriaInterface
{
    protected $filterCriteria = [
        'roles.id'         =>'=',
        'roles.name'       =>'ilike',
        'roles.slug'       =>'ilike',
        'roles.created_at' =>'between',
        'roles.updated_at' =>'between',
    ];
}
