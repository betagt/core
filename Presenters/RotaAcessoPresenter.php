<?php

namespace Modules\Core\Presenters;

use Modules\Core\Transformers\RotaAcessoTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class RotaAcessoPresenter
 *
 * @package namespace Modules\Core\Presenters;
 */
class RotaAcessoPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new RotaAcessoTransformer();
    }
}
