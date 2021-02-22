<?php

namespace App\Repositories;

use App\Exceptions\Category\AllCategoryException;
use App\Exceptions\Category\CreateCategoryException;
use App\Exceptions\Category\UpdateCategoryException;
use App\Exceptions\Category\DeleteCategoryException;
use App\Models\Category;

abstract class CategoryRepository implements RepositoryInterface
{
    private $model;
    
    public function __construct(Category $category)
    {
        $this->model = $category;
    }
    
    public function create(array $data)
    {
        try 
        {    
            $category = $this->model->create($data);
            
            return [
                'category' => $this->find($category->id)
            ];
        }
        catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
    
    public function delete($id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find category',
                ];
            }

            $this->model->destroy($id);

            return [
                'success' => true,
                'message' => 'Deleted successfully',
                'deletedCategory' => $temp,
            ];
        }
        catch (\Exception $exception) {
            throw new DeleteCategoryException($exception->getMessage());
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            if(!$temp = $this->model->find($id))
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find category',
                ];
            }

            $temp->update($data);
            $temp->save();
            
            return [
                'success' => true,
                'message' => 'Updated successfully!',
                'updated_category' => $this->find($temp->id),
            ];
        }
        catch (\Exception $exception) {
            throw new UpdateCategoryException($exception->getMessage());
        }
    }
    
    public function find($id)
    {
        try 
        {
            $category = $this->model::with('products')->find($id);
            if(!$category)
            {
                return [
                    'success' => false,
                    'message' => 'Could`nt find category',
                ];
            }
            return [
                'success' => true,
                'category' => $category,
            ];
        }
        catch (\Exception $exception) {

        }
    }
    
    public function all()
    {
        try {
            return $this->model::with('products')->get();
        }
        catch (\Exception $exception) {
            throw new AllCategoryException($exception->getMessage());
        }
    }
    
    public function paginate($pagination)
    {
        try {
            return $this->model::orderBy('created_at', 'DESC')->paginate($pagination);
        }
        catch (\Exception $exception) {
            throw new AllCategoryException($exception->getMessage());
        }
    }
}
