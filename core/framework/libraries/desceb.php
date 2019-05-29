<?php
/**
 * DES CEB 加密解密
 *
 * @package    
 */
defined('InShopNC') or exit('Access Invalid!');
class desceb {
    const KEY='yiyamaya';
    /** 
     * 转换字符串到16进制 
     * @param string $str 字符串 
     */  
    private function new_strToHex($string){
        return bin2hex($string);
    }

    /** 
     *  转换16进制到字符串
     * @param string $str 字符串 
     */  
    private function new_hexToStr($hex){
        $string='';
        for ($i=0; $i < strlen($hex)-1; $i+=2){
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return $string;
    }
    /**  
     * 加密 
     * @param string $str 字符串 
     * @param string $key 密钥 
     */  
    public function new_encrypt($str, $key=self::KEY)  {  
        $block = mcrypt_get_block_size('des', 'ecb');  
        $pad = $block - (strlen($str) % $block);  
        $str .= str_repeat(chr($pad), $pad);  
        $str = mcrypt_encrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);  
        return $this->new_strToHex($str);  
    }  
      
    /** 
     * 解密 
     * @param string $str 字符串 
     * @param string $key 密钥 
     */  
    public function new_decrypt($str, $key=self::KEY) {  
        $str = $this->new_hexToStr($str);  
        $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);  
        $block = mcrypt_get_block_size('des', 'ecb');  
        $pad = ord($str[($len = strlen($str)) - 1]);  
        return substr($str, 0, strlen($str) - $pad); 
    }
}