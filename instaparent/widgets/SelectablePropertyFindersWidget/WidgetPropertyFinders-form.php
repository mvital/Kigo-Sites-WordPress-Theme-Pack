<p>
    You may reorganize the property finders by dragging them.
</p>
<label>
    <h3>Title:</h3>
    <input type="text" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $ins['title']; ?>">
</label>

<label>
    <h3>Items per row:</h3>
    <select name="<?php echo $this->get_field_name('items_per_row'); ?>" id="<?php echo $this->get_field_id('items_per_row'); ?>">
        <?php
            for($i=1; $i<5; $i++){
                $selected = '';
                if($i == (int) $ins['items_per_row'] )
                {
                    $selected = 'selected';
                }
                ?>
                <option value="<?php echo $i; ?>" <?php echo $selected; ?>>
                    <?php echo $i; ?>
                </option>
            <?php }
        ?>
    </select>
</label>

<label>
    <h3>Select property Finders:</h3>
</label>
<ul class="sortable propery_finders_sortable_list">
       <?php

       foreach($ins['property_finders'] as $pfindid => $name){

            ?>
            <li>
            <label>
                <input type="checkbox" name="<?php echo $this->get_field_name('property_finders'); ?>[<?php echo $pfindid; ?>]" checked value="<?php echo $pfindid; ?>">
                <b><?php echo $this->getPropertyFinders()[$name]; ?></b>
                <span class="draggme">&equiv;</span>
            </label>
            </li>
                <?php
        }

        foreach($this->getPropertyFinders() as $pfindid => $name){
            $checked = '';
            if(!empty($ins['property_finders'][$pfindid])) continue;
            ?>
            <li>
            <label>
                <input type="checkbox" name="<?php echo $this->get_field_name('property_finders'); ?>[<?php echo $pfindid; ?>]" value="<?php echo $pfindid; ?>">
                <b><?php echo $name; ?></b>
                <span class="draggme">&equiv;</span>
            </label>
            </li>
                <?php
        }
    ?>
</ul>

<script>
$('.sortable').sortable();
</script>
