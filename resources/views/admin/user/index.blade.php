@extends('admin.layout.app')

@section('content')
    <section class="content-header">
        <h1>
            Users
            <small>index page</small>
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
                                <th tabindex="0" rowspan="1" colspan="1">Songs Posted</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr data-id="{{ $user->id }}" role="row" class="odd clickable-row">
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->songs->count() }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th rowspan="1" colspan="1">ID</th>
                                <th rowspan="1" colspan="1">Name</th>
                                <th rowspan="1" colspan="1">Email</th>
                                <th rowspan="1" colspan="1">Songs Posted</th>
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
            $('.clickable-row').click(function () {
                window.location = '{{ route('admin.users.index') }}' + '/' + $(this).data('id');
            });

            $('#index-table').DataTable({
                'order': [[0, 'desc']]
            });
        })
    </script>
@endpush
