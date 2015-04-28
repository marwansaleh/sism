<div class="container">
    <article>
        <h3 class="title"><?php echo $article->title; ?></h3>
        <div class="info">
            <span class="date"><?php echo date('d/m/Y',$article->date); ?></span>, by
            <span class="author"><?php echo $article->created_by_name; ?></span>
        </div>
        
        <figure>
            <img class="img-responsive" src="<?php echo get_image_thumb($article->image_url, IMAGE_THUMB_MEDIUM); ?>" alt="Article image">
        </figure>
        <div class="content">
            <?php echo $article->content; ?>
        </div>
    </article>
</div>

<!-- related news -->
<?php if ($related_news): ?>
<div class="container">
    <h3 class="page-header">Berita Terkait</h3>
    
    <ul class="media-list">
        <?php foreach($related_news as $news): ?>
        <li class="media" data-href="<?php echo site_url('detail/'.$news->url_title); ?>">
            <div class="media-body">
                <h4 class="media-heading"><a href="<?php echo site_url('detail/'.$news->url_title); ?>"><?php echo $news->title; ?></a></h4>
                <?php echo $news->synopsis; ?>
            </div>
        </li>
        <?php endforeach;?>
    </ul>
</div>
<?php endif; ?>

<p class="text-center"><a href="<?php echo site_url('home'); ?>">Back</a></p>