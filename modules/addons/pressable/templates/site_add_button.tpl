<script language="javascript" type="text/javascript">
{literal}
  function toggleSiteCreateForm() {
    $('#pressable-site-create-form').toggle();
    $('#pressable-site-create-button').toggle();
  }
{/literal}
</script>

<button class="btn btn-info" id="pressable-site-create-button" {if $disabled}disabled{else}onclick="toggleSiteCreateForm()"{/if}>Add Site</button>
<div id="pressable-site-create-form" style="display: none">
  <form method="post" action="{$url}">
    <input type="hidden" name="_action" value="createSite" />
    <table class="form">
      <tr>
        <td class="fieldlabel">Name</td>
        <td class="fieldarea"><input required type="text" name="name" /></td>
      </tr>
      <tr>
        <td class="fieldlabel">Staging</td>
        <td class="fieldarea"><input type="checkbox" name="staging" value="true" /></td>
      </tr>
      <tr>
        <td class="fieldlabel">Datacenter</td>
        <td class="fieldarea">
          <select class="form-control select-inline" name="datacenter_code">
            <option value="">(Use Default)</option>
            {foreach from=$datacenters key=key item=item}
              <option value="{$key}">{$item}</option>
            {/foreach}
          </select>
        </td>
      </tr>
      <tr>
        <td class="fieldlabel">Site Install</td>
        <td class="fieldarea">
          <select class="form-control select-inline" name="install">
            <option value="">(Use Default)</option>
            {foreach from=$installOptions item=item}
              <option value="{$item}">{$item}</option>
            {/foreach}
          </select>
        </td>
      </tr>
      <tr>
        <td class="fieldlabel">PHP Version</td>
        <td class="fieldarea">
          <select class="form-control select-inline" name="php_version">
            <option value="">(Use Default)</option>
            {foreach from=$phpVersions item=item}
              <option value="{$item}">{$item}</option>
            {/foreach}
          </select>
        </td>
      </tr>
    </table>
    <div class="btn-container" style="margin-top: 1em;">
      <input class="btn btn-primary" type="submit" value="Create Site" />
      <button class="btn btn-secondary" onclick="toggleSiteCreateForm()" type="button">Cancel</button>
    </div>
  </form>
</div>
