<?php

namespace App\Http\Controllers;

use App\Attendance;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AttendanceController extends Controller
{
    /**
     * Construct
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'is_admin']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // $data = Attendance::with('user');
            $data = Attendance::leftJoin('users', 'attendances.user_id', '=', 'users.id')
                ->select('attendances.id as id', 'attendances.updated_at as updated_at', 'attendances.created_at as created_at', 'attendances.status as status', 'attendances.user_id as user_id', 'users.name as name')
                ->groupBy('attendances.user_id');

            return DataTables::eloquent($data)
                ->addColumn('action', function ($data) {
                    return view('layouts._action', [
                        'model' => '',
                        'edit_url' => '',
                        'show_url' => route('attendance.show', $data->user_id),
                        'delete_url' => '',
                    ]);
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->toJson();
        }

        // $users = User::paginate(5);
        return view('pages.attendance.index');
    }

    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = Attendance::with('user')->where('user_id', $id);

            return DataTables::eloquent($data)
                ->addColumn('action', function ($data) {
                    $btn = "<button class='btn btn-sm btn-secondary' onclick='getDetail($data->id)'>Detail</button>";
                    return $btn;
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('pages.attendance.show', compact('id'));
    }

    public function cetak_pdf()
    {
        $attendance = Attendance::all();
        $pdf = PDF::loadview('page.attendance.pdf', ['cetak_pdf' => $attendance]);
        return $pdf->download('Laporan_Data_Barang');
    }

    public function getAttendanceDetail(Request $request)
    {
        $attendance = Attendance::with(['user', 'detail'])->findOrFail($request->id);
        return response()->json(['data' => $attendance], 200);
    }
}
