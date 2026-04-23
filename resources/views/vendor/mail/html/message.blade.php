@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<div style="width: 80%; margin: 0 auto">
<table width="100%">
    <tr>
        <td  align="left" style="font-size: 14pt; font-weight:bold">
            CAMTool Novo Nordisk
        </td>
        <td align="right">
            <img src="https://comercial.nnco.cloud/images/novo-logo-68.png" alt="CamTool">
        </td>
    </tr>
</table>
</div>
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} CAMTool Novo Nordisk. @lang('Todos los derechos reservados')
@endcomponent
@endslot
@endcomponent
