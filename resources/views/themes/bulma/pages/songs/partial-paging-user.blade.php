<div class="has-text-centered">
    @if($start >= 0 && ($start - $steps) >= 0)
        <a href="{{ route(Route::currentRouteName(),['start' => $start - $steps,'id' => $userId]) }}" class="pull-left">
            <button type="button" class="button is-primary has-text-weight-bold">
                <span class="glyphicon glyphicon-backwards" aria-hidden="true"></span> Previous Page ({{ (floor($start / $steps) ?: 1) }})
            </button>
        </a>
    @endif

    @if($start >= 0 && $songs && count($songs) == $steps )
        <a href="{{ route(Route::currentRouteName(),['start' => $start + $steps,'id' => $userId]) }}"
           class="pull-right">
            <button type="button" class="button is-primary has-text-weight-bold">
                Next Page ({{(floor($start / $steps)?: 1) +1 }}) <span class="glyphicon glyphicon-forward" aria-hidden="true"></span>
            </button>
        </a>
    @endif
</div>
