<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Workstation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkstationApplicationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Workstation $workstation
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, Workstation $workstation)
    {
        $this->authorize('update', $workstation);

        $validated = $request->validate([
            'application_id' => ['required', Rule::exists('applications', 'id')],
        ]);

        $workstation->applications()->attach($validated['application_id']);

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Workstation  $workstation
     * @param  \App\Models\Application  $application
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Workstation $workstation, Application $application)
    {
        $this->authorize('update', $workstation);

        $installedVersions = $workstation
            ->versions()
            ->where('application_id', '=', $application->id)
            ->get();

        return view('workstations.applications-edit')->with(compact('workstation', 'application', 'installedVersions'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param \App\Models\Workstation $workstation
     * @param \App\Models\Application $application
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Workstation $workstation, Application $application)
    {
        $this->authorize('update', $workstation);

        $workstation->applications()->detach($application->id);

        return redirect()->back();
    }
}
