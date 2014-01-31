<?php
/**
 * This comment block is used just to make IDE suggestions to work
 * @var $menuItem \Ip\Menu\Item
 * @var $this \Ip\View
 */

$css = array();
if($menuItem->isCurrent()) {
    $css[] = 'current';
    $selected = true;
} elseif ($menuItem->isInCurrentBreadcrumb()) {
    $css[] = 'selected';
    $selected = true;
}

if(sizeof($menuItem->getChildren()) > 0) {
    $css[] = 'subnodes';
}

$css[] = 'type'.ucwords($menuItem->getType());

if ($menuItem->getType() != 'inactive' && $menuItem->getUrl()) {
    $href = 'href="'.$menuItem->getUrl().'"';
} else {
    $href = '';
}
?><li class="<?php echo implode(' ', $css) ?>">
    <a <?php echo $href ?> title="<?php echo esc($menuItem->getPageTitle(), 'attr') ?>">
        <?php echo esc($menuItem->getTitle()) ?>
    </a>
    <?php if ($menuItem->getChildren()){ ?>
        <?php echo ipView('Ip/Internal/Config/view/menu.php', array('items' => $menuItem->getChildren(), 'depth' => $depth + 1))->render() ?>
    <?php } ?>
</li>