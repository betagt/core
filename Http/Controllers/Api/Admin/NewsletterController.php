<?php

namespace Modules\Core\Http\Controllers\Api\Admin;

use Modules\Anuncio\Criteria\MensagemAnuncioCriteria;
use Modules\Core\Criteria\NewsletterCriteria;
use Modules\Core\Http\Requests\NewsletterResquest;
use Modules\Core\Repositories\NewsletterRepository;
use Portal\Http\Controllers\BaseController;

class NewsletterController extends BaseController
{
    /**
     * @var NewsletterRepository
     */
    private $newsletterRepository;

    public function __construct(NewsletterRepository $newsletterRepository)
    {
        parent::__construct($newsletterRepository, NewsletterCriteria::class);
        $this->newsletterRepository = $newsletterRepository;
    }

    public function getValidator($id = null)
    {
        $this->validator = (new NewsletterResquest())->rules($id);
        return $this->validator;
    }
}
