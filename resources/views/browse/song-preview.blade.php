@component('browse.component-song-preview')
    @slot('id', $song['id'])
    @slot('key', $song['key'])
    @slot('name', $song['name'])
    @slot('uploader', $song['uploader'])
    @slot('uploaderId', $song['uploaderId'])
    @slot('authorName', $song['authorName'])
    @slot('songName', $song['songName'])
    @slot('songSubName', $song['songSubName'])
    @slot('difficulties')
        @foreach($song['difficulties'] as $diff => $data)
            {{ $diff }}@if(!$loop->last), @endif
            @php
                $events = $data['stats']['events'] ? 'Yes':'No'
            @endphp
        @endforeach
    @endslot
    @slot('events', $events)
    @slot('downloadCount', $song['downloadCount'])
    @slot('playedCount', $song['playedCount'])
    @slot('upVotes', $song['upVotes'])
    @slot('downVotes', $song['downVotes'])
    @slot('version', $song['version'])
    @slot('createdAt', $song['createdAt'])
    @slot('linkUrl', $song['linkUrl'])
    @slot('downloadUrl', $song['downloadUrl'])
    @slot('coverUrl', $song['coverUrl'])

@endcomponent
