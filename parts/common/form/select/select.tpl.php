<select name="<?=$this->name ?>" id="<?=$this->name ?>" >
    <? foreach ($this->select as $key => $val) { ?>
    <option value="<?=htmlspecialchars($key) ?>" <?=($_REQUEST[$this->_name] && $key == $_REQUEST[$this->_name]) || (!isset($_REQUEST[$this->_name]) && $key == $this->selected) ? ' selected' : ''?>><?= htmlspecialchars($val) ?></option>
    <? } ?>
</select>