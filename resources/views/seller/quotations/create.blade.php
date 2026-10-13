<x-seller-layout>

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <x-slot name="header">

        <div>

            <h2 class="text-xl font-bold text-[#123B38]">
                Create Quotation
            </h2>

            <p class="text-sm text-[#4F6663]">
                Create and send a quotation to the buyer.
            </p>

        </div>

    </x-slot>


    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}

    <div class="min-h-screen bg-[#F5F9F8]">

        <div class="w-full px-4 py-6 sm:px-6 lg:px-8">

            <div class="mx-auto max-w-6xl">


                {{-- =================================================
                     PAGE HEADER
                ================================================== --}}

                <div
                    class="mb-6 flex flex-col gap-4
                           sm:flex-row sm:items-center
                           sm:justify-between">

                    <div>

                        <h1 class="text-2xl font-bold text-gray-900">
                            Create Quotation
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Send a quotation for this buyer enquiry.
                        </p>

                    </div>


                    <a
                        href="{{ route('seller.enquiries.index') }}"
                        class="inline-flex w-fit items-center rounded-lg
                               border border-gray-200 bg-white px-4 py-2
                               text-sm font-medium text-gray-700
                               hover:border-[#00665F]
                               hover:text-[#00665F] transition">

                        ← Back

                    </a>

                </div>


                {{-- =================================================
                     SUCCESS MESSAGE
                ================================================== --}}

                @if(session('success'))

                    <div
                        class="mb-5 rounded-lg border border-green-200
                               bg-green-50 px-4 py-3 text-sm
                               text-green-700">

                        {{ session('success') }}

                    </div>

                @endif


                {{-- =================================================
                     ERROR MESSAGE
                ================================================== --}}

                @if($errors->any())

                    <div
                        class="mb-5 rounded-lg border border-red-200
                               bg-red-50 px-4 py-3">

                        <ul
                            class="list-disc space-y-1 pl-5
                                   text-sm text-red-600">

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                {{-- =================================================
                     QUOTATION CARD
                ================================================== --}}

                <div
                    class="overflow-hidden rounded-xl border
                           border-gray-200 bg-white shadow-sm">


                    {{-- =================================================
                         BUYER ENQUIRY
                    ================================================== --}}

                    <div
                        class="border-b border-gray-100
                               bg-gray-50 px-5 py-5 sm:px-6">

                        <h2 class="text-lg font-semibold text-gray-900">
                            Buyer Enquiry
                        </h2>


                        <div
                            class="mt-4 grid grid-cols-1 gap-5
                                   sm:grid-cols-2 lg:grid-cols-4">


                            {{-- Buyer --}}
                            <div>

                                <p class="text-xs text-gray-400">
                                    Buyer
                                </p>

                                <p
                                    class="mt-1 text-sm font-medium
                                           text-gray-900">

                                    {{ $requirement->user->name ?? $requirement->name ?? 'N/A' }}

                                </p>

                            </div>


                            {{-- Listing --}}
                            <div>

                                <p class="text-xs text-gray-400">
                                    Listing
                                </p>

                                <p
                                    class="mt-1 text-sm font-medium
                                           text-gray-900">

                                    {{ $requirement->listing->title ?? 'N/A' }}

                                </p>

                            </div>


                            {{-- Quantity --}}
                            <div>

                                <p class="text-xs text-gray-400">
                                    Requested Quantity
                                </p>

                                <p
                                    class="mt-1 text-sm font-medium
                                           text-gray-900">

                                    {{ $requirement->quantity ?? 'N/A' }}
                                    {{ $requirement->unit ?? '' }}

                                </p>

                            </div>


                            {{-- Requirement Type --}}
                            <div>

                                <p class="text-xs text-gray-400">
                                    Requirement Type
                                </p>

                                <p
                                    class="mt-1 text-sm font-medium
                                           capitalize text-gray-900">

                                    {{ $requirement->requirement_type
                                        ? str_replace('_', ' ', $requirement->requirement_type)
                                        : 'N/A' }}

                                </p>

                            </div>

                        </div>


                        {{-- Buyer Message --}}
                        @if($requirement->message)

                            <div class="mt-5">

                                <p class="text-xs text-gray-400">
                                    Buyer Message
                                </p>

                                <p
                                    class="mt-1 rounded-lg bg-white
                                           p-3 text-sm text-gray-600">

                                    {{ $requirement->message }}

                                </p>

                            </div>

                        @endif

                    </div>


                    {{-- =================================================
                         QUOTATION FORM
                    ================================================== --}}

                    <form
                        action="{{ route('seller.quotations.store', $requirement->id) }}"
                        method="POST">

                        @csrf


                        <div class="space-y-7 px-5 py-6 sm:px-6">


                            {{-- =================================================
                                 PRODUCT DETAILS
                            ================================================== --}}

                            <div>

                                <h3
                                    class="mb-4 text-sm font-semibold
                                           text-gray-900">

                                    Product Details

                                </h3>


                                <div
                                    class="grid grid-cols-1 gap-5
                                           sm:grid-cols-2">


                                    {{-- Quantity --}}
                                    <div>

                                        <label
                                            for="quantity"
                                            class="mb-2 block text-sm
                                                   font-medium text-gray-700">

                                            Quantity
                                            <span class="text-red-500">*</span>

                                        </label>


                                        <input
                                            type="number"
                                            id="quantity"
                                            name="quantity"
                                            value="{{ old('quantity', $requirement->quantity) }}"
                                            min="0.01"
                                            step="0.01"
                                            required
                                            class="w-full rounded-lg border
                                                   border-gray-200 px-4 py-2.5
                                                   text-sm focus:border-[#00665F]
                                                   focus:outline-none
                                                   focus:ring-1
                                                   focus:ring-[#00665F]">


                                        @error('quantity')

                                            <p class="mt-1 text-xs text-red-500">
                                                {{ $message }}
                                            </p>

                                        @enderror

                                    </div>


                                    {{-- Unit --}}
                                    <div>

                                        <label
                                            for="unit"
                                            class="mb-2 block text-sm
                                                   font-medium text-gray-700">

                                            Unit
                                            <span class="text-red-500">*</span>

                                        </label>


                                        <input
                                            type="text"
                                            id="unit"
                                            name="unit"
                                            value="{{ old('unit', $requirement->unit) }}"
                                            placeholder="e.g. Kg, Ton, Piece"
                                            required
                                            class="w-full rounded-lg border
                                                   border-gray-200 px-4 py-2.5
                                                   text-sm focus:border-[#00665F]
                                                   focus:outline-none
                                                   focus:ring-1
                                                   focus:ring-[#00665F]">


                                        @error('unit')

                                            <p class="mt-1 text-xs text-red-500">
                                                {{ $message }}
                                            </p>

                                        @enderror

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                 PRICING
                            ================================================== --}}

                            <div>

                                <h3
                                    class="mb-4 text-sm font-semibold
                                           text-gray-900">

                                    Pricing

                                </h3>


                                <div
                                    class="grid grid-cols-1 gap-5
                                           sm:grid-cols-3">


                                    {{-- Unit Price --}}
                                    <div>

                                        <label
                                            for="unit_price"
                                            class="mb-1.5 block text-sm
                                                   font-medium text-gray-700">

                                            Unit Price
                                            <span class="text-red-500">*</span>

                                        </label>


                                        <div class="relative">

                                            <span
                                                class="pointer-events-none
                                                       absolute left-3 top-1/2
                                                       z-10 -translate-y-1/2
                                                       text-sm text-gray-400">

                                                ₹

                                            </span>


                                            <input
                                                type="number"
                                                name="unit_price"
                                                id="unit_price"
                                                value="{{ old('unit_price') }}"
                                                min="0"
                                                step="0.01"
                                                required
                                                placeholder="0.00"
                                                class="w-full rounded-lg
                                                       border border-gray-200
                                                       py-2.5 pl-10 pr-4 text-sm
                                                       focus:border-[#00665F]
                                                       focus:outline-none
                                                       focus:ring-1
                                                       focus:ring-[#00665F]">

                                        </div>


                                        @error('unit_price')

                                            <p class="mt-1 text-xs text-red-500">
                                                {{ $message }}
                                            </p>

                                        @enderror

                                    </div>


                                    {{-- Delivery Charges --}}
                                    <div>

                                        <label
                                            for="delivery_charges"
                                            class="mb-1.5 block text-sm
                                                   font-medium text-gray-700">

                                            Delivery Charges

                                        </label>


                                        <div class="relative">

                                            <span
                                                class="pointer-events-none
                                                       absolute left-3 top-1/2
                                                       z-10 -translate-y-1/2
                                                       text-sm text-gray-400">

                                                ₹

                                            </span>


                                            <input
                                                type="number"
                                                name="delivery_charges"
                                                id="delivery_charges"
                                                value="{{ old('delivery_charges', 0) }}"
                                                min="0"
                                                step="0.01"
                                                placeholder="0.00"
                                                class="w-full rounded-lg
                                                       border border-gray-200
                                                       py-2.5 pl-10 pr-4 text-sm
                                                       focus:border-[#00665F]
                                                       focus:outline-none
                                                       focus:ring-1
                                                       focus:ring-[#00665F]">

                                        </div>


                                        @error('delivery_charges')

                                            <p class="mt-1 text-xs text-red-500">
                                                {{ $message }}
                                            </p>

                                        @enderror

                                    </div>


                                    {{-- Tax --}}
                                    <div>

                                        <label
                                            for="tax"
                                            class="mb-1.5 block text-sm
                                                   font-medium text-gray-700">

                                            Tax

                                        </label>


                                        <div class="relative">

                                            <span
                                                class="pointer-events-none
                                                       absolute left-3 top-1/2
                                                       z-10 -translate-y-1/2
                                                       text-sm text-gray-400">

                                                ₹

                                            </span>


                                            <input
                                                type="number"
                                                name="tax"
                                                id="tax"
                                                value="{{ old('tax', 0) }}"
                                                min="0"
                                                step="0.01"
                                                placeholder="0.00"
                                                class="w-full rounded-lg
                                                       border border-gray-200
                                                       py-2.5 pl-10 pr-4 text-sm
                                                       focus:border-[#00665F]
                                                       focus:outline-none
                                                       focus:ring-1
                                                       focus:ring-[#00665F]">

                                        </div>


                                        @error('tax')

                                            <p class="mt-1 text-xs text-red-500">
                                                {{ $message }}
                                            </p>

                                        @enderror

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                 DELIVERY INFORMATION
                            ================================================== --}}

                            <div>

                                <h3
                                    class="mb-4 text-sm font-semibold
                                           text-gray-900">

                                    Delivery Information

                                </h3>


                                <div
                                    class="grid grid-cols-1 gap-5
                                           sm:grid-cols-2">


                                    {{-- Valid Until --}}
                                    <div>

                                        <label
                                            for="valid_until"
                                            class="mb-2 block text-sm
                                                   font-medium text-gray-700">

                                            Quotation Valid Until
                                            <span class="text-red-500">*</span>

                                        </label>


                                        <input
                                            type="date"
                                            id="valid_until"
                                            name="valid_until"
                                            value="{{ old('valid_until') }}"
                                            min="{{ date('Y-m-d') }}"
                                            required
                                            class="w-full rounded-lg
                                                   border border-gray-200
                                                   px-4 py-2.5 text-sm
                                                   focus:border-[#00665F]
                                                   focus:outline-none
                                                   focus:ring-1
                                                   focus:ring-[#00665F]">


                                        @error('valid_until')

                                            <p class="mt-1 text-xs text-red-500">
                                                {{ $message }}
                                            </p>

                                        @enderror

                                    </div>


                                    {{-- Delivery Time --}}
                                    <div>

                                        <label
                                            for="delivery_time"
                                            class="mb-2 block text-sm
                                                   font-medium text-gray-700">

                                            Delivery Time

                                        </label>


                                        <input
                                            type="text"
                                            id="delivery_time"
                                            name="delivery_time"
                                            value="{{ old('delivery_time') }}"
                                            placeholder="e.g. 5-7 working days"
                                            class="w-full rounded-lg
                                                   border border-gray-200
                                                   px-4 py-2.5 text-sm
                                                   focus:border-[#00665F]
                                                   focus:outline-none
                                                   focus:ring-1
                                                   focus:ring-[#00665F]">


                                        @error('delivery_time')

                                            <p class="mt-1 text-xs text-red-500">
                                                {{ $message }}
                                            </p>

                                        @enderror

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                 NOTES
                            ================================================== --}}

                            <div>

                                <h3
                                    class="mb-4 text-sm font-semibold
                                           text-gray-900">

                                    Additional Notes

                                </h3>


                                <textarea
                                    id="notes"
                                    name="notes"
                                    rows="4"
                                    maxlength="3000"
                                    placeholder="Add payment terms, delivery conditions, product information, etc."
                                    class="w-full resize-none rounded-lg
                                           border border-gray-200 px-4 py-3
                                           text-sm focus:border-[#00665F]
                                           focus:outline-none
                                           focus:ring-1
                                           focus:ring-[#00665F]">{{ old('notes') }}</textarea>


                                @error('notes')

                                    <p class="mt-1 text-xs text-red-500">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- =================================================
                                 TOTAL
                            ================================================== --}}

                            <div class="border-t border-gray-100 pt-6">

                                <div class="ml-auto w-full sm:max-w-sm">

                                    <div
                                        class="space-y-3 rounded-lg
                                               bg-gray-50 p-5">


                                        {{-- Subtotal --}}
                                        <div
                                            class="flex justify-between
                                                   text-sm">

                                            <span class="text-gray-500">
                                                Subtotal
                                            </span>

                                            <span
                                                id="subtotalDisplay"
                                                class="font-medium
                                                       text-gray-900">

                                                ₹0.00

                                            </span>

                                        </div>


                                        {{-- Delivery --}}
                                        <div
                                            class="flex justify-between
                                                   text-sm">

                                            <span class="text-gray-500">
                                                Delivery Charges
                                            </span>

                                            <span
                                                id="deliveryDisplay"
                                                class="font-medium
                                                       text-gray-900">

                                                ₹0.00

                                            </span>

                                        </div>


                                        {{-- Tax --}}
                                        <div
                                            class="flex justify-between
                                                   text-sm">

                                            <span class="text-gray-500">
                                                Tax
                                            </span>

                                            <span
                                                id="taxDisplay"
                                                class="font-medium
                                                       text-gray-900">

                                                ₹0.00

                                            </span>

                                        </div>


                                        <div class="border-t border-gray-200">
                                        </div>


                                        {{-- Total --}}
                                        <div
                                            class="flex justify-between">

                                            <span
                                                class="font-semibold
                                                       text-gray-900">

                                                Total Amount

                                            </span>

                                            <span
                                                id="totalDisplay"
                                                class="text-xl font-bold
                                                       text-[#00665F]">

                                                ₹0.00

                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                 BUTTONS
                            ================================================== --}}

                            <div
                                class="flex flex-col-reverse gap-3
                                       border-t border-gray-100 pt-6
                                       sm:flex-row sm:justify-end">


                                <a
                                    href="{{ route('seller.enquiries.index') }}"
                                    class="inline-flex w-full items-center
                                           justify-center rounded-lg
                                           border border-gray-200 bg-white
                                           px-6 py-2.5 text-sm
                                           font-medium text-gray-700
                                           hover:bg-gray-50
                                           sm:w-auto">

                                    Cancel

                                </a>


                                <button
                                    type="submit"
                                    class="inline-flex w-full items-center
                                           justify-center rounded-lg
                                           bg-[#00665F] px-6 py-2.5
                                           text-sm font-semibold text-white
                                           hover:bg-[#00534E]
                                           sm:w-auto">

                                    Send Quotation

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         QUOTATION TOTAL CALCULATION
    ========================================================== --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const quantity =
                document.getElementById('quantity');

            const unitPrice =
                document.getElementById('unit_price');

            const delivery =
                document.getElementById('delivery_charges');

            const tax =
                document.getElementById('tax');


            const subtotalDisplay =
                document.getElementById('subtotalDisplay');

            const deliveryDisplay =
                document.getElementById('deliveryDisplay');

            const taxDisplay =
                document.getElementById('taxDisplay');

            const totalDisplay =
                document.getElementById('totalDisplay');


            if (
                !quantity ||
                !unitPrice ||
                !delivery ||
                !tax ||
                !subtotalDisplay ||
                !deliveryDisplay ||
                !taxDisplay ||
                !totalDisplay
            ) {
                return;
            }


            function getValue(input) {

                const value = parseFloat(input.value);

                return Number.isFinite(value)
                    ? value
                    : 0;

            }


            function formatCurrency(value) {

                return '₹' + value.toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

            }


            function calculateTotal() {

                const qty =
                    getValue(quantity);

                const price =
                    getValue(unitPrice);

                const deliveryAmount =
                    getValue(delivery);

                const taxAmount =
                    getValue(tax);


                const subtotal =
                    qty * price;


                const total =
                    subtotal +
                    deliveryAmount +
                    taxAmount;


                subtotalDisplay.textContent =
                    formatCurrency(subtotal);

                deliveryDisplay.textContent =
                    formatCurrency(deliveryAmount);

                taxDisplay.textContent =
                    formatCurrency(taxAmount);

                totalDisplay.textContent =
                    formatCurrency(total);

            }


            quantity.addEventListener(
                'input',
                calculateTotal
            );

            unitPrice.addEventListener(
                'input',
                calculateTotal
            );

            delivery.addEventListener(
                'input',
                calculateTotal
            );

            tax.addEventListener(
                'input',
                calculateTotal
            );


            calculateTotal();

        });

    </script>

</x-seller-layout>