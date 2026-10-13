@php $layout = auth()->user()->isAdmin() ? 'admin-layout' : 'staf-layout'; @endphp

<x-dynamic-component :component="$layout" title="Profil Saya">
    <div class="grid gap-4 lg:grid-cols-2">
        <x-card>
            @include('profile.partials.update-profile-information-form')
        </x-card>

        <x-card>
            @include('profile.partials.update-password-form')
        </x-card>
    </div>
</x-dynamic-component>
