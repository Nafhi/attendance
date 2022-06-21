<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\AttendanceDetail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;

class PdfController extends Controller
{
    public function print()
    {
        $data = array(
            'dd' => User::with('attrpt')->get()
        );
        $filename = 'Report Presensi Reka '.Carbon::parse(now());
        $view = \View::make('pdf', $data);
        $html = $view->render();
        PDF::SetTitle('Report');
        PDF::AddPage();
        PDF::WriteHTML($html, true, false, true, false);
        PDF::Output($filename.'.pdf');
    }
}
