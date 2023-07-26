{if $state == 'live' || $state == 'disabled'}
  <form method="post" action="{$url}">
    <input type="hidden" name="siteId" value="{$siteId}" />

    {if $state == 'live'}
      <input type="hidden" name="_action" value="suspendSite" />
      <input class="btn btn-danger" type="submit" value="Suspend" />
    {/if}

    {if $state == 'disabled'}
      <input type="hidden" name="_action" value="unsuspendSite" />
      <input class="btn btn-danger" type="submit" value="Unsuspend" />
    {/if}
  </form>
{/if}
