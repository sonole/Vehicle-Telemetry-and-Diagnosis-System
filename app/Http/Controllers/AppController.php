<?php

namespace App\Http\Controllers;

use App\Models\Packet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppController extends Controller
{
    public function searchRoute(Request $request)
    {
        //Validation
        $validator = Validator::make($request->all(), [
            'datestart' => 'required|date_format:d/m/Y H:i',
            'datefinish' => 'required|date_format:d/m/Y H:i|after_or_equal:datestart'
        ]);
        if ($validator->fails()) {
            return redirect()->route('index')
                ->withErrors($validator)->withInput();
        }

        //Search db for packets
        $start = Carbon::createFromFormat('d/m/Y H:i:s', $request->datestart .':00')->format('Y-m-d H:i:s');
        $end = Carbon::createFromFormat('d/m/Y H:i:s', $request->datefinish .':00')->format('Y-m-d H:i:s');
        $packets = Packet::where('sn', 'rdt002')->whereBetween('datetime', [$start, $end])->get();

        //Return if no results found
        if (count($packets) < 1) {
            session()->flashInput($request->all());
            return $this->index(null, 'No results found!');
        }

        //Create data
        $path = array();
        $infoData = array();
        foreach ($packets as  $packet) {
            $path[] = [
                'general' => [
                    'sn' => $packet->sn,
                    'Date & Time' =>  Carbon::createFromFormat('Y-m-d H:i:s', $packet->datetime)->format('d/m/Y H:i:s')
                ],
                'GPS' => [
                    'lat' => $packet->latitude,
                    'lng' => $packet->longitude,
                    'Satellites' => $packet->satellites,
                    'Altitude' => $packet->altitude,
                    'Speed' => $packet->speed,
                ],
                'OBD' => [
                    'DTC Status' => $packet->dtc_status ?: 'N/A',
                    'pid001' => 'asfasfa',
                    'pid002' => 'asfasfa',
                    'pid003' => 'asfasfa',
                    'pid004' => 'asfasfa',
                    'pid005' => 'asfasfa',
                    'pid006' => 'asfasfa',
                    'pid007' => 'asfasfa',
                    'pid008' => 'asfasf'
                ],
            ];
        }
        $infoData['text'] = 'Route details';

        $infoData['dt'] = [ 'start' => $request->datestart, 'end' => $request->datefinish ];

        session()->flashInput($request->all());
        return $this->index($path, $infoData);
    }

    public function index($path = null, $infoData = null)
    {
        //Return Athens location if nothing found
        if ( $path == null ) {
            $path = [ 0 => [37.987996098919645, 23.726237223907738] ];
        }
        if ( is_string($infoData)) {
            $temp = $infoData;
            $infoData = array();
            $infoData['text'] = $temp;
        }

        return view('app', [
                'path' =>  $path,
                'infoData' => $infoData
        ]);
    }
}
