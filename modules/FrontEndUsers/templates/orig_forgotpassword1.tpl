<!-- forgot password template -->
{$startform}
<h4>{$title}</h4>
{if !empty($message) }
  {if !empty($error) }
    <div class="alert alert-danger">{$mesasge}</div>
  {else}
    <div class="alert alert-info">{$message}</div>
  {/if}
{/if}
<p>{$lostpw_message}</p>
<div class="row">
  <div class="col-sm-2 text-right">{$prompt_username}:</div>
  <div class="col-sm-10">{$input_username}</p>
</div>
{if isset($captcha)}
<div class="row">
  <div class="col-sm-2 text-right">{$captcha_title}:</div>
  <div class="col-sm-10">{$input_captcha}</p>
</div>
{/if}
<div class="row">
  <div class="col-sm-10 col-sm-offset-2">{$hidden}{$submit} {$cancel}</div>
</div>
{$endform}
<!-- forgot password template -->
