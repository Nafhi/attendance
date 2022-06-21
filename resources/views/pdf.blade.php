<?php
use Carbon\Carbon;
?>
{{-- @extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content">
        <div class="page-inner">

                    <!-- /.card-header -->
                    <div class="card-body">

                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Status</th>
                                    <th>Check In Time</th>
                                    <th>Check Out Time</th>
                                    <th>Overtime</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                        <a href="{{ route('attendance.pdf') }}" class="btn btn-sm btn-danger mb-2">Print</a>
                    </div>

@endsection --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Presensi Reka</title>
</head>
<body>
    <h3><center>Report Data Absensi Karyawan</center></h3>
    <table border="1" cellpadding="8">
        <thead>
            <tr bgcolor="#1E90FF">
                <th>No</th>
                <th>User</th>
                <th>Check In Time</th>
                <th>Check Out Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dd as $key => $d)
                @if ($d->attrpt->count() != 0)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $d->name }}</td>
                        @if ($d->attrpt->count() == 2)
                            @foreach ($d->attrpt as $check)
                                <td>{{ Carbon::parse($check->created_at)->format('h:i d M Y') }}</td>
                            @endforeach
                        @elseif ($d->attrpt->count() == 1)
                            <td>{{ Carbon::parse($check->created_at)->format('h:i d M Y') }}</td>
                            <td>-</td>
                        @endif
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</body>
</html>

