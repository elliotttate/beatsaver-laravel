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
                            <tbody>
                            @foreach($songs as $song)
                                <tr data-id="{{ $song->id }}" role="row" class="odd clickable-row">
                                    <td>{{ $song->id }}</td>
                                    <td>{{ $song->name }}</td>
                                    <td>{{ $song->uploader->name }}</td>
                                    <td>{{ $song->details->first()->play_count }}</td>
                                    <td>{{ $song->details->first()->download_count }}</td>
                                    <td>{{ $song->details->first()->votes->where('direction', true)->count() }}</td>
                                    <td>{{ $song->details->first()->votes->where('direction', false)->count() }}</td>
                                    <td>
                                        {!! $song->deleted_at ? '<span class="label label-danger">Hidden</span>' : '' !!}
                                        {!! $song->created_at->diffInDays(Carbon\Carbon::now()) < 30  ? '<span class="label label-info">New</span>' : '' !!}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
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
            $('.clickable-row').click(function () {
                window.location = '{{ route('admin.songs.index') }}' + '/' + $(this).data('id');
            });

            $('#index-table').DataTable({
                'order': [[0, 'desc']]
            });
        })
    </script>
@endpush
