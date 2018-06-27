<h2>{{ $name }}</h2>
<table id="song-{{ $id }}" class="table" style="table-layout:fixed;">
    <tr>
        <th rowspan="6" style="width: 15%;" class="text-center">
            <div>
                <img src="{{ asset("storage/songs/$cover.$coverMime") }}" alt="{{ $name }}" style="min-width: 10em; max-width: 10em;">
            </div>
            <br/>
            <div>
                <a class="btn btn-default" href="{{ route('download', ['key' => $downloadKey]) }}" role="button">Download File</a>
            </div>
            <br/>

            <div>
                <a class="btn btn-default" href="{{ URL::previous('home') }}">Back</a>
            </div>
        </th>
        <th colspan="2">
            <small>Uploaded by: {{ $uploader }}<br></small>
        </th>
    </tr>
    <tr>
        <td colspan="2">Song: {{ $songName }} - {{ $songSubName }}</td>
    </tr>
    <tr>
        <td colspan="2">Difficulties: {{ $difficulties }}</td>
    </tr>
    <tr>
        <td>
            Downloads: {{ $downloadCount }} || Finished: {{ $playedCount }}
        </td>
    </tr>
    <tr>
        <td colspan="2">{{ $description }}</td>
    </tr>
    <tr>
        <td colspan="2">
            @auth
                <div>
                    <form action="{{ route('votes.submit',['key' => $downloadKey]) }}" method="post">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-default" name="type" value="up" {{ $alreadyVoted }}>
                            <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> Up {{ $upvotes }}
                        </button>
                        <button type="submit" class="btn btn-default" name="type" value="down" {{ $alreadyVoted }}>
                            <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> Down {{ $downvotes }}
                        </button>
                    </form>
                </div>
                <br/>
            @endauth
        </td>
    </tr>
</table>
