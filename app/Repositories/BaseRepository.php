<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements RepositoryInterface
{
    /**
     * Base repository for all models Repository
     */
    protected $modal;
    public function __construct(Model $modal)
    {
        //define modal
        $this->modal = $modal;
    }

    // fetch all data from modal
    public function all(){
        return $this->modal->all();
    }

    // create new entry in database
    public function create(array $data){
        return $this->modal->create($data);
    }

    // update entry in database
    public function update(array $data, $id){
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    // delete record
    public function delete($id){
        $record = $this->find($id);
        $record->delete();
        return $record;
    }

    // find records
    public function find($id){
        return $this->modal->findOrFail($id);
    }
}
