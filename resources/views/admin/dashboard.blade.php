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
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $dashboard['userCount'] }}</h3>
                        <p>User Registrations</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
