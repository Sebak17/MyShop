@component('mail::message')
# Restowanie hasła dla konta {{ $user->email }}

<p style="text-align: justify;">
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec et dui aliquam, maximus orci a, blandit ante. Aenean ultricies sem eros, vitae mollis leo tempus id. Praesent tempor, massa eget ornare cursus, lectus sem ullamcorper mauris, eget blandit dui urna vel tellus. Mauris id diam placerat, viverra augue vitae, pellentesque mi.
</p>

@component('mail::button', ['url' => route('resetPasswordCheckPage', $user->info->passwordResetHash)])
RESTUJ HASŁO
@endcomponent

Pozdrawiamy!<br>
{{ config('app.name') }}
@endcomponent