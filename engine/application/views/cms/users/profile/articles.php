<div class="large-box nicescroll">
    <ul class="media-list">
        <?php foreach ($user->last_articles as $la): ?>
        <li class="media">
            <div class="media-left">
                <a href="<?php echo site_url('cms/article/edit?id='.$la->id); ?>">
                    <img class="media-object" src="<?php echo get_image_thumb($la->image_url, IMAGE_THUMB_SMALLER) ?>" />
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-heading"><a href="<?php echo site_url('cms/article/edit?id='.$la->id); ?>"><?php echo $la->title; ?></a></h4>
                <p><?php echo $la->synopsis; ?>  <i class="fa fa-user"></i> <span class="text-maroon"><?php echo $la->author;?></span> <i class="fa fa-clock-o"></i> <span class="text-maroon"><?php echo date('d M Y H:i', $la->modified); ?></span></p>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</div>