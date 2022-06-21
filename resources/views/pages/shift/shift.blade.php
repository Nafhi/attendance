@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Shift</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Shift</li>
                </ol>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<br>

<a href="{{ route('shift.create') }}" class="btn btn-sm btn-primary mb-2">Add</a>
<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-body p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kelompok Shift</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Overtime</th>
                            <th>Opsi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                <td>1</td>
                                <td>08:00</td>
                                <td>17:00</td>
                                <td>02.00</td>
                                <td class="text-left py-0 align-middle">
                                    <div class="btn-group btn-group-sm">
                                        <a href="" class="btn btn-info"><i
                                                class="fas fa-pencil-alt mr-1"></i></a>
                                        <form action="" method="POST">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger"><i
                                                    class="fas fa-trash"></i></button>

                                        </form>
                                    </div>
                                </td>
                            </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <!-- /.card -->
</div>
@endsection
