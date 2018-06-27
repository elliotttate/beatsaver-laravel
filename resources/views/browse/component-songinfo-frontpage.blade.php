<h2>{{ $name }}</h2>
<table id="song-{{ $id }}" class="table" style="table-layout:fixed;">
    <tr>
        <th rowspan="5" style="width: 15%;" class="text-center">
            <div>
                <img src="{{ asset("storage/songs/$cover.$coverMime") }}" alt="{{ $name }}" style="min-width: 10em; max-width: 10em;">
            </div>
            <br/>
            <div>
                <a class="btn btn-default" href="{{ route('browse.detail', ['key' => $downloadKey]) }}" role="button">Details</a>
            </div>
        </th>
        <th>
            <small>Uploaded by: {{ $uploader }}<br></small>
        </th>
    </tr>
    <tr>
        <td>Song: {{ $songName }} - {{ $songSubName }}</td>
    </tr>
    <tr>
        <td>Difficulties: {{ $difficulties }}</td>
    </tr>
    <tr>
        <td>
            Downloads: {{ $downloadCount }} || Finished: {{ $playedCount }} || <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> {{ $upvotes }} / <span
                    class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> {{ $downvotes }}
        </td>
    </tr>
    <tr>
        <td>
            <a class="btn btn-default" href="{{ route('download', ['key' => $downloadKey]) }}" role="button">Download File</a>
        </td>
    </tr>
</table>
