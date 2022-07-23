<?php

namespace App\Http\Controllers;

use App\Shift;
use App\Traits\ImageStorage;
use App\User;
use App\UserShift;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    use ImageStorage;

    /**
     * Construct
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'is_admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::leftJoin('user_shifts', 'user_shifts.user_id', '=', 'users.id')
                ->leftJoin('shift', 'user_shifts.shift_id', '=', 'shift.id')
                ->select('users.id as id', 'users.name as name', 'users.email as email', 'shift.nama as shift_nama');

            return DataTables::eloquent($data)
                ->addColumn('action', function ($data) {
                    return view('layouts._action', [
                        'model' => $data,
                        'edit_url' => route('user.edit', $data->id),
                        'show_url' => route('user.show', $data->id),
                        'delete_url' => route('user.destroy', $data->id),
                    ]);
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->toJson();
        }

        // $users = User::paginate(5);
        return view('pages.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shifts = Shift::all();
        return view('pages.user.create', compact('shifts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $photo = $request->file('image');

        if ($photo) {
            $request['photo'] = $this->uploadImage($photo, $request->name, 'profile');
        }

        $request['password'] = Hash::make($request->password);

        $user = User::create($request->all());

        $user_shift = new UserShift();
        $user_shift->user_id = $user->id;
        $user_shift->shift_id = $request->shift_id;
        $user_shift->save();

        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shifts = Shift::all();
        $user = User::findOrFail($id);
        return view('pages.user.show', compact('user', 'shifts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shifts = Shift::all();
        $user_shift = UserShift::where('user_id', $id)->first();
        $user = User::findOrFail($id);
        return view('pages.user.edit', compact('user', 'shifts', 'user_shift'));
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
        $user = User::findOrFail($id);
        $photo = $request->file('image');

        if ($photo) {
            $request['photo'] = $this->uploadImage($photo, $request->name, 'profile', true, $user->photo);
        }

        if ($request->password) {
            $request['password'] = Hash::make($request->password);
        } else {
            $request['password'] = $user->password;
        }

        $user->update($request->all());

        $user_shift = UserShift::where('user_id', $user->id)->first();

        if ($user_shift == null) {
            $user_shift = new UserShift();
            $user_shift->user_id = $user->id;
            $user_shift->shift_id = $request->shift_id;
            $user_shift->save();
        } else {
            $user_shift->user_id = $id;
            $user_shift->shift_id = $request->shift_id;
            $user_shift->save();
        }

        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if ($user->photo) {
            $this->deleteImage($user->photo, 'profile');
        }

        $user->delete();

        return redirect()->route('user.index');
    }
}
