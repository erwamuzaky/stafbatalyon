@php $layout = auth()->user()->isAdmin() ? 'admin-layout' : 'staf-layout'; @endphp

<x-dynamic-component :component="$layout" title="Profil Saya">
    @if ($user->stafProfile)
        <x-card title="Foto Profil" class="mb-4 max-w-3xl">
            <x-foto-staf :profil="$user->stafProfile" :nama="$user->name" />
        </x-card>
    @endif

    <div class="grid gap-4 lg:grid-cols-2">
        <x-card>
            @include('profile.partials.update-profile-information-form')
        </x-card>

        <x-card>
            @include('profile.partials.update-password-form')
        </x-card>
    </div>
</x-dynamic-component>
