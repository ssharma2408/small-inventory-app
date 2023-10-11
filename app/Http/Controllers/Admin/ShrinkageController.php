<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyShrinkageRequest;
use App\Http\Requests\StoreShrinkageRequest;
use App\Http\Requests\UpdateShrinkageRequest;
use App\Models\Product;
use App\Models\Shrinkage;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ShrinkageController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('shrinkage_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shrinkages = Shrinkage::with(['product', 'added_by'])->get();

        return view('admin.shrinkages.index', compact('shrinkages'));
    }

    public function create()
    {
        abort_if(Gate::denies('shrinkage_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $added_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.shrinkages.create', compact('added_bies', 'products'));
    }

    public function store(StoreShrinkageRequest $request)
    {
        $shrinkage = Shrinkage::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $shrinkage->id]);
        }

        return redirect()->route('admin.shrinkages.index');
    }

    public function edit(Shrinkage $shrinkage)
    {
        abort_if(Gate::denies('shrinkage_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $added_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $shrinkage->load('product', 'added_by');

        return view('admin.shrinkages.edit', compact('added_bies', 'products', 'shrinkage'));
    }

    public function update(UpdateShrinkageRequest $request, Shrinkage $shrinkage)
    {
        $shrinkage->update($request->all());

        return redirect()->route('admin.shrinkages.index');
    }

    public function show(Shrinkage $shrinkage)
    {
        abort_if(Gate::denies('shrinkage_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shrinkage->load('product', 'added_by');

        return view('admin.shrinkages.show', compact('shrinkage'));
    }

    public function destroy(Shrinkage $shrinkage)
    {
        abort_if(Gate::denies('shrinkage_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shrinkage->delete();

        return back();
    }

    public function massDestroy(MassDestroyShrinkageRequest $request)
    {
        $shrinkages = Shrinkage::find(request('ids'));

        foreach ($shrinkages as $shrinkage) {
            $shrinkage->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('shrinkage_create') && Gate::denies('shrinkage_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Shrinkage();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
