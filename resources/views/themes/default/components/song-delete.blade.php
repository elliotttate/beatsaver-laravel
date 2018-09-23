<div class="content">
    <div style="display: flex;flex-direction: column;align-items: center;">
        <article class="message is-danger" style="max-width: 600px;width: 100%;">
            <div class="message-header">
                Confirm Track Deletion
            </div>
            <div class="message-body">
                <p>
                    You are about to delete <strong>{{ $name }}</strong><br />
                    If your song has multiple versions, all of them will be deleted
                </p>

                 <form method="post" action="{{ route('browse.detail.delete.submit',['id' => $id]) }}">
                    {{ csrf_field() }}

                    <a class="button" href="{{route('browse.detail',['key' => $id]) }}">Back</a>
                    <button class="button is-danger" name="confirm" value="1" type="submit">DELETE</button>
                </form>
            </div>
        </article>
    </div>
</div>