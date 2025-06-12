<x-mail::message>
    # Welcome, {{ $user->name }} 🎉

    Thank you for verifying your email and joining Venus Hotel Management System!

    We’re excited to help you manage your property efficiently.
    Here are some resources to get you started:
    - **Video Tutorials**: [View Documentation](https://www.youtube.com/@fortranhouse)
    - **Support**: [Contact Support][Whatsapp](https://wa.me/+2349165426799)
    If you have any questions, feel free to reach out to our support team [Whatsapp](https://wa.me/+2349165426799).
    <x-mail::panel>
        For more information, visit our [website](https://venushotelsoftware.com).
    </x-mail::panel>

    <x-mail::button :url="url('/dashboard')">
        Go to Dashboard
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>