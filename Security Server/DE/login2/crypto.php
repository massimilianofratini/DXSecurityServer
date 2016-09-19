<?php
require_once 'mylogger.php';

class Crypto
{
    const METHOD = 'aes-256-ctr';
	const SEED = '000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f';
    /**
     * Encrypts (but does not authenticate) a message
     * 
     * @param string $message - plaintext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encode - set to TRUE to return a base64-encoded 
     * @return string (raw binary)
     */
    public static function encrypt($message, $key, $encode = false)
    {
        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = openssl_random_pseudo_bytes($nonceSize);

        $ciphertext = openssl_encrypt(
            $message,
            self::METHOD,
            $key,
            true,
            $nonce
        );

        // Now let's pack the IV and the ciphertext together
        // Naively, we can just concatenate
        if ($encode) {
            return base64_encode($nonce.$ciphertext);
        }
        return $nonce.$ciphertext;
    }

    /**
     * Decrypts (but does not verify) a message
     * 
     * @param string $message - ciphertext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encoded - are we expecting an encoded string?
     * @return string
     */
    public static function decrypt($message, $key, $encoded = false)
    {
        if ($encoded) {
            $message = base64_decode($message, true);
            mydebug('DECRYPT base64 decoded:'.$message);
            if ($message === false) {
                mydebug("DECRYPT Encryption failure");
		
                throw new Exception('Encryption failure');
            }
        }

        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        mydebug('NONCESIZE:'.$nonceSize);
        $nonce = mb_substr($message, 0, $nonceSize, '8bit');
        mydebug('NONCE:'.$nonce);
        $ciphertext = mb_substr($message, $nonceSize, mb_strlen($message)-$nonceSize, '8bit');
        mydebug('DECRYPT ciphertext:'.$ciphertext);

        $plaintext = openssl_decrypt(
            $ciphertext,
            self::METHOD,
            $key,
            true,
            $nonce
        );

        return $plaintext;
    }
}

function myhex2bin($hexstr) 
{ 
        $n = strlen($hexstr); 
        $sbin="";   
        $i=0; 
        while($i<$n) 
        {       
            $a =substr($hexstr,$i,2);           
            $c = pack("H*",$a); 
            if ($i==0){$sbin=$c;} 
            else {$sbin.=$c;} 
            $i+=2; 
        } 
        return $sbin; 
} 
?>
