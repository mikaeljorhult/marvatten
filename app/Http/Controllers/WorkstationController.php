<?php

namespace App\Http\Controllers;

use App\Models\Workstation;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WorkstationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workstations = Workstation::orderBy('name')->get();

        return view('workstations.index')->with(compact('workstations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Workstation::class);

        return view('workstations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Workstation::class);

        $validated = $request->validate([
            'name'           => ['required'],
            'serial'         => ['required', Rule::unique('workstations')],
            'fields.*.label' => ['required_with:fields.*.value', 'min:3'],
            'fields.*.value' => ['required_with:fields.*.label'],
        ]);

        $workstation = Workstation::create(Arr::only($validated, ['name', 'serial']));

        if ($fields = Arr::get($validated, 'fields')) {
            $workstation->addMetas($fields);
        }

        return redirect()->route('workstations.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Workstation  $workstation
     * @return \Illuminate\Http\Response
     */
    public function show(Workstation $workstation)
    {
        return view('workstations.show')->with(compact('workstation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Workstation  $workstation
     * @return \Illuminate\Http\Response
     */
    public function edit(Workstation $workstation)
    {
        $this->authorize('update', $workstation);

        return view('workstations.edit')->with(compact('workstation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Workstation  $workstation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Workstation $workstation)
    {
        $this->authorize('update', $workstation);

        $validated = $request->validate([
            'name'           => ['required'],
            'serial'         => ['required', Rule::unique('workstations')->ignoreModel($workstation)],
            'fields.*.id'    => ['sometimes', 'nullable'],
            'fields.*.label' => ['required_with:fields.*.value', 'min:3'],
            'fields.*.value' => ['required_with:fields.*.label'],
        ]);

        $workstation->update(Arr::only($validated, ['name', 'serial']));
        $workstation->syncMetas(Arr::get($validated, 'fields') ?? []);

        return redirect()->route('workstations.show', [$workstation]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Workstation  $workstation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Workstation $workstation)
    {
        $this->authorize('delete', $workstation);

        $workstation->delete();

        return redirect()->route('workstations.index');
    }
}
