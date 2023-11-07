<?php
class PluginErrorPush{
  public function push($data, $post){
    /**
     * Used from plugin wf/errorhandling.
     */
    $data = new PluginWfArray($data);
    /**
     * 
     */
    $ch = curl_init($data->get('data/url'));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }
  private function push2($url, $post){
    /**
     * Used from this plugin event.
     */
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }
  public function event_shutdown($data){
    wfPlugin::includeonce('wf/array');
    $data = new PluginWfArray($data);
    /**
     * Get error.
     */
    $error = error_get_last();
    /**
     * If error.
     */
    if($error){
      $error = new PluginWfArray($error);
      /**
       * Server
       */
      $server_variables = array('HTTP_HOST', 'HTTP_USER_AGENT', 'HTTP_REFERER', 'SERVER_NAME', 'SERVER_ADDR', 'DOCUMENT_ROOT', 'REDIRECT_QUERY_STRING', 'REDIRECT_URL', 'REQUEST_METHOD', 'QUERY_STRING', 'REQUEST_URI');
      $server = new PluginWfArray($_SERVER);
      /**
       * 
       */
      $post = new PluginWfArray();
      $post->set('error_message', $error->get('message'));
      $post->set('error_file', $error->get('file'));
      $post->set('error_line', $error->get('line'));
      $post->set('error_type', PluginErrorPush::ErrorTypeToString($error->get('type')));
      foreach($server_variables as $v){
        $post->set($v, $server->get($v));
      }
      $post->set('session_id', '_not_set_');
      $post->set('session', wfHelp::getYmlDump($_SESSION, true));
      $post->set('phpversion', phpversion());
      $this->push2($data->get('data/url'), $post->get());
    }
  }
  public static function ErrorTypeToString($type)
  {
    switch($type)
      {
      case E_ERROR: // 1 //
          return 'E_ERROR';
      case E_WARNING: // 2 //
          return 'E_WARNING';
      case E_PARSE: // 4 //
          return 'E_PARSE';
      case E_NOTICE: // 8 //
          return 'E_NOTICE';
      case E_CORE_ERROR: // 16 //
          return 'E_CORE_ERROR';
      case E_CORE_WARNING: // 32 //
          return 'E_CORE_WARNING';
      case E_CORE_ERROR: // 64 //
          return 'E_COMPILE_ERROR';
      case E_CORE_WARNING: // 128 //
          return 'E_COMPILE_WARNING';
      case E_USER_ERROR: // 256 //
          return 'E_USER_ERROR';
      case E_USER_WARNING: // 512 //
          return 'E_USER_WARNING';
      case E_USER_NOTICE: // 1024 //
          return 'E_USER_NOTICE';
      case E_STRICT: // 2048 //
          return 'E_STRICT';
      case E_RECOVERABLE_ERROR: // 4096 //
          return 'E_RECOVERABLE_ERROR';
      case E_DEPRECATED: // 8192 //
          return 'E_DEPRECATED';
      case E_USER_DEPRECATED: // 16384 //
          return 'E_USER_DEPRECATED';
      }
    return $type;
  }
}
