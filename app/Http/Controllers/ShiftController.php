<?php

namespace App\Http\Controllers;

use App\Shift;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Shift::query();

            return DataTables::eloquent($data)
                ->addColumn('action', function ($data) {
                    return view('layouts._action', [
                        'model' => $data,
                        'edit_url' => route('shift.edit', $data->id),
                        'show_url' => '',
                        'delete_url' => route('shift.destroy', $data->id),
                    ]);
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.shift.shift');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.shift.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (date('H:i', strtotime($request->checkout)) == date('H:i', strtotime($request->checkin))) {
            return redirect()->back()->with('error', 'Waktu Checkout tidak boleh sama waktu Checkin');
        } else if (date('H:i', strtotime($request->checkout)) < date('H:i', strtotime($request->checkin))) {
            return redirect()->back()->with('error', 'Waktu Checkout tidak boleh kurang dari waktu Checkin');
        } else if (date('H:i', strtotime($request->checkout)) > date('H:i', strtotime($request->overtime))) {
            return redirect()->back()->with('error', 'Waktu Overtime tidak boleh kurang dari waktu Checkout');
        } else {
            $data = new Shift;
            $data->nama = $request->nama;
            $data->checkin = $request->checkin;
            $data->checkout = $request->checkout;
            $data->overtime = $request->overtime;
            $data->save();
            return redirect()->route('shift.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Shift::find($id);
        return view('pages.shift.edit')->with(compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (date('H:i', strtotime($request->checkout)) == date('H:i', strtotime($request->checkin))) {
            return redirect()->back()->with('error', 'Waktu Checkout tidak boleh sama waktu Checkin');
        } else if (date('H:i', strtotime($request->checkout)) < date('H:i', strtotime($request->checkin))) {
            return redirect()->back()->with('error', 'Waktu Checkout tidak boleh kurang dari waktu Checkin');
        } else if (date('H:i', strtotime($request->checkout)) > date('H:i', strtotime($request->overtime))) {
            return redirect()->back()->with('error', 'Waktu Overtime tidak boleh kurang dari waktu Checkout');
        } else {
            $data = Shift::find($id);
            $data->nama = $request->nama;
            $data->checkin = $request->checkin;
            $data->checkout = $request->checkout;
            $data->overtime = $request->overtime;
            $data->save();
            return redirect()->route('shift.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Shift::find($id);
        $data->delete();
        return redirect()->route('shift.index');
    }
}
