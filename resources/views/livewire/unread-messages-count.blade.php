<div wire:poll.3000ms>
    <a class="nav-link" href="{{  route('messages.inbox') }}">
        @lang('navigation.messages')
        <small class="d-none d-sm-none d-md-inline-block">
            {{ $count }} @lang('messages.newMessages')
        </small>
    </a>
</div>
