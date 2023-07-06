<form method="post" action="{$url}">
  <input type="hidden" name="_action" value="domainDelete" />
  <input type="hidden" name="siteId" value="{$siteId}" />
  <input type="hidden" name="domainId" value="{$domainId}" />
  <input class="btn btn-danger" type="submit" value="Delete" />
</form>
