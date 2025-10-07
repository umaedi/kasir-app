<?php

if (! function_exists('formatRupiah')) {
    function formatRupiah($value)
    {
        if (is_null($value) || $value === '') {
            return 'Rp 0';
        }

        return 'Rp ' . number_format($value, 0, ',', '.');
    }
}
