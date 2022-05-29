<?php

namespace App\Services;

use App\Repositories\AbstractRepository;
use Illuminate\Http\Request;

class AbstractService
{
    /**
     * @var AbstractRepository
     */
    protected $repository;

    /**
     * EloquentRepository constructor.
     */
    public function __construct(AbstractRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->repository->query();
    }

    /**
     * Find one by id
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Update
     *
     * @param int $id
     * @param Request $request
     * @return bool|mixed
     */
    public function update(int $id, Request $request)
    {
        return $this->repository->update($id, $request->validated());
    }

    /**
     * Create
     *
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        return $this->repository->create($request->validated());
    }

    /**
     * Delete
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }
}
