<div class="container">
    <ul id="news-list" class="media-list">
        <?php foreach($mobile_news as $index => $news): ?>
        
        <li data-id="<?php echo $news->id; ?>" class="media <?php echo $index==0?'first-item':''; ?>" data-href="<?php echo site_url('detail/'.$news->url_title); ?>">
            <?php if ($index==0):?>
            <a href="<?php echo site_url('detail/'.$news->url_title); ?>">
                <img class="media-object img-responsive" src="<?php echo get_image_thumb($news->image_url, IMAGE_THUMB_MEDIUM); ?>" alt="<?php echo $news->title; ?>">
            </a>
            
            <div class="media-body">
                <h4 class="media-heading"><a href="<?php echo site_url('detail/'.$news->url_title); ?>"><?php echo $news->title; ?></a></h4>
                <p><?php echo $news->synopsis; ?></p>
            </div>
            <?php else: ?>
            <div class="media-left">
                <a href="<?php echo site_url('detail/'.$news->url_title); ?>">
                    <img class="media-object" src="<?php echo get_image_thumb($news->image_url, IMAGE_THUMB_SQUARE); ?>" alt="<?php echo $news->title; ?>">
                </a>
            </div>
            
            <div class="media-body">
                <h4 class="media-heading"><?php echo $news->title; ?></h4>
                <p><?php echo $news->synopsis; ?></p>
            </div>
            <?php endif; ?>
        </li>
        <?php endforeach;?>
    </ul>
    <div id="lastPostsLoader"></div>
    <input type="hidden" id="limit" name="limit" value="<?php echo $limit; ?>">
    <input type="hidden" id="lastPage" name="lastPage" value="1">
</div>

<script type="text/javascript">
    var in_process = false;
    var list_id = [];
    function lastAddedLiveFunc()
    {
        if (in_process){
            return;
        }
        in_process = true;
        $('div#lastPostsLoader').html('Loading news...');
 
        $.post('<?php echo site_url('ajax/news/last'); ?>',{last_page:$('input#lastPage').val(), limit:$('input#limit').val()}, function(data){
            if (data.status == 1) {
                for (var i in data.items){
                    var news = data.items[i];
                    if (list_id.indexOf(news.id)>=0){
                        continue;
                    }
                    list_id.push(news.id);
                    var s = '<li class="media" data-href="'+news.data_href+'">';
                        s+= '<div class="media-left">';
                            s+= '<a href="'+news.data_href+'">';
                                s+= '<img class="media-object" src="'+news.image_url+'" alt="'+news.title+'">' ;
                            s+= '</a>';
                        s+= '</div>';

                        s+= '<div class="media-body">';
                            s+= '<h4 class="media-heading"><a href="'+news.data_href+'">'+news.title+'</a></h4>';
                            s+= '<p>'+news.synopsis+'</p>';
                        s+= '</div>';
                    s+= '</li>';
                    
                    $('#news-list').append(s);
                }
            }
            $('div#lastPostsLoader').empty();
            $('#lastPage').val(data.last_page);
            in_process = false;
        },'json');
    };
    
    $(document).ready(function(){
        //store article ids
        $('.media').each(function(){
            list_id.push($(this).attr('data-id'));
        });
        //lastAddedLiveFunc();
        $(window).scroll(function(){

            var wintop = $(window).scrollTop(), docheight = $(document).height(), winheight = $(window).height();
            var  scrolltrigger = 0.95;

            if  ((wintop/(docheight-winheight)) > scrolltrigger) {
             //console.log('scroll bottom');
             lastAddedLiveFunc();
            }
        });
    });
    
</script>