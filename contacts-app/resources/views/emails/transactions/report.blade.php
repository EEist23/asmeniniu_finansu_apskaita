@component('mail::message')
# Finansų ataskaita

Sveiki,

Prisegame jūsų finansų ataskaitą PDF formatu už {{ $month }}/{{ $year }}.

Dėkojame,  
{{ config('app.name') }}
@endcomponent