<p>
  <h3>{$site.name} ({$site.state}){if $site.staging} STAGING{/if}</h3>
  {if $site.url}
    <p>
      <a href="http://{$site.url}" target="_blank">{$site.url}</a>    </p>
  {/if}
  {$site.datacenterName}<br />
  IPS: {$site.ipAddressOne} | {$site.ipAddressTwo}<br />
  PHP: {$site.phpVersion} {include file="./site_change_php_version_button.tpl" siteId=$site.id url=$url currentVersion=$site.phpVersion versions=$phpVersions}
</p>

<hr />

<h2>Manage</h2>
<table>
  <tr>
    <td>
      <a href="{$url}&_action=phpMyAdmin" target="_blank"><i class="fas fa-external-link"></i> Launch phpMyAdmin</a>
    </td>
    <td></td>
  </tr>
  <tr>
    <td>
      <a href="http://{$site.wordpressLoginUrl}" target="_blank"><i class="fas fa-external-link"></i> WordPress Admin</a>
    </td>
    <td>
      {include file="./reset_wordpress_password_button.tpl" siteId=$site.id url=$url}
    </td>
  </tr>
  <tr>
    <td>
      SFTP username: <code>{$site.ftpUsername}</code>
    </td>
    <td>
      {include file="./reset_ftp_password_button.tpl" siteId=$site.id url=$url username=$site.ftpUsername}
    </td>
  </tr>
</table>

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
          <td class="text-center"><input type="checkbox" name="filesystem_id" {if empty($item.filesystemBackupId)}disabled{/if} value="{$item.filesystemBackupId}" /></td>
          <td class="text-center"><input type="checkbox" name="database_id" {if empty($item.databaseBackupId)}disabled{/if} value="{$item.databaseBackupId}" /></td>
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
