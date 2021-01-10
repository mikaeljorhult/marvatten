<?php

namespace App\Http\Controllers;

use App\Models\Version;
use App\Models\Workstation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkstationVersionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Workstation  $workstation
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, Workstation $workstation)
    {
        $this->authorize('update', $workstation);

        $validated = $request->validate([
            'version_id' => ['required', Rule::exists('versions', 'id')],
        ]);

        $workstation->versions()->attach($validated['version_id']);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @param  \App\Models\Workstation  $workstation
     * @param  \App\Models\Version  $version
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request, Workstation $workstation, Version $version)
    {
        $this->authorize('update', $workstation);

        $workstation->versions()->detach($version->id);

        return redirect()->back();
    }
}
