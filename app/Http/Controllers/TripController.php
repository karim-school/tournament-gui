<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTripRecordRequest;
use App\Http\Requests\UpdateTripRecordRequest;
use App\Models\Station;
use App\Models\TripRecord;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TripController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request)
    {
        try {
            $query = TripRecord::query();

            if ($request->filled('rideable_type') && $request->rideable_type !== 'all') {
                $query->where('rideable_type', $request->rideable_type);
            }

            if ($request->filled('member_casual') && $request->member_casual !== 'all') {
                $query->where('member_casual', $request->member_casual);
            }

            if ($request->filled('station')) {
                $stationSearch = '%'.$request->station.'%';
                $query->where(function ($q) use ($stationSearch) {
                    $q->whereHas('startStation', fn ($q) => $q->where('name', 'like', $stationSearch))
                        ->orWhereHas('endStation', fn ($q) => $q->where('name', 'like', $stationSearch));
                });
            }

            if ($request->filled('date_from')) {
                $query->where('started_at', '>=', $request->date_from.' 00:00:00');
            }

            if ($request->filled('date_to')) {
                $query->where('started_at', '<=', $request->date_to.' 23:59:59');
            }

            $page = (int) $request->get('page', 1);
            $totalCount = (clone $query)->count();
            $hasMore = ($page * self::PER_PAGE) < $totalCount;

            $trips = $query->orderBy('started_at', 'desc')
                ->offset(($page - 1) * self::PER_PAGE)
                ->limit(self::PER_PAGE)
                ->get();

            return Inertia::render('Trips/Index', [
                'trips' => $trips->toResourceCollection()->resolve(),
                'hasMore' => $hasMore,
                'currentPage' => $page,
                'totalCount' => $totalCount,
                'filters' => [
                    'rideable_type' => $request->get('rideable_type', 'all'),
                    'member_casual' => $request->get('member_casual', 'all'),
                    'station' => $request->get('station', ''),
                    'date_from' => $request->get('date_from', ''),
                    'date_to' => $request->get('date_to', ''),
                ],
            ]);
        } catch (\Throwable) {
            return abort(500);
        }
    }

    public function create()
    {
        $stations = Station::all();

        return Inertia::render('Trips/Create', [
            'stations' => $stations,
        ]);
    }

    public function store(StoreTripRecordRequest $request)
    {
        try {
            $startStation = Station::findOrFail($request->input('start_station_id'));
            $endStation = Station::findOrFail($request->input('end_station_id'));

            TripRecord::create([
                'rideable_type' => $request->input('rideable_type'),
                'started_at' => $request->input('started_at'),
                'ended_at' => $request->input('ended_at'),
                'start_station_id' => $request->input('start_station_id'),
                'start_station_sub_id' => $startStation->sub_id,
                'end_station_id' => $request->input('end_station_id'),
                'end_station_sub_id' => $endStation->sub_id,
                'start_location_latitude' => $request->input('start_location_latitude', 40.75),
                'start_location_longitude' => $request->input('start_location_longitude', -73.95),
                'end_location_latitude' => $request->input('end_location_latitude', 40.75),
                'end_location_longitude' => $request->input('end_location_longitude', -73.95),
                'member_casual' => $request->input('member_casual'),
            ]);

            return redirect('/trips')->with('success', 'Trip record created successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to create trip record: '.$e->getMessage());
        }
    }

    public function show(TripRecord $tripRecord)
    {
        return Inertia::render('Trips/Show', ['trip' => $tripRecord->toResource()->resolve()]);
    }

    public function edit(TripRecord $tripRecord)
    {
        //
    }

    public function update(UpdateTripRecordRequest $request, TripRecord $tripRecord)
    {
        //
    }

    public function destroy(TripRecord $tripRecord)
    {
        //
    }
}
