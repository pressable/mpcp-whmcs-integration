<p>{include file='./site_add_button.tpl' url=$url phpVersions=$createOptions.phpVersions datacenters=$createOptions.datacenters installOptions=$createOptions.installOptions}</p>
<div class="table-container clearfix">
  <table class="table table-list">
    <tr>
      <th>Name</th>
      <th>DC</th>
      <th>IP</th>
      <th>State</th>
      <th>Delete</th>
    </tr>
    {foreach from=$list item=item}
      <tr>
        <td>
          {if $item.state == 'live' || $item.state == 'disabled'}
            <a href="{$url}&_action=showSite&siteId={$item.id}">{$item.name}</a>
          {else}
            {$item.name}
          {/if}
          {if $item.staging} (staging){/if}</td>
        <td>{$item.datacenterCode}</td>
        <td>
          {$item.ipAddressOne}<br />
          {$item.ipAddressTwo}
        </td>
        <td><span class="badge {if $item.state == 'live'}badge-success{/if} {if $item.state == 'disabled'}badge-warning{/if} {if $item.state != 'live' && $item.state != 'disabled'}badge-secondary{/if}">{$item.state}</span></td>
        <td>{if $item.state == 'live' or $item.state == 'disabled'}{include file='./site_delete_button.tpl' siteId=$item.id url=$url}{/if}</td>
      </tr>
    {foreachelse}
      <tr><td class="text-center" colspan="5">None</td></tr>
    {/foreach}
  </table>
  <p>{include file='./pagination.tpl' url=$url pagination=$pagination number=$list|@count}</p>
</div>
