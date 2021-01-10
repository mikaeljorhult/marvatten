<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Version;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Application $application
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Application $application)
    {
        $this->authorize('create', Version::class);

        $validated = $request->validate([
            'name'       => ['required'],
            'is_current' => ['sometimes', 'required', 'boolean'],
        ]);

        $application->versions()->create($validated);

        return redirect()->route('applications.show', [$application]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Application $application
     * @param \App\Models\Version $version
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Application $application, Version $version)
    {
        $this->authorize('update', $version);

        return view('versions.edit')->with(compact('version'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Application $application
     * @param \App\Models\Version $version
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Application $application, Version $version)
    {
        $this->authorize('update', $version);

        $validated = $request->validate([
            'name'       => ['required'],
            'is_current' => ['sometimes', 'required', 'boolean'],
        ]);

        if (! $request->has('is_current')) {
            $validated['is_current'] = false;
        }

        $version->update($validated);

        return redirect()->route('applications.show', [$application, 'tab' => 'versions']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Version $version
     * @param \App\Models\Application $application
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $application, Version $version)
    {
        $this->authorize('delete', $version);

        $version->delete();

        return redirect()->route('applications.show', [$application]);
    }
}
