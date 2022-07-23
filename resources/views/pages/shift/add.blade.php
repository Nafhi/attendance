@extends('layouts.app')

@section('content')

<form action="{{ route('shift.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Shift</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/shift">Shift</a></li>
                    <li class="breadcrumb-item active">Tambah Shift</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
    <div class="card-body">

        @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
        @endif

        <div class="form-group">
            <label for="inputName">Kelompok Shift</label>
            <input type="text" name="nama" id="inputName" class="form-control">
            <p></p>
        </div>
        <div class="form-group">
            <label for="inputJam">Check-in</label>
            <input type="time" name="checkin" id="inputJam" class="form-control">
        </div>
        <div class="form-group">
            <label for="inputJam">Check-out</label>
            <input type="time" name="checkout" id="inputJam" class="form-control">
        </div>
        <div class="form-group">
            <label for="inputJamKel">Overtime</label>
            <input type="time" name="overtime" id="inputJamKel" class="form-control"> <br><br>
            <p></p>
            <button class="btn btn-danger" value="Submit">Tambah</button></a>
        </div>
    </div>
    </div>
</form>
@endsection
