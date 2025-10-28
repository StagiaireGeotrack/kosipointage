<?php
// app/Repositories/BaseRepository.php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected $model;
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    
    public function getAll($perPage = 5)
    {
        return $this->model->paginate($perPage);
    }
    
    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }
    
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }
    
    public function update($id, array $attributes)
    {
        $model = $this->findById($id);
        $model->update($attributes);
        return $model;
    }
    
    public function delete($id)
    {
        $model = $this->findById($id);
        return $model->delete();
    }
    
    public function getFiltered($filters = [], $perPage = 5)
    {
        $query = $this->model->newQuery();
        
        // Appliquer les filtres
        foreach ($filters as $field => $value) {
            if ($value !== null && $value !== '') {
                if (is_array($value)) {
                    if (isset($value['from']) && isset($value['to'])) {
                        $query->whereBetween($field, [$value['from'], $value['to']]);
                    }
                } else {
                    $query->where($field, 'LIKE', "%{$value}%");
                }
            }
        }
        
        // Appliquer le tri
        if (isset($filters['sort_by']) && isset($filters['sort_order'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_order']);
        }
        
        return $query->paginate($perPage);
    }
}