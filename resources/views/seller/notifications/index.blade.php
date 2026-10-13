<x-seller-layout>

    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-[#123B38]">
                Notifications
            </h1>

            <p class="text-sm text-[#718A86]">
                Stay updated with your marketplace activities.
            </p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8">

        <div class="mx-auto max-w-5xl space-y-6">

            {{-- Header --}}
            <div class="flex items-center justify-between">

                <div>
                    <h2 class="text-lg font-semibold text-[#123B38]">
                        All Notifications
                    </h2>

                    <p class="text-sm text-[#718A86]">
                        {{ $unreadCount }} unread notification(s)
                    </p>
                </div>

                @if($unreadCount > 0)

                    <form
                        method="POST"
                        action="{{ route('seller.notifications.readAll') }}"
                    >
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="rounded-lg border border-[#CFE1DE]
                                   bg-white px-4 py-2
                                   text-sm font-medium
                                   text-[#056663]
                                   transition hover:bg-[#EAF5F3]"
                        >
                            Mark all as read
                        </button>

                    </form>

                @endif

            </div>


            {{-- Notifications --}}
            <div
                class="overflow-hidden rounded-xl
                       border border-[#D3E3E0]
                       bg-white shadow-sm"
            >

                @forelse($notifications as $notification)

                    @php
                        $data = $notification->data;

                        $type = $data['type'] ?? 'system';

                        $icons = [
                            'requirement' => 'description',
                            'enquiry' => 'chat',
                            'rfq' => 'request_quote',
                            'quotation' => 'receipt_long',
                            'listing' => 'inventory_2',
                            'bid' => 'gavel',
                            'order' => 'shopping_cart',
                            'review' => 'star',
                            'account' => 'person',
                            'system' => 'notifications',
                        ];

                        $icon = $icons[$type] ?? 'notifications';
                    @endphp

                    <div
                        class="flex items-start gap-4
                               border-b border-[#E6EFED]
                               p-5 last:border-b-0
                               {{ $notification->read_at
                                    ? 'bg-white'
                                    : 'bg-[#F2FAF8]' }}"
                    >

                        {{-- Icon --}}
                        <div
                            class="flex h-11 w-11 shrink-0
                                   items-center justify-center
                                   rounded-xl
                                   bg-[#EAF5F3]
                                   text-[#056663]"
                        >

                            <span class="material-symbols-outlined">
                                {{ $icon }}
                            </span>

                        </div>


                        {{-- Content --}}
                        <div class="min-w-0 flex-1">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <h3
                                        class="text-sm font-semibold
                                               text-[#123B38]"
                                    >
                                        {{ $data['title'] ?? 'Notification' }}
                                    </h3>

                                    <p
                                        class="mt-1 text-sm
                                               text-[#5F7773]"
                                    >
                                        {{ $data['message'] ?? '' }}
                                    </p>

                                    <p
                                        class="mt-2 text-xs
                                               text-[#8A9C99]"
                                    >
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>

                                </div>

                                @if(!$notification->read_at)

                                    <span
                                        class="mt-1 h-2.5 w-2.5
                                               shrink-0 rounded-full
                                               bg-[#056663]"
                                        title="Unread"
                                    ></span>

                                @endif

                            </div>


                            {{-- Actions --}}
                            <div class="mt-3 flex items-center gap-3">

                                @if(!$notification->read_at)

                                    <a
                                        href="{{ route(
                                            'seller.notifications.read',
                                            $notification->id
                                        ) }}"
                                        class="text-sm font-medium
                                               text-[#056663]
                                               hover:underline"
                                    >
                                        View
                                    </a>

                                @elseif(!empty($data['url']))

                                    <a
                                        href="{{ $data['url'] }}"
                                        class="text-sm font-medium
                                               text-[#056663]
                                               hover:underline"
                                    >
                                        View
                                    </a>

                                @endif


                                <form
                                    method="POST"
                                    action="{{ route(
                                        'seller.notifications.destroy',
                                        $notification->id
                                    ) }}"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="text-sm text-red-500
                                               hover:text-red-600"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-16 text-center">

                        <span
                            class="material-symbols-outlined
                                   text-5xl text-[#B5C9C5]"
                        >
                            notifications_none
                        </span>

                        <h3
                            class="mt-3 text-base font-semibold
                                   text-[#123B38]"
                        >
                            No notifications
                        </h3>

                        <p
                            class="mt-1 text-sm text-[#718A86]"
                        >
                            You're all caught up.
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

    </div>

</x-seller-layout>