@component('browse.component-songinfo-detailpage')
    @slot('id')
        {{ $song['id'] }}
    @endslot
    @slot('key')
        {{ $song['key'] }}
    @endslot
    @slot('name')
        {{ $song['name'] }}
    @endslot
    @slot('uploader')
        {{ $song['uploader'] }}
    @endslot
    @slot('uploaderId')
        {{ $song['uploaderId'] }}
    @endslot
    @slot('authorName')
        {{ $song['authorName'] }}
    @endslot
    @slot('songName')
        {{ $song['songName'] }}
    @endslot
    @slot('songSubName')
        {{ $song['songSubName'] }}
    @endslot
    @slot('description')
        {{ $song['description'] }}
    @endslot
    @slot('difficulties')
        @foreach($song['difficulties'] as $diff => $data)
            {{ $diff }}@if(!$loop->last), @endif
            @php
                $events = $data['stats']['events'] ? 'Yes':'No'
            @endphp
        @endforeach
    @endslot
    @slot('events')
        {{ $events }}
    @endslot
    @slot('downloadCount')
        {{ $song['downloadCount'] }}
    @endslot
    @slot('playedCount')
        {{ $song['playedCount'] }}
    @endslot
    @slot('upVotes')
        {{ $song['upVotes'] }}
    @endslot
    @slot('downVotes')
        {{ $song['downVotes'] }}
    @endslot
    @slot('version')
        {{ $song['version'] }}
    @endslot
    @slot('createdAt')
        {{ $song['createdAt'] }}
    @endslot
    @slot('linkUrl')
        {{ $song['linkUrl'] }}
    @endslot
    @slot('downloadUrl')
        {{ $song['downloadUrl'] }}
    @endslot
    @slot('coverUrl')
        {{ $song['coverUrl'] }}
    @endslot
@endcomponent
