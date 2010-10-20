<? use_helper('Javascript', 'Form'); ?>
<?if (!isset($show_comments)) $show_comments = 0 ?>
<?if (!isset($showAddComment)) $showAddComment = 0 ?>
<h1 class="post_description"><?echo $description?></h1>
<?if ($tagnames):?>
<h2 class="post_description"><?echo __("Теги") . ": " . $tagnames?></h2>
<?endif?>
<div id="postContainer<? echo $post->getId() ?>" >
    <? include_partial('post/post', array('post' => $post, 'show_comments' => 1, 'showAddComment' => $showAddComment)) ?>
</div>
<?if (isset($simPosts)):?>
<h2><?echo __('Похожие посты')?></h2>
<table width="100%">
    <tr>
        <?foreach ($simPosts as $simPost):?>
        <td style="padding: 0 5px 0 5px;">
            <a href="<?echo url_for('post/show?id=' . $simPost->getId())?>">
                <img src="<?echo $simPost->getThumbnail()?>" alt="<?echo $simPost->getSeoDescription()?>" title="<?echo $simPost->getSeoDescription()?>"/>
                <h3><?echo $simPost->getSeoShortTitle()?></h3>
            </a>
        </td>
        <?endforeach?>
    </tr>
</table>
<?endif?>
<? if ($sf_user->isAuthenticated() && $post->getUser() == $sf_user->getGuardUser()): ?>
    <? include_partial('global/myLeftMenu') ?>
    <? slot('rsslink') ?>
    <?echo include_partial('rss/link', array('url' => 'rss/user?username=' . $sf_user->getGuardUser()->getUsername()))?>
    <? end_slot() ?>
<? else: ?>
    <? include_partial('global/userLeftMenu', array('user' => $post->getUser())) ?>
    <? slot('rsslink') ?>
    <?echo include_partial('rss/link', array('url' => 'rss/user?username=' . $post->getUser()->getUsername()))?>
    <? end_slot() ?>
<? endif ?>