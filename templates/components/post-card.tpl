<article class="post-card">

    <a href="/posts/{$post.id|escape}" class="post-card__image-link">
        {if !empty($post.image)}
            <img
                    src="{$post.image|escape}"
                    alt="{$post.title|escape}"
                    class="post-card__image"
                    loading="lazy"
            >
        {else}
            <div class="post-card__placeholder">
                &lt;/&gt;
            </div>
        {/if}
    </a>

    <div class="post-card__content">

        <div class="post-card__meta">
            <time datetime="{$post.published_at|date_format:'%Y-%m-%d'}">
                {$post.published_at|date_format:'%b %e, %Y'}
            </time>
        </div>

        <h3 class="post-card__title">
            <a href="/posts/{$post.id|escape}">
                {$post.title|escape}
            </a>
        </h3>

        <p class="post-card__description">
            {$post.description|escape}
        </p>

        <div class="post-card__footer">
            <span class="post-card__views">
                {$post.views} views
            </span>

            <a
                    href="/posts/{$post.id|escape}"
                    class="text-link"
                    aria-label="Read {$post.title|escape}"
            >
                Read article &rarr;
            </a>
        </div>

    </div>
</article>