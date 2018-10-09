<div class="alert alert-danger text-center" role="alert">
    <strong>
        <div>Selecting a new song archive will create a version of the song!</div>
        <div>The new version will have its own votes, download count and scoreboard.</div>
    </strong>
</div>

<form id="update-form" class="form-inline" method="post" enctype="multipart/form-data" action="{{ route('browse.detail.edit.submit',['id' => $id]) }}">
    {{ csrf_field() }}
    <h2>
        <input type="text" name="name" value="{{ $name }}" id="inputTrackName"  style="width:100%;" class="form-control" placeholder="Track Name e.g. My awesome track!" maxlength="160" required autofocus/>
    </h2>

    <table id="song-{{ $id }}" class="table" style="table-layout:fixed;">
        <tr>
            <th rowspan="7" style="width: 15%;" class="text-center">
                <div>
                    <img src="{{ $coverUrl }}" alt="{{ $name }}" style="min-width: 10em; max-width: 10em;">
                </div>
                <br/>
                <div>
                    <a class="btn btn-default" href="{{ $downloadUrl }}" role="button">Download File</a>
                </div>
            </th>
            <th colspan="2">
                <small>Uploaded by: <a href="{{ route('browse.user',['id' => $uploaderId]) }}">{{ $uploader }}</a> ({{ $createdAt }})</small>
            </th>
        </tr>
        <tr>
            <td>Song: {{ $songName }} - {{ $songSubName }}</td>
            <td class="text-right">Version: {{$version}}</td>
        </tr>
        <tr>
            <td>Author: {{ $authorName }}</td>
            <td>Difficulties: {{ $difficulties }}</td>
        </tr>
        <tr>
            <td>
                <div class="form-group">
                    Genre:
                    <select name="genre_id" id="GenreDropdown" class="form-control" required>
                        <option value="" {{ $genreId == 0 ? 'selected' : '' }}>Please select a genre</option>
                        @foreach ($genres as $genre)
                            <option value={{ $genre->id }} {{ $genreId == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                        @endforeach
                    </select> 
                </div>
            </td>
            <td>
            </td>
        </tr>
        <tr>
            <td>
                Downloads: {{ $downloadCount }} || Finished: {{ $playedCount }}
            </td>
            <td>
                Lighting Events: {{ $events }}
            </td>
        </tr>
        <tr>
            <td colspan="2"><textarea name="description" rows="5" class="form-control" style="width:100%;">{{$description}}</textarea></td>
        </tr>
        <tr>
            <td>
                <label id="song-archive-label" class="btn btn-danger" for="song-archive">
                    <input id="song-archive" type="file" style="display: none" name="fileupload" accept=".zip, application/zip" onchange="$('#song-archive-label span').html(this.files[0].name)">
                    <span>(optional) Select updated song archive...</span>
                </label>
            </td>
            <td class="text-right">
                <button class="btn btn-primary" type="submit">Update</button>
                <a class="btn btn-default" href="{{route('browse.detail',['key' => $id]) }}">Back</a>
            </td>
        </tr>
    </table>
</form>