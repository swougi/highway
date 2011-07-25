<a href='<?=blog_path($blog)?>' class='item-link'>
    <div class='item'>
        <div class='image'></div>
        <div class='title'>
                <h3>
                        <?=$blog->name?>
                </h3>
                <div class='cb'></div>
                <div class="info">
                        <ul>

                            <li>Tags : <?=implode(",",array_map(function($tag){return $tag->name;},$blog->tags))?>  </li>

                        </ul>
                </div>
        </div>
        <div>

        </div>
        <div class='grey-arrow'></div>
        <div class='cb'></div>
    </div>
</a>