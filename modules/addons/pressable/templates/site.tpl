<p>
  {$site.name} ({$site.state})<br />
  {$site.ipAddress} {$site.datacenterName}<br />
  PHP: {$site.phpVersion} {include file="./site_change_php_version_button.tpl" siteId=$siteId url=$url currentVersion=$site.phpVersion versions=$phpVersions}
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
