@extends('layouts.content')

@section('css')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@stop

@section('js')
    @parent
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js')}}" defer></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js')}}" defer></script>

    <script>
        $(document).ready(function(){
            $('#dataTable').DataTable({
                "scrollX": true,
                columnDefs: [{
                    orderable: false,
                    targets: [ 5 ]
                }]
            });

            $('.delete').on('click', function(e) {
               $('#ordertype_id').val(this.id);
            });
        });
    </script>
@stop

@section('content')

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Order Types</h6>
                        </div>
                        <div class="card-body">
                            <a style="margin-bottom: 20px" href="{{route('ordertype.add')}}" class="btn btn-xs btn-success"><i class="fas fa-fw fa-plus-circle"></i></a>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Updated By</th>
                                        <th>Update Time</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Updated By</th>
                                        <th>Update Time</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>

                                    @foreach($ordertypes as $ordertype)
                                        <tr>
                                            <td>{{$ordertype->id}}</td>
                                            <td>{{$ordertype->name}}</td>
                                            <td>{{$ordertype->value}}</td>
                                            <td>{{$ordertype->updated_by}}</td>
                                            <td>{{$ordertype->updated_at}}</td>
                                            <td>
                                                <a style="margin: 2px" href="{{route('ordertype.edit', [$ordertype->id])}}" class="btn btn-xs btn-primary"><i class="fas fa-fw fa-edit"></i></a>
                                                <a style="margin: 2px" href id="{{$ordertype->id}}" data-toggle="modal" data-target="#destoryModal" class="btn btn-xs btn-danger delete"><i class="fas fa-fw fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="destoryModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete Order Type</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Delete this order type?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <form method="post" action="{{route('ordertype.destroy')}}" class="inline">
                                    @csrf
                                    <input type="hidden" id="ordertype_id" name="id">
                                    <button type="submit" class="btn btn-danger">Yes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
@endsection
