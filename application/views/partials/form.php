<!--
    Form Partial

    Partial used to build a basic form given an array of inputs.

    @param Array $inputs Array wherein each element is an associative array with
                         the following keys being recognized:
                            - type [hidden, text, number, textarea, select, dropzone]
                            - name [HTML name attribute]
                            - label [Label tag text shown to users]
                            - placeholder [HTML placeholder attribute]
                            - value [HTML value attribute]
                            - min [HTML min attribute]
                            - max [HTML max attribute]
                            - options [select tag options array]
                                o value [HTML value attribute]
                                o label [option text displayed to users]
-->
<?php foreach($inputs as $input): ?>
    <div class="form-group contains-<?= $input['type'] ?>">
        <?php if($input['type'] != 'hidden'): ?>
            <label for="<?= $input['name'] ?>"><?= $input['label'] ?></label>
        <?php endif; ?>

        <?php if ($input['type'] == 'text'
               || $input['type'] == 'hidden'
               || $input['type'] == 'number'
               || $input['type'] == 'textarea'
              ):
        ?>
            <?= $input['type'] == 'textarea' ? '<textarea ' : '<input ' ?>
                class='form-control'
                name="<?= $input['name'] ?>"
                id="<?= $input['name'] ?>"
                <?php if ($input['type'] != 'textarea'): ?>
                    type='<?= $input['type'] ?>'
                    <?= array_key_exists('placeholder', $input) ? " placeholder='" . $input['placeholder'] . "' " : ''?>
                    <?= array_key_exists('value', $input) ? " value='" . $input['value'] . " " : '' ?>
                    <?= array_key_exists('min', $input) ? " min='" . $input['min'] . " " : '' ?>
                    <?= array_key_exists('max', $input) ? " max='" . $input['max'] . " " : '' ?>
                <?php endif; ?>
            <?= $input['type'] == 'textarea' ? '></textarea>' : '></input>' ?>
        <?php elseif ($input['type'] == 'select'): ?>
            <select name="<?= $input['name'] ?>" class="form-control">
                <?php foreach($input['options'] as $option): ?>
                    <option value="<?= $option['value'] ?>"><?= $option['label'] ?></option>
                <?php endforeach; ?>
            </select>

        <?php elseif ($input['type'] == 'dropzone'): ?>
            <?php $_id = uniqid('js'); ?>
            <div class='dropzoneContainer'>
                <div>
                    <div class="dropzone <?= $input['id'] ?>"
                         id="<?= $_id ?>"
                         data-type="<?= $input['dzType'] ?>"
                         data-url="<?= $input['url'] ?>"
                         data-accepted-files="<?= $input['acceptedFiles'] ?>">

                        <div class="dz-default dz-message">
                            <p>Drag &amp; Drop <?= $input['noun'] ?> Here</p>
                            <p>(click to browse)</p>
                        </div>
                    </div>
                </div>
                <a class="jsExpand" href="javascript:void(0);"><i class="fa fa-caret-right"></i> or select existing</a>


                <div class="collapsed">'
                        <select class='<?= $input['dzType'] ?>Select'>
                            <?php foreach($input['dzSelectEntities'] as $entity): ?>
                                <option value='<?= $entity->id ?>'> <?= $entity->nice_name ?></option>
                            <?php endforeach; ?>
                        </select>
                </div>

            </div>
        <?php else: ?>
                <p class='red'>Unknown input type</p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
