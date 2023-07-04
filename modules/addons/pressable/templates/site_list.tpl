<p>{include file='./site_add_button.tpl' url=$url}</p>
<table>
  <tr>
    <th>Name</th>
    <th>DC</th>
    <th>IP</th>
    <th>State</th>
    <th>Delete</th>
  </tr>
  {foreach from=$list item=item}
    <tr>
      <td><a href="{$url}&_action=showSite&siteId={$item.id}">{$item.name}</a>{if $item.staging} (staging){/if}</td>
      <td>{$item.datacenterCode}</td>
      <td>{$item.ipAddress}</td>
      <td>{$item.state}</td>
      <td>{if $item.state == 'live' or $item.state == 'disabled'}{include file='./site_delete_button' siteId=$item.id url=$url}{/if}</td>
    </tr>
  {/foreach}
</table>
