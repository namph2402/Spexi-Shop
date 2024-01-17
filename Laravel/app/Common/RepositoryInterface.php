<?php

namespace App\Common;

interface RepositoryInterface
{
    /**
     * Get All
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll();

    /**
     * get
     * @param \App\Common\WhereClause[] $whereClauses
     * @param string $orderBy
     * @param array $with
     * @param array $withCount
     * @return \Illuminate\Support\Collection
     */
    public function get($whereClauses, $orderBy = 'id:desc', $with = [], $withCount = []);


    /**
     * get
     * @param \App\Common\WhereClause[] $whereClauses
     * @param string $orderBy
     * @param array $with
     * @param array $withCount
     * @return \Illuminate\Support\Collection
     */
    public function pluck($whereClauses, $value);

    /**
     * paginate
     * @param int $limit
     * @param \App\Common\WhereClause[] $whereClauses
     * @param string $orderBy
     * @param array $with
     * @param array $withCount
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($limit, $whereClauses, $orderBy = 'id:desc', $with = [], $withCount = []);

    /**
     * Find by id
     * @param $id
     * @param array $with
     * @param array $withCount
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    public function findById($id, $with = [], $withCount = []);


    /**
     * find
     * @param \App\Common\WhereClause[] $whereClauses
     * @param string $orderByFields
     * @param array $with
     * @param array $withCount
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    public function find($whereClauses, $orderByFields = 'id:desc', $with = [], $withCount = []);

    /**
     * Create
     * @param array $attributes
     * @param array $with
     * @param array $withCount
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    public function create(array $attributes, $with = [], $withCount = []);

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @param array $with
     * @param array $withCount
     * @return bool|\Illuminate\Database\Eloquent\Model|mixed
     */
    public function update($id, array $attributes, $with = [], $withCount = []);

    /**
     * Delete
     *
     * @param $id
     * @param array $with
     * @return bool
     */
    public function delete($id, $with = []);

    /**
     * Bulk Update
     * @param array $whereClauses
     * @param array $attributes
     * @return int|boolean
     */
    public function bulkUpdate(array $whereClauses, array $attributes);


    /**
     * Bulk Delete
     * @param array $whereClauses
     * @return mixed
     */
    public function bulkDelete(array $whereClauses);


    /**
     * Truncate table
     * @return mixed
     */
    public function truncate();

}
