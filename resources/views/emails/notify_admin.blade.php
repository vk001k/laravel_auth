@component('mail::layout')
{{-- Header --}}
@slot('header')
<b>Company Information</b>
@endslot

{{-- Body --}}
# Hello

New company register.<br />

<b>Company INFORMATION:</b>
**Name :** {{ $data->name}}<br />
**Email :** {{ $data->email}}<br />


Thanks,

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
@endcomponent
@endslot
@endcomponent
