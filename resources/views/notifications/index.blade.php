@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container-app section-gap">

    <div class="page-header" style="margin-bottom:24px;">
        <h1 class="page-header__title">Notifications</h1>
    </div>

    @if($notifications->isEmpty())
        <div class="empty-state">
            <i data-lucide="bell-off" class="empty-state__icon"></i>
            <p class="empty-state__text">You have no notifications yet.</p>
        </div>
    @else
        <div class="notifications-list">
            @foreach($notifications as $notification)
                <div class="notification-item {{ $notification->is_read ? '' : 'notification-item--unread' }}">
                    <div class="notification-item__dot"></div>
                    <div class="notification-item__body">
                        @if($notification->link)
                            <a href="{{ $notification->link }}" class="notification-item__message">
                                {{ $notification->message }}
                            </a>
                        @else
                            <span class="notification-item__message">{{ $notification->message }}</span>
                        @endif
                        <span class="notification-item__time">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination-wrap" style="margin-top:24px;">
            {{ $notifications->links() }}
        </div>
    @endif

</div>
@endsection
