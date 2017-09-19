<?php

namespace Modules\Core\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface RotaAcessoRepository
 * @package namespace Modules\Core\Repositories;
 */
interface RotaAcessoRepository extends RepositoryInterface
{
    public function findByRoleIds(array $roles, $ambiente = null);

    public function findByRoleAllIds(array $roles, $ambiente = null);
}
