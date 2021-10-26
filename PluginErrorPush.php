<?php
class PluginErrorPush{
  public function push($data, $post){
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
}
