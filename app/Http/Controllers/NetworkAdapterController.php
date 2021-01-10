<?php

namespace App\Http\Controllers;

use App\Models\NetworkAdapter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NetworkAdapterController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', NetworkAdapter::class);

        $validated = $request->validate([
            'name'           => ['required'],
            'mac_address'    => ['required', 'regex:/^([0-9A-F]{2}[:-]){5}([0-9A-F]{2})$/i', Rule::unique('network_adapters')],
            'workstation_id' => ['required', Rule::exists('workstations', 'id')],
        ]);

        NetworkAdapter::create($validated);

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NetworkAdapter  $networkAdapter
     * @return \Illuminate\Http\Response
     */
    public function edit(NetworkAdapter $networkAdapter)
    {
        $this->authorize('update', $networkAdapter);

        // return edit view
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NetworkAdapter  $networkAdapter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NetworkAdapter $networkAdapter)
    {
        $this->authorize('update', $networkAdapter);

        $validated = $request->validate([
            'name'           => ['required'],
            'mac_address'    => ['required', 'regex:^([0-9A-F]{2}[:-]){5}([0-9A-F]{2})$', Rule::unique('network_adapters')->ignoreModel($networkAdapter)],
            'workstation_id' => ['required', Rule::exists('workstations', 'id')],
        ]);

        $networkAdapter->update($validated);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NetworkAdapter  $networkAdapter
     * @return \Illuminate\Http\Response
     */
    public function destroy(NetworkAdapter $networkAdapter)
    {
        $this->authorize('delete', $networkAdapter);

        $networkAdapter->delete();

        return redirect()->back();
    }
}
