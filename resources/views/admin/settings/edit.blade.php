<x-layouts.admin title="Site settings">
    <form
        method="POST"
        action="{{ route('admin.settings.update') }}"
        class="max-w-2xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-stone-200"
    >
        @csrf
        @method('PUT')

        <h2 class="mb-4 text-lg font-semibold">Contact</h2>
        <x-form-field name="phone_display" label="Phone as shown" :value="$site['phone_display']" placeholder="470-887-FORT (3678)" required />
        <x-form-field name="phone_short" label="Phone number (digits)" :value="$site['phone_short']" placeholder="470-887-3678" required />
        <x-form-field name="email" label="Email" type="email" :value="$site['email']" required />

        <h2 class="mt-8 mb-4 text-lg font-semibold">Service area</h2>
        <x-form-field name="service_area" label="Service area (footer)" :value="$site['service_area']" placeholder="North Atlanta, GA" required />
        <x-form-field name="service_area_tagline" label="Service area (top bar)" :value="$site['service_area_tagline']" placeholder="Serving North Atlanta & Surrounding Areas" required />

        <h2 class="mt-8 mb-4 text-lg font-semibold">Social media</h2>
        <p class="mb-4 text-sm text-stone-600">Leave a link blank to hide its icon.</p>
        <x-form-field name="facebook_url" label="Facebook" type="url" :value="$site['social_links']['facebook'] ?? null" />
        <x-form-field name="instagram_url" label="Instagram" type="url" :value="$site['social_links']['instagram'] ?? null" />
        <x-form-field name="linkedin_url" label="LinkedIn" type="url" :value="$site['social_links']['linkedin'] ?? null" />

        <button type="submit" class="rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600">
            Save settings
        </button>
    </form>
</x-layouts.admin>
