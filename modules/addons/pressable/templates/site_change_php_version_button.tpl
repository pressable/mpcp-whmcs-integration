<script language="javascript" type="text/javascript">
{literal}
  function togglePhpVersionForm() {
    $('#pressable-change-php-version-form').toggle();
    $('#pressable-change-php-version-button').toggle();
  }
{/literal}
</script>

<button id="pressable-change-php-version-button" onclick="togglePhpVersionForm()">&#8644;</button>
<div id="pressable-change-php-version-form" style="display: none">
  <form method="post" action="{$url}">
    <input type="hidden" name="_action" value="changePhpVersion" />
    <input type="hidden" name="siteId" value="{$siteId}" />

    <select name="version">
      {foreach from=$versions item=item}
        <option value="{$item}" {if $item == $currentVersion}selected disabled{/if}>{$item}</option>
      {/foreach}
    </select>
    <input type="submit" value="Change PHP Version" />
    <button onclick="togglePhpVersionForm()" type="button">Cancel</button>
  </form>
</div>
