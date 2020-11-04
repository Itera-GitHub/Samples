<?php


namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

abstract class FullTextBaseRepository extends BaseRepository
{

    /**
     * Fulltext Index Fields
     * @var array
     */
    protected $fulltextSearchableFields = [];

    /**
     * Get Fulltext Searchable Fields
     *
     * @return array
     */
    public function getFulltextSearchableFields()
    {
        return $this->fulltextSearchableFields;
    }

    /**
     * Return the model table name
     */
    public function getTableName()
    {
        return $this->model->getTable();
    }


}
