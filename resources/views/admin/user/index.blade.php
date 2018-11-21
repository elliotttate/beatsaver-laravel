@extends('admin.layout.app')

@section('content')
    <section class="content-header">
        <h1>
            Users
            <small>index page</small>
            <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-xs">Add user</a>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <div class="box-body">
                        <table id="index-table" class="table table-bordered table-striped table-hover" role="grid">
                            <thead>
                            <tr role="row">
                                <th tabindex="0" rowspan="1" colspan="1">ID</th>
                                <th tabindex="0" rowspan="1" colspan="1">Name</th>
                                <th tabindex="0" rowspan="1" colspan="1">Email</th>
                                <th tabindex="0" rowspan="1" colspan="1">Songs</th>
                                <th tabindex="0" rowspan="1" colspan="1">States</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th rowspan="1" colspan="1">ID</th>
                                <th rowspan="1" colspan="1">Name</th>
                                <th rowspan="1" colspan="1">Email</th>
                                <th rowspan="1" colspan="1">Songs</th>
                                <th rowspan="1" colspan="1">States</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script type="application/javascript">
        // Initialize datatable
        $(document).ready(function () {
            let dataTable = $('#index-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.users.datatable') !!}',
                columns: [
                    {data: 'id', name: 'ID'},
                    {data: 'name', name: 'Name'},
                    {data: 'email', name: 'Email'},
                    {data: 'songs', name: 'Songs', 'searchable': false, 'orderable': false},
                    {
                        data: 'states',
                        name: 'States',
                        'searchable': false,
                        'orderable': false,
                        "render": function (data) {
                            return prettifyStates(data);
                        },
                    }
                ],
                "order": [[0, "desc"]]
            });

            // Make rows clickable
            dataTable.on('click', 'tbody tr', function () {
                window.location.href = '{!! route('admin.users.index') !!}' + '/' + $(this).find('td:first').text();
            });

            // Turn states into html labels
            function prettifyStates(data) {
                let states = data.split('.').splice(1);
                let prettyStates = '';
                let labelBuilder = $('<span class="label">');

                for (let state of states) {
                    console.log(state);
                    switch (state) {
                        case 'Unverified':
                            labelBuilder.attr('class', 'label label-warning');
                            break;
                        case 'Banned':
                            labelBuilder.attr('class', 'label label-danger');
                            break;
                        case 'New':
                            labelBuilder.attr('class', 'label label-info');
                            break;
                        case 'Administrator':
                            labelBuilder.attr('class', 'label label-info');
                            break;
                    }
                    labelBuilder.text(state);

                    prettyStates += labelBuilder.prop('outerHTML') + '&nbsp';
                }

                return prettyStates
            }
        })
    </script>
@endpush
