<p>{include file='./site_add_button.tpl' disabled=!$canAddSite url=$url phpVersions=$createOptions.phpVersions datacenters=$createOptions.datacenters installOptions=$createOptions.installOptions}</p>
<div class="table-container clearfix">
  <table class="table table-list">
    <tr>
      <th>Name</th>
      <th>Location</th>
      <th>IP</th>
      <th>State</th>
      {if $isAdmin}<th>Admin Action</th>{/if}
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
        <td>{$item.datacenterName}</td>
        <td>
          {$item.ipAddressOne}<br />
          {$item.ipAddressTwo}
        </td>
        <td><span class="badge {if $item.state == 'live'}badge-success{/if} {if $item.state == 'disabled'}badge-warning{/if} {if $item.state != 'live' && $item.state != 'disabled'}badge-secondary{/if}">{$item.state}</span></td>
        {if $isAdmin}<td>{include file='./site_state_button.tpl' state=$item.state siteId=$item.id url=$url}</td>{/if}
        <td>{if $item.state == 'live' or $item.state == 'disabled'}{include file='./site_delete_button.tpl' siteId=$item.id url=$url}{/if}</td>
      </tr>
    {foreachelse}
      <tr><td class="text-center" colspan="6">None</td></tr>
    {/foreach}
  </table>
  <p>{include file='./pagination.tpl' url=$url pagination=$pagination number=$list|@count}</p>
</div>
