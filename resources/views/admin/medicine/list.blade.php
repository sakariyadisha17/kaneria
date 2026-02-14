@extends('layouts.master')
@section('title')
    Medicine
@endsection
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Medicine</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('admin/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Medicine</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section id="column">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Medicine List</h4>
                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="feather icon-minus"></i></a></li>
                                        <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card-content collapse show">
                                <div class="card-body card-dashboard">
                                    <div class="card-header text-right">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info btn-import"><i class="fa fa-plus"></i> Import</button>
                                        </div>
                                        <div class="btn-group">
                                            <a href="{{URL('admin/medicine/add')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Medicine</a>
                                        </div>

                                    </div>
                                    @include('notifications')
                                    <table class="table table-striped table-bordered" id="medicine-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>                                                
                                                <th>Quantity</th>
                                                <th>Frequency</th>
                                                <th>Note</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Column rendering table -->
        </div>
    </div>
</div>
<!-- END: Content-->

<!-- Delete Modal -->
<div class="modal fade text-left" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash"></i> Delete Medicine</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Confirm Medicine Deletion.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Cancel</button>
                <a type="button" class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="import-modal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 40%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="importModalLabel"><i class="fa fa-upload"></i> Import Old Patients</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('medicine.import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Choose CSV File:</label>
                        <input type="file" class="form-control" name="file" id="file" required>
                    </div>
                    <a href="{{ route('medicine.import.sample') }}" class="btn btn-outline-success">Download Sample CSV</a>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('additionalcss')
    <link rel="stylesheet" href="{{ url('plugins/datatables/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
@endsection
@section('additionaljs')
    <script type="text/javascript" src="{{ url('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="/assets/js/plugins/flatpickr.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#medicine-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                aaSorting: [0, 'desc'],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                ajax: '{!! route('medicine.data') !!}',
                columns: [
                   
                    { data: 'id', name: 'id', visible: false },
                    { data: 'name', name: 'name' },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'frequency', name: 'frequency' },
                    { data: 'note', name: 'note' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            $('#confirm-delete').on('show.bs.modal', function(e) {
                $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            });
        });
    </script>
    
    <script type="text/javascript">
    $(document).ready(function() {
        $('.btn-import').on('click', function() {
            $('#import-modal').modal('show');
        });
    });

    $(document).ready(function() {
        $('#import-modal form').on('submit', function(e) {
            var fileInput = $('#file');
            var file = fileInput[0].files[0];
            if (file) {
                var fileType = file.type;
                if (fileType !== 'text/csv' && fileType !== 'application/vnd.ms-excel') {
                    e.preventDefault(); 
                    alert('Please upload a valid CSV file.');
                }
            }
        });
    });

    </script>
@endsection
