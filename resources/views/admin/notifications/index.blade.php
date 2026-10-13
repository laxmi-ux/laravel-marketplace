
@extends('admin.layouts.app')

@section('title', 'Notifications')
@section('page_title', 'Notifications')

@section('content')

<div class="mx-auto max-w-6xl space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
            <p class="mt-1 text-sm text-gray-500">
                Manage marketplace payments, orders, transactions and other activity.
            </p>
            <p class="mt-2 text-sm text-gray-600">
                Unread notifications:
                <span class="font-bold text-[#056663]">{{ $unreadCount }}</span>
            </p>
        </div>

        @if($unreadCount > 0)
            <form method="POST" action="{{ route('admin.notifications.readAll') }}">
                @csrf
                @method('PATCH')

                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-[#056663] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#045653]">
                    <span class="material-symbols-outlined text-[18px]">done_all</span>
                    Mark all as read
                </button>
            </form>
        @endif
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Notifications --}}
    <div class="overflow-hidden rounded-2xl border border-[#DDEBE8] bg-white">

        @forelse($notifications as $notification)
            @php
                $data = $notification->data ?? [];
                $isUnread = is_null($notification->read_at);
                $type = strtolower($data['type'] ?? 'general');

                $icon = match ($type) {
                    'payment' => 'payments',
                    'requirement' => 'assignment',
                    'rfq' => 'request_quote',
                    'quotation' => 'receipt_long',
                    'enquiry' => 'chat',
                    'listing' => 'inventory_2',
                    'seller_verification' => 'verified',
                    'seller_featured' => 'star',
                    'account' => 'person',
                    'order' => 'shopping_cart',
                    'transaction' => 'handshake',
                    'review' => 'reviews',
                    default => 'notifications',
                };
            @endphp

            <div class="border-b border-[#EDF3F1] last:border-b-0 {{ $isUnread ? 'bg-[#F1F8F7]' : 'bg-white' }}">
                <div class="flex items-start gap-4 px-5 py-5 sm:px-6">

                    {{-- Icon --}}
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $isUnread ? 'bg-[#D9EFEC] text-[#056663]' : 'bg-gray-100 text-gray-500' }}">
                        <span class="material-symbols-outlined">{{ $icon }}</span>
                    </div>

                    {{-- Content --}}
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <h3 class="text-sm {{ $isUnread ? 'font-bold text-gray-900' : 'font-semibold text-gray-700' }}">
                                    {{ $data['title'] ?? 'Notification' }}
                                </h3>

                                <p class="mt-1 text-sm leading-6 text-gray-500">
                                    {{ $data['message'] ?? 'You have a new marketplace notification.' }}
                                </p>
                            </div>

                            @if($isUnread)
                                <span class="inline-flex w-fit shrink-0 items-center rounded-full bg-[#D9EFEC] px-2.5 py-1 text-[10px] font-bold text-[#056663]">
                                    UNREAD
                                </span>
                            @endif
                        </div>

                        <div class="mt-3 flex flex-wrap items-center gap-3">
                            <span class="text-xs text-gray-400">
                                {{ $notification->created_at?->diffForHumans() }}
                            </span>

                            <a href="{{ route('admin.notifications.read', $notification->id) }}"
                               class="text-xs font-semibold text-[#056663] hover:underline">
                                {{ $isUnread ? 'Open notification' : 'View details' }}
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.notifications.destroy', $notification->id) }}"
                                  onsubmit="return confirm('Delete this notification?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="text-xs font-semibold text-red-600 hover:underline">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        @empty
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[#F1F8F7]">
                    <span class="material-symbols-outlined text-3xl text-[#056663]">
                        notifications_none
                    </span>
                </div>

                <h3 class="mt-4 text-base font-semibold text-gray-800">
                    No notifications yet
                </h3>

                <p class="mt-1 text-sm text-gray-400">
                    Payment and marketplace activity notifications will appear here.
                </p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
        <div>
            {{ $notifications->links() }}
        </div>
    @endif

</div>

@endsection
