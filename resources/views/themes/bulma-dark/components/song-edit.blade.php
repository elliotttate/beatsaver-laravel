<article class="message is-danger">
    <div class="message-body">
        <strong>
            <div>Selecting a new song archive will create a version of the song!</div>
            <div>The new version will have its own votes, download count and scoreboard.</div>
        </strong>
    </div>
</article>

<form id="update-form" method="post" enctype="multipart/form-data" action="{{ route('browse.detail.edit.submit',['id' => $id]) }}">
    <h2>
        <input type="text" name="name" value="{{ $name }}" id="inputTrackName" class="input" placeholder="Track Name e.g. My awesome track!" maxlength="160" required autofocus/>
    </h2>

    {{ csrf_field() }}

    <div class="columns is-mobile" style="margin-top: 10px;">
        <div class="column" style="max-width: 200px;">
            <img src="{{ $coverUrl }}" alt="{{ $name }}" class="image" style="border-radius: 8px;">

            <a class="button is-light is-fullwidth has-text-weight-bold" href="{{ $downloadUrl }}" style="margin-top: 8px;">Download File</a>
        </div>

        <div class="column">
            <table class="table is-fullwidth">
                <tr>
                    <th colspan="2">
                        <small>Uploaded by: <a href="{{ route('browse.user',['id' => $uploaderId]) }}">{{ $uploader }}</a> ({{ $createdAt }})</small>
                    </th>
                </tr>

                <tr>
                    <td>Song: {{ $songName }} - {{ $songSubName }}</td>
                    <td class="has-text-right">Version: {{$version}}</td>
                </tr>

                <tr>
                    <td>Author: {{ $authorName }}</td>
                    <td>Difficulties: {{ $difficulties }}</td>
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
                    <td colspan="2">
                        <textarea name="description" rows="5" class="textarea">{{$description}}</textarea>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label id="song-archive-label" class="button is-danger" for="song-archive">
                            <input id="song-archive" type="file" style="display: none" name="fileupload" accept=".zip, application/zip" onchange="$('#song-archive-label span').html(this.files[0].name)">
                            <span>(optional) Select updated song archive...</span>
                        </label>
                    </td>
                    <td class="text-right">
                        <a class="button is-light" href="{{route('browse.detail',['key' => $id]) }}">Back</a>
                        <button class="button is-link" type="submit">Update</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>