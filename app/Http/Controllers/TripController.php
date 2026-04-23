<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTripRecordRequest;
use App\Http\Requests\UpdateTripRecordRequest;
use App\Models\TripRecord;
use Inertia\Inertia;
use Inertia\Response;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
//            $trips = TripRecord::all()->take(5);
            $trips = TripRecord::factory()->count(5)->make();
            return Inertia::render('Trips/Index', [
                'trips' => $trips->toResourceCollection()->resolve(),
            ]);
        } catch (\Throwable $e) {
            dump($e);
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
        //
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
