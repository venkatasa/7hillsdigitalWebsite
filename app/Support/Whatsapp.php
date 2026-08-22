<?php

namespace App\Support;

/**
 * Click-to-chat links.
 *
 * The number is stored once, in local form (`068 859 6695`), because that is
 * how it is displayed everywhere. wa.me needs it in international form with no
 * punctuation, so the conversion happens here rather than being duplicated in
 * every template — and rather than storing the same number twice in config
 * where the two copies would eventually drift apart.
 */
class Whatsapp
{
    /** The display number, exactly as configured. */
    public static function display(): string
    {
        return (string) config('brand.contact.whatsapp');
    }

    /** "27688596695" — digits only, in international form. */
    public static function number(): ?string
    {
        $digits = preg_replace('/\D+/', '', self::display());

        if ($digits === '') {
            return null;
        }

        // A local South African number starts 0; wa.me needs the country code.
        if (str_starts_with($digits, '0')) {
            $digits = '27'.substr($digits, 1);
        }

        return $digits;
    }

    /** A click-to-chat URL, optionally with the message already typed out. */
    public static function url(?string $message = null): ?string
    {
        $number = self::number();

        if ($number === null) {
            return null;
        }

        $url = "https://wa.me/{$number}";

        return $message ? $url.'?text='.rawurlencode($message) : $url;
    }
}
