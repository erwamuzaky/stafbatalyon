@php $layout = auth()->user()->isAdmin() ? 'admin-layout' : 'staf-layout'; @endphp

<x-dynamic-component :component="$layout" title="Profil Saya">
    <div class="space-y-4">
        <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm sm:p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="rounded-xl border border-laut-100 bg-white p-4 shadow-sm sm:p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</x-dynamic-component>
