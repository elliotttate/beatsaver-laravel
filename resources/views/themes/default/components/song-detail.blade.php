<h2>{{ $name }}</h2>
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
            <br/>
            <div>
                <a class="btn btn-default" href="https://bsaber.com/songs/{{ $id }}" role="button">view at bsaber.com</a>
            </div>
            <br/>
            <div>
                <a class="btn btn-default" href="{{ route('browse.top.newest') }}">Back</a>
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
            Genre: {{ $genre }}
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
        <td colspan="2">{!! nl2br(e($description)) !!}</td>
    </tr>
    <tr>
        <td>
            @auth
                <div>
                    <form action="{{ route('votes.submit',['key' => $key]) }}" method="post">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-default" name="type" value="up">
                            <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> Up {{ $upVotes }}
                        </button>
                        <button type="submit" class="btn btn-default" name="type" value="down">
                            <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> Down {{ $downVotes }}
                        </button>
                    </form>
                </div>
                <br/>
            @endauth
        </td>
        <td class="text-right">
            @auth
                @if(auth()->id() == $uploaderId)
                    <a class="btn btn-default btn-primary" href="{{ route('browse.detail.edit',['id' => $id]) }}" role="button">Edit</a>
                    <a class="btn btn-default btn-danger" href="{{ route('browse.detail.delete',['id' => $id]) }}" role="button">Delete</a>
                @endif
            @endauth
        </td>
    </tr>
</table>
