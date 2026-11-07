{include file="layouts/header.tpl"}

<main class="site-main category-page">

    {* Category header *}
    <section class="category-page__hero">

        <div class="container container--narrow">

            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="/">Home</a>
                <span aria-hidden="true">/</span>
                <span>{$category.name|escape}</span>
            </nav>

            <div class="category-page__heading">

                <span class="section__eyebrow">
                    Explore category
                </span>

                <h1 class="category-page__title">
                    {$category.name|escape}
                </h1>

                {if !empty($category.description)}
                    <p class="category-page__description">
                        {$category.description|escape}
                    </p>
                {/if}

                <div class="category-page__meta">
                    <span class="badge badge--primary">
                        {$pagination.total}
                        {if $pagination.total == 1}
                            article
                        {else}
                            articles
                        {/if}
                    </span>
                </div>

            </div>

        </div>

    </section>

    {* Posts and filters *}
    <section class="section category-page__content">

        <div class="container">

            <div class="section__header">

                <div>
                    <span class="section__eyebrow">
                        Discover more
                    </span>

                    <h2 class="section__title">
                        Latest Articles
                    </h2>
                </div>

                <a href="/" class="text-link">
                    &larr; All Categories
                </a>

            </div>

            {* Reusable filters *}
            {include
            file="components/post-filters.tpl"
            filter=$filter
            pagination=$pagination
            filterAction=$filterAction
            }

            <div class="category-page__results">

                <span>
                    Found <strong>{$pagination.total}</strong>
                    {if $pagination.total == 1}
                        article
                    {else}
                        articles
                    {/if}
                </span>

                {if $pagination.total > 0}
                    <span>
                        Page {$pagination.currentPage}
                        of {$pagination.totalPages}
                    </span>
                {/if}

            </div>

            {* Posts grid *}
            {if !empty($posts)}

                <div class="post-grid">

                    {foreach $posts as $post}

                        {include
                        file="components/post-card.tpl"
                        post=$post
                        }

                    {/foreach}

                </div>

                {* Reusable pagination *}
                {include
                file="components/pagination.tpl"
                pagination=$pagination
                paginationBase=$paginationBase
                }

            {else}

                <div class="empty-state">

                    <h3>No articles found</h3>

                    <p>
                        No articles match your filters.
                        Try changing your search criteria.
                    </p>

                    <a href="{$filterAction|escape}"
                       class="btn btn--primary">
                        Clear Filters
                    </a>

                </div>

            {/if}

        </div>

    </section>

</main>

{include file="layouts/footer.tpl"}