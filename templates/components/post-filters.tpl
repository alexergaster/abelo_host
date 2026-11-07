<form
        method="GET"
        action="{$filterAction|default:'/posts'|escape}"
        class="post-filters"
        aria-label="Filter articles"
>

    <div class="post-filters__header">
        <div class="post-filters__heading">
            <h3 class="post-filters__title">Filter Articles</h3>
            <p class="post-filters__subtitle">
                Refine articles by views, publication date and sort order.
            </p>
        </div>

        <a
                href="{$filterAction|default:'/posts'|escape}"
                class="post-filters__reset"
        >
            Clear filters
        </a>
    </div>

    <div class="post-filters__grid">

        {* Minimum views *}
        <div class="post-filters__field">
            <label for="views_min">Minimum Views</label>

            <input
                    type="number"
                    id="views_min"
                    name="views_min"
                    min="0"
                    step="1"
                    placeholder="e.g. 100"
                    value="{$filter->viewsMin|default:''|escape}"
            >
        </div>

        {* Maximum views *}
        <div class="post-filters__field">
            <label for="views_max">Maximum Views</label>

            <input
                    type="number"
                    id="views_max"
                    name="views_max"
                    min="0"
                    step="1"
                    placeholder="e.g. 1000"
                    value="{$filter->viewsMax|default:''|escape}"
            >
        </div>

        {* Published from *}
        <div class="post-filters__field">
            <label for="date_from">Published From</label>

            <input
                    type="date"
                    id="date_from"
                    name="date_from"
                    value="{$filter->dateFrom|default:''|escape}"
            >
        </div>

        {* Published to *}
        <div class="post-filters__field">
            <label for="date_to">Published To</label>

            <input
                    type="date"
                    id="date_to"
                    name="date_to"
                    value="{$filter->dateTo|default:''|escape}"
            >
        </div>

        {* Sort order *}
        <div class="post-filters__field">
            <label for="sort">Sort By</label>

            <select id="sort" name="sort">

                <option
                        value="newest"
                        {if $filter->sort == 'newest'}selected{/if}
                >
                    Newest First
                </option>

                <option
                        value="oldest"
                        {if $filter->sort == 'oldest'}selected{/if}
                >
                    Oldest First
                </option>

                <option
                        value="views_desc"
                        {if $filter->sort == 'views_desc'}selected{/if}
                >
                    Most Viewed
                </option>

                <option
                        value="views_asc"
                        {if $filter->sort == 'views_asc'}selected{/if}
                >
                    Least Viewed
                </option>

            </select>
        </div>

        {* Posts per page *}
        <div class="post-filters__field">
            <label for="perPage">Articles Per Page</label>

            <select id="perPage" name="perPage">
                {foreach [6, 12, 24, 48] as $size}
                    <option
                            value="{$size}"
                            {if $pagination.perPage == $size}selected{/if}
                    >
                        {$size} articles
                    </option>
                {/foreach}
            </select>
        </div>

    </div>

    <div class="post-filters__footer">

        <p class="post-filters__hint">
            Filters are applied to published articles only.
        </p>

        <div class="post-filters__actions">
            <a
                    href="{$filterAction|default:'/posts'|escape}"
                    class="btn btn--outline"
            >
                Reset
            </a>

            <button type="submit" class="btn btn--primary">
                Apply Filters
                <span aria-hidden="true">&rarr;</span>
            </button>
        </div>

    </div>

</form>