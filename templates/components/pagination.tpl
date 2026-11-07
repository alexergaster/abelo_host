{if $pagination.totalPages > 1}


    {assign var="current" value=$pagination.currentPage}
    {assign var="total" value=$pagination.totalPages}

    {* Calculate visible page range *}
    {assign var="start" value=$current - 2}
    {assign var="end" value=$current + 2}

    {* Minimum start page is 2 *}
    {if $start < 2}
        {assign var="start" value=2}
    {/if}

    {* Maximum end page is total - 1 *}
    {if $end > $total - 1}
        {assign var="end" value=$total - 1}
    {/if}
    <nav class="pagination" aria-label="Pagination">

        {* Previous *}
        {if $pagination.hasPrevious}
            <a
                    href="{$paginationBase|escape}page={$pagination.previousPage}"
                    class="pagination__item pagination__arrow"
                    rel="prev"
                    aria-label="Previous page"
            >
                <span aria-hidden="true">&larr;</span>
                <span class="pagination__arrow-label">Previous</span>
            </a>
        {else}
            <span
                    class="pagination__item pagination__arrow pagination__item--disabled"
                    aria-disabled="true"
            >
                <span aria-hidden="true">&larr;</span>
                <span class="pagination__arrow-label">Previous</span>
            </span>
        {/if}

        <div class="pagination__pages">

            {* First page *}
            <a
                    href="{$paginationBase|escape}page=1"
                    class="pagination__item {if $current == 1}pagination__item--active{/if}"
                    {if $current == 1}aria-current="page"{/if}
                    aria-label="Page 1"
            >1</a>

            {* Left ellipsis *}
            {if $start > 2}
                <span class="pagination__ellipsis" aria-hidden="true">
                    &hellip;
                </span>
            {/if}

            {* Middle pages *}
            {if $start <= $end}
                {for $pageNumber=$start to $end}
                    <a
                            href="{$paginationBase|escape}page={$pageNumber}"
                            class="pagination__item {if $pageNumber == $current}pagination__item--active{/if}"
                            {if $pageNumber == $current}aria-current="page"{/if}
                            aria-label="Page {$pageNumber}"
                    >
                        {$pageNumber}
                    </a>
                {/for}
            {/if}

            {* Right ellipsis *}
            {if $end < $total - 1}
                <span class="pagination__ellipsis" aria-hidden="true">
                    &hellip;
                </span>
            {/if}

            {* Last page *}
            <a
                    href="{$paginationBase|escape}page={$total}"
                    class="pagination__item {if $current == $total}pagination__item--active{/if}"
                    {if $current == $total}aria-current="page"{/if}
                    aria-label="Page {$total}"
            >
                {$total}
            </a>

        </div>

        {* Next *}
        {if $pagination.hasNext}
            <a
                    href="{$paginationBase|escape}page={$pagination.nextPage}"
                    class="pagination__item pagination__arrow"
                    rel="next"
                    aria-label="Next page"
            >
                <span class="pagination__arrow-label">Next</span>
                <span aria-hidden="true">&rarr;</span>
            </a>
        {else}
            <span
                    class="pagination__item pagination__arrow pagination__item--disabled"
                    aria-disabled="true"
            >
                <span class="pagination__arrow-label">Next</span>
                <span aria-hidden="true">&rarr;</span>
            </span>
        {/if}

    </nav>
{/if}