<?php
namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function all()
    {
        return Category::all();
    }

    public function create(array $data){
        return Category::create($data);
    }
    public function update(Category $category,array $data){
        return $category->update($data);

    }
    public function delete(Category $category){
        return $category->delete();
    }
}
