<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MsgBase
 *
 * @author winne
 */
class MsgBase {
    
    protected function checkSignature($signature, $timestamp, $nonce)
    {
      $token = TOKEN;
      $tmpArr = array($token, $timestamp, $nonce);
      sort($tmpArr, SORT_STRING);
      $tmpStr = implode( $tmpArr );
      $tmpStr = sha1( $tmpStr );
      if( $tmpStr == $signature ){
             return true;
      }else{
             return false;
      }
    }
}
