<x-mail::message>
    # New User Registration 🛎️

    A new user has registered on the hotel platform.

    **Name:** {{ $user->name }}
    **Email:** {{ $user->email }}
    **Registered At:** {{ $user->created_at->format('d M Y, h:i A') }}

    <x-mail::button :url="url('/dashboard')">
        Login to Admin Panel
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>