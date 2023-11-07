<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCreditNoteRequest;
use App\Http\Requests\UpdateCreditNoteRequest;
use App\Http\Resources\Admin\CreditNoteResource;
use App\Models\CreditNote;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CreditNoteApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('credit_note_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CreditNoteResource(CreditNote::with(['order'])->get());
    }

    public function store(StoreCreditNoteRequest $request)
    {
        $creditNote = CreditNote::create($request->all());

        return (new CreditNoteResource($creditNote))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CreditNote $creditNote)
    {
        abort_if(Gate::denies('credit_note_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CreditNoteResource($creditNote->load(['order']));
    }

    public function update(UpdateCreditNoteRequest $request, CreditNote $creditNote)
    {
        $creditNote->update($request->all());

        return (new CreditNoteResource($creditNote))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CreditNote $creditNote)
    {
        abort_if(Gate::denies('credit_note_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $creditNote->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
