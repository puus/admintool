<!--
    Pagination Partial

    Partial used to construct pagination controls including previous, next and
    an appropriate number of page buttons.

    @param Array  $noun
    @param String $url
    @param Bool   $duplicate
    @param Int    $limit
    @param Int    $total
    @param Int    $offset
-->

<div class="mailbox-controls">
    <button type="button" class="btn btn-default btn-sm checkbox-toggle" data-state="off"><i class="fa fa-square-o"></i>
    </button>

    <?php if(is_array($noun) && count($noun)): ?>
        <div class='btn-group'>
            <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-plus"></i> &nbsp; <?= $noun['label'] ?> &nbsp;
                <span class="fa fa-caret-down"></span>
            </button>

            <ul class="dropdown-menu">
                <?php foreach($noun['options'] as $option): ?>
                    <li><a href="<?= $option['href'] ?>"><?= $option['label'] ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php $noun = $noun['noun']; ?>
    <?php elseif (is_string($noun)): ?>
        <a class="btn btn-primary btn-sm" href="<?= $url ?>/add"><i class="fa fa-plus"></i> Add <?= $noun ?></a>
    <?php endif; ?>

    <?php if (isset($duplicate) && $duplicate): ?>
        <a class="btn btn-default btn-sm table-duplicate" href="#" disabled data-toggle="modal" data-target="#duplicateModal"><i class="fa fa-trash"></i> Duplicate <?= $noun ?>(s)</a>
    <?php endif; ?>

    <a class="btn btn-danger btn-sm table-delete" href="#" disabled data-toggle="modal" data-target="#deleteModal"><i class="fa fa-trash"></i> Delete <?= $noun ?>(s)</a>

    <div class="pull-right">
        <?= $offset + 1 ?> - <?= $limit > $total || $limit + $offset > $total ? $total : $limit + $offset ?> / <?= $total ?>
        <div class="btn-group">
            <a class="btn btn-default btn-sm" href="<?= $url ?>?limit=<?= $limit ?>&amp;offset=<?= ($offset - $limit) > 0 ? $offset - $limit : 0 ?>" <?= $offset == 0 ? 'disabled' : '' ?>><i class="fa fa-chevron-left"></i></a>
            <a class="btn btn-default btn-sm" href="<?= $url ?>?limit=<?= $limit ?>&amp;offset=<?= $offset + $limit ?>" <?= $offset + $limit >= $total ? 'disabled' : '' ?>><i class="fa fa-chevron-right"></i></a>
        </div>
    </div>
</div>
