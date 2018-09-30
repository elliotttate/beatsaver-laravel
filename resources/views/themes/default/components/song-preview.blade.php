<a href="{{ $linkUrl }}"><h2>{{ $name }}</h2></a>
<table id="song-{{ $id }}" class="table" style="table-layout:fixed;">
    <tr>
        <th rowspan="6" style="width: 15%;" class="text-center">
            <div>
                <img src="{{ $coverUrl }}" alt="{{ $name }}" style="min-width: 10em; max-width: 10em;">
            </div>
            <br/>
            <div>
                <a class="btn btn-default btn-primary" href="{{ $linkUrl }}" role="button">Details</a>
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
            Downloads: {{ $downloadCount }} || Finished: {{ $playedCount }} || <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span> {{ $upVotes }} / <span
                    class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span> {{ $downVotes }}
        </td>
        <td>Lighting Events: {{ $events }}</td>
    </tr>
    <tr>
        <td colspan="2">
            <a class="btn btn-default" href="{{ $downloadUrl }}" role="button">Download File</a>
            <a class="btn btn-default" href="https://bsaber.com/songs/{{ $id }}" role="button">view at bsaber.com</a>
        </td>
    </tr>
</table>
