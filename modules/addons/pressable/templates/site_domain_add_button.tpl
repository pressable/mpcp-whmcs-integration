<script language="javascript" type="text/javascript">
{literal}
  function toggleSiteDomainAddForm() {
    $('#pressable-add-site-domain-form').toggle();
    $('#pressable-add-site-domain-button').toggle();
  }
{/literal}
</script>

<button id="pressable-add-site-domain-button" onclick="toggleSiteDomainAddForm()">Add a Domain</button>
<div id="pressable-add-site-domain-form" style="display: none">
  <form method="post" action="{$url}">
    <input type="hidden" name="_action" value="domainAdd" />
    <input type="hidden" name="siteId" value="{$siteId}" />

    <label>Domain <input type="text" name="name" required /></label><br />
    <input type="submit" value="Add Domain" />
    <button onclick="toggleSiteDomainAddForm()" type="button">Cancel</button>
  </form>
</div>
