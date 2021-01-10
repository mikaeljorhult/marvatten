<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $applications = Application::orderBy('name')->get();

        return view('applications.index')->with(compact('applications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Application::class);

        return view('applications.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Application::class);

        $validated = $request->validate([
            'name'        => ['required'],
            'seats'       => ['nullable', 'integer', 'min:0'],
            'is_floating' => ['sometimes', 'required', 'boolean']
        ]);

        if (! $request->has('is_floating')) {
            $validated['is_floating'] = false;
        }

        Application::create($validated);

        return redirect()->route('applications.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Application $application
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Application $application)
    {
        return view('applications.show')->with(compact('application'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Application $application
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application)
    {
        $this->authorize('update', $application);

        return view('applications.edit')->with(compact('application'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Application $application
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Application $application)
    {
        $this->authorize('update', $application);

        $validated = $request->validate([
            'name'        => ['required'],
            'seats'       => ['nullable', 'integer', 'min:0'],
            'is_floating' => ['sometimes', 'required', 'boolean']
        ]);

        if (! $request->has('is_floating')) {
            $validated['is_floating'] = false;
        }

        $application->update($validated);

        return redirect()->route('applications.show', [$application]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Application $application
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $application)
    {
        $this->authorize('delete', $application);

        $application->delete();

        return redirect()->route('applications.index');
    }
}
