<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ImportingNoteDetail;
use App\Repository\ImportNoteDetailRepositoryInterface;

class ImportNoteDetailRepository extends EloquentRepository implements ImportNoteDetailRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ImportingNoteDetail::class;
    }

}
