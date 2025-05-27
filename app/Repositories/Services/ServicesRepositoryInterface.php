<?php
namespace App\Repositories\Services;
use App\Repositories\RepositoryInterface;

interface ServicesRepositoryInterface extends RepositoryInterface{
    public function searchServices(array $filters);

    public function update($id,$attributes=[]);
}