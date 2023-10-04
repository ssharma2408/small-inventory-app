<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Http\Resources\Admin\InventoryResource;
use App\Models\Inventory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InventoryApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        //abort_if(Gate::denies('inventory_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InventoryResource(Inventory::with(['supplier', 'product'])->get());
    }

    public function store(StoreInventoryRequest $request)
    {
        $inventory = Inventory::create($request->all());

        if ($request->input('po_file', false)) {
            $inventory->addMedia(storage_path('tmp/uploads/' . basename($request->input('po_file'))))->toMediaCollection('po_file');
        }

        return (new InventoryResource($inventory))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Inventory $inventory)
    {
        //abort_if(Gate::denies('inventory_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InventoryResource($inventory->load(['supplier', 'product']));
    }

    public function update(UpdateInventoryRequest $request, Inventory $inventory)
    {
        $inventory->update($request->all());

        if ($request->input('po_file', false)) {
            if (! $inventory->po_file || $request->input('po_file') !== $inventory->po_file->file_name) {
                if ($inventory->po_file) {
                    $inventory->po_file->delete();
                }
                $inventory->addMedia(storage_path('tmp/uploads/' . basename($request->input('po_file'))))->toMediaCollection('po_file');
            }
        } elseif ($inventory->po_file) {
            $inventory->po_file->delete();
        }

        return (new InventoryResource($inventory))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Inventory $inventory)
    {
        //abort_if(Gate::denies('inventory_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inventory->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
