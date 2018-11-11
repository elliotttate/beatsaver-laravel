<div>
    <h2 style="border-bottom: 1px solid #dbdbdb;margin-bottom: 10px;">
        <span class="has-text-weight-semibold is-size-3">{{ $name }}</span>
    </h2>

    <div class="columns is-mobile">
        <div class="column" style="max-width: 200px;">
            <img src="{{ $coverUrl }}" alt="{{ $name }}" class="image" style="border-radius: 8px;">

            <a class="button is-light is-fullwidth has-text-weight-bold" href="{{ $downloadUrl }}" style="margin-top: 8px;">Download File</a>
            <a class="button is-light is-fullwidth has-text-weight-bold" href="https://bsaber.com/songs/{{ $id }}" style="margin-top: 8px;">View on BeastSaber</a>
            <button
                class="button is-light is-fullwidth has-text-weight-bold for-playing"
                onclick="previewSong(this, '{{ $downloadUrl }}')"
                role="button"
                style="margin-top: 8px;"
            >
                Preview
            </button>
            <a class="button is-light is-fullwidth has-text-weight-bold" href="{{ route('browse.top.newest') }}" style="margin-top: 8px;">Back</a>
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
                                    <button type="submit" class="button is-light" name="type" value="up">
                                        <span class="icon"><i class="fas fa-thumbs-up"></i></span>
                                        <span>Up {{ $upVotes }}</span>
                                    </button>
                                    <button type="submit" class="button is-light" name="type" value="down">
                                        <span class="icon"><i class="fas fa-thumbs-down"></i></span>
                                        <span>Down {{ $downVotes }}</span>
                                    </button>
                                </form>
                            </div>
                            <br/>
                        @endauth
                    </td>
                    <td class="text-right">
                        @auth
                            @if(auth()->id() == $uploaderId)
                                <a class="button is-link" href="{{ route('browse.detail.edit',['id' => $id]) }}" role="button">Edit</a>
                                <a class="button is-danger" href="{{ route('browse.detail.delete',['id' => $id]) }}" role="button">Delete</a>
                            @endif
                        @endauth
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
