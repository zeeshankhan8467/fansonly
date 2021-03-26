<div>
    <a class="nav-link" href="{{ route('notifications.index') }}">
        @lang('navigation.myNotifications') 
        <small>{{ auth()->user()->unreadNotifications()->count() }} new</small>
    </a>
</div>
