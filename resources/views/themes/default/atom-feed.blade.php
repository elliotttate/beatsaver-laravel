{!! '<'.'?'.'xml version="1.0" encoding="UTF-8" ?>' !!}
<feed xmlns="http://www.w3.org/2005/Atom">
    <title type="text">Beat Saver - {{ $title }}</title>
    <id>{{ Request::fullUrl() }}</id>
    <link rel="self" type="application/atom+xml" href="{{ Request::fullUrl() }}" />
    <icon>{{ URL::to('/favicon.ico') }}</icon>
@foreach ($songs as $song)
@php ($uploaderUrl = route('browse.user', ['id' => $song['uploaderId']]))
@php ($version = $song['version'][$song['key']])
@php ($updated = $version['createdAt']->toRfc3339String())
@if ($loop->first)
    <updated>{{ $updated }}</updated>
@endif
    <entry>
        <id>{{ $version['linkUrl'] }}</id>
        <title>{{ $song['name'] }}</title>
        <updated>{{ $updated }}</updated>
        <author>
            <name>{{ $song['uploader'] }}</name>
            <uri>{{ $uploaderUrl }}</uri>
        </author>
        <link rel="alternate" type="text/html" href="{{ $version['linkUrl'] }}"/>
        <link rel="enclosure" type='application/zip' href="{{ $version['downloadUrl'] }}"/>
        <summary type="html"><![CDATA[
            <img src="{{ $version['coverUrl'] }}" width="150px"/>
            <p>
                Song: {{ $version['songName'] }} - {{ $version['songSubName'] }}<br/>
                Author: {{ $version['authorName'] }}<br/>
                Uploader: <a href="{{ $uploaderUrl }}">{{ $song['uploader'] }}</a><br/>
                Difficulties: {{ implode(", ", array_keys($version['difficulties'])) }}<br/>
                Version: {{ $song['key'] }}<br/>
            </p>
        ]]></summary>
    </entry>
@endforeach
</feed>
