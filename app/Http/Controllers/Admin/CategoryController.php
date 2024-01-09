<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Gate;
use Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::all();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        abort_if(Gate::denies('category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		
		$categories = Category::where('category_id', null)->pluck('name', 'id');

        return view('admin.categories.create', compact('categories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        if($request->hasFile('category_image')){
			$file = $request->file('category_image');
			
			$extension  = $file->getClientOriginalExtension();
			$name = time() .'_' . str_replace(" ", "_", $request->name) . '.' . $extension;
			
			$store = Storage::disk('do')->put(
				'/'.$_ENV['DO_FOLDER'].'/'.$name,
				file_get_contents($request->file('category_image')->getRealPath()),
				'public'
				);
			
			$category_detail = $request->all();
			
			$category_detail['image_url'] = $name;
			
			$category = Category::create($category_detail);		
			
			if ($request->redirect !="") {
			   if(request()->redirect == "add-product"){
				  return redirect()->route('admin.products.create'); 
			   }else{
				   return redirect("admin/products/".request()->redirect_id."/edit");			   
			   }
			}

			return redirect()->route('admin.categories.index');
		}
    }

    public function edit(Category $category)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		
		$categories = Category::where('category_id', null)->pluck('name', 'id');

        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category_detail = $request->all();
		
		if($request->hasFile('category_image')){
			$file = $request->file('category_image');
			
			$extension  = $file->getClientOriginalExtension();
			$name = time() .'_' . str_replace(" ", "_", $request->name) . '.' . $extension;
			
			$store = Storage::disk('do')->put(
				'/'.$_ENV['DO_FOLDER'].'/'.$name,
				file_get_contents($request->file('category_image')->getRealPath()),
				'public'
				);	
			
			$category_detail['image_url'] = $name;
		}
		
		$category->update($category_detail);

        return redirect()->route('admin.categories.index');
    }

    public function show(Category $category)
    {
        abort_if(Gate::denies('category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.categories.show', compact('category'));
    }

    public function destroy(Category $category)
    {
        abort_if(Gate::denies('category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $category->delete();

        return back();
    }

    public function massDestroy(MassDestroyCategoryRequest $request)
    {
        $categories = Category::find(request('ids'));

        foreach ($categories as $category) {
            $category->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
	
	public function get_sub_category($cat_id){
		if($cat_id ==""){
			return false;
		}
		$sub_categories = Category::where('category_id', $cat_id)->get()->toArray();
		
		return response()->json(array('success'=>1, 'subcategories'=>$sub_categories), 200);		
	}
}
