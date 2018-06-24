<h2><a href="{{ route('browse.detail',['id' => $downloadId]) }}">{{ $name }}</a></h2>
<table class="table" style="table-layout:fixed;">
    <tr>
        <th rowspan="5" style="width: 15%;"><a href="{{ route('browse.detail',['id' => $downloadId]) }}"><img src="img/{{ $cover }}.{{ $coverMime }}" alt="{{ $name }}"
                                                                                                      style="min-width: 10em; max-width: 10em;"></a></th>
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
        <td>{{ $description }}</td>
    </tr>
    <tr>
        <td>Downloads: {{ $downloadCount }} || Finished: {{ $playedCount }}
            <br>
            @auth
                <a href="{{ route('votes.down', ['id' => $downloadId]) }}" class="pull-right">
                    <button type="button" class="btn btn-default">
                        <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> Down {{ $downvotes }}
                    </button>
                </a>
                <a href="{{ route('votes.up', ['id' => $downloadId]) }}" class="pull-right">
                    <button type="button" class="btn btn-default">
                        <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> Up {{ $upvotes }}
                    </button>
                </a>
                <br><br>
            @endauth
            <p><a class="btn btn-default" href="{{ route('download', ['id' => $downloadId]) }}" role="button">Download File</a></p>
        </td>
    </tr>
</table>
