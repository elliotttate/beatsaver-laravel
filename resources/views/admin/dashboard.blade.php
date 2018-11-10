@extends('admin.layout.app')

@section('content')
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
    </section>
    <section class="content">
        <div class="col-lg-6 col-xs-12">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>0</h3>
                    <p>Songs</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xs-12">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>0</h3>
                    <p>User Registrations</p>
                </div>
            </div>
        </div>
    </section>
@endsection
