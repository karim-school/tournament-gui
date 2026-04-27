<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTripRecordRequest;
use App\Http\Requests\UpdateTripRecordRequest;
use App\Models\TripRecord;
use Inertia\Inertia;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $trips = TripRecord::all()->take(5);

            return Inertia::render('Trips/Index', [
                'trips' => $trips->toResourceCollection()->resolve(),
            ]);
        } catch (\Throwable) {
            return abort(500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTripRecordRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TripRecord $tripRecord)
    {
        return Inertia::render('Trips/Show', ['trip' => $tripRecord->toResource()->resolve()]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TripRecord $tripRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTripRecordRequest $request, TripRecord $tripRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TripRecord $tripRecord)
    {
        //
    }
}
