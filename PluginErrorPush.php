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
      $post->set('error_type', $error->get('type'));
      foreach($server_variables as $v){
        $post->set($v, $server->get($v));
      }
      $post->set('session_id', '_not_set_');
      $post->set('session', wfHelp::getYmlDump($_SESSION, true));
      $post->set('phpversion', phpversion());
      $this->push2($data->get('data/url'), $post->get());
    }
  }
}
