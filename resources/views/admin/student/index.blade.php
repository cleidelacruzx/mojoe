@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/addons/datatables.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="post-prev-title">
                <h3>RHU Accounts</h3>
            </div>
            <hr class="mt-3">
        </div>
    </div>
    <div class="row mt-2 justify-content-between">
        <div class="col-lg-4 col-md-4 col-sm-6 mb-3">
            <div class="card">
                <div class="text-white blue text-center py-4 px-4">
                    <i class=""></i>
                    <h2 class="card-title pt-2 text-white text-oswald"><strong>{{ number_format(count($students) )}}</strong></h2>
                    <h2 class="text-uppercase text-white text-oswald">RHU Account{{ count($students) > 1 ? 's' : '' }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card">
                <div class="card-body pb-0">
                    <table id="example" class="table text-nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                 <th>Registered Since</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                          
                            <tr>
                                @foreach ($students as $student)
                                <td>{{$student->name()}}</td>
                                <td>{{$student->email}}</td>
                                <td>{{date('F j, Y',strtotime($student->created_at))}}</td>
                                 @endforeach
                                 @foreach ($students as $data)
                                <td>
                                     <div class="switch">
                                            <label>
                                                Inactive
                                                <input class="active-mode-switch" type="checkbox" {{$data->status ? 'checked' : ''}} studentId="{{$data->id}}">
                                                <span class="lever"></span> Active
                                            </label>
                                        </div>
                                </td>
                                @endforeach
                                
                            </tr>
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/addons/datatables.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#example').DataTable({
            "scrollX": true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search",
            },
            order:[]
        });

 $('#table').on('change', '.active-mode-switch', function() {
            var status = 0;
            var id = $(this).attr('studentId');
            if ($(this).is(':checked')) {
                status = 1;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/admin/course/"+id+"/status",
                type : 'PUT',
                data: { id: id, status : status },
                success: function(result) {
                    var newResult = JSON.parse(result);
                    const toast = swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    toast({
                        type: 'success',
                        title: newResult.status
                    })
                },
                error : function(error) {
                    console.log('error');
                    console.log(error);
                }
            });
        });


    });

</script>
@endsection
