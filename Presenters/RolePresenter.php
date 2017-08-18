<?php

namespace Modules\Core\Presenters;

use Modules\Core\Transformers\RoleTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class RolePresenter
 *
 * @package namespace Modules\Core\Presenters;
 */
class RolePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new RoleTransformer();
    }
}
