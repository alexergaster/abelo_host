{include file="layouts/header.tpl"}

<main class="site-main">

    <section class="section categories-page">
        <div class="container">

            {* Page heading *}
            <header class="section__header categories-page__intro">
                <div>
                    <span class="section__eyebrow">
                        Browse topics
                    </span>

                    <h1 class="section__title">
                        {$pageTitle|escape}
                    </h1>

                    <p class="section__description">
                        {$pageDescription|escape}
                    </p>
                </div>
            </header>

            {* Categories with latest posts *}
            {if !empty($categories)}

                <div class="categories-list">

                    {foreach $categories as $category}

                        <section
                                class="category-section"
                                aria-labelledby="category-{$category.id}"
                        >

                            <div class="category-section__header">

                                <div class="category-section__info">

                                    <div class="category-section__heading">

                                        <h2
                                                class="category-section__title"
                                                id="category-{$category.id}"
                                        >
                                            {$category.name|escape}
                                        </h2>

                                        <span class="badge badge--primary">
                                            {$category.posts|count}
                                            {if $category.posts|count == 1}
                                                article
                                            {else}
                                                articles
                                            {/if}
                                        </span>

                                    </div>

                                    {if !empty($category.description)}
                                        <p class="category-section__description">
                                            {$category.description|escape}
                                        </p>
                                    {/if}

                                </div>

                                <a
                                        href="/categories/{$category.slug|escape:'url'}"
                                        class="btn btn--outline category-section__link"
                                        aria-label="View all articles in {$category.name|escape}"
                                >
                                    All Articles
                                    <span aria-hidden="true">&rarr;</span>
                                </a>

                            </div>

                            <div class="post-grid">

                                {foreach $category.posts as $post}

                                    {include
                                    file="components/post-card.tpl"
                                    post=$post
                                    }

                                {/foreach}

                            </div>

                        </section>

                    {/foreach}

                </div>

            {else}

                <div class="empty-state">
                    <h2>No categories found</h2>
                    <p>
                        There are no published articles available yet.
                    </p>
                </div>

            {/if}

        </div>
    </section>

</main>

{include file="layouts/footer.tpl"}