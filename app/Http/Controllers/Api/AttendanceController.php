<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Shift;
use App\Traits\ImageStorage;
use App\UserShift;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller
{
    use ImageStorage;

    /**
     * Store presence status
     * @param Request $request
     * @return JsonResponse|void
     * @throws InvalidFormatException
     * @throws BindingResolutionException
     */
    public function store(Request $request)
    {
        // return response()->json(['data'=>$request->photo->data]); //cek

        $request->validate([
            'long' => ['required'],
            'lat' => ['required'],
            'address' => ['required'],
            'type' => ['in:in,out', 'required'],
            'photo' => ['required']
        ]);

        $photo = $request->file('photo');
        $attendanceType = $request->type;
        $userAttendanceToday = $request->user()
            ->attendances()
            ->whereDate('created_at', Carbon::today())
            ->first();

        $user_shift = UserShift::leftJoin('shift', 'user_shifts.shift_id', '=', 'shift.id')
            ->where('user_id', $request->user()->id)
            ->first();

        $checkin = Str::substr($user_shift->checkin, 0, 5);
        $checkout = Str::substr($user_shift->checkout, 0, 5);
        $overtime = Str::substr($user_shift->overtime, 0, 5);
        $now = Carbon::now()->format('H:i');
        // // is presence type equal with 'in' ?
        if ($attendanceType == 'in') {
            if (date('H:i', strtotime($checkin)) > $now) {
                return response()->json(
                    [
                        'message' => "Checkin was open on $checkin"
                    ],
                    Response::HTTP_OK
                );
            }

            if (date('H:i', strtotime($checkout)) < $now) {
                $attendance = $request
                    ->user()
                    ->attendances()
                    ->create(
                        [
                            'status' => 'late checkin'
                        ]
                    );

                $attendance->detail()->create(
                    [
                        'type' => 'in',
                        'long' => $request->long,
                        'lat' => $request->lat,
                        'photo' => $this->uploadImage($photo, $request->user()->name, 'attendance'),
                        'address' => $request->address
                    ]
                );

                return response()->json(
                    [
                        'message' => "User Late, checkin must be do before $checkout"
                    ],
                    Response::HTTP_CREATED
                );
            }

            // is $userPresenceToday not found?
            if (!$userAttendanceToday) {
                $attendance = $request
                    ->user()
                    ->attendances()
                    ->create(
                        [
                            'status' => 'in'
                        ]
                    );

                $attendance->detail()->create(
                    [
                        'type' => 'in',
                        'long' => $request->long,
                        'lat' => $request->lat,
                        'photo' => $this->uploadImage($photo, $request->user()->name, 'attendance'),
                        'address' => $request->address
                    ]
                );

                return response()->json(
                    [
                        'message' => 'Success'
                    ],
                    Response::HTTP_CREATED
                );
            }

            // else show user has been checked in
            return response()->json(
                [
                    'message' => 'User has been checked in',
                ],
                Response::HTTP_OK
            );
        }

        if ($attendanceType == 'out') {
            if (date('H:i', strtotime($checkout)) < $now) {
                $attendance = $request
                    ->user()
                    ->attendances()
                    ->create(
                        [
                            'status' => 'late checkout'
                        ]
                    );

                $attendance->detail()->create(
                    [
                        'type' => 'out',
                        'long' => $request->long,
                        'lat' => $request->lat,
                        'photo' => $this->uploadImage($photo, $request->user()->name, 'attendance'),
                        'address' => $request->address
                    ]
                );

                return response()->json(
                    [
                        'message' => "User Late, checkout must be do before $overtime"
                    ],
                    Response::HTTP_CREATED
                );
            }

            if ($userAttendanceToday) {

                if ($userAttendanceToday->status) {
                    return response()->json(
                        [
                            'message' => 'User has been checked out',
                        ],
                        Response::HTTP_OK
                    );
                }

                $userAttendanceToday->update(
                    [
                        'status' => 'out'
                    ]
                );

                $userAttendanceToday->detail()->create(
                    [
                        'type' => 'out',
                        'long' => $request->long,
                        'lat' => $request->lat,
                        'photo' => $this->uploadImage($photo, $request->user()->name, 'attendance'),
                        'address' => $request->address
                    ]
                );

                return response()->json(
                    [
                        'message' => 'Success'
                    ],
                    Response::HTTP_CREATED
                );
            }

            return response()->json(
                [
                    'message' => 'Please do check in first',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    /**
     * Get List Presences by User
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function history(Request $request)
    {
        $request->validate(
            [
                'from' => ['required'],
                'to' => ['required'],
            ]
        );

        $history = $request->user()->attendances()->with('detail')
            ->whereBetween(
                DB::raw('DATE(created_at)'),
                [
                    $request->from, $request->to
                ]
            )->get();

        return response()->json(
            [
                'message' => "list of presences by user",
                'data' => $history,
            ],
            Response::HTTP_OK
        );
    }
}
