<?php

namespace App\Http\Controllers;

use App\Helpers\DateTimeFormatter;
use App\Http\Requests\StoreTripRecordRequest;
use App\Http\Requests\UpdateTripRecordRequest;
use App\Models\Station;
use App\Models\TripRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Inertia\Response;

class TripController extends Controller
{
    private const int PER_PAGE = 20;

    public function index(Request $request): JsonResponse|Response
    {
        if (! auth()->check()) {
            session(['url.intended' => url()->current()]);
        }

        if (! Schema::hasTable('trip_records')) {
            return Inertia::render('trips/Index', [
                'trips' => [],
                'hasMore' => false,
                'currentPage' => 1,
                'totalCount' => 0,
                'filters' => [
                    'ride_type' => $request->input('ride_type', 'all'),
                    'rider_type' => $request->input('rider_type', 'all'),
                    'station' => $request->input('station', ''),
                    'date_from' => $request->input('date_from', ''),
                    'date_to' => $request->input('date_to', ''),
                    'min_duration' => $request->input('min_duration', ''),
                ],
            ]);
        }

        try {
            $query = TripRecord::query();

            if ($request->filled('ride_type') && $request->ride_type !== 'all') {
                $query->where('ride_type', $request->ride_type);
            }

            if ($request->filled('rider_type') && $request->rider_type !== 'all') {
                $query->whereHas('user', function ($query) use ($request) {
                    return $query->where('membership', $request->rider_type);
                });
            }

            if ($request->filled('station')) {
                $stationSearch = '%'.$request->station.'%';
                $query->where(function ($q) use ($stationSearch) {
                    $q->whereRaw('EXISTS (SELECT 1 FROM stations WHERE stations.name LIKE ? AND stations.id = trip_records.start_station_id)', [$stationSearch])
                        ->orWhereRaw('EXISTS (SELECT 1 FROM stations WHERE stations.name LIKE ? AND stations.id = trip_records.end_station_id)', [$stationSearch]);
                });
            }

            if ($request->filled('date_from')) {
                $query->where('started_at', '>=', $request->date_from.' 00:00:00');
            }

            if ($request->filled('date_to')) {
                $query->where('started_at', '<=', $request->date_to.' 23:59:59');
            }

            if (($min_duration = $request->input('min_duration', 0)) > 0) {
                // $query->whereRaw('DATEDIFF(minute, started_at, ended_at) >= ?', $min_duration);
                $query->whereRaw('cast((julianday(ended_at) - julianday(started_at)) * 24 * 60 as integer) >= ?', $min_duration);
            }

            $page = (int) $request->input('page', 1);
            $totalCount = (clone $query)->count();
            $hasMore = ($page * self::PER_PAGE) < $totalCount;

            $trips = $query->orderBy('started_at', 'desc')
                ->offset(($page - 1) * self::PER_PAGE)
                ->limit(self::PER_PAGE)
                ->with('user')
                ->get();

            if ($request->boolean('api')) {
                return response()->json([
                    'trips' => $trips->toResourceCollection()->resolve(),
                    'hasMore' => $hasMore,
                ]);
            }

            return Inertia::render('trips/Index', [
                'trips' => $trips->toResourceCollection()->resolve(),
                'hasMore' => $hasMore,
                'currentPage' => $page,
                'totalCount' => $totalCount,
                'filters' => [
                    'ride_type' => $request->input('ride_type', 'all'),
                    'rider_type' => $request->input('rider_type', 'all'),
                    'station' => $request->input('station', ''),
                    'date_from' => $request->input('date_from', ''),
                    'date_to' => $request->input('date_to', ''),
                    'min_duration' => $request->input('min_duration', ''),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error($e);
            abort(500);
        }
    }

    public function create(): Response|RedirectResponse
    {
        if (! auth()->check()) {
            return redirect()->route('home');
        }

        $stations = Station::all();

        return Inertia::render('trips/Create', [
            'stations' => $stations,
        ]);
    }

    public function store(StoreTripRecordRequest $request): Redirector|RedirectResponse
    {
        try {
            $start_time = (new \DateTime($request->input('started_at')))
                ->format(DateTimeFormatter::ISO_DATETIME_BY_MINUTE);
            $end_time = (new \DateTime($request->input('ended_at')))
                ->format(DateTimeFormatter::ISO_DATETIME_BY_MINUTE);

            $start_location = Station::create([
                'name' => '',
                'latitude' => $request->input('start_location.latitude'),
                'longitude' => $request->input('start_location.longitude'),
            ]);

            $end_location = Station::create([
                'name' => '',
                'latitude' => $request->input('end_location.latitude'),
                'longitude' => $request->input('end_location.longitude'),
            ]);

            $trip = TripRecord::create([
                'user_id' => auth()->id(),
                'start_station_id' => $start_location->id,
                'end_station_id' => $end_location->id,
                'started_at' => $start_time,
                'ended_at' => $end_time,
                'ride_type' => $request->input('ride_type'),
            ]);

            return redirect()->route('trips.show', ['trip' => $trip->id])->with('success', 'Trip record created successfully.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Failed to create trip record: '.$e->getMessage());
        }
    }

    public function show(TripRecord $trip): Response
    {
        if (! auth()->check()) {
            session(['url.intended' => url()->current()]);
        }

        $trip->load('user');

        return Inertia::render('trips/Show', ['trip' => $trip->toResource()->resolve()]);
    }

    public function edit(TripRecord $trip): Response|RedirectResponse
    {
        if (! auth()->check() || auth()->id() !== $trip->user_id) {
            return redirect()->route('trips.show', ['trip' => $trip->id]);
        }

        $stations = Station::all();

        return Inertia::render('trips/Edit', [
            'trip' => $trip->toResource()->resolve(),
            'stations' => $stations,
        ]);
    }

    public function update(UpdateTripRecordRequest $request, TripRecord $trip): RedirectResponse
    {
        $trip->update([
            'ride_type' => $request->input('ride_type'),
            'started_at' => $request->input('started_at'),
            'ended_at' => $request->input('ended_at'),
        ]);

        $trip->startStation()->update([
            'latitude' => $request->input('start_location.latitude'),
            'longitude' => $request->input('start_location.longitude'),
        ]);

        $trip->endStation()->update([
            'latitude' => $request->input('end_location.latitude'),
            'longitude' => $request->input('end_location.longitude'),
        ]);

        return redirect()->route('trips.show', $trip->id)->with('success', 'Trip updated successfully.');
    }

    public function destroy(TripRecord $trip): RedirectResponse
    {
        if (! auth()->check() || auth()->id() !== $trip->user_id) {
            abort(403);
        }

        TripRecord::destroy($trip->id);

        return redirect()->route('home')->with('success', 'Trip record deleted successfully.');
    }
}
