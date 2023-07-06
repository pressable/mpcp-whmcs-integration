<p>
  {$site.name} ({$site.state}){if $site.staging} STAGING{/if}<br />
  {$site.datacenterName}<br />
  IPS: {$site.ipAddressOne} | {$site.ipAddressTwo}<br />
  PHP: {$site.phpVersion} {include file="./site_change_php_version_button.tpl" siteId=$site.id url=$url currentVersion=$site.phpVersion versions=$phpVersions}
  {include file="./reset_wordpress_password_button.tpl" siteId=$site.id}
</p>

<hr />

<h2>Domains</h2>

{if $domains}
  <table>
    <tr>
      <th>Name</th>
      <th>Delete</th>
    </tr>
    {foreach from=$domains item=item}
      <tr>
        <td>{$item.domainName}</td>
        <td>{include file='./site_domain_delete_button.tpl' siteId=$site.id domainId=$item.id url=$url}</td>
      </tr>
    {/foreach}
  </table>
{else}
  <h4>None</h4>
{/if}

<p>{include file="./site_domain_add_button.tpl" siteId=$site.id url=$url}</p>

<hr />

<h2>Backups</h2>
{if $backups}
  <table>
    <tr>
      <th>Timestamp</th>
      <th>Restore Files</th>
      <th>Restore Database</th>
      <th></th>
    </tr>
    {foreach from=$backups item=item}
      <tr>
        <form method="post" action="">
          <input type="hidden" name="_action" value="restoreBackup" />
          <input type="hidden" name="siteId" value="{$site.id}" />
          <td>{$item.timestamp}</td>
          <td><input type="checkbox" name="filesystem_id" value="{$item.filesystemBackupId}" /></td>
          <td><input type="checkbox" name="database_id" value="{$item.databaseBackupId}" /></td>
          <td><input type="submit" title="Restore From Backup" value="&#9100;" /></td>
        </form>
      </tr>
    {/foreach}
  </table>
{else}
  <h4>None</h4>
{/if}

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
