
<x-buyer-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between ">
            <div class="flex items-center gap-2">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">
                    <span class="material-symbols-outlined">notifications</span>
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
                    <p class="text-sm text-gray-500">
                        Stay updated with your marketplace activities.
                    </p>
                </div>
            </div>

            @if($unreadCount > 0)
                <form action="{{ route('buyer.notifications.readAll') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-[#056663] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#045451]">
                        <span class="material-symbols-outlined text-[18px]">done_all</span>
                        Mark All as Read
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mx-5 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        {{-- Statistics --}}
        <div class="grid grid-cols-1 gap-4 m-5 md:grid-cols-3">

            {{-- Total Notifications --}}
            <div class="mt-9 rounded-2xl border border-[#DDE9E8] bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Notifications</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $notifications->total() }}
                        </p>
                        <p class="mt-1 text-xs text-gray-400">All marketplace updates</p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#E8F5F4] text-[#056663]">
                        <span class="material-symbols-outlined">notifications</span>
                    </div>
                </div>
            </div>

            {{-- Unread Notifications --}}
            <div class="mt-9 rounded-2xl border border-[#DDE9E8] bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Unread</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $unreadCount }}</p>
                        <p class="mt-1 text-xs text-gray-400">Require your attention</p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-500">
                        <span class="material-symbols-outlined">mark_email_unread</span>
                    </div>
                </div>
            </div>

            {{-- Notification Status --}}
            <div class="mt-9 rounded-2xl border border-[#DDE9E8] bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Notification Status</p>

                        @if($unreadCount > 0)
                            <p class="mt-2 text-lg font-bold text-orange-600">Action Required</p>
                            <p class="mt-1 text-xs text-gray-400">You have unread updates</p>
                        @else
                            <p class="mt-2 text-lg font-bold text-green-600">All Caught Up</p>
                            <p class="mt-1 text-xs text-gray-400">No unread notifications</p>
                        @endif
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl
                        {{ $unreadCount > 0 ? 'bg-orange-50 text-orange-500' : 'bg-green-50 text-green-500' }}">
                        <span class="material-symbols-outlined">
                            {{ $unreadCount > 0 ? 'priority_high' : 'check_circle' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notifications Container --}}
        <div class="m-7 overflow-hidden rounded-2xl border border-[#DDE9E8] bg-white shadow-sm">

            {{-- Header --}}
            <div class="flex flex-col gap-3 border-b border-[#DDE9E8] px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Recent Notifications</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Updates related to your enquiries, RFQs, quotations, bids, orders and reports.
                    </p>
                </div>

                <div class="flex items-center gap-2 rounded-lg bg-[#F7FAFA] px-3 py-2 text-xs font-medium text-gray-500">
                    <span class="h-2 w-2 rounded-full bg-[#056663]"></span>
                    {{ $unreadCount }} unread
                </div>
            </div>

            @if($notifications->count() > 0)

                <div class="divide-y divide-[#EDF2F2]">

                    @foreach($notifications as $notification)

                        @php
                            $type = $notification->data['type'] ?? 'system';
                            $title = $notification->data['title'] ?? 'Notification';
                            $message = $notification->data['message'] ?? '';
                            $url = $notification->data['url'] ?? null;

                            $icon = match($type) {
                                'quotation' => 'request_quote',
                                'rfq' => 'description',
                                'bid' => 'gavel',
                                'enquiry' => 'mail',
                                'order' => 'shopping_cart',
                                'listing' => 'inventory_2',
                                'report' => 'report',
                                default => 'notifications',
                            };

                            $iconBackground = match($type) {
                                'quotation' => 'bg-blue-50',
                                'rfq' => 'bg-purple-50',
                                'bid' => 'bg-orange-50',
                                'enquiry' => 'bg-green-50',
                                'order' => 'bg-indigo-50',
                                'listing' => 'bg-teal-50',
                                'report' => 'bg-red-50',
                                default => 'bg-gray-100',
                            };

                            $iconColor = match($type) {
                                'quotation' => 'text-blue-600',
                                'rfq' => 'text-purple-600',
                                'bid' => 'text-orange-600',
                                'enquiry' => 'text-green-600',
                                'order' => 'text-indigo-600',
                                'listing' => 'text-[#056663]',
                                'report' => 'text-red-600',
                                default => 'text-gray-600',
                            };

                            $typeLabel = match($type) {
                                'quotation' => 'Quotation',
                                'rfq' => 'RFQ',
                                'bid' => 'Bid',
                                'enquiry' => 'Enquiry',
                                'order' => 'Order',
                                'listing' => 'Listing',
                                'report' => 'Report',
                                default => 'System',
                            };
                        @endphp

                        {{-- Notification Item --}}
                        <div class="group px-5 py-5 transition
                            {{ $notification->read_at
                                ? 'bg-white hover:bg-gray-50'
                                : 'bg-[#F7FAFA] hover:bg-[#F0F8F7]' }}">

                            <div class="flex gap-4">

                                {{-- Icon --}}
                                <div class="flex-shrink-0">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $iconBackground }} {{ $iconColor }}">
                                        <span class="material-symbols-outlined">{{ $icon }}</span>
                                    </div>
                                </div>

                                {{-- Notification Details --}}
                                <div class="min-w-0 flex-1">

                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <h3 class="text-sm font-semibold text-gray-900">
                                                    {{ $title }}
                                                </h3>

                                                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-gray-500">
                                                    {{ $typeLabel }}
                                                </span>

                                                @if(!$notification->read_at)
                                                    <span class="flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-[10px] font-semibold text-red-600">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                                        Unread
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-600">
                                                {{ $message }}
                                            </p>
                                        </div>

                                        <span class="whitespace-nowrap text-xs text-gray-400">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="mt-4 flex flex-wrap items-center gap-2">

                                        {{-- View: opens the notification's destination --}}
                                        <a href="{{ route('buyer.notifications.read', $notification->id) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-[#056663] px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-[#045451]">
                                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                                            View
                                        </a>

                                        {{-- Mark as Read: use your existing read route --}}
                                        @if(!$notification->read_at)
                                            <a href="{{ route('buyer.notifications.read', $notification->id) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-[#DDE9E8] bg-white px-3.5 py-2 text-xs font-semibold text-gray-700 transition hover:border-[#056663] hover:bg-[#E8F5F4] hover:text-[#056663]">
                                                <span class="material-symbols-outlined text-[16px]">done</span>
                                                Mark as Read
                                            </a>
                                        @endif

                                        {{-- Delete --}}
                                        <form action="{{ route('buyer.notifications.destroy', $notification->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this notification?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-white px-3.5 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                                Delete
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($notifications->hasPages())
                    <div class="border-t border-[#DDE9E8] bg-[#FAFCFC] px-5 py-4">
                        {{ $notifications->links() }}
                    </div>
                @endif

            @else

                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center px-6 py-20 text-center">
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-[#E8F5F4] text-[#056663]">
                        <span class="material-symbols-outlined text-[40px]">notifications_none</span>
                    </div>

                    <h3 class="mt-5 text-lg font-bold text-gray-900">You're All Caught Up</h3>

                    <p class="mt-2 max-w-md text-sm leading-6 text-gray-500">
                        You don't have any notifications yet. New enquiries, RFQs, quotations,
                        bids, orders and report updates will appear here.
                    </p>

                    <a href="{{ route('buyer.dashboard') }}"
                        class="mt-6 inline-flex items-center gap-2 rounded-lg bg-[#056663] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#045451]">
                        <span class="material-symbols-outlined text-[18px]">dashboard</span>
                        Go to Dashboard
                    </a>
                </div>

            @endif
        </div>
    </div>

</x-buyer-layout>
