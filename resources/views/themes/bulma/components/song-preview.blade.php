<div style="margin-bottom: 15px;">
    <h2 style="border-bottom: 1px solid #dbdbdb;margin-bottom: 10px;">
        <a href="{{ $linkUrl }}" class="has-text-weight-semibold is-size-3">{{ $name }}</a>
    </h2>

    <div class="columns is-mobile">
        <div class="column" style="max-width: 200px;">
            <img src="{{ $coverUrl }}" alt="{{ $name }}" class="image" style="border-radius: 8px;margin-bottom: 8px;">

            <a class="button is-link has-text-weight-bold is-fullwidth" href="{{ $linkUrl }}" role="button">Details</a>
        </div>

        <div class="column">
            <table class="table is-fullwidth">
                <tr>
                    <th colspan="2">
                        <small>Uploaded by: <a href="{{ route('browse.user',['id' => $uploaderId]) }}">{{ $uploader }}</a> ({{ $createdAt }})</small>
                    </th>
                </tr>

                <tr>
                    <td>Song: {{ $songName }} - {{ $songSubName }}</td>
                    <td class="has-text-right">Version: {{$version}}</td>
                </tr>

                <tr>
                    <td>Author: {{ $authorName }}</td>
                    <td>Difficulties: {{ $difficulties }}</td>
                </tr>

                <tr>
                    <td>
                        Downloads: {{ $downloadCount }} || Finished: {{ $playedCount }} || <i class="fas fa-thumbs-up"></i> {{ $upVotes }} / <i
                            class="fas fa-thumbs-down"></i> {{ $downVotes }}
                    </td>
                    <td>
                        Lighting Events: {{ $events }}
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <a class="button" href="{{ $downloadUrl }}" role="button">Download File</a>
                        <a class="button" href="https://bsaber.com/songs/{{ $id }}" role="button">View on BeastSaber</a>
                        <button class="button for-playing" onclick="previewSong(this, '{{ $downloadUrl }}')" role="button">Preview</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
