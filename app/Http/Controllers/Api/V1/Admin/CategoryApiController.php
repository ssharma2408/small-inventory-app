<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\Admin\CategoryResource;
use App\Models\Category;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryApiController extends Controller
{

    public function getcategory(){
     $unique_category = Category::groupBy('category_id')->pluck('category_id')->toArray();
     $get_only_category = Category::whereNotIn('id',array_filter($unique_category))->where('category_id',null)->get()->toArray();
     $get_all_category = Category::whereIn('id',$unique_category)->get()->toArray();
     return new CategoryResource(array_merge($get_all_category,$get_only_category));
    }

    public function getsubcategory($id, $show=0){
     $query = Category::where('category_id',$id);
	 if($show){
		$query ->where('show_fe', 1);
	 }
	 $get_subcategory = $query ->get();
     return new CategoryResource($get_subcategory);
    }

    /*public function index()
    {
        abort_if(Gate::denies('category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CategoryResource(Category::all());
    }*/

    public function index($id, $show=0)
    {
        if($id==0){
            $query  = Category::where('category_id',NULL);
			if($show){
				$query ->where('show_fe', 1);
			}
			$category = $query ->get();
        }else{
            $query  = Category::where('category_id',$id);
			if($show){
				$query ->where('show_fe', 1);
			}
			$category = $query ->get();
        }
        return new CategoryResource($category);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->all());

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Category $category)
    {
        abort_if(Gate::denies('category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->all());

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Category $category)
    {
        abort_if(Gate::denies('category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $category->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
	
	public function get_category_detail($id)
    {
        $category = Category::where('id', $id)->get();
		return new CategoryResource($category);
    }
}
