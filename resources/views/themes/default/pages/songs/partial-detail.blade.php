@component('themes.default.components.song-detail',['uploaderId' => $song['uploaderId']])
    @slot('id', $song['id'])
    @slot('key', $song['key'])
    @slot('name', $song['name'])
    @slot('genre', array_key_exists ('genre', $song) ? $song['genre'] : 'No genre')
    @slot('uploader', $song['uploader'])
    @slot('uploaderId', $song['uploaderId'])
    @slot('authorName', $song['version'][$song['key']]['authorName'] )
    @slot('songName', $song['version'][$song['key']]['songName'])
    @slot('songSubName', $song['version'][$song['key']]['songSubName'])
    @slot('description', $song['description'])
    @slot('difficulties')
        @foreach($song['version'][$song['key']]['difficulties'] as $diff => $data)
            {{ $diff }}@if(!$loop->last), @endif
            @php
                $events = $data['stats']['events'] ? 'Yes':'No'
            @endphp
        @endforeach
    @endslot
    @slot('events', $events)
    @slot('downloadCount', $song['version'][$song['key']]['downloadCount'])
    @slot('playedCount', $song['version'][$song['key']]['playedCount'])
    @slot('upVotes', $song['version'][$song['key']]['upVotes'])
    @slot('downVotes', $song['version'][$song['key']]['downVotes'])
    @slot('version', $song['key'])
    @slot('createdAt', $song['version'][$song['key']]['createdAt'])
    @slot('linkUrl', $song['version'][$song['key']]['linkUrl'])
    @slot('downloadUrl', $song['version'][$song['key']]['downloadUrl'])
    @slot('coverUrl', $song['version'][$song['key']]['coverUrl'])
@endcomponent
