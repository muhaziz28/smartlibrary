<?php

namespace App\Repositories;

use App\Models\Instrument;

class InstrumentRepository implements InstrumentRepositoryInterface
{
    public function all()
    {
        return Instrument::all();
    }

    public function store(array $data)
    {
        return Instrument::create($data);
    }

    public function find($id)
    {
        return Instrument::find($id);
    }

    public function update(array $data, $id)
    {
        $result = Instrument::findOrFail($id);
        $result->update($data);

        return $result;
    }

    public function delete($id)
    {
        return Instrument::destroy($id);
    }
}
