{include file="layouts/header.tpl"}

<main class="site-main">

    <article class="post">

        <header class="post__header container container--narrow">

            <a href="/" class="back-link">
                &larr; Back to articles
            </a>

            <div class="post__categories">
                {foreach $post.categories as $category}
                    <a
                            href="/categories/{$category.slug|escape:'url'}"
                            class="badge badge--primary"
                    >
                        {$category.name|escape}
                    </a>
                {/foreach}
            </div>

            <h1 class="post__title">
                {$post.title|escape}
            </h1>

            <p class="post__description">
                {$post.description|escape}
            </p>

            <div class="post__meta">
                <time datetime="{$post.published_at|date_format:'%Y-%m-%d'}">
                    {$post.published_at|date_format:'%B %e, %Y'}
                </time>

                <span class="meta-separator"></span>

                <span>{$post.views} views</span>
            </div>

            {if !empty($post.tags)}
                <div class="tag-list">
                    {foreach $post.tags as $tag}
                        <a
                                href="/tags/{$tag.slug|escape:'url'}"
                                class="tag"
                        >
                            #{$tag.name|escape}
                        </a>
                    {/foreach}
                </div>
            {/if}

        </header>

        {if !empty($post.image)}
            <div class="post__hero container">
                <img
                        src="{$post.image|escape}"
                        alt="{$post.title|escape}"
                        class="post__image"
                >
            </div>
        {/if}

        <div class="post__body container container--narrow">
            <div class="prose">
                {$post.content|escape|nl2br}
            </div>
        </div>

    </article>

    {if !empty($relatedPosts)}
        <section class="section section--muted related-posts">
            <div class="container">

                <div class="section__header">
                    <div>
                        <span class="section__eyebrow">Keep exploring</span>
                        <h2 class="section__title">Related Articles</h2>
                    </div>

                    <a href="/" class="text-link">
                        View all articles &rarr;
                    </a>
                </div>

                <div class="post-grid">
                    {foreach $relatedPosts as $relatedPost}
                        {include
                        file="components/post-card.tpl"
                        post=$relatedPost
                        }
                    {/foreach}
                </div>

            </div>
        </section>
    {/if}

</main>

{include file="layouts/footer.tpl"}