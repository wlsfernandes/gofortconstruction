@php
    $inputClasses = 'block h-12 w-full rounded-md border bg-white px-3 text-sm shadow-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500';
    $fieldBorder = fn (string $field): string => $errors->has($field) ? 'border-red-500' : 'border-gray-200';
@endphp

@if (session('contactSubmitted'))
    <div class="py-12 text-center" role="status">
        <div class="mx-auto mb-4 flex size-16 items-center justify-center rounded-full bg-green-100">
            <x-icon name="circle-check" class="size-8 text-green-600" />
        </div>
        <h3 class="mb-2 text-2xl font-bold text-gray-900">Thank You!</h3>
        <p class="mb-6 text-gray-600">We've received your request and will contact you within 24 hours.</p>
        <p class="text-sm text-gray-500">
            Need immediate assistance? Call us at
            <a href="tel:{{ config('site.phone_dial') }}" class="font-semibold text-orange-500">{{ config('site.phone_short') }}</a>
        </p>
    </div>
@else
    <form method="POST" action="{{ route('contact.store') }}" class="space-y-4" x-data="{ isSubmitting: false }" x-on:submit="isSubmitting = true">
        @csrf

        {{-- Anti-spam: a honeypot people never see, and the encrypted time the form was first shown. --}}
        <div class="absolute -left-[9999px] h-px w-px overflow-hidden" aria-hidden="true">
            <label for="{{ \App\Http\Requests\StoreContactSubmissionRequest::HONEYPOT_FIELD }}">Leave this field empty</label>
            <input id="{{ \App\Http\Requests\StoreContactSubmissionRequest::HONEYPOT_FIELD }}" name="{{ \App\Http\Requests\StoreContactSubmissionRequest::HONEYPOT_FIELD }}" type="text" value="" tabindex="-1" autocomplete="off">
        </div>
        <input type="hidden" name="{{ \App\Http\Requests\StoreContactSubmissionRequest::STARTED_AT_FIELD }}" value="{{ old(\App\Http\Requests\StoreContactSubmissionRequest::STARTED_AT_FIELD) ?? \App\Http\Requests\StoreContactSubmissionRequest::startedAtToken() }}">

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700">Full Name *</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="John Smith" required maxlength="255" autocomplete="name" class="{{ $inputClasses }} {{ $fieldBorder('name') }}">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="phone" class="mb-1.5 block text-sm font-medium text-gray-700">Phone Number *</label>
                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="(470) 555-0123" required maxlength="50" autocomplete="tel" class="{{ $inputClasses }} {{ $fieldBorder('phone') }}">
                @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700">Email Address</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="john@example.com" maxlength="255" autocomplete="email" class="{{ $inputClasses }} {{ $fieldBorder('email') }}">
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="project_type" class="mb-1.5 block text-sm font-medium text-gray-700">Project Type</label>
            <select id="project_type" name="project_type" class="{{ $inputClasses }} {{ $fieldBorder('project_type') }}">
                <option value="">Select project type</option>
                @foreach (\App\Enums\ContactProjectType::cases() as $projectType)
                    <option value="{{ $projectType->value }}" @selected(old('project_type') === $projectType->value)>{{ $projectType->label() }}</option>
                @endforeach
            </select>
            @error('project_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="message" class="mb-1.5 block text-sm font-medium text-gray-700">Project Details</label>
            <textarea id="message" name="message" rows="4" placeholder="Tell us about your project..." maxlength="5000" class="block w-full resize-none rounded-md border bg-white px-3 py-2 text-sm shadow-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 {{ $fieldBorder('message') }}">{{ old('message') }}</textarea>
            @error('message') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit" x-bind:disabled="isSubmitting" class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-md bg-orange-500 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-orange-600 disabled:pointer-events-none disabled:opacity-50">
            <span x-show="isSubmitting" x-cloak class="inline-flex items-center gap-2">
                <x-icon name="loader-circle" class="size-4 animate-spin" />
                Sending...
            </span>
            <span x-show="! isSubmitting">Request Free Estimate</span>
        </button>

        <p class="text-center text-xs text-gray-500">By submitting, you agree to be contacted about your project.</p>
    </form>
@endif
