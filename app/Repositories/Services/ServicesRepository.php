<?php
namespace App\Repositories\Services;
use App\Repositories\BaseRepository;
use App\Repositories\Services\ServicesRepositoryInterface;
use App\Models\Services;
class ServicesRepository extends BaseRepository implements ServicesRepositoryInterface{
    public function getModel(){
        Return Services::class;
    }
    public function getAll(){

    }
    public function searchServices($filters){
        $query = $this->model->query();

        if (!empty($filters['service_name'])) {
            $query->where('service_name', 'like', '%' . $filters['service_name'] . '%');
        }

        if (!empty($filters['duration_minute'])) {
            $query->where('duration_minute',  '>=' , $filters['duration_minute']);
        }

        if (!empty($filters['price'])) {
            $query->where('price', '>=',  $filters['price']);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function update($id,$attributes=[]){
        $services= $this->find($id);
        if($services){
            $services->update($attributes);
            return $services;
        }
        return false;
    }
}