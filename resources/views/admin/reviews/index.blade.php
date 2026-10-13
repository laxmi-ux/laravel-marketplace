
@extends('admin.layouts.app')

@section('title', 'Reviews Management')

@section('page-title', 'Reviews Management')

@section('content')
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Review & Rating Management
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Review buyer feedback and manage publication status.
            </p>
        </div>
    </x-slot>

    <div class="space-y-6">

        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50
                        px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50
                        px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Review statistics --}}
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach([
                'all' => ['All Reviews', 'text-gray-800'],
                'pending' => ['Pending', 'text-amber-600'],
                'approved' => ['Approved', 'text-green-600'],
                'rejected' => ['Rejected', 'text-red-600'],
            ] as $key => $item)
                <div class="rounded-2xl border border-[#DDE9E8]
                            bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">
                        {{ $item[0] }}
                    </p>
                    <p class="mt-2 text-2xl font-bold {{ $item[1] }}">
                        {{ $counts[$key] }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Status filters --}}
        <div class="rounded-2xl border border-[#DDE9E8]
                    bg-white p-5 shadow-sm">
            <form method="GET"
                  action="{{ route('admin.reviews.index') }}"
                  class="flex flex-wrap items-center gap-3">

                <label for="status"
                       class="text-sm font-semibold text-gray-700">
                    Filter reviews:
                </label>

                <select name="status" id="status"
                        class="rounded-lg border-gray-300 text-sm
                               focus:border-[#056663] focus:ring-[#056663]">
                    <option value="">All Reviews</option>
                    <option value="pending"
                        @selected(request('status') === 'pending')>
                        Pending
                    </option>
                    <option value="approved"
                        @selected(request('status') === 'approved')>
                        Approved
                    </option>
                    <option value="rejected"
                        @selected(request('status') === 'rejected')>
                        Rejected
                    </option>
                </select>

                <button type="submit"
                        class="rounded-lg bg-[#056663] px-4 py-2
                               text-sm font-semibold text-white
                               hover:bg-[#045552]">
                    Apply Filter
                </button>

                <a href="{{ route('admin.reviews.index') }}"
                   class="rounded-lg border border-gray-300 px-4 py-2
                          text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Reset
                </a>
            </form>
        </div>

        {{-- Reviews list --}}
        <div class="overflow-hidden rounded-2xl border border-[#DDE9E8]
                    bg-white shadow-sm">

            <div class="border-b border-[#DDE9E8] p-5">
                <h3 class="font-bold text-gray-800">Buyer Reviews</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Approve reviews to make them eligible for public display.
                </p>
            </div>

            @forelse($reviews as $review)
                <div class="border-b border-[#DDE9E8] p-5 last:border-b-0">
                    <div class="flex flex-col justify-between gap-4
                                lg:flex-row lg:items-start">

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-3">
                                <h4 class="font-semibold text-gray-900">
                                    {{ $review->listing?->title ?? 'Listing unavailable' }}
                                </h4>

                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-100 text-amber-700',
                                        'approved' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                    ];
                                    $reviewStatus = strtolower($review->status ?? 'pending');
                                @endphp

                                <span class="rounded-full px-3 py-1
                                             text-xs font-semibold
                                             {{ $statusClasses[$reviewStatus] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($reviewStatus) }}
                                </span>
                            </div>

                            <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2
                                        text-sm text-gray-500">
                                <span>
                                    Buyer:
                                    <strong class="text-gray-700">
                                        {{ $review->buyer?->name ?? 'N/A' }}
                                    </strong>
                                </span>

                                <span>
                                    Seller:
                                    <strong class="text-gray-700">
                                        {{ $review->seller?->business_name
                                            ?? $review->seller?->bussiness_name
                                            ?? $review->seller?->company_name
                                            ?? 'N/A' }}
                                    </strong>
                                </span>

                                <span>
                                    Order:
                                    <strong class="text-gray-700">
                                        {{ $review->order?->order_number
                                            ?? $review->order_id
                                            ?? 'N/A' }}
                                    </strong>
                                </span>
                            </div>

                            <div class="mt-4 flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $review->rating
                                        ? 'text-amber-400'
                                        : 'text-gray-300' }} text-xl">
                                        ★
                                    </span>
                                @endfor

                                <span class="ml-2 text-sm font-semibold
                                             text-gray-700">
                                    {{ $review->rating }}/5
                                </span>
                            </div>

                            <p class="mt-3 whitespace-pre-line text-sm
                                      leading-6 text-gray-700">
                                {{ $review->review }}
                            </p>

                            <p class="mt-3 text-xs text-gray-400">
                                Submitted
                                {{ $review->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                            </p>
                        </div>

                        @if($reviewStatus === 'pending')
                            <div class="flex shrink-0 flex-wrap gap-2">
                                <form method="POST"
                                      action="{{ route('admin.reviews.updateStatus', $review->id) }}"
                                      onsubmit="return confirm('Approve this review?')">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="status"
                                           value="approved">

                                    <button type="submit"
                                            class="rounded-lg bg-[#056663] px-4 py-2
                                                   text-sm font-semibold text-white
                                                   hover:bg-[#045552]">
                                        Approve
                                    </button>
                                </form>

                                <form method="POST"
                                      action="{{ route('admin.reviews.updateStatus', $review->id) }}"
                                      onsubmit="return confirm('Reject this review?')">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="status"
                                           value="rejected">

                                    <button type="submit"
                                            class="rounded-lg border border-red-200
                                                   bg-white px-4 py-2 text-sm
                                                   font-semibold text-red-600
                                                   hover:bg-red-50">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        @endif

                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <h3 class="font-semibold text-gray-800">
                        No reviews found
                    </h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Reviews will appear here when buyers submit them.
                    </p>
                </div>
            @endforelse

            @if($reviews->hasPages())
                <div class="border-t border-[#DDE9E8] p-5">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>

  @endsection