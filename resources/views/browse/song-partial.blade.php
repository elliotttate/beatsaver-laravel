@component('browse.component-songinfo')
    @slot('id')
        {{ $song['id'] }}
    @endslot
    @slot('name')
        {{ $song['name'] }}
    @endslot
    @slot('uploader')
        {{ $song['uploader'] }}
    @endslot
    @slot('songName')
        {{ $song['songName'] }}
    @endslot
    @slot('songSubName')
        {{ $song['songSubName'] }}
    @endslot
    @slot('cover')
        {{ $song['cover'] }}
    @endslot
    @slot('coverMime')
        {{ $song['coverMime'] }}
    @endslot
    @slot('description')
        {{ $song['description'] }}
    @endslot
    @slot('difficulties')
        @foreach($song['difficulties'] as $diff)
            {{ $diff }}@if(!$loop->last), @endif
        @endforeach
    @endslot
    @slot('downloadCount')
        {{ $song['downloadCount'] }}
    @endslot
    @slot('playedCount')
        {{ $song['playedCount'] }}
    @endslot
    @slot('upvotes')
        {{ $song['upvotes'] }}
    @endslot
    @slot('downvotes')
        {{ $song['downvotes'] }}
    @endslot
    @slot('downloadKey')
        {{ $song['downloadKey'] }}
    @endslot
    @slot('version')
        {{ $song['version'] }}
    @endslot
@endcomponent
