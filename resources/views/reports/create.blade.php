@extends('layouts.base')

@section('title', 'Submit a Report')

@section('content')

<div class="mx-auto max-w-2xl px-4 py-10 ">
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm md:p-8 mt-20">
        <a href="{{ url()->previous() }}"
           class="text-sm font-semibold text-[#056663] hover:underline">
            &larr; Back
        </a>


    <h1 class="mt-4 text-2xl font-bold text-gray-800">
        Report {{ $type === 'listing' ? 'Listing' : 'Seller' }}
    </h1>

    <p class="mt-2 text-sm text-gray-500">
        Tell us about the issue. Our admin team will review your report.
    </p>

    @if(session('success'))
        <div class="mt-5 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
            <ul class="list-inside list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reports.store') }}" method="POST" class="mt-6 space-y-5">
        @csrf

        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="target_id" value="{{ $target->id }}">

        <div>
            <label class="mb-2 block text-sm font-semibold text-gray-700">
                {{ $type === 'listing' ? 'Listing ID' : 'Seller ID' }}
            </label>
            <div class="rounded-lg bg-gray-50 px-4 py-3 text-gray-700">
                #{{ $target->id }}
                @if($type === 'listing' && isset($target->title))
                    — {{ $target->title }}
                @endif
            </div>
        </div>

        <div>
            <label for="reason" class="mb-2 block text-sm font-semibold text-gray-700">
                Reason for Report *
            </label>

            <select id="reason" name="reason" required
                    class="w-full rounded-lg border-gray-300 focus:border-[#056663] focus:ring-[#056663]">
                <option value="">Select a reason</option>
                <option value="spam" @selected(old('reason') === 'spam')>Spam</option>
                <option value="misleading_information" @selected(old('reason') === 'misleading_information')>Misleading Information</option>
                <option value="fraud" @selected(old('reason') === 'fraud')>Suspected Fraud or Scam</option>
                <option value="inappropriate" @selected(old('reason') === 'inappropriate')>Inappropriate Content</option>
                <option value="poor_service" @selected(old('reason') === 'poor_service')>Poor Service</option>
                <option value="other" @selected(old('reason') === 'other')>Other</option>
            </select>
        </div>

        <div>
            <label for="description" class="mb-2 block text-sm font-semibold text-gray-700">
                Describe the Issue *
            </label>

            <textarea id="description" name="description" rows="5"
                      minlength="10" maxlength="3000" required
                      placeholder="Explain what happened and why you are reporting this..."
                      class="w-full rounded-lg border-gray-300 focus:border-[#056663] focus:ring-[#056663]">{{ old('description') }}</textarea>

            <p class="mt-1 text-xs text-gray-500">
                Please provide at least 10 characters.
            </p>
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-[#056663] px-5 py-3 font-semibold text-white hover:bg-[#044f4d]">
            Submit Report
        </button>
    </form>
</div>


</div>
@endsection
