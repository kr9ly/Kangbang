<select name="<?=$this->name ?>" id="<?=$this->name ?>" >
    <? foreach ($this->select as $key => $val) { ?>
    <option value="<?=htmlspecialchars($key) ?>" <?=$key == $this->selected ? ' selected' : ''?>><?= htmlspecialchars($val) ?></option>
    <? } ?>
</select>