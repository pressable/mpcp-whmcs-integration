<p>
  {$site.name} ({$site.state}){if $site.staging} STAGING{/if}<br />
  {$site.datacenterName}<br />
  IPS: {$site.ipAddressOne} | {$site.ipAddressTwo}<br />
  PHP: {$site.phpVersion} {include file="./site_change_php_version_button.tpl" siteId=$site.id url=$url currentVersion=$site.phpVersion versions=$phpVersions}
</p>
<p>
  {include file="./reset_wordpress_password_button.tpl" siteId=$site.id url=$url}
</p>

<hr />

<h2>Domains</h2>

<div class="tablebg">
  <table class="datatable table table-list">
    <tr>
      <th>Name</th>
      <th>Delete</th>
    </tr>
    {foreach from=$domains item=item}
      <tr>
        <td>{$item.domainName}</td>
        <td>{include file='./site_domain_delete_button.tpl' siteId=$site.id domainId=$item.id url=$url}</td>
      </tr>
    {foreachelse}
      <tr><td class="text-center" colspan="2">None</td></tr>
    {/foreach}
  </table>
</div>

<p>{include file="./site_domain_add_button.tpl" siteId=$site.id url=$url}</p>

<hr />

<h2>Backups</h2>
<div class="tablebg">
  <table class="datatable table table-list">
    <tr>
      <th>Timestamp</th>
      <th class="text-center">Restore Files</th>
      <th class="text-center">Restore Database</th>
      <th></th>
    </tr>
    {foreach from=$backups item=item}
      <tr>
        <form method="post" action="">
          <input type="hidden" name="_action" value="restoreBackup" />
          <input type="hidden" name="siteId" value="{$site.id}" />
          <td>{$item.timestamp}</td>
          <td class="text-center"><input type="checkbox" name="filesystem_id" value="{$item.filesystemBackupId}" /></td>
          <td class="text-center"><input type="checkbox" name="database_id" value="{$item.databaseBackupId}" /></td>
          <td class="text-center"><input class="btn btn-warning" type="submit" title="Restore From Backup" value="Restore" /></td>
        </form>
      </tr>
    {foreachelse}
      <tr><td class="text-center" colspan="4">None</td></tr>
    {/foreach}
  </table>
</div>

<hr />

<h2>Nameservers</h2>
<p>
  <code>
    ns1.openhostingservice.com<br />
    ns2.openhostingservice.com<br />
    ns3.openhostingservice.com<br />
    ns4.openhostingservice.com<br />
    ns5.openhostingservice.com
  </code>
</p>
