<?php

namespace App\Repositories;

interface InstrumentRepositoryInterface
{
    public function all();

    public function store(array $data);

    public function update(array $data, $id);

    public function find($id);

    public function delete($id);
}
