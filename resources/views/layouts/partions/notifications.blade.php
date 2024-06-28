<li class="nav-item dropdown">

    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
        <i class="bi bi-bell"></i>
        @if (auth()->user()->unreadNotifications->count())
            <span class="badge bg-primary badge-number">{{ auth()->user()->unreadNotifications->count() }}</span>
        @endif
    </a><!-- End Notification Icon -->

    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
        <li class="dropdown-header">
            You have {{ auth()->user()->unreadNotifications->count() }} new notifications
            <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
        </li>

        @php
            $notifications = auth()->user()->notifications->take(4);
        @endphp

        @foreach ($notifications as $notif)
            <li>
                <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
                <i
                    class="{{ $notif->data['status'] == 'success' ? 'bi bi-check-circle text-success' : 'bi bi-x-circle text-danger' }}"></i>
                <div onclick="window.location.href = 'notifications/{{ $notif->id }}'">
                    <h4>{{ $notif->data['title'] }}
                        @if ($notif->read_at == null)
                            <span class="badge rounded-pill bg-primary ">unread</span>
                        @endif
                    </h4>
                    <p>{{ $notif->data['message'] }}</p>
                    <p>{{ $notif->created_at->diffForhumans() }}</p>
                </div>
            </li>

            <li>
                <hr class="dropdown-divider">
            </li>
        @endforeach

        <li class="dropdown-footer">
            <a
                href="{{ auth()->user()->role == 'seller' ? route('seller.notifications.index') : route('admin.notifications.index') }}">Show
                all notifications</a>
        </li>

    </ul><!-- End Notification Dropdown Items -->

</li><!-- End Notification Nav -->
