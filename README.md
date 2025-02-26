<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii Security</h1>
    <br>
</p>

Security package provides a set of classes to handle common security-related tasks:

- Random values generation
- Password hashing and validation
- Encryption and decryption
- Data tampering prevention
- Masking token length

[![Latest Stable Version](https://poser.pugx.org/yiisoft/security/v/stable.png)](https://packagist.org/packages/yiisoft/security)
[![Total Downloads](https://poser.pugx.org/yiisoft/security/downloads.png)](https://packagist.org/packages/yiisoft/security)
[![Build Status](https://travis-ci.com/yiisoft/security.svg?branch=master)](https://travis-ci.com/yiisoft/security)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yiisoft/security/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/security/?branch=master)
[![Code coverage](https://scrutinizer-ci.com/g/yiisoft/security/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/security/?branch=master)


## Random values generation

In order to generate a string that is 42 characters long use:

```php
$randomString = Random::string(42);
```

The following extras are available via PHP directly:

- `random_bytes()` for bytes. Note that output may not be ASCII.
- `random_int()` for integers.

## Password hashing and validation

Working with passwords includes two steps. Saving password hashes:
 
```php
$hash = (new PasswordHasher())->hash($password);

// save hash to database or another storage
saveHash($hash); 
```

Validating password against the hash:

```php
// obtain hash from database or another storage
$hash = getHash();

$result = (new PasswordHasher())->validate($password, $hash); 
```

## Encryption and decryption by password

Encrypting data:

```php
$encryptedData = (new Crypt())->encryptByPassword($data, $password);

// save data to database or another storage
saveData($encryptedData);
```

Decrypting it:

```php
// obtain encrypted data from database or another storage
$encryptedData = getEncryptedData();

$data = (new Crypt())->decryptByPassword($encryptedData, $password);
```

## Encryption and decryption by key

Encrypting data:

```php
$encryptedData = (new Crypt())->encryptByKey($data, $key);

// save data to database or another storage
saveData($encryptedData);
```

Decrypting it:

```php
// obtain encrypted data from database or another storage
$encryptedData = getEncryptedData();

$data = (new Crypt())->decryptByKey($encryptedData, $key);
```

## Data tampering prevention

MAC signing could be used in orde to prevent data tampering. The `$key` should be present at both sending and receiving
sides. At the sending side:

```php
$signedMessage = (new Mac())->sign($message, $key);

sendMessage($signedMessage);
```

At the receiving side:

```php
$signedMessage = receiveMessage($signedMessage);

try {
    $message = (new Mac())->getMessage($signedMessage, $key);
} catch (\Yiisoft\Security\DataIsTampered $e) {
    // data is tampered
}
```

## Masking token length

Masking a token helps to mitigate BREACH attack by randomizing how token is outputted on each request.
A random mask is applied to the token making the string always unique.

In order to mask a token:

```php
$maskedToken = TokenMasker::mask($token);
```

In order to get original value from the masked one:

```php
$token = TokenMasker::unmask($maskedToken);
```

## Native PHP functionality

Additionally to this library methods, there is a set of handy native PHP methods.

### Timing attack resistant string comparison

Comparing strings as usual is not secure when dealing with user inputed passwords or key phrases. Usual string comparison
return as soon as a difference between the strings is found so attacker could efficiently brute-force character by character
going to the next one as soon as response time increases.

There is a special function in PHP that compares strings in a constant time:

```php
hash_equals($expected, $actual);
```

