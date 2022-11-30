<?php

namespace App\Services\V1\Intelligence;

use App\Repositories\V1\Intelligence\Interfaces\IntelligenceRepositoryInterface;
use App\Services\V1\BaseService;

class IntelligenceService extends BaseService
{
    /**
     * @var IntelligenceRepositoryInterface
     */
    private IntelligenceRepositoryInterface $intelligenceRepository;

    #region constructor.

    /**
     * @param IntelligenceRepositoryInterface $intelligenceRepository
     */
    public function __construct(IntelligenceRepositoryInterface $intelligenceRepository)
    {
        $this->intelligenceRepository = $intelligenceRepository;
    }

    #endregion

    #region Public methods



    #endregion
}
