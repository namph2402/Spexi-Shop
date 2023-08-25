<?php

namespace App\Repository\Eloquent;


use App\Common\EloquentRepository;
use App\Models\ImportingNote;
use App\Repository\ImportNoteRepositoryInterface;

class ImportNoteRepository extends EloquentRepository implements ImportNoteRepositoryInterface
{
    /**
     * get model
     * @return string
     */
    public function getModel()
    {
        return ImportingNote::class;
    }

}
