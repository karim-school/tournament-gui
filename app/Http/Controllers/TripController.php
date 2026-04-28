<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTripRecordRequest;
use App\Http\Requests\UpdateTripRecordRequest;
use App\Models\Station;
use App\Models\TripRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class TripController extends Controller
{
    private const int PER_PAGE = 20;

    public function index(Request $request)
    {
        if (!Schema::hasTable('trip_records')) {
            return Inertia::render('Trips/Index', []);
        }

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
                    $q->whereRaw('EXISTS (SELECT 1 FROM stations WHERE stations.name LIKE ? AND stations.id = trip_records.start_station_id AND stations.sub_id = trip_records.start_station_sub_id)', [$stationSearch])
                        ->orWhereRaw('EXISTS (SELECT 1 FROM stations WHERE stations.name LIKE ? AND stations.id = trip_records.end_station_id AND stations.sub_id = trip_records.end_station_sub_id)', [$stationSearch]);
                });
            }

            if ($request->filled('date_from')) {
                $query->where('started_at', '>=', $request->date_from.' 00:00:00');
            }

            if ($request->filled('date_to')) {
                $query->where('started_at', '<=', $request->date_to.' 23:59:59');
            }

            if (($min_duration = $request->input('min_duration', 0)) > 0) {
                //$query->whereRaw('DATEDIFF(minute, started_at, ended_at) >= ?', $min_duration);
                $query->whereRaw('cast((julianday(ended_at) - julianday(started_at)) * 24 * 60 as integer) >= ?', $min_duration);
            }

            $page = (int) $request->input('page', 1);
            $totalCount = (clone $query)->count();
            $hasMore = ($page * self::PER_PAGE) < $totalCount;

            $trips = $query->orderBy('started_at', 'desc')
                ->offset(($page - 1) * self::PER_PAGE)
                ->limit(self::PER_PAGE)
                ->get();

            if ($request->boolean('api')) {
                return response()->json([
                    'trips' => $trips->toResourceCollection()->resolve(),
                    'hasMore' => $hasMore,
                ]);
            }

            return Inertia::render('Trips/Index', [
                'trips' => $trips->toResourceCollection()->resolve(),
                'hasMore' => $hasMore,
                'currentPage' => $page,
                'totalCount' => $totalCount,
                'filters' => [
                    'rideable_type' => $request->input('rideable_type', 'all'),
                    'member_casual' => $request->input('member_casual', 'all'),
                    'station' => $request->input('station', ''),
                    'date_from' => $request->input('date_from', ''),
                    'date_to' => $request->input('date_to', ''),
                    'min_duration' => $request->input('min_duration', ''),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error($e);
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
