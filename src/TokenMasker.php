<?php
namespace Yiisoft\Security;

use Yiisoft\Strings\StringHelper;

/**
 * TokenMask helps to mitigate BREACH attack by randomizing how token is outputted on each request.
 * A random mask is applied to the token making the string always unique.
 */
final class TokenMasker
{
    /**
     * Masks a token to make it uncompressible.
     * Applies a random mask to the token and prepends the mask used to the result making the string always unique.
     * @param string $token An unmasked token.
     * @return string A masked token.
     * @throws \Exception if unable to securely generate random bytes
     */
    public static function mask(string $token): string
    {
        // The number of bytes in a mask is always equal to the number of bytes in a token.
        $mask = random_bytes(StringHelper::byteLength($token));
        return StringHelper::base64UrlEncode($mask . ($mask ^ $token));
    }

    /**
     * Unmasks a token previously masked by `mask`.
     * @param string $maskedToken A masked token.
     * @return string An unmasked token, or an empty string in case of token format is invalid.
     */
    public static function unmask(string $maskedToken): string
    {
        $decoded = StringHelper::base64UrlDecode($maskedToken);
        $length = StringHelper::byteLength($decoded) / 2;
        // Check if the masked token has an even length.
        if (!is_int($length)) {
            return '';
        }

        return StringHelper::byteSubstr($decoded, $length, $length) ^ StringHelper::byteSubstr($decoded, 0, $length);
    }
}
