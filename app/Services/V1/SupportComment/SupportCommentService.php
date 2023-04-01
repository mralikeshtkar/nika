<?php

namespace App\Services\V1\SupportComment;

use App\Repositories\V1\SupportComment\Interfaces\SupportCommentRepositoryInterface;
use App\Services\V1\BaseService;
use Illuminate\Http\Request;

class SupportCommentService extends BaseService
{
    /**
     * @var SupportCommentRepositoryInterface
     */
    private SupportCommentRepositoryInterface $supportCommentRepository;

    /**
     * @param SupportCommentRepositoryInterface $supportCommentRepository
     */
    public function __construct(SupportCommentRepositoryInterface $supportCommentRepository)
    {
        $this->supportCommentRepository = $supportCommentRepository;
    }

    public function store(Request $request, $rahjooSupport)
    {
        $rahjooSupport = $this->supportCommentRepository->findOrFailById($rahjooSupport);
        dd($rahjooSupport);
    }
}
