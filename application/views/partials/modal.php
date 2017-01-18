<!--
    Modal partial

    Partial used to populate the HTML for a modal

    @param String $classes       Comma seperated list of class names
    @param String $id            HTML id attribute value
    @param String $title         Modal title displayed to the users
    @param Mixed  $content       Modal HTML contents
    @param String $buttonClasses
-->

<div class="modal <?= $classes ?>" id="<?= $id ?>">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?= $title ?></h4>
      </div>
      <div class="modal-body">
          <?= $content ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary <?= $buttonClasses ?>">Save changes</button>
      </div>
    </div>
  </div>
</div>
