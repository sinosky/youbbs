<?php
//一些常用的数据操作

if (!defined('IN_SAESPOT')) {
    include_once(ROOT . '/error/403.php');
    exit;
};

//获取网站基本配置
$options = $MMC->get('options');
if(!$options){
    $query = $DBS->query("SELECT title, value FROM yunbbs_settings");
    $options = array();
    while($setting = $DBS->fetch_array($query)) {
        $options[$setting['title']] = $setting['value'];
    }

    $options = stripslashes_array($options);

    if(!$options['safe_imgdomain']){
        $options['safe_imgdomain'] = $_SERVER['HTTP_HOST']."\nbcs.duapp.com";
    }
    $MMC->set('options', $options);

    unset($setting);
    $DBS->free_result($query);
}

//获取链接
function get_links() {
    global $MMC;
    $links = $MMC->get('site_links');
    if($links){
        return $links;
    }else{
        global $DBS;
        $query = $DBS->query("SELECT name, url FROM yunbbs_links");
        $links = array();
        while($link = $DBS->fetch_array($query)) {
            $links[$link['name']] = $link['url'];
        }
        if($links){
            $MMC->set('site_links', $links);
        }
        unset($link);
        $DBS->free_result($query);
        return $links;
    }
}

// 隐藏节点
function hide_nodes_str() {
    global $options;
    $hide_nodes_str = $options['hide_nodes'] ? "WHERE id <> ".str_replace(",", " AND id <> ", $options['hide_nodes']) : "";
    return $hide_nodes_str;
}

// 获取最近新增节点
function get_newest_nodes() {
    global $MMC;
    $newest_nodes = $MMC->get('newest_nodes');
    if ($newest_nodes) {
        return $newest_nodes;
    } else {
        global $DBS, $options;
        //$hide_nodes_str = hide_nodes_str();
        //$query = $DBS->query("SELECT id, name, articles FROM yunbbs_categories $hide_nodes_str ORDER BY id DESC LIMIT ".$options['newest_node_num']);
        $query = $DBS->query("SELECT id, name, articles FROM yunbbs_categories ORDER BY id DESC LIMIT ".$options['newest_node_num']);
        $node_arr = array();
        while ($node = $DBS->fetch_array($query)) {
            $node_arr['node-'.$node['id']] = $node['name'];
        }
        if ($node_arr) {
            $MMC->set('newest_nodes', $node_arr, 0 ,3600);
        }
        unset($node);
        $DBS->free_result($query);
        return $node_arr;
    }
}

// 获取热门节点
function get_hot_nodes() {
    global $MMC;
    $hot_nodes = $MMC->get('hot_nodes');
    if ($hot_nodes) {
        return $hot_nodes;
    } else {
        global $DBS, $options;
        //$hide_nodes_str = hide_nodes_str();
        //$query = $DBS->query("SELECT id, name, articles FROM yunbbs_categories $hide_nodes_str ORDER BY articles DESC LIMIT ".$options['hot_node_num']);
        $query = $DBS->query("SELECT id, name, articles FROM yunbbs_categories ORDER BY articles DESC LIMIT ".$options['hot_node_num']);
        $node_arr = array();
        while ($node = $DBS->fetch_array($query)) {
            $node_arr['node-'.$node['id']] = $node['name'];
        }
        if ($node_arr) {
            $MMC->set('hot_nodes', $node_arr, 0 ,3600);
        }
        unset($node);
        $DBS->free_result($query);
        return $node_arr;
    }
}

// 获取所有节点
function get_bot_nodes() {
    global $MMC;
    $bot_nodes = $MMC->get('bot_nodes');
    if ($bot_nodes) {
        return $bot_nodes;
    } else {
        global $DBS, $options;
        //$hide_nodes_str = hide_nodes_str();
        //$query = $DBS->query("SELECT id, name, articles FROM yunbbs_categories $hide_nodes_str ORDER BY id");
        $query = $DBS->query("SELECT id, name, articles FROM yunbbs_categories ORDER BY id");
        $node_arr = array();
        while($node = $DBS->fetch_array($query)) {
            $node_arr['node-'.$node['id']] = $node['name'];
        }
        if($node_arr){
            $MMC->set('bot_nodes', $node_arr, 0, 3600);
        }
        unset($node);
        $DBS->free_result($query);
        return $node_arr;
    }
}

// 获取站点信息
function get_site_infos() {
    global $MMC;
    $site_infos = $MMC->get('site_infos');
    if($site_infos){
        return $site_infos;
    }else{
        global $DBS;
        // 如果删除表里的数据则下面信息不准确
        $site_infos = array();
        $table_status = $DBS->fetch_one_array("SHOW TABLE STATUS LIKE 'yunbbs_users'");
        $site_infos['会员'] = $table_status['Auto_increment'] -1;
        $table_status = $DBS->fetch_one_array("SHOW TABLE STATUS LIKE 'yunbbs_categories'");
        $site_infos['节点'] = $table_status['Auto_increment'] -1;
        $table_status = $DBS->fetch_one_array("SHOW TABLE STATUS LIKE 'yunbbs_articles'");
        $site_infos['帖子'] = $table_status['Auto_increment'] -1;
        $table_status = $DBS->fetch_one_array("SHOW TABLE STATUS LIKE 'yunbbs_comments'");
        $site_infos['回复'] = $table_status['Auto_increment'] -1;

        $MMC->set('site_infos', $site_infos, 0 ,3600);
        return $site_infos;
    }
}

?>