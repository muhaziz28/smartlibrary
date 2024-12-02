<?php

namespace App\Services;

use App\Repositories\InstrumentRepositoryInterface;

class InstrumentService
{
    public function __construct(protected InstrumentRepositoryInterface $instrumentRepository)
    {
    }

    public function create(array $data)
    {
        return $this->instrumentRepository->store($data);
    }

    public function update(array $data, $id)
    {
        return $this->instrumentRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->instrumentRepository->delete($id);
    }

    public function all()
    {
        return $this->instrumentRepository->all();
    }

    public function find($id)
    {
        return $this->instrumentRepository->find($id);
    }
}
