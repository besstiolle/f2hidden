<!-- forgot password verification template -->
{$startform}
<h4>{$title}</h4>
{if !empty($message)}
  {if !empty($error) }
    <div class="alert alert-danger">{$message}</div>
  {else}
    <div class="alert alert-info">{$message}</div>
  {/if}
{/if}
<p>{$prompt_username}&nbsp;{$input_username}</p>
<p>{$prompt_code}&nbsp;{$input_code}</p>
<p>{$prompt_password}&nbsp;{$input_password}</p>
<p>{$prompt_repeatpassword}&nbsp;{$input_repeatpassword}</p>
<p>{$hidden}{$submit}</p>
{$endform}
<!-- forgot password verification template -->
