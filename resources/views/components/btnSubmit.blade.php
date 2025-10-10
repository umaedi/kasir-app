@props([
    'id'    => '',
    'text'  => 'Simpan',
    'onclick'   => ''
    ])
<button id="{{ $id }}" class="btn btn-primary" type="submit" onclick="{{ $onclick }}">{{ $text }}</button>