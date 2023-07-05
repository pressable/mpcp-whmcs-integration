<script language="javascript" type="text/javascript">
{literal}
  function toggleSiteCreateForm() {
    $('#pressable-site-create-form').toggle();
    $('#pressable-site-create-button').toggle();
  }
{/literal}
</script>

<button id="pressable-site-create-button" onclick="toggleSiteCreateForm()">Add Site</button>
<div id="pressable-site-create-form" style="display: none">
  <form method="post" action="{$url}">
    <input type="hidden" name="_action" value="createSite" />
    <label>Name <input required type="text" name="name" /></label><br />
    <label>Staging <input type="checkbox" name="staging" value="true" /></label><br />

    <label>
      Datacenter
      <select name="datacenter_code">
        <option value="">(Use Default)</option>
        {foreach from=$datacenters key=key item=item}
          <option value="{$key}">{$item}</option>
        {/foreach}
      </select>
    </label>
    <br />

    <label>
      Site Install
      <select name="install">
        <option value="">(Use Default)</option>
        {foreach from=$installOptions item=item}
          <option value="{$item}">{$item}</option>
        {/foreach}
      </select>
    </label>
    <br />

    <label>
      PHP Version
      <select name="php_version">
        <option value="">(Use Default)</option>
        {foreach from=$phpVersions item=item}
          <option value="{$item}">{$item}</option>
        {/foreach}
      </select>
    </label>
    <br />

    <input type="submit" value="Create Site" />
    <button onclick="toggleSiteCreateForm()" type="button">Cancel</button>
  </form>
</div>
