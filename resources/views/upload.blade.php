@extends('layout')
@section('title', '- Song Upload')

@section('content')
    <div class="container">
        <form class="form-signin" method="post" enctype="multipart/form-data" action="{{ route('upload.submit') }}">
            {{ csrf_field() }}
            <h2 class="form-signin-heading">Upload Beat Track</h2>
            <label for="inputEmail" class="sr-only">Beat Track Name</label>
            <div class="form-group">
                <label for="InputFile">Beat Track Name</label>
                <input type="text" name="name" id="inputTitle" class="form-control" placeholder="160 Letters Max" maxlength="160" required autofocus/>
            </div>
            <div class="form-group">
                <label for="InputFile">File input</label>
                <input type="file" id="InputFile" name="fileupload" accept=".zip, application/zip"/>
                <p class="help-block">Must meet the following upload rules<br>
                <ul>
                    <li>Must be a ZIP file with the songs subfolder in the root (EG: SongName/info.json)</li>
                    <li>Must be under 15MB</li>
                    <li>Must contain vaild metadata and album art</li>
                    <li>You will not be able to edit your song after uploading, only delete. This is due to how scoreboards work. Stick to uploading completed and tested songs to the benefit of your
                        fans.
                    </li>
                    <li>Make sure you have permission to use any content involved in your beatmap. This includes songs, videos, hit sounds, graphics, and any other content that isn't your own
                        creation.
                    </li>
                    <li>Do not plagiarise or attempt to steal the work of others. Do not also upload or use other people's work without their explicit permission (including, but not limited to, skins
                        and guest difficulties).
                    </li>
                    <li>Protip: Check out these <a href="https://www.youtube.com/playlist?list=PLYeZR6d3zDPgDgWogOwMteL-5SQWAE14b">great videos by Freeek</a> about how to make a good beat track
                </ul>
                </p>
            </div>
            <div class="form-group">
                <label for="TextFile">Beat Description</label>
                <textarea name="description" id="TextFile" class="form-control" rows="3"></textarea>
                <p class="help-block">
                    Plain Text Only,
                </p>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
        </form>

    </div>
@endsection