<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreShrinkageRequest;
use App\Http\Requests\UpdateShrinkageRequest;
use App\Http\Resources\Admin\ShrinkageResource;
use App\Models\Shrinkage;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShrinkageApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        //abort_if(Gate::denies('shrinkage_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ShrinkageResource(Shrinkage::with(['product', 'added_by'])->get());
    }

    public function store(StoreShrinkageRequest $request)
    {
        $shrinkage = Shrinkage::create($request->all());

        return (new ShrinkageResource($shrinkage))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Shrinkage $shrinkage)
    {
       // abort_if(Gate::denies('shrinkage_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ShrinkageResource($shrinkage->load(['product', 'added_by']));
    }

    public function update(UpdateShrinkageRequest $request, Shrinkage $shrinkage)
    {
        $shrinkage->update($request->all());

        return (new ShrinkageResource($shrinkage))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Shrinkage $shrinkage)
    {
        //abort_if(Gate::denies('shrinkage_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $shrinkage->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
