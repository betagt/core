<?php

namespace Modules\Core\Repositories;

use Modules\Core\Models\Newsletter;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Portal\Validators\NewsletterValidator;

/**
 * Class NewsletterRepositoryEloquent
 * @package namespace Portal\Repositories;
 */
class NewsletterRepositoryEloquent extends BaseRepository implements NewsletterRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Newsletter::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
