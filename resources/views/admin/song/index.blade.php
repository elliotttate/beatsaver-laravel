@extends('admin.layout.app')

@section('content')
    <section class="content-header">
        <h1>
            Songs
            <small>Index</small>
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
                                <th tabindex="0" rowspan="1" colspan="1">Author Name</th>
                                <th tabindex="0" rowspan="1" colspan="1">Play Count</th>
                                <th tabindex="0" rowspan="1" colspan="1">Download Count</th>
                                <th tabindex="0" rowspan="1" colspan="1">Upvotes</th>
                                <th tabindex="0" rowspan="1" colspan="1">Downvotes</th>
                                <th tabindex="0" rowspan="1" colspan="1">States</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th rowspan="1" colspan="1">ID</th>
                                <th rowspan="1" colspan="1">Name</th>
                                <th rowspan="1" colspan="1">Author Name</th>
                                <th rowspan="1" colspan="1">Play Count</th>
                                <th rowspan="1" colspan="1">Download Count</th>
                                <th rowspan="1" colspan="1">Upvotes</th>
                                <th rowspan="1" colspan="1">Downvotes</th>
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
        $(document).ready(function () {
            // Initialize datatable
            $(document).ready(function () {
                let dataTable = $('#index-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('admin.songs.datatable') !!}',
                    columns: [
                        {data: 'id', name: 'ID'},
                        {data: 'name', name: 'Name'},
                        {data: 'author_name', name: 'Author Name', 'searchable': false, 'orderable': false},
                        {data: 'play_count', name: 'Play Count', 'searchable': false, 'orderable': false},
                        {data: 'download_count', name: 'Download Count', 'searchable': false, 'orderable': false},
                        {data: 'upvotes', name: 'Upvotes', 'searchable': false, 'orderable': false},
                        {data: 'downvotes', name: 'Downvotes', 'searchable': false, 'orderable': false},
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
                    window.location.href = '{!! route('admin.songs.index') !!}' + '/' + $(this).find('td:first').text();
                });

                // Turn states into html labels
                function prettifyStates(data) {
                    let states = data.split('.').splice(1);
                    let prettyStates = '';
                    let labelBuilder = $('<span class="label">');

                    for (let state of states) {
                        switch (state) {
                            case 'New':
                                labelBuilder.attr('class', 'label label-warning');
                                break;
                            case 'Hidden':
                                labelBuilder.attr('class', 'label label-danger');
                                break;
                        }
                        labelBuilder.text(state);

                        prettyStates += labelBuilder.prop('outerHTML') + '&nbsp';
                    }

                    return prettyStates
                }
            })
        })
    </script>
@endpush
