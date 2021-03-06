--TEST--
secp256k1_ecdsa_verify fails when the signature requires normalization
--SKIPIF--
<?php
if (!extension_loaded("secp256k1")) print "skip extension not loaded";
?>
--FILE--
<?php

// fixture came from our signatures.yml
$sigIn = hex2bin("30460221008a40123bd34eb158206cda02203b470615a15bd4727dbfa50a1ed367b4759c8a022100cf969c4fe1faf41bfa32297226bf3dac95eab9ca3cf81d9ee248ce6a2be5538a");
$msg32 = \pack("H*", "844afb89e72c14f4455e1232852c4f210a5533fb2454be422268dabab22a225c");
$priv = \pack("H*", "17a2209250b59f07a25b560aa09cb395a183eb260797c0396b82904f918518d5");

$ctx = secp256k1_context_create(SECP256K1_CONTEXT_SIGN | SECP256K1_CONTEXT_VERIFY);

$inputSig = null;
$result = secp256k1_ecdsa_signature_parse_der($ctx, $inputSig, $sigIn);
echo $result . PHP_EOL;
echo get_resource_type($inputSig) . PHP_EOL;

$pub = null;
$result = \secp256k1_ec_pubkey_create($ctx, $pub, $priv);
echo $result . PHP_EOL;

// this *should* fail when we remove normalization from verify!
$result = \secp256k1_ecdsa_verify($ctx, $inputSig, $msg32, $pub);
echo $result . PHP_EOL;

?>
--EXPECT--
1
secp256k1_ecdsa_signature
1
0

