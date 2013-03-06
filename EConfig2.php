<?php 
// EConfig.php
// from http://d.hatena.ne.jp/anatoo/comment/20080120/1200814827
/* Example:
    $config = EConfig::getInstance();
    $config->hoge = "fuga";
*/

class EConfig2
{
  private static $instance = null;

  private function __construct()
  {
    //print "EConfig2::__construct()\n";
    $state = @unserialize( substr( file_get_contents( __FILE__), __COMPILER_HALT_OFFSET__));
    if( $state !== false) foreach( $state as $key => $value) $this->{$key} = $value;
  }
  public static function getInstance()
  {
    if( self::$instance === null) self::$instance = new self();
    
    return self::$instance;
  }
  public function __clone()
  {
    return $this->getInstance();
  }
  public function __destruct()
  {
    $fp = fopen( __FILE__, 'r+');
    flock( $fp, LOCK_EX);
    fseek( $fp, __COMPILER_HALT_OFFSET__);
    fwrite( $fp, serialize( get_object_vars( $this)));
    if( !feof( $fp)) ftruncate( $fp, ftell( $fp));
    fclose( $fp);
    //print "EConfig2::__destruct()\n";
  }
}
__halt_compiler();
