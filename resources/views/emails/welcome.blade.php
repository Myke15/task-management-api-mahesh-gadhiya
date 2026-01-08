@component('mail::message')
# Welcome to Task Management System!

Hello **{{ $user->name }}**,

Welcome aboard! We're excited to have you join our task management platform. Your account has been successfully created and you're ready to start organizing your projects and tasks.

## Getting Started

Here's what you can do next:

- **Create your first project** - Start organizing your work into manageable projects
- **Add tasks** - Break down your projects into actionable tasks
- **Set priorities** - Keep track of what's most important
- **Collaborate** - Invite team members to work together

@component('mail::button', ['url' => config('app.url')])
Get Started
@endcomponent

If you have any questions or need help getting started, don't hesitate to reach out to our support team.

Thanks for choosing our platform!

Best regards,<br>
{{ config('app.name') }} Team

@endcomponent