@extends('themes.default.layout')
@section('title', '- Song Upload')

@section('content')
    @if(auth()->user()->isVerified())
        <div class="container">
            <form class="form-signin" method="post" enctype="multipart/form-data" action="{{ route('upload.submit') }}">
                {{ csrf_field() }}
                <h2 class="form-signin-heading">Upload Beat Track</h2>
                <div class="form-group">
                    <input type="text" name="name" id="inputTitle" class="form-control" placeholder="160 Letters Max" maxlength="160" required autofocus/>
                </div>
                <div class="form-group">
                    <label for="GenreDropdown">Genre</label>
                        <select name="genre_id" id="GenreDropdown" class="form-control" required>
                            @foreach ($genres as $genre)
                                <option value={{ $genre->id }}>{{ $genre->name }}</option>
                            @endforeach
                        </select> 
                </div>
                <div class="form-group">
                    <label for="InputFile">File input</label>
                    <input type="file" id="InputFile" name="fileupload" accept=".zip, application/zip"/>
                    <p class="help-block">Must meet the following upload rules<br>
                    <ul>
                        <li>Must be a ZIP file with the songs subfolder in the root (EG: SongName/info.json)</li>
                        <li>Must be under 15MB</li>
                        <li>Must contain valid metadata <strong>(UTF-8 encoded)</strong> and album art</li>
                        <li>Make sure you have permission to use any content involved in your beatmap. This includes songs, videos, hit sounds, graphics,
                            and any other content that isn't your own creation.
                        </li>
                        <li>Do not plagiarise or attempt to steal the work of others. Do not also upload or use other people's work without their explicit permission
                            (including, but not limited to, skins and guest difficulties).
                        </li>
                    </ul>
                    </p>
                    <p class="help-block">Useful tips for avoiding problems<br>
                    <ul>
                        <li>Avoid using UNICODE charters in folder or file names. BeatSaber has no support for them.</li>
                        <li>Remove unnecessary content from the zip file (like autosaves folder from the 3D editor).</li>
                        <li>Check out these <a href="https://www.youtube.com/playlist?list=PLYeZR6d3zDPgDgWogOwMteL-5SQWAE14b">great videos by Freeek</a> about how to make a good beat track.</li>
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
    @else
        <br/>
        <div class="alert alert-warning text-center" role="alert">
            Your account is not verified. You won't be able to upload songs until you verify your account!
            Please visit your <a href="{{ route('profile') }}">profile</a> page if you need re-request the verification mail.
        </div>
    @endif
@endsection