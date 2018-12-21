@extends('admin.layout.app')

@section('content')
    <section class="content-header">
        <h1>
            Dashboard
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $dashboard['songCount'] }}</h3>
                        <p>Songs</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $dashboard['downloadCount'] }}</h3>
                        <p>Total Downloads</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $dashboard['playCount'] }}</h3>
                        <p>Total Plays</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $dashboard['userCount'] }}</h3>
                        <p>User Registrations</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">Play & Downloads</h3>
                    </div>
                    <div class="box-body">
                        <canvas id="playsDownloadsChart" style="height: 40vh"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js" integrity="sha256-oSgtFCCmHWRPQ/JmR4OoZ3Xke1Pw4v50uh6pLcu+fIc=" crossorigin="anonymous"></script>
    <script>
        let playsDownloadsCanvas = $('#playsDownloadsChart');

        new Chart(playsDownloadsCanvas, {
            type: 'line',
            options: {
                resonsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                    }],
                },
                tooltip : {
                    mode: 'index'
                },
            },
            data: {
                labels: {!! json_encode($registrationsUploadsChart['labels']) !!},
                datasets: [
                    {
                        borderColor: 'rgba(51, 122, 183, 1)',
                        backgroundColor: 'rgba(51, 122, 183, 0.3)',
                        label: 'Registrations',
                        data: {!! json_encode($registrationsUploadsChart['data']['registrations']) !!}
                    },
                    {
                        borderColor: 'rgba(0, 166, 90, 1)',
                        backgroundColor: 'rgba(0, 166, 90, 0.3)',
                        label: 'Song Uploads',
                        data: {!! json_encode($registrationsUploadsChart['data']['uploads']) !!}
                    }
                ]
            }
        });
    </script>
@endpush
