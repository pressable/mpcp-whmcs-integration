{if $pagination.totalItems >=1 && $number > 0 && $number < $pagination.totalItems}
  {math assign="prev" equation="current - 1" current=$pagination.currentPage}
  {math assign="first" equation="prev * per + 1" prev=$prev per=$pagination.perPage}
  {math assign="last" equation="current * per" current=$pagination.currentPage per=$pagination.perPage}
  {if $last > $pagination.totalItems}
    {assign var="last" value=$pagination.totalItems}
  {/if}

  <span>Showing {$first}-{$last} of {$pagination.totalItems}</span>

  {if $prev > 0}
    <a href="{$url}&page={$prev}"><button class="btn btn-sm btn-light">Prev</button></a>
  {/if}

  {if $pagination.nextPage > 0}
    <a href="{$url}&page={$pagination.nextPage}"><button class="btn btn-sm btn-light">Next</button></a>
  {/if}
{/if}
