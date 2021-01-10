<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AttachmentController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Attachment::class);

        $validated = $request->validate([
            'name'            => ['required'],
            'file'            => ['required', 'file'],
            'user_id'         => ['sometimes', 'required', Rule::exists('users', 'id')],
            'attachable_type' => ['required', Rule::in(['App\Models\Application', 'App\Models\Workstation'])],
            'attachable_id'   => ['required', Rule::exists(app($request->get('attachable_type'))->getTable(), 'id')],
        ]);

        $attachment = new Attachment();
        $attachment->fill($validated);
        $attachment->path = $request->file('file')->storeAs('attachments', Str::slug($validated['name']).'-'.time().'.'.$request->file('file')->extension());
        $attachment->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Attachment $attachment
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Attachment $attachment)
    {
        return Storage::download($attachment->path);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attachment $attachment)
    {
        $this->authorize('update', $attachment);

        $validated = $request->validate([
            'name'            => ['sometimes', 'required'],
            'user_id'         => ['sometimes', 'required', Rule::exists('users', 'id')],
            'attachable_type' => ['required', Rule::in(['App\Models\Application', 'App\Models\Workstation'])],
            'attachable_id'   => ['required', Rule::exists(app($request->get('attachable_type'))->getTable(), 'id')],
        ]);

        $attachment->update($validated);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attachment $attachment)
    {
        $this->authorize('delete', $attachment);


        $attachment->delete();

        return redirect()->back();
    }
}
