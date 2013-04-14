<?php
if (!defined('IN_SAESPOT')) exit(header('location: /static/error/403.html'));

echo '
<div class="title">
    <div class="float-left fs14">
        <a href="/">',$options['name'],'</a> &raquo; <a href="/node-',$c_obj['id'],'">',$c_obj['name'],'</a> (',$c_obj['articles'],')
    </div>';
if($cur_user && $cur_user['flag']>4){
    echo '<div class="float-right"><a href="/newpost-',$t_obj['cid'],'" class="newpostbtn">+发新帖</a></div>';
}
echo '    <div class="c"></div>
</div>

<div class="main-box">
<div class="topic-title">
    <div class="topic-title-main float-left">
        <h1>',$t_obj['title'],'</h1>
        <div class="topic-title-date">
        By <a href="/member-',$t_obj['uid'],'.html">',$t_obj['author'],'</a> at ',$t_obj['addtime'],' • ',$t_obj['views'],'次点击';
if($t_obj['favorites']){
    echo ' • ',$t_obj['favorites'],'收藏';
}

if($cur_user && $cur_user['flag']>4){
    if(!$t_obj['closecomment']){
        echo '&nbsp;&nbsp;&nbsp;<a href="#new-comment">回复</a>';
    }
    if($in_favorites){
        echo ' • <a href="/favorites?act=del&id=',$t_obj['id'],'" title="点击取消收藏">取消收藏</a>';
    }else{
        echo ' • <a href="/favorites?act=add&id=',$t_obj['id'],'" title="点击收藏">收藏</a>';
    }
    if($cur_user['flag']>=88){
        echo ' • <a href="/admin-edit-post-',$t_obj['id'],'">编辑</a>';
    }
}


echo '        </div>
    </div>
    <div class="detail-avatar">
        <a href="/member-',$t_obj['uid'],'.html"><img src="',TUCHUANG_URL,'/avatar/large/',$t_obj['uavatar'],'.png" alt="',$t_obj['author'],'" /></a>
    </div>
    <div class="c"></div>
</div>
<div class="topic-content">
',$options['ad_post_top'],'
<p>',$t_obj['content'],'</p>
',$options['ad_post_bot'],'
</div>

</div>
<!-- post main content end -->';

if($t_obj['comments']){
echo '
<div class="title">
    ',$t_obj['comments'],' 回复  |  直到 ',$t_obj['edittime'],'
</div>
<div class="main-box home-box-list">';

$count_n = ($page-1)*$options['commentlist_num'];
foreach($commentdb as $comment){
$count_n += 1;
echo '
    <div class="commont-item">
        <div class="commont-avatar">
            <a href="/member-',$comment['uid'],'.html"><img src="',TUCHUANG_URL,'/avatar/normal/',$comment['avatar'],'.png" alt="',$comment['author'],'" /></a>
        </div>
        <div class="commont-data">
            <div class="commont-content">
            <p>',$comment['content'],'</p>
            </div>

            <div class="commont-data-date">
                <div class="float-left"><a href="/member-',$comment['uid'],'.html">',$comment['author'],'</a> at ',$comment['addtime'];
if($cur_user && $cur_user['flag']>=88){
    echo '&nbsp;&nbsp;&nbsp;<a href="/admin-edit-comment-',$comment['id'],'"><strong>编辑</strong></a>';
}
                echo '</div>
                <div class="float-right">';
if(!$t_obj['closecomment'] && $cur_user && $cur_user['flag']>4 && $cur_user['name'] != $comment['author']){
    echo '&laquo; <a href="#new-comment" onclick="replyto(\'',$comment['author'],'\');">回复</a>';
}
echo '                <span class="commonet-count">',$count_n,'</span></div>
                <div class="c"></div>
            </div>
            <div class="c"></div>
        </div>
        <div class="c"></div>
    </div>';
}

if($t_obj['comments'] > $options['commentlist_num']){
echo '<div class="pagination">';
if($page>1){
echo '<a href="/topic-',$tid,'-',$page-1,'.html" class="float-left">&laquo; 上一页</a>';
}
if($page<$taltol_page){
echo '<a href="/topic-',$tid,'-',$page+1,'.html" class="float-right">下一页 &raquo;</a>';
}
echo '<div class="c"></div>
</div>';
}

echo '

</div>
<!-- comment list end -->

<script type="text/javascript">
function replyto(somebd){
    var con = document.getElementById("id-content").value;
    document.getElementsByTagName(\'textarea\')[0].focus();
    document.getElementById("id-content").value = " @"+somebd+" " + con;
}
</script>

';

}else{
    echo '<div class="no-comment">目前尚无回复</div>';
}

if($t_obj['closecomment']){
    echo '<div class="no-comment">该帖评论已关闭</div>';
}else{

if($cur_user && $cur_user['flag']>4){
echo '

<a name="new-comment"></a>
<div class="title">
    <div class="float-left">添加一条新回复</div>
    <div class="float-right"><a href="#">↑ 回到顶部</a></div>
    <div class="c"></div>
</div>
<div class="main-box">';
if($tip){
    echo '<p class="red">',$tip,'</p>';
}
echo '    <form action="',$_SERVER["REQUEST_URI"],'#new-comment" method="post">
<input type="hidden" name="formhash" value="',$formhash,'" />
    <p><textarea id="id-content" name="content" class="comment-text mll">',htmlspecialchars($c_content),'</textarea></p>';

if(!$options['close_upload']){
    include(dirname(__FILE__) . '/upload.php');
}

echo '
    <p>
    <div class="float-left"><input type="submit" value=" 提 交 " name="submit" class="textbtn" /></div>
    <div class="float-right fs12 grey">请尽量让自己的回复能够对别人有帮助。</div>
    <div class="c"></div>
    </p>
    </form>
</div>
<!-- new comment end -->';

}else{
    echo '<div class="no-comment">请 <a href="/login" rel="nofollow">登录</a> 后发表评论</div>';
}


}

?>