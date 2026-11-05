<?php
declare(strict_types=1);

/**
 * Contact data regression check.
 * Each school must expose valid phone/mailto links with stable public identifiers.
 */
$schools = json_decode((string)file_get_contents(dirname(__DIR__) . '/content/schools.json'), true, 512, JSON_THROW_ON_ERROR);
$expected = [
    'wagholi' => ['cbse' => '1130282', 'udise' => '27250508634'],
    'kalyani-nagar' => ['cbse' => '1130609', 'udise' => '27251500113'],
    'hadapsar' => ['cbse' => '1130583', 'udise' => '27250501934'],
];
if (!is_array($schools) || count($schools) !== 3) {
    throw new RuntimeException('Expected three Lexicon campuses');
}
$seen = [];
foreach ($schools as $school) {
    $slug = (string)($school['slug'] ?? '');
    if (!isset($expected[$slug]) || isset($seen[$slug])) {
        throw new RuntimeException("Invalid or duplicate school slug: $slug");
    }
    $seen[$slug] = true;
    if (($school['affiliation'] ?? '') !== $expected[$slug]['cbse']
        || ($school['udise'] ?? '') !== $expected[$slug]['udise']) {
        throw new RuntimeException("CBSE/UDISE identifier drift: $slug");
    }
    if (empty($school['name']) || empty($school['address'])) {
        throw new RuntimeException("Missing campus identity/address: $slug");
    }
    $phones = $school['contactPhones'] ?? [];
    $emails = $school['contactEmails'] ?? [];
    if (!is_array($phones) || !$phones || !is_array($emails) || !$emails) {
        throw new RuntimeException("Missing campus contact directory: $slug");
    }
    $phoneNumbers = [];
    foreach ($phones as $phone) {
        $href = (string)($phone['href'] ?? '');
        if (!preg_match('/^\\+91[0-9]{10}$/', $href) || empty($phone['display']) || isset($phoneNumbers[$href])) {
            throw new RuntimeException("Bad or duplicate phone number for $slug");
        }
        $phoneNumbers[$href] = true;
    }
    if (!isset($phoneNumbers[(string)($school['phoneHref'] ?? '')])) {
        throw new RuntimeException("Legacy phoneHref is not in canonical contacts: $slug");
    }
    $addresses = [];
    foreach ($emails as $email) {
        $address = strtolower((string)($email['address'] ?? ''));
        if (!filter_var($address, FILTER_VALIDATE_EMAIL)
            || !str_ends_with($address, '@lexiconedu.in')
            || isset($addresses[$address])) {
            throw new RuntimeException("Bad or duplicate email for $slug");
        }
        $addresses[$address] = true;
    }
    if (!isset($addresses[(string)($school['email'] ?? '')])) {
        throw new RuntimeException("Primary school email not in canonical contacts: $slug");
    }
}
echo 'Campus identities and contact links verified for all three schools.' . PHP_EOL;
