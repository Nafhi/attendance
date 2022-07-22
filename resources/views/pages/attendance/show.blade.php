@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Attendance</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item">Attendance</li>
                    <li class="breadcrumb-item active">Show</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<section class="content">
    <div class="container-fluid">
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-12">
                <!-- Attendance Chart -->
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary mb-2">Back</a>

                <div class="card">
                    <div class="card-header">
                        <div class="row" style="align-items: center;">
                            <div class="col-md-2">
                                <h3 class="card-title">
                                    <i class="ion ion-clipboard mr-1"></i>
                                    Attendance
                                </h3>
                            </div>
                            <div class="col-md-3">
                                <input class="form-control" type="month" id="filter_month">
                            </div>
                            <div class="col-md-7">

                            </div>
                        </div>
                    </div>

                    <!-- /.card-header -->
                    <div class="card-body">

                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Check In Time</th>
                                    <th>Check Out Time</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>

                    </div>
                </div>

                <div class="attendance-loader p-4 d-none">
                    <div class="loader"></div>
                </div>


                <div class="attendance-details d-none">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ion ion-clipboard mr-1"></i>
                                Attendance
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td id="attendance_name"></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td id="attendance_status"></td>
                                    </tr>
                                    <tr>
                                        <th>Check In</th>
                                        <td id="attendance_in"></td>
                                    </tr>
                                    <tr>
                                        <th>Check Out</th>
                                        <td id="attendance_out"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>

                <div id="attendance_in_out">
                    <p></p>
                </div>

            </section>
            <!-- /.Left col -->
        </div>
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
</section>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: `{{ route('attendance.show', $id) }}`,
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: function(row) {
                    let date = new Date(row.created_at);
                    return date.toLocaleString();
                }, name: 'created_at'},
                {data: function(row) {
                    let date = new Date(row.updated_at);
                    return date.toLocaleString();
                }, name: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        $('#filter_month').change(function() {
            $('.attendance-details').addClass('d-none')
            $('#attendance_in_out').html('')
            let date = $(this).val()
            table.destroy()
            table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: `{{ url('getAttendanceByMonth') }}/` + date,
            columns: [
                {data: 'DT_RowIndex', name: 'id'},
                {data: function(row) {
                    let date = new Date(row.created_at);
                    return date.toLocaleString();
                }, name: 'created_at'},
                {data: function(row) {
                    let date = new Date(row.updated_at);
                    return date.toLocaleString();
                }, name: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
        })
    });

    function getDetail(id)
    {
        $('#attendance_in_out').html('')
        $('.attendance-loader').removeClass('d-none')
        if($('.attendance-details').hasClass('d-none')){
            $('.attendance-details').removeClass('d-none')
        }else{
            $('.attendance-details').addClass('d-none')
        }
        console.log(id)
        $.ajax({
            url: `{{route("getAttendanceDetail")}}`,
            type: "POST",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                'id': id,
            },
            statusCode: {
                500: function (response) {
                    console.log(response)
                },
            },
            success: function (data) {

                $('.attendance-details').removeClass('d-none')

                $('#attendance_name').text(data.data.user.name)
                $('#attendance_status').text(data.data.status == 1 ? 'Check Out' : 'Check In')

                $('#attendance_in').text(new Date(data.data.created_at).toLocaleString())
                $('#attendance_out').text(new Date(data.data.updated_at).toLocaleString())

                $('.attendance-loader').addClass('d-none')
                let html_in_out = ``
                data.data.detail.forEach(item => {
                    html_in_out += `
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ion ion-clipboard mr-1"></i>
                                Attendance ${item.type}
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table" id="datatable">
                                <tbody>
                                    <tr>
                                        <th>Time</th>
                                        <td>${new Date(item.created_at).toLocaleString()}</td>
                                    </tr>
                                    <tr>
                                        <th>Long, lat</th>
                                        <td>${item.long}, ${item.lat}</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>${item.address}</td>
                                    </tr>
                                    <tr>
                                        <th>Location</th>
                                        <td>
                                            <div style="width: 100%">
                                                <iframe width="100%" height="300" frameborder="0" scrolling="no"
                                                    marginheight="0" marginwidth="0"
                                                    src="https://maps.google.com/maps?q=${item.lat},${item.long}&hl=en&z=14&amp;output=embed">
                                                </iframe>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Photo</th>
                                        <td><img width="350" src="{{ asset('/storage/attendance/') }}/${item.photo}" alt=""></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    `
                });
                $('#attendance_in_out').html(html_in_out)
            }
        });
    }
</script>
@endpush
